<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderFreightBill;
use App\Package;
use App\Permission;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class PackageController extends Controller
{
    protected $action_error = [];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @author vanhs
     * @desc Cac hanh dong tren trang tao kien
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function action(Request $request)
    {
        try{
            DB::beginTransaction();

            $action = '__' . $request->get('action');
            $barcode = $request->get('barcode');

            if(empty($barcode)){
                return response()->json(['success' => false, 'message' => 'Mã quét không để trống!']);
            }

            if (!method_exists($this, $action)) {
                return response()->json(['success' => false, 'message' => 'Not support action!']);
            }

            $result = $this->$action($request);
            if(!$result){
                return response()->json( ['success' => false, 'message' => implode('<br>', $this->action_error)] );
            }

            DB::commit();

            $view = View::make($request->get('response'), $this->__getInitData('layouts/app_blank'));
            $html = $view->render();

            return response()->json([
                'success' => true,
                'message' => 'success',
                'html' => $html
            ]);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại' . $e->getMessage()]);
        }

    }

    private function __create_package_item($order, $barcode = null){
        if(empty($barcode)) return null;

        $package = new Package();
        if($order instanceof Order){
            $code = Package::genPackageCode($order);
            $package->code = $code;
            $package->order_id = $order->id;
            $package->buyer_id = $order->user_id;
            $package->user_address_id = $order->user_address_id;
        }else{

        }

        $package->logistic_package_barcode = Package::generateBarcode();
        $package->status = Package::STATUS_INIT;
        $package->freight_bill = $barcode;
        $package->created_by = Auth::user()->id;
        return $package->save();
    }

    private function __create_package(Request $request){
        $can_create = Permission::isAllow(Permission::PERMISSION_PACKAGE_ADD);
        if(!$can_create){
            $this->action_error[] = 'Bạn không có quyền!';
        }

        if(count($this->action_error)){
            return false;
        }

        $barcode = $request->get('barcode');

        $orders_freight_bill = OrderFreightBill::select('order_id')->where([
            'freight_bill' => $barcode,
            'is_deleted' => 0,
        ])->get();

        $orders_freight_bill = count($orders_freight_bill) > 0 ? $orders_freight_bill : [ null ];

        foreach($orders_freight_bill as $order_freight_bill){
            $order = null;
            if($order_freight_bill instanceof OrderFreightBill){
                $order = Order::find($order_freight_bill->order_id);
            }
            $this->__create_package_item($order, $barcode);
        }
        return true;
    }

    private function __getInitData($layout = null){
        return [
            'page_title' => 'Tạo kiện',
            'layout' => $layout
        ];
    }

    public function index()
    {
        $data = $this->__getInitData('layouts.app');

        return view('package_add', $data);
    }

    private function __getDetailData(Request $request, $layout = null){
        $package_code = $request->route('code');
        return [
            'page_title' => 'Thông tin kiện hàng ' . $package_code,
            'package_code' => $package_code,
            'layout' => $layout
        ];
    }

    public function detail(Request $request)
    {
        $data = $this->__getDetailData($request, 'layouts.app');
        return view('package_detail', $data);
    }

    private function __getListData($layout = null){
        $per_page = 50;

        $packages = Package::select('*')->orderBy('id', 'desc');
        $total_packages = $packages->count();
        $packages = $packages->paginate($per_page);

        if($packages){
            foreach($packages as $key => $package){
                $packages[$key]->order = Order::find($package->order_id);
                $packages[$key]->customer = User::find($package->buyer_id);
            }
        }

        return [
            'page_title' => 'Danh sách kiện hàng',
            'packages' => $packages,
            'total_packages' => $total_packages,
            'layout' => $layout
        ];
    }

    public function indexs()
    {
        $can_view = Permission::isAllow(Permission::PERMISSION_PACKAGE_LIST_VIEW);
        if(!$can_view){
            return redirect('403');
        }

        $data = $this->__getListData('layouts.app');

        return view('package_list', $data);
    }
}
