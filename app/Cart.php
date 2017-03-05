<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Auth;
use App\CartItem;

class Cart extends Model
{
    public $deposit_percent = 50;

    protected $table_name = 'carts';

    public function getDepositPercent(){
        return $this->deposit_percent;
    }

    public function getDepositAmount($total_amount){
        $deposit_amount = $total_amount * $this->getDepositPercent() / 100;
        return $deposit_amount;
    }

    public function getItems(){
        return CartItem::where([
            'cart_id' => $this->id,
            'user_id' => Auth::user()->id
        ])->get();
    }

    public function depositOrder($user_id, $shop_id, $address_id, $deposit_amount){
        DB::beginTransaction();

        try{

            $shops = Cart::where([
                'user_id' => $user_id
            ])
            ->whereIn('shop_id', $shop_id)
            ->get();

            foreach($shops as $shop):

                if(!$shop):
                    continue;
                endif;

                $cart_services = $shop->services;
                $cart_comment = $shop->comment;
                $cart_items = $shop->getItems();
                $exchange_rate = Exchange::getExchange();

                //todo:: can bo sung them 2 truong nay
                $destination_warehouse = '';
                $code = '';

                $total_amount = 0;
                $order_quantity = 0;

                $insert_id_order = Order::insertGetId([
                    'user_id' => $shop->user_id,
                    'code' => $code,
                    'destination_warehouse' => $destination_warehouse,
                    'shop_id' => $shop->shop_id,
                    'shop_name' => $shop->shop_name,
                    'shop_link' => $shop->link,
                    'shop_avatar' => $shop->avatar ? urldecode($shop->avatar) : '',
                    'site' => strtolower($shop->site),
                    'exchange_rate' => $exchange_rate,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $order_item_insert = [];
                if($cart_items):
                    foreach($cart_items as $cart_item):
                        if(!$cart_item):
                            continue;
                        endif;

                        $order_item_insert[] = [
                            'user_id' => $cart_item->user_id,
                            'order_id' => $insert_id_order,
                            'title' => $cart_item->title_origin,
                            'title_translated' => $cart_item->title_translated,
                            'link' => $cart_item->link_origin,
                            'image' => $cart_item->image_model ? urldecode($cart_item->image_model) : '',
                            'property' => $cart_item->property,
                            'property_translated' => $cart_item->property_translated,
                            'property_value' => $cart_item->data_value,
                            'price' => $cart_item->price_origin,
                            'price_promotion' => $cart_item->promotion,
                            'price_table' => $cart_item->price_table,
                            'order_quantity' => $cart_item->quantity,
                            'tool' => $cart_item->tool,
                            'step' => $cart_item->step,
                            'item_id' => $cart_item->item_id,
                            'require_min' => $cart_item->require_min,
                            'stock' => $cart_item->stock,
                            'site' => $cart_item->site,
                            'comment' => $cart_item->comment,
                            'location_sale' => $cart_item->location_sale,
                            'created_at' => date('Y-m-d H:i:s'),
                        ];


                        $order_quantity += $cart_item->quantity;
                        $total_amount += $cart_item->getPriceCalculator();
                    endforeach;
                endif;

                Order::where([
                    'id' => $insert_id_order
                ])->update([
                    'total_amount' => $total_amount,
                    'order_quantity' => $order_quantity,
                    'deposit_at' => date('Y-m-d H:i:s'),
                    'status' => Order::STATUS_DEPOSITED,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                if(count($order_item_insert)):
                    OrderItem::insert($order_item_insert);
                endif;

                if($cart_services):
                    $service_data_insert = [];
                    $cart_services = explode('|', $cart_services);
                    foreach($cart_services as $cart_service):
                        $service_data_insert[] = [
                            'order_id' => $insert_id_order,
                            'service_code' => $cart_service,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    endforeach;

                    if(count($service_data_insert)):
                        OrderService::insert($service_data_insert);
                    endif;
                endif;

                if($cart_comment):
                    Comment::insert([
                        'user_id' => $user_id,
                        'object_id' => $insert_id_order,
                        'object_type' => Comment::TYPE_OBJECT_ORDER,
                        'scope' => Comment::TYPE_EXTERNAL,
                        'message' => $cart_comment,
                        'type_context' => Comment::TYPE_CONTEXT_CHAT,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                endif;

            endforeach;

            $order = Order::find($insert_id_order);

            $deposit_amount = 0 - $deposit_amount;
            $transaction_note = sprintf('Dat coc don hang %s', $order->code);

            User::where(['id' => $user_id])->update([
                'account_balance' => DB::raw("account_balance+$deposit_amount")
            ]);

            UserTransaction::insert([
                'user_id' => $user_id,
                'state' => UserTransaction::STATE_COMPLETED,
                'transaction_code' => UserTransaction::generateTransactionCode(),
                'transaction_type' => UserTransaction::TRANSACTION_TYPE_ORDER_DEPOSIT,
                'ending_balance' => User::find($user_id)->account_balance,
                'created_by' => $user_id,
                'object_id' => $insert_id_order,
                'object_type' => UserTransaction::OBJECT_TYPE_ORDER,
                'amount' => $deposit_amount,
                'transaction_detail' => json_encode($order),
                'transaction_note' => $transaction_note,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            Cart::where(['user_id' => $user_id])
                ->whereIn('shop_id', $shop_id)
                ->delete();

            CartItem::where(['user_id' => $user_id])
                ->whereIn('shop_id', $shop_id)
                ->delete();

            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollback();
            throw $e;
        }
    }

    /**
     * @author vanhs
     * @desc Kiem tra xem thong tin shop da ton tai trong gio hang hay chua, doi voi tung user
     * @param $shop_id
     * @param $user_id
     * @return bool
     */
    public static function checkExistsShopWithUser($shop_id, $user_id){
        $row = DB::table('carts')->select('id', 'shop_id')
            ->where('user_id', $user_id)
            ->where('shop_id', $shop_id)
            ->first();
        if($row):
            return $row;
        endif;
        return false;
    }

    /**
     * @author vanhs
     * @desc Them san pham vao gio hang
     * @param $params
     * @return bool
     * @throws \Exception
     */
    public static function addCart($params){
        DB::beginTransaction();

        try {
            $params['site'] = strtolower($params['site']);
            $user_id = 1;//fixdata
            $now = date('Y-m-d H:i:s');

            //check exists shop with user
            $exist_shop_id = self::checkExistsShopWithUser($params['shop_id'], $user_id);
            if(!$exist_shop_id):
                $data_insert_cart = [
                    'user_id' => $user_id,
                    'shop_id' => $params['shop_id'],
                    'shop_name' => $params['shop_name'],
                    'shop_link' => null,
                    'avatar' => $params['image_model'],
                    'site' => $params['site'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];

                $insert_id_cart = DB::table('carts')->insertGetId($data_insert_cart);
            else:
                DB::table('carts')
                    ->where(['shop_id' => $params['shop_id'], 'user_id' => $user_id])
                    ->update(['updated_at' => $now]);
                $insert_id_cart = $exist_shop_id->id;
            endif;

            $exist_cart_item_with_property = CartItem::checkExistsCartItemWithItemAndProperty(
                $params['shop_id'],
                $user_id,
                $params['item_id'],
                $params['data_value']
            );

            $exchange = new Exchange();
            $exchange_rate = $exchange->getExchange();

            if(!$exist_cart_item_with_property):
                $data_insert_item = $params;
                $data_insert_item['cart_id'] = $insert_id_cart;
                $data_insert_item['user_id'] = $user_id;
                $data_insert_item['price_vnd'] = $data_insert_item['price_promotion'] * $exchange_rate;
                $data_insert_item['property_md5'] = CartItem::genPropertyMd5($params['item_id'], $params['data_value']);
                $data_insert_item['created_at'] = $now;
                unset($data_insert_item['version']);
                unset($data_insert_item['is_translate']);

                DB::table('cart_items')->insert($data_insert_item);
            else:
                DB::table('cart_items')
                ->where('id', $exist_cart_item_with_property->id)
                ->update([
                    'quantity' => DB::raw("quantity+{$params['quantity']}"),
                ]);
            endif;

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            return false;
        }

    }
}
