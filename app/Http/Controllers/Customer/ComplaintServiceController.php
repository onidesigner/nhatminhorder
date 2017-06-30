<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 15/04/2017
 * Time: 13:07
 */

namespace App\Http\Controllers\Customer;


use App\ComplaintFiles;
use App\Complaints;
use App\Http\Controllers\Controller;
use App\Order;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ComplaintServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

        $order_id = $request->route('order_id');

        #region --validate--
        $order = Order::findOneByIdOrCode($order_id);
        $current_user = User::find(Auth::user()->id);

        if(!$order || !$order instanceof Order):
            return redirect('404');
        endif;

        $customer = User::find($order->user_id);

        if($customer->id != $current_user->id):
            return redirect('403');
        endif;

        #endregion --end validate--

        return view('customer/complaint_create',[
            'data' => $order,
            'page_title' => 'Khiếu nại'
        ]);
    }

    /**
     * lấy danh sách của khiếu nại
     * của khách hàng đó
     */
   public function listComplaint( Request $request ){

       $order_code = $request->get('ordercode');// mã code của KN
       $complaint_status = $request->get('status_complaint');

       $where = [];
       $condition = [];

       if($order_code && $complaint_status){
           $order = Order::findOneByIdOrCode($order_code);
           if($order instanceof  Order){
               $where = [
                   'order_id' => $order->id,
                   'status' => $complaint_status
               ];
               $condition = [
                   'ordercode' => $order_code,
                   'status' => $complaint_status
               ];

           }
       }
       if(!$order_code && $complaint_status){
           $where = [
               'status' => $complaint_status
           ];
           $condition = ['status' => $complaint_status];
       }
       if($order_code && !$complaint_status){
           $order = Order::findOneByIdOrCode($order_code);
           if($order instanceof  Order){
               $where = [
                   'order_id' => $order->id,
               ];
               $condition = ['ordercode' => $order_code,];
           }
       }

        $list  = DB::table('complaints')
            ->where('customer_id', '=', Auth::user()->id)
            ->where($where)
            ->orderBy('id', 'desc')->paginate(20);
       
       return view('customer/complaint_list',[
           'data' => $list->appends($condition),
           'complaint_status' => Complaints::$alias_array,
           'page_title' => 'Khiếu nại'
       ]);

   }

    /**
     * ham tao khieu nai
     * tạo khiếu nại
     */



    /**
     * khach hang tao khieu nai
     */
    public function createComplaintCustomer(Request $request){

        $order_code = $request->get('order_code','');
        $compalaint_name = $request->get('complaint_name','');
        $description = $request->get('description','');
        $image_path = $request->get('image_path',[]);
        $title = $request->get('title');


        $order = Order::findOneByIdOrCode($order_code);
        if($order instanceof Order){
            if($compalaint_name == ''){
                return response()->json([
                    'type' => 'error',
                    'message' => 'Bạn vui lòng nhập tên khiếu nại !'
                ]);
            }
            if($description == ''){
                return response()->json([
                    'type' => 'error',
                    'message' => 'Bạn vui lòng mô tả khiếu nại !'
                ]);
            }
            #region --tao khieu nai--
            $complaint = new Complaints();
            $complaint->order_id = $order->id;
            $complaint->customer_id = $order->user_id; // don hang cua khach hang
            $complaint->create_by = Auth::user()->id; // ai la tao khieu nai
            $complaint->description = $description;
            $complaint->status = Complaints::STATUS_CREATE;
            $complaint->title = $title;
            $complaint->created_time = date('Y-m-d H:i:s',time());

            $complaint->save();
            #endregion --ket thuc tao khieu nai--

            #region --luu lai duong dan file--
                if(count($image_path) > 0){
                    foreach ($image_path as $item_image){
                        $complaint_file = new ComplaintFiles();
                        $complaint_file->name = $order->code;
                        $complaint_file->path = $item_image;
                        $complaint_file->complaint_id = $complaint->id;
                        $complaint_file->create_time = date('Y-m-d H:i:s',time());
                        $complaint_file->save();
                    }
                }
            #endregion --ket thu luu laij duong dan file--
            #return redirect('chi-tiet-khieu-nai/'.$complaint->id)->with('message','Tạo khiệu nại thành công !');
            return response()->json([
                'type' => 'success',
                'message' => 'Thành công',
                'complaint_id' => $complaint->id,
                'url' => 'chi-tiet-khieu-nai/'
            ]);


        }else{

            return response()->json([
                'type' => 'error',
                'message' => 'Không tồn tại đơn hàng !'
            ]);
        }



    }

    /**
     * hàm thực hiện upload ảnh mà ko cần click submit
     */
    public function uploadImageInComplaint(){

        if($_FILES["file"]["name"] != '')
        {
            $test = explode('.', $_FILES["file"]["name"]);
            $ext = end($test);
            $name = rand(100,999) . '.' . $ext;
            $location = './uploads/' . $name;
            $path = '/uploads/'.$name;
            move_uploaded_file($_FILES["file"]["tmp_name"], $location);

            echo ' <span>
                        <div class="col-md-4" style="margin:5px 0px 5px 0px;" >
                            <img src="'.$path.'" height="160" width="250" class="_display_image">
                        </div>
                        <div class="col-md-1" style="margin-top: 1px;margin-left: -15px;">
                            <i class="fa fa-times _remove_class" aria-hidden="true"></i>
                        </div>
                                             
                    </span>
            ';
        }
    }
    
    /**
     * ham redirect sang chi tiet khieu nai
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|
     * \Illuminate\Http\RedirectResponse|\Illuminate\Routing\
     * Redirector|\Illuminate\View\View
     */
    public function complaintDetail(Request $request){

        $complaint_id = $request->route('complaint_id');

        $list = Complaints::where(['id' => $complaint_id])->first();

        if($list instanceof  Complaints){
            $customer_id = $list->customer_id;
            $current_id = Auth::user()->id;
            if($customer_id != $current_id){
                return redirect('404');
            }
            $complaint = ComplaintFiles::where(['complaint_id' => $complaint_id])->get();
            $data_complaint = [];
            if (count($complaint) > 0){
                $data_complaint = $complaint;
            }
            // xu ly du lieu tra ve
            return view('customer/complaint_detail',[
                'data_complaint' => $list,
                'data_complaint_file' => $data_complaint,
                'page_title' => 'Khiếu nại'
            ]);
        }else{
            return redirect('404');
        }
    }

    

}