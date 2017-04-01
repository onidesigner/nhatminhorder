<?php

namespace App\Http\Controllers;

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
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại']);
        }

    }

    private function __in(Request $request, WareHouse $warehouse, User $currentUser){
        //Neu la nhap kho TQ: tao kien voi ma quet + chuyen trang thai don hang sang "Nhatminh247 nhan hang"
        //Neu la nhap kho phan phoi: tien hanh gui tin nhan cho khach hang + chuyen trang thai don sang "Cho giao hang"
        $barcode = $request->get('barcode');
        $action = $request->get('action');
        $warehouse = $request->get('warehouse');

        if($warehouse->type == WareHouse::TYPE_RECEIVE){

        }else if($warehouse->type == WareHouse::TYPE_DISTRIBUTION){

        }

        $this->__writeActionLog($request, $warehouse, $currentUser);
        return true;
    }

    private function __out(Request $request, WareHouse $warehouse, User $currentUser){
        if($warehouse->type == WareHouse::TYPE_RECEIVE){

        }else if($warehouse->type == WareHouse::TYPE_DISTRIBUTION){

        }

        $this->__writeActionLog($request, $warehouse, $currentUser);
        return true;
    }

    private function __writeActionLog(Request $request, WareHouse $warehouse, User $currentUser){
        $scan = new Scan();
        $scan->barcode = $request->get('barcode');
        $scan->action = $request->get('action');
        $scan->warehouse = $request->get('warehouse');
        $scan->created_by = $currentUser->id;
        return $scan->save();
    }
}
