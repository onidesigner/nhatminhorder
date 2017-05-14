<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 19/04/2017
 * Time: 14:01
 */

namespace App\Http\Controllers;


use App\Library\Sms\SendSmsToCustomer;
use App\SendSms;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;


class SendSmsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ondex(){

        if(isset($_POST['importSubmit'])){
            $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
            if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$csvMimes)){
                if(is_uploaded_file($_FILES['file']['tmp_name'])){

                    //open uploaded csv file with read only mode
                    $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

                    //skip first line
                    fgetcsv($csvFile);

                    //parse data from csv file line by line
                    while(($line = fgetcsv($csvFile)) !== FALSE){
                        $smsQuery = SendSms::where('email',$line['1']);
                        if($smsQuery instanceof SendSms){
                            //update member data
                            SendSms::where([
                                'email' => $line[1]
                            ])->update([
                                'name' => $line[0],
                                'phone' => $line[2],
                                'level' => $line[3],
                                'created' => $line[4],
                                'modified' => $line[4],
                                'status' => $line[5]
                            ]);
                        }else{
                            $send_sms = new SendSms();
                            $send_sms->insert([
                                [
                                    'name' => $line[0],
                                    'email' =>$line[1],
                                    'phone' => $line[2],
                                    'level' => $line[3],
                                    'created' => $line[4],
                                    'modified' => $line[4],
                                    'status' => $line[5]
                                ]
                            ]);
                        }
                    }

                    //close opened csv file
                    fclose($csvFile);

                    $qstring = '?status=succ';
                }else{
                    $qstring = '?status=err';
                }
            }else{
                $qstring = '?status=invalid_file';
            }
        }

        return redirect('/send-sms'.$qstring);


    }

    public function index(){
        $per_page = 50;
        $page = Input::get('page');
        if(!$page || $page == 1){
            $page = 0;
        }else{
            $page = $page - 1;
        }


        $condition = Input::all();
        $where = [];



        if(!empty($condition['status'])){
            $where['status'] = $condition['status'];
        }
        if(!empty($condition['level'])){
            $where['level'] = $condition['level'];
        }
        if(!empty($condition['number'])){
            $where['number_send'] = $condition['number'];
        }



        
        $sms_customer = SendSms::where($where);


        $sms_customer = $sms_customer->orderBy('id', 'ASC');
        $sms_customer_list = $sms_customer->paginate($per_page);
        $result = $sms_customer->count();




        return view('send_sms',[
            'page_title' => 'send sms',
            'data_sms' => $sms_customer_list->appends($where),
            'per_page' => $per_page,
            'page' => $page,
            'count_item' =>$result
        ]);
    }

    /**
     * ham gui tin nhan
     */
    public function sendSms(){

        $numbers = Input::get('list_phone');

        if(count($numbers) <= 0){
            $response = array(
                'status' => 'error',
                'msg' => 'kiểm tra lại đường truyền !',
            );

            return  response()->json($response);
        }else{
            $list_number = ["966986304","1649647164"];
            foreach($numbers as $number ){
                $list_number[] = $number;

            }
            $arr = [" 04.2262.6699","04.2265.6699"];


            $rand_key = array_rand($arr,1);

            $content= 'Nhatminh247.vn: 
            Dịch vụ đặt hàng TQ,vận chuyển chỉ từ 13k/kg.
             Hàng về 2-3 ngày(HN), 4-5 ngày SG. HotLine: 04.2262.6699-04.2265.6699';



            foreach ($list_number as $number){


                $sms_send = new SendSmsToCustomer();
                $result = $sms_send->sendSms([$number],$content);
                if($result['status'] == 'success'){

                    $send_sms =  SendSms::where('phone', $number)->first();


                    SendSms::where('phone', $number)
                        ->update(['status' => 'SEND_SUCCESS']);

                }elseif ($result['status'] == null){

                    $send_sms =  SendSms::where('phone', $number)->first();

                    SendSms::where('phone', $number)
                        ->update(['status' => 'SEND_NOT_SUCCESS']);

                }elseif ($result['status'] == 'error'){
                    $send_sms =  SendSms::where('phone', $number)->first();

                    SendSms::where('phone', $number)
                        ->update(['status' => 'ERROR']);
                }
                Log::info('sms-send', [$result]);
            }
            $response = array(
                'status' => 'success',
                'msg' => $list_number,
                '$result' => $result
            );
            return response()->json($response);
        }
    }

    public function downloadExcel()
    {


        $per_page = 50;
       // $page = Input::get('page');
//        if(!$page || $page == 1){
//            $page = 0;
//        }else{
//            $page = $page - 1;
//        }
        $status = Input::get('status','');
        $number = Input::get('number','');



        $sms_customer = SendSms::select('*')->orderBy('id', 'ASC');
        if($status){
            $sms_customer = SendSms::where('status','=',$status);
        }
        if($number){
            $sms_customer = SendSms::where('number_send','=',$number);
        }




        $sms_customer = $sms_customer->paginate($per_page);
        $data = $sms_customer->toArray();


        return Excel::create('danh_sach_khach_hang', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download('csv');
    }
}
