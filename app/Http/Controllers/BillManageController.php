<?php

namespace App\Http\Controllers;

use App\BillManage;
use App\Comment;
use App\Order;
use App\Package;
use App\Permission;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillManageController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    protected $_per_page = 1;

    /**
     * @param Request $request
     * @author vanhs
     * @desc danh sach phieu giao hang
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function listView(Request $request){
        $can_view = Permission::isAllow(Permission::PERMISSION_BILL_MANAGE_LIST_VIEW);
        if(!$can_view){
            return redirect('403');
        }

        $bill_mange = BillManage::orderBy('id', 'desc')->paginate($this->_per_page);

        return view('billManage', [
            'page_title' => 'Phiếu giao hàng',
            'bll_manage' => $bill_mange
        ]);
    }

    /**
     * @author vanhs
     * @desc Xu ly tao phieu giao:
     *  - xuat toan bo kien (da chon) trong kien
     *  - tao thong tin phieu
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        $can_create = Permission::isAllow(Permission::PERMISSION_BILL_MANAGE_CREATE);
        if(!$can_create){
            return response()->json(['success' => false, 'message' => 'Ban khong co quyen thuc hien hanh dong nay!']);
        }

        try{
            DB::beginTransaction();

            $create_user = User::find(Auth::user()->id);
            $packages = $request->get('packages');
            $package_string = implode(',', $packages);
            $orders = $request->get('orders');
            $order_array = [];
            foreach($orders as $order_id){
                $order_array[$order_id] = $order_id;
            }
            $order_string = implode(',', array_values($order_array));

            foreach($packages as $logistic_package_barcode){
                $package = Package::where([
                    ['logistic_package_barcode', '=', $logistic_package_barcode]
                ])->first();

                $message_internal = sprintf("Kiện hàng %s xuất kho phân phối %s",
                    $logistic_package_barcode,
                    $package->current_warehouse);

                if($package instanceof Package){
                    $package->outputWarehouseDistribution($package->current_warehouse);
                    $order = Order::find($package->order_id);
                    if($order instanceof Order){
                        $order->changeOrderDelivering();
                        Comment::createComment($create_user, $order, $message_internal, Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
                    }
                }
            }

            $id = BillManage::insertGetId(
                [
                    'create_user' => Auth::user()->id,
                    'code' => BillManage::getCode(),
                    'domestic_shipping_vietnam' => $request->get('domestic_shipping_vietnam'),
                    'amount_cod' => $request->get('amount_cod'),
                    'packages' => $package_string,
                    'orders' => $order_string,
                    'created_at' => date('Y-m-d H:i:s')
                ]
            );
            $url = url('BillManage/Detail', $id);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'thanh cong', 'url' => $url]);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'co loi xay ra, vui long thu lai' . $e->getMessage()]);
        }

    }

    /**
     * @author vanhs
     * @desc Chi tiet phieu giao hang
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function detailView(Request $request){
        $id = $request->route('id');
        $bill = BillManage::find($id);

        $can_view = Permission::isAllow(Permission::PERMISSION_BILL_MANAGE_LIST_VIEW);
        if(!$can_view){
            return redirect('403');
        }

        if(!$bill instanceof BillManage){
            return redirect('404');
        }

        return view('billDetail', [
            'page_title' => sprintf("Phiếu giao #%s", $bill->code)
        ]);
    }
}
