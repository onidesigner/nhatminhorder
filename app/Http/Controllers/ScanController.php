<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Order;
use App\OrderFreightBill;
use App\Package;
use App\Scan;
use App\User;
use App\WareHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ScanController extends Controller
{
    protected $action_error = [];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexs()
    {
        $data = $this->__getInitData('layouts.app');

        return view('scan', $data);
    }

    private function __getInitData($layout = null){
        $warehouse_list = WareHouse::getAllWarehouse();

        $history_scan_list = Scan::findByUser(Auth::user()->id);

        return [
            'action_list' => Scan::$action_list,
            'warehouse_list' => $warehouse_list,
            'history_scan_list' => $history_scan_list,
            'page_title' => 'Quét mã vạch',
            'layout' => $layout
        ];
    }

    /**
     * @author vanhs
     * @desc Cac hanh dong tren trang quet ma vach
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function action(Request $request)
    {

        try{
            DB::beginTransaction();

            $currentUser = User::find(Auth::user()->id);
            $action = '__' . $request->get('action');
            $warehouse = WareHouse::retrieveByCode($request->get('warehouse'));

            if(empty($request->get('barcode'))){
                return response()->json(['success' => false, 'message' => 'Vui lòng nhập vào mã quét!']);
            }

            if(!$warehouse || !$warehouse instanceof WareHouse){
                return response()->json(['success' => false, 'message' => sprintf('Kho %s không tồn tại!', $request->get('warehouse'))]);
            }

            if (!method_exists($this, $action)) {
                return response()->json(['success' => false, 'message' => 'Not support action!']);
            }

            $result = $this->$action($request, $warehouse, $currentUser);
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

    /**
     * @author vanhs
     * @desc Logic
     * - Nhap kho Trung Quoc
     *      + Chuyen trang thai kien sang "NhatMinh247 nhận"
     *      + Chuyen trang thai don hang sang "NhatMinh247 nhận" (neu la kien dau tien nhap kho TQ)
     * - Nhap kho phan phoi tai Viet Nam
     *      + Chuyen trang thai kien hang sang "Cho giao hang"
     *      + Gui tin nhan thong bao hang ve kho (neu la kien dau tien nhap kho phan phoi)
     *      + Chuyen trang thai don hang sang "Cho giao hang"
     *
     * @param Request $request
     * @param WareHouse $warehouse
     * @param User $currentUser
     * @return bool
     */
    private function __in(Request $request, WareHouse $warehouse, User $currentUser){
        $barcode = $request->get('barcode');
        $create_user = User::find(Auth::user()->id);

        if($warehouse->type == WareHouse::TYPE_RECEIVE){

            $packages = Package::where([
                'logistic_package_barcode' => $barcode,
                'status' => Package::STATUS_INIT,
            ])->get();
            if($packages){
                foreach($packages as $package){
                    if(!$package instanceof Package){
                        continue;
                    }
                    $package->inputWarehouseReceive($warehouse->code);

                    $order = Order::find($package->order_id);
                    if($order instanceof Order){
                        $order->changeOrderReceivedFromSeller();

                        $message_internal = sprintf("Kiện hàng %s nhập kho %s", $package->logistic_package_barcode, $warehouse->code);
                        Comment::createComment($create_user, $order, $message_internal, Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
                    }
                }
            }

        }else if($warehouse->type == WareHouse::TYPE_DISTRIBUTION){
            $package = Package::where([
                'logistic_package_barcode' => $barcode,
                'status' => Package::STATUS_TRANSPORTING,
                'warehouse_status' => Package::WAREHOUSE_STATUS_OUT,
            ])->first();

            if($package instanceof Package){
                $package->inputWarehouseDistribution($warehouse->code);

                $order = Order::find($package->order_id);
                if($order instanceof Order){
                    $order->changeOrderWaitingDelivery();

                    $message_internal = sprintf("Kiện hàng %s nhập kho %s", $package->logistic_package_barcode, $warehouse->code);
                    Comment::createComment($create_user, $order, $message_internal, Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

                    //todo:: gui tin nhan bao hang da ve kho phan phoi tai viet nam
                }
            }
        }

        $this->__writeActionScanLog($request, $warehouse, $currentUser);

        return true;
    }

    /**
     * @author vanhs
     * @desc Logic
     * - Xuat kho Trung Quoc
     *      + Chuyen trang thai kien hang sang "Van Chuyen"
     *      + Chuyen trang thai don hang sang "Van Chuyen" (neu la kien dau tien xuat kho TQ)
     * - Xuat kho phan phoi tai Viet Nam
     *      + Chuyen trang thai kien hang sang "Dang giao hang"
     *      + Chuyen trang thai don hang sang "Dang giao hang" (neu la kien dau tien xuat kho phan phoi tai VN)
     *
     * @param Request $request
     * @param WareHouse $warehouse
     * @param User $currentUser
     * @return bool
     */
    private function __out(Request $request, WareHouse $warehouse, User $currentUser){
        $barcode = $request->get('barcode');
        $create_user = User::find(Auth::user()->id);

        if($warehouse->type == WareHouse::TYPE_RECEIVE){

            $packages = Package::where([
                'logistic_package_barcode' => $barcode,
                'status' => Package::STATUS_RECEIVED_FROM_SELLER,
                'warehouse_status' => Package::WAREHOUSE_STATUS_IN,
                'current_warehouse' => $warehouse->code,
            ])->get();
            if($packages){
                foreach($packages as $package){
                    if(!$package instanceof Package){
                        continue;
                    }
                    $package->outputWarehouseReceive($warehouse->code);

                    $order = Order::find($package->order_id);
                    if($order instanceof Order){
                        $order->changeOrderTransporting();

                        $message_internal = sprintf("Kiện hàng %s xuất kho %s", $package->logistic_package_barcode, $warehouse->code);
                        Comment::createComment($create_user, $order, $message_internal, Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
                    }
                }
            }

        }else if($warehouse->type == WareHouse::TYPE_DISTRIBUTION){
            $package = Package::where([
                'logistic_package_barcode' => $barcode,
                'status' => Package::STATUS_WAITING_FOR_DELIVERY,
                'warehouse_status' => Package::WAREHOUSE_STATUS_IN,
                'current_warehouse' => $warehouse->code
            ])->first();

            if($package instanceof Package){
                $package->outputWarehouseDistribution($warehouse->code);

                $order = Order::find($package->order_id);
                if($order instanceof Order){
                    $order->changeOrderDelivering();

                    $message_internal = sprintf("Kiện hàng %s xuất kho %s", $package->logistic_package_barcode, $warehouse->code);
                    Comment::createComment($create_user, $order, $message_internal, Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
                }
            }
        }

        $this->__writeActionScanLog($request, $warehouse, $currentUser);
        return true;
    }

    private function __writeActionScanLog(Request $request, WareHouse $warehouse, User $currentUser){
        $scan = new Scan();
        $scan->barcode = $request->get('barcode');
        $scan->action = $request->get('action');
        $scan->warehouse = $request->get('warehouse');
        $scan->created_by = $currentUser->id;
        return $scan->save();
    }
}
