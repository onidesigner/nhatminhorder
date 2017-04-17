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

        $order_id = $request->route('id');

        #region --validate--
//        $order = Order::findOneByIdOrCode($order_id);
//        $current_user = User::find(Auth::user()->id);
//
//        if(!$order || !$order instanceof Order):
//            return redirect('404');
//        endif;
//
//        $customer = User::find($order->user_id);
//
//        if($customer->id != $current_user->id):
//            return redirect('403');
//        endif;

        #endregion --end validate--

        return view('customer/complaint_create',[
           # 'data' => $order,
            'page_title' => 'Khiếu nại'
        ]);
    }

    /**
     * lấy danh sách của khiếu nại
     */
   public function listComplaint(){
        $list  = DB::table('complaints')
            ->where('user_id', '=', Auth::user()->id)
            ->orderBy('id', 'desc')->paginate(20);
       return view('customer/complaint_list',[
            'data' => $list,
           'page_title' => 'Khiếu nại'
       ]);

   }

    /**
     * ham tao khieu nai
     * tạo khiếu nại
     */
    public function createComplaint()
    {
        $img = $_FILES['image'];
        $order_code = $_POST['order_code'];
        $title = $_POST['title_complaint'];
        $comment = $_POST['comment'];


        $order = Order::findOneByIdOrCode($order_code);

        if($order instanceof  Order){
            $order_id = $order->id;
        }else{
            return redirect('404');
        }

        if (!$order_code){
            return redirect('tao-khieu-nai/'.$order_id)->with('error','Mã đơn hàng không tồn tại !');
        }
        if(!$title){
            return redirect('tao-khieu-nai/'.$order_id)->with('error','Tên khiếu nại không được bỏ trống !');
        }
        if(!$comment){
            return redirect('tao-khieu-nai/'.$order_id)->with('error','Bạn chưa mô tả lỗi sản phẩm !');
        }



        $complaint = new Complaints();
        $complaint_data = [
            'order_id' => $order_id,
            'customer_id' => Auth::user()->id,
            'title' => $title,
            'comment' => $comment,
            'status' => Complaints::STATUS_CREATE
        ];
        $complaint_id =  $complaint->createComplaint($complaint_data);

        if(!$complaint_id){
            return redirect('tao-khieu-nai/'.$order_id)->with('error','Tạo khiếu nại thất bại !');
        }

        $complaint_file = new ComplaintFiles();

        if(!empty($img))
        {
            $errors = [];
            $img_desc = $this->reArrayFiles($img);
            foreach($img_desc as $val)
            {
                $newname = date('YmdHis',time()).mt_rand().'.jpg';
                move_uploaded_file($val['tmp_name'],'./uploads/'.$newname);
                $path = './uploads/'.$newname;
                $complaint_data = [
                    'name' => 'image',
                    'path' => $path,
                    'complaint_id' => $complaint_id,
                ];
                $complaint_file->createComplaintFile($complaint_data);
            }
        }

        return redirect('tao-khieu-nai/'.$order_id)->with('message','Tạo khiệu nại thành công !');

    }

    /**
     * ham xử lý bên trong tạo khiếu nại
     * @param $file
     * @return array
     */
    private function reArrayFiles($file)
    {
        $file_ary = array();
        $file_count = count($file['name']);
        $file_key = array_keys($file);

        for($i=0;$i<$file_count;$i++)
        {
            foreach($file_key as $val)
            {
                $file_ary[$i][$val] = $file[$val][$i];
            }
        }
        return $file_ary;
    }


}