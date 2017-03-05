<?php

namespace App\Http\Controllers;

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

    public function getTransactions(){
        $per_page = 20;
        $user_transaction = new UserTransaction();
        $transactions = $user_transaction->newQuery()
            ->orderBy('id', 'desc')
            ->paginate($per_page);

        return view('transactions', [
            'page_title' => 'Lich su giao dich',
            'transactions' => $transactions
        ]);
    }

    public function createTransactionAdjustment(Request $request){
        try{

            $data_insert = $request->all();
            $data_insert['created_at'] = date('Y-m-d H:i:s');

            unset($data_insert['_token']);

            #region -- begin validate --
            if(Auth::user()->section == User::SECTION_CUSTOMER):
                return Response::json(['success' => false, 'message' => 'Khong the tao giao dich!']);
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
                    switch ($data_insert['object_type']):
                        case UserTransaction::OBJECT_TYPE_ORDER:
                            $rules['order_code'] = 'required|order_exists';
                            break;
                    endswitch;

                    break;
                case UserTransaction::TRANSACTION_TYPE_GIFT:
                    $rules['user_id'] = 'required|user_exists';
                    break;

                default:

                    break;
            endswitch;

            $messages = [
                'transaction_type.required' => 'Loai giao dich khong de trong!',
                'amount.required' => 'So tien khong de trong!',
                'transaction_note.required' => 'Ly do khong de trong!',
                'user_id.required' => 'Vui long chon khach hang!',
                'transaction_adjustment_type.required' => 'Vui long chon loai dieu chinh!',
                'object_type.required' => 'Vui long chon doi tuong!',
                'amount.has_amount_value' => 'So tien khong hop le!',
                'user_id.user_exists' => 'Tai khoan khach hang khong hop le!',
                'order_code.order_exists' => 'Don hang khong hop le!',
                'order_code.required' => 'Ma don hang khong de trong!',
            ];

            Validator::extend('has_amount_value', function ($attribute, $value, $parameters, $validator) {
                return $value > 0;
            });

            Validator::extend('user_exists', function ($attribute, $value, $parameters, $validator) {
                $user = new User();
                $exists = $user->newQuery()->select('id')
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
                $order = new Order();
                $exists = $order->newQuery()->select('id')
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
                return Response::json(array('success' => false, 'message' => implode('<br>', $errors) ));
            }
            #endregion

            $user_transaction = new UserTransaction();

            $code = UserTransaction::generateTransactionCode();

            switch ($data_insert['transaction_type']):
                case UserTransaction::TRANSACTION_TYPE_ADJUSTMENT:

                    if($data_insert['transaction_adjustment_type'] == 'negative'):
                        $data_insert['amount'] = 0 - $data_insert['amount'];
                    endif;

                    $user = User::find($data_insert['user_id']);

                    $user_transaction->newQuery()->insert([
                        'ending_balance' => $user->account_balance,
                        'user_id' => $data_insert['user_id'],
                        'state' => UserTransaction::STATE_COMPLETED,
                        'transaction_code' => $code,
                        'transaction_type' => $data_insert['transaction_type'],
                        'amount' => $data_insert['amount'],
                        'created_by' => Auth::user()->id,
                        'transaction_detail' => $data_insert['transaction_note'],
                        'transaction_note' => $data_insert['transaction_note'],
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    $user->updateAccountBalance($data_insert['amount'], $data_insert['user_id']);

                    break;

                case UserTransaction::TRANSACTION_TYPE_GIFT:
                    $user = User::find($data_insert['user_id']);

                    $user_transaction->newQuery()->insert([
                        'ending_balance' => $user->account_balance,
                        'user_id' => $data_insert['user_id'],
                        'state' => UserTransaction::STATE_COMPLETED,
                        'transaction_code' => $code,
                        'transaction_type' => $data_insert['transaction_type'],
                        'amount' => $data_insert['amount'],
                        'created_by' => Auth::user()->id,
                        'transaction_detail' => $data_insert['transaction_note'],
                        'transaction_note' => $data_insert['transaction_note'],
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    $user->updateAccountBalance($data_insert['amount'], $data_insert['user_id']);

                    break;

                case UserTransaction::TRANSACTION_TYPE_REFUND:
                case UserTransaction::TRANSACTION_TYPE_PAYMENT:

                    if($data_insert['transaction_type'] == UserTransaction::TRANSACTION_TYPE_PAYMENT):
                        $data_insert['amount'] = 0 - $data_insert['amount'];
                    endif;

                    $object = null;
                    $object_type = null;
                    switch ($data_insert['object_type']):
                        case UserTransaction::OBJECT_TYPE_ORDER:
                            $object_type = UserTransaction::OBJECT_TYPE_ORDER;
                            $order = new Order();
                            $object = $order->newQuery()->select('*')
                                ->where([
                                    'code' => $data_insert['order_code']
                                ])
                                ->first();
                            break;
                    endswitch;

                    $user = User::find($object->buyer_id);

                    $user_transaction->newQuery()->insert([
                        'ending_balance' => $user->account_balance,
                        'user_id' => $object->buyer_id,
                        'object_id' => $object->id,
                        'object_type' => $object_type,
                        'state' => UserTransaction::STATE_COMPLETED,
                        'transaction_code' => $code,
                        'transaction_type' => $data_insert['transaction_type'],
                        'amount' => $data_insert['amount'],
                        'created_by' => Auth::user()->id,
                        'transaction_detail' => json_encode($object),
                        'transaction_note' => $data_insert['transaction_note'],
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    $user->updateAccountBalance($data_insert['amount'], $object->buyer_id);

                    break;

            endswitch;

            DB::commit();

            return Response::json(['success' => true, 'message' => 'Tao giao dich thanh cong!']);
        }catch(\Exception $e){
            DB::rollback();
            return Response::json(['success' => false, 'message' => 'Tao giao dich khong thanh cong!']);
        }
    }

    public function renderTransactionAdjustment(){
        $user = new User();
        $users_customer = $user->newQuery()->where([
            'status' => User::STATUS_ACTIVE,
            'section' => User::SECTION_CUSTOMER,
        ])->orderBy('name', 'asc')->get()->toArray();

        return view('transaction_adjustment', [
            'page_title' => 'Tao dieu chinh tai chinh',
            'users_customer' => $users_customer
        ]);
    }
}
