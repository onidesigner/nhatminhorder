<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use App\OrderItem;

class Order extends Model
{
    protected $table = 'order';


    const SITE_TAOBAO = 'taobao';
    const SITE_TMALL = 'tmall';
    const SITE_1688 = '1688';

    const STATUS_DEPOSITED = '';
    const STATUS_BUYING = '';
    const STATUS_NEGOTIATING = '';
    const STATUS_NEGOTIATED = '';
    const STATUS_BOUGHT = '';
    const STATUS_SELLER_DELIVERY = '';
    const STATUS_RECEIVED_FROM_SELLER = '';
    const STATUS_TRANSPORTING = '';
    const STATUS_CHECKING = '';
    const STATUS_CHECKED = '';
    const STATUS_WAITING_FOR_DELIVERY = '';
    const STATUS_CUSTOMER_CONFIRM_DELIVERY = '';
    const STATUS_DELIVERING = '';
    const STATUS_RECEIVED = '';
    const STATUS_OUT_OF_STOCK = '';
    const STATUS_CANCELLED = '';
    const STATUS_LOST = '';

    public static $statusTitle = array(
        self::STATUS_DEPOSITED => 'Đã đặt cọc',
        self::STATUS_BUYING => 'Đang mua hàng',
        self::STATUS_NEGOTIATING => 'Đang đàm phán',
        self::STATUS_NEGOTIATED => 'Đã đàm phán',
        self::STATUS_BOUGHT => 'Đã mua hàng',
        self::STATUS_SELLER_DELIVERY => "Người bán giao",
        self::STATUS_RECEIVED_FROM_SELLER => "NhatMinh247 Nhận",
        self::STATUS_TRANSPORTING => "Vận chuyển",
        self::STATUS_CHECKING => 'Đang kiểm hàng',
        self::STATUS_CHECKED => 'Đã kiểm hàng',
        self::STATUS_WAITING_FOR_DELIVERY => "Chờ giao hàng",
        self::STATUS_CUSTOMER_CONFIRM_DELIVERY => "Yêu cầu giao",
        self::STATUS_DELIVERING => "Đang giao hàng",
        self::STATUS_RECEIVED => 'Khách nhận hàng',
        self::STATUS_OUT_OF_STOCK => 'Hết hàng',
        self::STATUS_CANCELLED => "Hủy bỏ",
        self::STATUS_LOST => 'Thất lạc'
    );

    /**
     * @desc Lay danh sach san pham nam trong don hang
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getItemInOrder()
    {
        $order_item = new OrderItem();
        return $order_item->getItemsInOrder($this->id);
    }

    /**
     * @desc Lay ra tong so san pham loai thuong
     * @return int
     */
    public function getItemNormalQuantity()
    {
        $total = 0;
        $items = $this->getItemInOrder();

        if (!empty($items)) {
            foreach ($items as $item) {
                if (!$item->checkItemAssess($this->exchange)) {
                    $total += $item->check_quantity;
                }
            }
        }

        return $total;
    }

    /**
     * @desc Lay ra tong so san pham la phu kien
     * @return int
     */
    public function getItemAssessQuantity()
    {
        $total = 0;
        $items = $this->getItemInOrder();

        if (!empty($items)) {
            foreach ($items as $item) {
                if ($item->checkItemAssess($this->exchange)) {
                    $total += $item->check_quantity;
                }
            }
        }

        return $total;
    }

}
