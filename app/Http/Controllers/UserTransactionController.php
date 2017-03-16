<?php

namespace App\Http\Controllers;

use App\Permission;
use App\UserTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Order;

class UserTransactionController extends Controller
{
    protected $table = 'user_transaction';

    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * @author vanhs
     * @desc Danh sach giao dich
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTransactions(){
        $can_view = Permission::isAllow(Permission::PERMISSION_TRANSACTION_VIEW);
        if(!$can_view):
            return redirect('403');
        endif;

        $can_create_transaction = Permission::isAllow(Permission::PERMISSION_TRANSACTION_CREATE);

        $per_page = 20;
        $transactions = UserTransaction::orderBy('id', 'desc')
            ->paginate($per_page);

        return view('transactions', [
            'page_title' => 'Lịch sử giao dịch ',
            'can_create_transaction' => $can_create_transaction,
            'transactions' => $transactions
        ]);
    }


    /**
     * @author vanhs
     * @desc Kiem tra cac dieu kien dau vao khi tao giao dich
     * @param $data_insert
     * @return mixed
     */
    private function __validateBeforeCreateTransactionAdjustment($data_insert){
        $can_create_transaction = Permission::isAllow(Permission::PERMISSION_TRANSACTION_CREATE);
        if(!$can_create_transaction):
            return ['success' => false, 'message' => 'not permission'];
        endif;

        $rules = [
            'transaction_type' => 'required',
            'amount' => 'required|has_amount_value',
            'transaction_note' => 'required'
        ];

        switch ($data_insert['transaction_type']):

            case UserTransaction::TRANSACTION_TYPE_ADJUSTMENT:
                $rules['user_id'] = 'required|user_exists';
                $rules['transaction_adjustment_type'] = 'required';
                break;

            case UserTransaction::TRANSACTION_TYPE_PAYMENT:
            case UserTransaction::TRANSACTION_TYPE_REFUND:

                $rules['object_type'] = 'required';
                switch ($data_insert['object_type']){
                    case UserTransaction::OBJECT_TYPE_ORDER:
                        $rules['order_code'] = 'required|order_exists';
                        break;
                }

                break;

            case UserTransaction::TRANSACTION_TYPE_GIFT:
                $rules['user_id'] = 'required|user_exists';
                break;
            default:
                break;
        endswitch;

        $messages = [
            'transaction_type.required' => 'Loại giao dịch không để trống !',
            'amount.required' => 'Số tiền không để trống !',
            'transaction_note.required' => 'Lý do không để trống !',
            'user_id.required' => 'Vui lòng chọn khách hàng !',
            'transaction_adjustment_type.required' => 'Vui lòng chọn loại điều chỉnh !',
            'object_type.required' => 'Vui lòng chọn đối tượng !',
            'amount.has_amount_value' => 'Số tiền không hợp lệ !',
            'user_id.user_exists' => 'Tài khoản khách hàng không hợp lệ !',
            'order_code.order_exists' => 'Đơn hàng không hợp lệ !',
            'order_code.required' => 'Mã đơn hàng không để trống !',
        ];

        Validator::extend('has_amount_value', function ($attribute, $value, $parameters, $validator) {
            return $value > 0;
        });

        Validator::extend('user_exists', function ($attribute, $value, $parameters, $validator) {
            $exists = User::select('id')
                ->where([
                    'id' => $value,
                    'status' => User::STATUS_ACTIVE,
                    'section' => User::SECTION_CUSTOMER
                ])
                ->first();
            if($exists):
                return true;
            endif;
            return false;
        });

        Validator::extend('order_exists', function ($attribute, $value, $parameters, $validator) {
            $exists = Order::select('id')
                ->where([
                    'code' => $value
                ])
                ->first();
            if($exists):
                return true;
            endif;
            return false;
        });

        $validator = Validator::make($data_insert, $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return array('success' => false, 'message' => implode('<br>', $errors) );
        }

        return ['success' => true];
    }

    /**
     * @author vanhs
     * @desc Tao giao dich: luu thong tin vao bang user_transaction, cap nhat tai chinh khach
     * @param User $user
     * @param $state
     * @param $amount
     * @param $transaction_type
     * @param $transaction_detail
     * @param $transaction_note
     * @param string $order_id
     * @param $object_type
     */
    private function __createTransaction(User $user, $state, $amount, $transaction_type,
                                         $transaction_detail, $transaction_note,
                                         $order_id = '', $object_type= ''){
        $code = UserTransaction::generateTransactionCode();

        $ending_balance = $user->account_balance + $amount;

        UserTransaction::insert([
            'ending_balance' => $ending_balance,
            'user_id' => $user->id,
            'state' => $state,
            'object_id' => $order_id,
            'object_type' => $object_type,
            'transaction_code' => $code,
            'transaction_type' => $transaction_type,
            'amount' => $amount,
            'created_by' => Auth::user()->id,
            'transaction_detail' => $transaction_detail,
            'transaction_note' => $transaction_note,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $user->updateAccountBalance($amount, $user->id);
    }

    public function createTransactionAdjustment(Request $request){
        try{

            $data_insert = $request->all();
            $data_insert['created_at'] = date('Y-m-d H:i:s');

            unset($data_insert['_token']);

            $validate_data = $this->__validateBeforeCreateTransactionAdjustment($data_insert);
            if(!$validate_data['success']):
                return Response::json(array('success' => false, 'message' => $validate_data['message'] ));
            endif;

            switch ($data_insert['transaction_type']):
                case UserTransaction::TRANSACTION_TYPE_ADJUSTMENT:

                    if($data_insert['transaction_adjustment_type'] == 'negative'):
                        $data_insert['amount'] = 0 - $data_insert['amount'];
                    endif;

                    $user = User::find($data_insert['user_id']);

                    $this->__createTransaction($user, UserTransaction::STATE_COMPLETED, $data_insert['amount'],
                        $data_insert['transaction_type'], $data_insert['transaction_note'],
                        $data_insert['transaction_note']);

                    break;

                case UserTransaction::TRANSACTION_TYPE_GIFT:

                    $user = User::find($data_insert['user_id']);

                    $this->__createTransaction($user, UserTransaction::STATE_COMPLETED, $data_insert['amount'],
                        $data_insert['transaction_type'], $data_insert['transaction_note'],
                        $data_insert['transaction_note']);

                    break;

                case UserTransaction::TRANSACTION_TYPE_REFUND:
                case UserTransaction::TRANSACTION_TYPE_PAYMENT:

                    if($data_insert['transaction_type'] == UserTransaction::TRANSACTION_TYPE_PAYMENT):
                        $data_insert['amount'] = 0 - $data_insert['amount'];
                    endif;

                    $object = null;
                    $object_id = null;
                    $object_type = $data_insert['object_type'];
                    switch ($data_insert['object_type']):
                        case UserTransaction::OBJECT_TYPE_ORDER:
                            $object = Order::select('*')
                                ->where([
                                    'code' => $data_insert['order_code']
                                ])
                                ->first();
                            $object_id = $object->code;
                            break;
                    endswitch;

                    $user = User::find($object->buyer_id);

                    $this->__createTransaction($user, UserTransaction::STATE_COMPLETED, $data_insert['amount'],
                        $data_insert['transaction_type'], json_encode($object),
                        $data_insert['transaction_note'], $object_id, $object_type);

                    break;

            endswitch;

            DB::commit();

            return Response::json(['success' => true, 'message' => 'insert success!']);
        }catch(\Exception $e){
            DB::rollback();
            return Response::json(['success' => false, 'message' => 'insert fail!' . $e->getMessage()]);
        }
    }

    public function renderTransactionAdjustment(){
        $user = new User();
        $users_customer = $user->newQuery()->where([
            'status' => User::STATUS_ACTIVE,
            'section' => User::SECTION_CUSTOMER,
        ])->orderBy('name', 'asc')->get()->toArray();

        return view('transaction_adjustment', [
            'page_title' => 'Tạo giao dịch điều chỉnh tài chính ',
            'users_customer' => $users_customer
        ]);
    }
}
