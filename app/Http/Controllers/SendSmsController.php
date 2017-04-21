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
                        $smsQuery = SendSms::where('email','=',$line['1']);
                        if($smsQuery instanceof SendSms){
                            //update member data
                            SendSms::where([
                                'email' => $line[1]
                            ])->update([
                                'name' => $line[0],
                                'phone' => $line[2],
                                'created' => $line[3],
                                'modified' => $line[3],
                                'status' => $line[4]
                            ]);
                        }else{
                            $send_sms = new SendSms();
                            $send_sms->insert([
                                [
                                    'name' => $line[0],
                                    'email' =>$line[1],
                                    'phone' => $line[2],
                                    'created' => $line[3],
                                    'modified' => $line[3],
                                    'status' => $line[4]
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
        $sms_customer = SendSms::select('*')->orderBy('id', 'ASC')->paginate($per_page);
        return view('send_sms',[
            'page_title' => 'send sms',
            'data_sms' => $sms_customer,
            'per_page' => $per_page,
            'page' => $page
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
            $list_number = [];
            foreach($numbers as $number ){
                $list_number[] = $number;

            }
            $content= 'nhatminh247.vn: giá vận chuyển chỉ 15k/cân. Hàng về 2-3 ngày(HN). HotLine:04.2262.6699';
            $list_numbersss = ["01649647164","090481197"];
            foreach ($list_numbersss as $number){
                $sms_send = new SendSmsToCustomer();
                $result = $sms_send->sendSms([$number],$content);

                if($result['status'] == 'success'){
                    SendSms::where('phone', $number)
                        ->update(['status' => 'SEND_SUCCESS']);
                }elseif ($result['status'] == null){
                    SendSms::where('phone', $number)
                        ->update(['status' => 'SEND_NOT_SUCCESS']);
                }elseif ($result['status'] == 'error'){
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
}
