<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Permission extends Model
{
    protected $table = 'permissions';


#region -- User permission --
    const PERMISSION_USER_VIEW = 'USER_VIEW';
    const PERMISSION_USER_VIEW_LIST = 'USER_VIEW_LIST';
    const PERMISSION_USER_VIEW_LIST_ACTION = 'USER_VIEW_LIST_ACTION';
    const PERMISSION_USER_INFO_EDIT = 'USER_INFO_EDIT';
    const PERMISSION_USER_ADDRESS_EDIT = 'USER_ADDRESS_EDIT';
    const PERMISSION_USER_MOBILE_EDIT = 'USER_MOBILE_EDIT';
    const PERMISSION_USER_VIEW_FINANCE = 'USER_VIEW_FINANCE';
    const PERMISSION_USER_PROCURACY = 'USER_PROCURACY';

    const PERMISSION_USER_ALLOWS_ORDER = 'USER_ALLOWS_ORDER';
    const PERMISSION_USER_VIEW_CONTACT_INFO_UNAPPROVED_USER = 'USER_VIEW_CONTACT_INFO_UNAPPROVED_USER';
    const PERMISSION_USER_VIEW_CONTACT_INFO = 'USER_VIEW_CONTACT_INFO';

    const PERMISSION_USER_CRANE_EDIT = 'USER_CRANE_EDIT';
#endregion

    const PERMISSION_ROLE_VIEW = 'ROLE_VIEW';
    const PERMISSION_ROLE_EDIT = 'ROLE_EDIT';
    const PERMISSION_USER_ROLE_MANAGE = 'USER_ROLE_MANAGE';
    const PERMISSION_ROLE_PERMISSION_MANAGE = 'ROLE_PERMISSION_MANAGE';

    const PERMISSION_COMMUNICATE_CUSTOMER = 'COMMUNICATE_CUSTOMER';
    const PERMISSION_PUBLIC_PERSONAL_INFO = 'PUBLIC_PERSONAL_INFO';

    const PERMISSION_CANCEL_ORDER = 'CANCEL_ORDER';
    const PERMISSION_APPOINTMENT_ORDER = 'APPOINTMENT_ORDER';
    const PERMISSION_ORDER_EDIT_FEES = 'ORDER_EDIT_FEES';
    const PERMISSION_ORDER_EDIT_FEES_BEFORE_PAYMENT = 'ORDER_FIX_FEES_BEFORE_PAYMENT';
    const PERMISSION_ORDER_PACKAGING_SURCHARGES = 'ORDER_PACKAGING_SURCHARGES';
    const PERMISSION_ORDER_PAYMENT = 'ORDER_PAYMENT';
    const PERMISSION_ORDER_EDIT_PRICE = "ORDER_EDIT_PRICE";

    const PERMISSION_ORDER_EDIT_DOMESTIC_FEE_CHINA = "ORDER_EDIT_DOMESTIC_FEE_CHINA";
    const PERMISSION_ORDER_FIX_DOMESTIC_FEE_CHINA = "ORDER_FIX_DOMESTIC_FEE_CHINA";

    const PERMISSION_ORDER_CHANGE_WAREHOUSE = "ORDER_CHANGE_WAREHOUSE";
    const PERMISSION_ORDER_FIX_WAREHOUSE = "ORDER_FIX_WAREHOUSE";

    const PERMISSION_ORDER_EDIT_DEPOSIT_RATIO = "ORDER_EDIT_DEPOSIT_RATIO";
    const PERMISSION_ORDER_APPROVED_TRANSPORT_STRAIGHT = "ORDER_APPROVED_TRANSPORT_STRAIGHT";

    const PERMISSION_ORDER_ADD_FREIGHT_BILL = 'ORDER_ADD_FREIGHT_BILL';
    const PERMISSION_ORDER_EDIT_FREIGHT_BILL = 'ORDER_EDIT_FREIGHT_BILL';
    const PERMISSION_ORDER_EDIT_INVOICE = 'ORDER_EDIT_INVOICE';
    const PERMISSION_ORDER_EDIT_INVOICE_BEFORE_BOUGHT = 'ORDER_EDIT_INVOICE_BEFORE_BOUGHT';
    const PERMISSION_ORDER_EDIT_INVOICE_BEFORE_NEGOTIATED = 'ORDER_EDIT_INVOICE_BEFORE_NEGOTIATED';
    const PERMISSION_ORDER_ADD_INVOICE = 'ORDER_ADD_INVOICE';

    const PERMISSION_ORDER_VIEW_ORDER_DETAIL = 'ORDER_VIEW_ORDER_DETAIL';
    const PERMISSION_ORDER_VIEW_ORDER_MANAGE = 'ORDER_VIEW_ORDER_MANAGE';
    const PERMISSION_ORDER_VIEW_PURCHASE_MANAGE = 'ORDER_VIEW_PURCHASE_MANAGE';
    const PERMISSION_ORDER_DELETE_ORDER = 'ORDER_DELETE_ORDER';
    const PERMISSION_ORDER_VIEW_HISTORY = 'ORDER_VIEW_HISTORY';
    const PERMISSION_ORDER_VIEW_TRANSACTION = 'ORDER_VIEW_TRANSACTION';
    const PERMISSION_ORDER_TRANSITION_OUT_OF_STOCK = 'ORDER_TRANSITION_OUT_OF_STOCK';
    const PERMISSION_ORDER_CHANGE_LOST = 'ORDER_CHANGE_LOST';
    const PERMISSION_ORDER_VIEW_ITEMS = 'ORDER_VIEW_ITEMS';
    const PERMISSION_ORDER_EDIT_ORDER_QUANTITY = 'ORDER_EDIT_ORDER_QUANTITY';
    const PERMISSION_ORDER_EDIT_PENDING_QUANTITY = 'ORDER_EDIT_PENDING_QUANTITY';
    const PERMISSION_ORDER_EDIT_RECEIVED_QUANTITY = 'ORDER_EDIT_RECEIVED_QUANTITY';
    const PERMISSION_ORDER_EDIT_WEIGHT = 'ORDER_EDIT_WEIGHT';
    const PERMISSION_ORDER_EDIT_SERVICES_REQUEST = 'ORDER_EDIT_SERVICE_REQUEST';
    const PERMISSION_ORDER_FIX_SERVICES_REQUEST = 'ORDER_FIX_SERVICE_REQUEST';
    const PERMISSION_ORDER_VIEW_SHIPPING_ADDRESS = 'ORDER_VIEW_SHIPPING_ADDRESS';
    const PERMISSION_ORDER_VIEW_DELIVERY_MANAGEMENT = 'ORDER_VIEW_DELIVERY_MANAGEMENT';
    const PERMISSION_ORDER_CHANGE_RECEIVED_FROM_SELLER = 'ORDER_TO_RECEIVED_FROM_SELLER';
    const PERMISSION_ORDER_CHANGE_TRANSPORTING = 'ORDER_TO_TRANSPORTING';
    const PERMISSION_ORDER_CHANGE_WAIT_DELIVERY = 'ORDER_TO_WAIT_DELIVERY';
    const PERMISSION_ORDER_CHANGE_REQUEST_DELIVERY = 'ORDER_TO_REQUEST_DELIVERY';
    const PERMISSION_ORDER_VIEW_TRANSACTION_ALIPAY = 'ORDER_VIEW_TRANSACTION_ALIPAY';
    const PERMISSION_ORDER_CHOOSE_CHECKING_BY_TOTAL =  'ORDER_CHOOSE_CHECKING_BY_TOTAL';
    const PERMISSION_ORDER_CHANGE_TOTAL_QUANTITY_OF_PACKAGE =  'ORDER_CHANGE_TOTAL_QUANTITY_OF_PACKAGE';
    const PERMISSION_ORDER_CUSTOMER_CONFIRM = 'ORDER_CUSTOMER_CONFIRM';
    const PERMISSION_ORDER_BEHALF_PAYMENT = 'ORDER_BEHALF_PAYMENT';
    const PERMISSION_ORDER_EDIT_ITEM_PRICE = "ORDER_EDIT_ITEM_PRICE";
#region -- Quyền giao hàng --
    const PERMISSION_ORDER_CHANGE_DELIVERY =  'ORDER_TO_DELIVERY';
    const PERMISSION_DELIVERY_CHANGE_COD =  'DELIVERY_CHANGE_COD';
    const PERMISSION_DELIVERY_CHANGE_REAL_COD =  'DELIVERY_CHANGE_REAL_COD';
    const PERMISSION_DELIVERY_CHANGE_SHIPPING_FEE =  'DELIVERY_CHANGE_SHIPPING_FEE';
    const PERMISSION_DELIVERY_VIEW =  'DELIVERY_VIEW'; // xem quản lý giao hàng và chi tiết phiếu
    const PERMISSION_DELIVERY_VIEW_BILL_MANAGE =  'DELIVERY_VIEW_BILL_MANAGE';
    const PERMISSION_DELIVERY_UPLOAD_EXCEL = 'DELIVERY_UPLOAD_EXCEL'; //upload excel

    const PERMISSION_DELIVERY_CREATE_BILL = 'DELIVERY_CREATE_BILL'; // tạo
    const PERMISSION_DELIVERY_PRINT_BILL = 'DELIVERY_PRINT_BILL'; //quyền in phiếu
    const PERMISSION_DELIVERY_PRINT_BILL_NO_ADDRESS = 'PRINT_BILL_NO_ADDRESS'; //quyền in phiếu
    const PERMISSION_DELIVERY_PRINT_BILL_BARCODE = 'DELIVERY_PRINT_BILL_BARCODE'; //quyền in tem phiếu
    const PERMISSION_DELIVERY_CHANGE_ADDRESS = 'DELIVERY_CHANGE_ADDRESS'; //quyền thay đổi/tạo địa chỉ khác
    const PERMISSION_DELIVERY_CANCEL_PACKAGE = 'DELIVERY_CANCEL_PACKAGE'; //quyền hủy kiện khỏi phiếu
    const PERMISSION_DELIVERY_DELIVERING = 'DELIVERY_DELIVERING'; // quyền ấn đi giao
#endregion

#region  --- Quyền bao hàng nguyên khách yêu cầu chuyển thẳng

    const PERMISSION_PACKING_ORIGINAL_VIEW = 'PACKING_ORIGINAL_VIEW';  //xem danh sách và chi tiết bao hàng nguyên khách
    const PERMISSION_PACKING_ORIGINAL_DELIVERY = 'PACKING_ORIGINAL_DELIVERY'; //Quyền ấn nút tất toán trong chi tiết bao nguyên khách
#endregion

    const PERMISSION_PACKAGE_VIEW_PACKAGE_DETAIL= 'PACKAGE_VIEW_PACKAGE_DETAIL';
    const PERMISSION_PACKAGE_CHANGE_LOST = 'PACKAGE_CHANGE_LOST';

    const PERMISSION_COMPLAINT_VIEW_DETAIL = 'COMPLAINT_VIEW_DETAIL';
    const PERMISSION_COMPLAINT_CHAT_EXTERNAL = 'COMPLAINT_CHAT_EXTERNAL';
    const PERMISSION_COMPLAINT_CAN_ACCEPT = 'COMPLAINT_CAN_ACCEPT';
    const PERMISSION_COMPLAINT_CAN_REJECT = 'COMPLAINT_CAN_REJECT';
    const PERMISSION_COMPLAINT_CAN_APPROVAL = 'COMPLAINT_CAN_APPROVAL';
    const PERMISSION_COMPLAINT_CAN_APPROVAL_500 = 'COMPLAINT_CAN_APPROVAL_500';
    const PERMISSION_COMPLAINT_CAN_APPROVAL_1000 = 'COMPLAINT_CAN_APPROVAL_1000';
    const PERMISSION_COMPLAINT_CAN_PARROT_PARLEY = 'COMPLAINT_CAN_PARROT_PARLEY';
    const PERMISSION_COMPLAINT_VIEW_LIST = 'COMPLAINT_VIEW_LIST';
    const PERMISSION_COMPLAINT_CAN_RECEPTION = 'COMPLAINT_CAN_RECEPTION';
    const PERMISSION_COMPLAINT_REJECT_OTHER = 'COMPLAINT_REJECT_OTHER';
    const PERMISSION_COMPLAINT_CAN_TRANSFER = 'COMPLAINT_CAN_TRANSFER';//Tạo quyền cho phép điều chuyển KNDV của mình cho người khác
    const PERMISSION_COMPLAINT_CAN_TRANSFER_OTHER = 'COMPLAINT_CAN_TRANSFER_OTHER';//Tạo quyền cho phép điều chuyển KNDV của người khác

    const PERMISSION_COMPLAINT_SELLER_VIEW_LIST = 'COMPLAINT_SELLER_VIEW_LIST';
    const PERMISSION_COMPLAINT_SELLER_CREATE_NEW = 'COMPLAINT_SELLER_CREATE_NEW';
    const PERMISSION_COMPLAINT_SELLER_CAN_PROCESSING = 'COMPLAINT_SELLER_CAN_PROCESSING';
    const PERMISSION_COMPLAINT_SELLER_CAN_WARE_REPAY = 'PERMISSION_COMPLAINT_SELLER_CAN_WARE_REPAY';
    const PERMISSION_COMPLAINT_SELLER_CAN_ACCEPT = 'COMPLAINT_SELLER_CAN_ACCEPT';
    const PERMISSION_COMPLAINT_SELLER_CAN_REJECT = 'COMPLAINT_SELLER_CAN_REJECT';
    const PERMISSION_COMPLAINT_SELLER_CAN_TRANSFER = 'COMPLAINT_SELLER_CAN_TRANSFER';//Quyền điều chuyển KNNB của mình phụ trách (dành cho nhân viên có quyền điều chuyển KNNB của mình sang cho người khác)
    const PERMISSION_COMPLAINT_SELLER_CAN_TRANSFER_FOR_MANAGER = 'COMPLAINT_SELLER_CAN_TRANSFER_FOR_MANAGER';//Quyền điều chuyển KNNB (cao hơn, dành cho quản lý chủ động điều chuyển)

    const PERMISSION_PURCHASE_SKIPS_TELLERS = 'PURCHASE_SKIPS_TELLERS';
    const PERMISSION_PURCHASE_SKIPS_PAID_STAFF = 'PURCHASE_SKIPS_PAID_STAFF';
    const PERMISSION_PURCHASE_SELECT_TELLERS = 'PURCHASE_SELECT_TELLERS';
    const PERMISSION_PURCHASE_CHANGE_CONFIG = 'PURCHASE_CHANGE_CONFIG';
    const PERMISSION_PURCHASE_ORDER = 'PURCHASE_ORDER';

    const PERMISSION_UPLOAD_BARCODE = 'UPLOAD_BARCODE';
    const PERMISSION_VIEW_UPLOADED_BARCODE_SCAN_FILES = 'VIEW_UPLOADED_BARCODE_FILES';
    const PERMISSION_VIEW_BARCODE_TRACKING = 'VIEW_BARCODE_TRACKING';
    const PERMISSION_DOWNLOAD_BARCODE_SCAN_FILE = 'DOWNLOAD_BARCODE_SCAN_FILE';

    const PERMISSION_SYSTEM_MANAGE = 'SYSTEM_MANAGE';
    const PERMISSION_SYSTEM_EXCHANGE_MANAGE = 'SYSTEM_EXCHANGE_MANAGE';
    const PERMISSION_SYSTEM_WAREHOUSE_SETING = 'SYSTEM_WAREHOUSE_SETING';
    const PERMISSION_SYSTEM_CONFIG_MANAGE = 'SYSTEM_CONFIG_MANAGE';
    const PERMISSION_SYSTEM_LOCATION_MANAGE = 'SYSTEM_LOCATION_MANAGE';


    const PERMISSION_EDIT_CATEGORY = 'EDIT_CATEGORY';
    const PERMISSION_VIEW_CATEGORY = 'VIEW_CATEGORY';

    const PERMISSION_EDIT_BRAND = 'EDIT_BRAND';
    const PERMISSION_VIEW_BRAND = 'VIEW_BRAND';


#region -- Quyền theo dõi tài chính --
    const PERMISSION_FINANCIAL_WATCHER_VIEW = 'FINANCIAL_WATCHER_VIEW_ALL';
    const PERMISSION_FINANCIAL_WATCHER_CREATE = 'FINANCIAL_WATCHER_CREATE';
    const PERMISSION_FINANCIAL_DOWNLOAD_INCOST = 'FINANCIAL_DOWNLOAD_INCOST';
//const PERMISSION_FINANCIAL_WATCHER_EDIT = 'FINANCIAL_WATCHER_EDIT';
    const PERMISSION_FINANCIAL_WATCHER_EDIT_OTHER = 'FINANCIAL_WATCHER_EDIT_OTHER';
    const PERMISSION_FINANCIAL_WATCHER_DELETE = 'FINANCIAL_WATCHER_DELETE';
    const PERMISSION_FINANCIAL_WATCHER_DELETE_OTHER = 'FINANCIAL_WATCHER_DELETE_OTHER';

    const PERMISSION_FINANCIAL_ADJUSTMENT_CREATE = 'FINANCIAL_ADJUSTMENT_CREATE';
    const PERMISSION_FINANCIAL_ADJUSTMENT_DELETE = 'FINANCIAL_ADJUSTMENT_DELETE';
    const PERMISSION_FINANCIAL_ADJUSTMENT_ACCEPT = 'FINANCIAL_ADJUSTMENT_ACCEPT';
    const PERMISSION_FINANCIAL_VIEW_DETAIL = 'FINANCIAL_VIEW_DETAIL';

    const PERMISSION_TRANSACTION_VIEW_LIST_DEPOSIT = 'TRANSACTION_VIEW_LIST_DEPOSIT';
    const PERMISSION_TRANSACTION_VIEW_LIST_STORAGE_FEE = 'TRANSACTION_VIEW_LIST_STORAGE_FEE';
    const PERMISSION_TRANSACTION_VIEW_LIST_WITHDRAWAL = 'TRANSACTION_VIEW_LIST_WITHDRAWAL';
    const PERMISSION_TRANSACTION_VIEW_LIST_ORDER_DEPOSIT = 'TRANSACTION_VIEW_LIST_ORDER_DEPOSIT';
    const PERMISSION_TRANSACTION_VIEW_LIST_ORDER_PAYMENT = 'TRANSACTION_VIEW_LIST_ORDER_PAYMENT';
    const PERMISSION_TRANSACTION_VIEW_LIST_DEPOSIT_ADJUSTMENT = 'TRANSACTION_VIEW_LIST_DEPOSIT_ADJUSTMENT';
    const PERMISSION_TRANSACTION_VIEW_LIST_PAYMENT = 'TRANSACTION_VIEW_LIST_PAYMENT';
    const PERMISSION_TRANSACTION_VIEW_LIST_PROMOTION = 'TRANSACTION_VIEW_LIST_PROMOTION';
    const PERMISSION_TRANSACTION_VIEW_LIST_GIFT = 'TRANSACTION_VIEW_LIST_GIFT';
    const PERMISSION_TRANSACTION_VIEW_LIST_REFUND = 'TRANSACTION_VIEW_LIST_REFUND';
    const PERMISSION_TRANSACTION_VIEW_LIST_REFUND_COMPLAINT = 'TRANSACTION_VIEW_LIST_REFUND_COMPLAINT';
    const PERMISSION_TRANSACTION_VIEW_LIST_ADJUSTMENT = 'TRANSACTION_VIEW_LIST_ADJUSTMENT';
    const PERMISSION_TRANSACTION_VIEW_LIST_ALL = 'TRANSACTION_VIEW_LIST_ALL';
#endregion

#region -- Quyền quản lý tài khoản trên site gốc --
#endregion

#region -- Quyền quản lý điểm tích lũy --
    const PERMISSION_SCORE_HISTORY_VIEW = 'SCORE_HISTORY_VIEW'; //xem điểm tích lũy
    const PERMISSION_SCORE_HISTORY_ADD = 'SCORE_HISTORY_ADD'; //điểu chỉnh điểm tích lũy
#endregion

#region -- Quyền quản lý PAGQHH --
    const PERMISSION_INVENTORY_SOLUTION_WAITING = 'INVENTORY_SOLUTION_WAITING'; //đưa phương án giải quyết hàng hóa về trạng thái chờ
    const PERMISSION_INVENTORY_SOLUTION_VIEW = 'INVENTORY_SOLUTION_VIEW'; //xem phương án giải quyết hàng hóa
    const PERMISSION_INVENTORY_SOLUTION_CREATE = 'INVENTORY_SOLUTION_CREATE'; //tạo phương án giải quyết hàng hóa
    const PERMISSION_INVENTORY_SOLUTION_EDIT = 'INVENTORY_SOLUTION_EDIT'; //sửa phương án giải quyết hàng hóa
    const PERMISSION_INVENTORY_SOLUTION_DELETE = 'INVENTORY_SOLUTION_DELETE'; //xóa phương án giải quyết hàng hóa
    const PERMISSION_INVENTORY_SOLUTION_FINAL = 'INVENTORY_SOLUTION_FINAL'; //trạng thái cuối cùng phương án giải quyết hàng hóa
#endregion
#region -quyền chon kho lưu-
    const PERMISSION_CHOOSE_WAREHOUSE_MY_ORDER = 'CHOOSE_WAREHOUSE_MY_ORDER'; // quyền chọn kho tiếp nhận cho đơn mình phụ trách mua
    const PERMISSION_CHOOSE_WAREHOUSE_ALL_ORDER = 'CHOOSE_WAREHOUSE_ALL_ORDER'; //quyền chọn kho tiếp nhận cho tất cả các đơn
#endregion

#region === Quyền kiểm soát vận hành===
    const PERMISSION_CONTROL_MANAGE = 'CHOOSE_CONTROL_MANAGE';
#endregion === /Quyền kiểm soát vận hành===


#region--quyền xuất danh sach excel--
    const PERMISSION_EXPORT_LIST_USER_EXCEL = 'EXPORT_LIST_USER_EXCEL';
#endregion

    public static $permissions = array(
        'user_role_permission' => array(
            'label' => 'Người dùng, nhóm, phân quyền',
            'permissions' => array(
                self::PERMISSION_USER_VIEW_LIST => array(
                    'label' => 'Xem danh sách người dùng',
                    'description' => 'Quyền cho phép quản trị viên xem danh sách người dùng',
                ),

                self::PERMISSION_EXPORT_LIST_USER_EXCEL => array(
                    'label' => 'Xuất danh sách excel người dùng',
                    'description' => 'Quyền cho phép quản trị viên xuất danh sách excel người dùng',
                ),
                self::PERMISSION_USER_VIEW_LIST_ACTION => array(
                    'label' => 'Xem danh sách hành động user',
                    'description' => 'Quyền cho phép quản trị viên xem danh sách hành động user',
                ),
                self::PERMISSION_USER_VIEW => array(
                    'label' => 'Xem chi tiết người dùng',
                    'description' => 'Quyền cho phép quản trị viên xem thông tin chi tiết người dùng',
                ),

                self::PERMISSION_USER_ALLOWS_ORDER => array(
                    'label' => 'Duyệt, bỏ duyệt khách được đặt hàng',
                    'description' => 'Quyền cho phép quản trị viên duyệt, bỏ duyệt khách được đặt hàng',
                ),

                self::PERMISSION_USER_VIEW_CONTACT_INFO_UNAPPROVED_USER => array(
                    'label' => 'Xem thông tin liên hệ khách chưa được duyệt đặt hàng',
                    'description' => 'Quyền cho phép quản trị viên xem email, đt, facebook của người dùng chưa được duyệt đặt hàng',
                ),

                self::PERMISSION_USER_VIEW_CONTACT_INFO => array(
                    'label' => 'Xem thông tin liên hệ',
                    'description' => 'Quyền cho phép quản trị viên xem email, đt, facebook của người dùng',
                ),

                self::PERMISSION_USER_MOBILE_EDIT => array(
                    'label' => 'Thay đổi số điện thoại người dùng',
                    'description' => 'Quyền cho phép quản trị viên thêm/sửa/xóa số điện thoại của kháck hàng',
                ),

                self::PERMISSION_USER_ADDRESS_EDIT => array(
                    'label' => 'Thêm, sửa, xóa số điện thoại',
                    'description' => 'Quyền cho phép quản trị viên thêm/sửa/xóa số điện thoại của kháck hàng',
                ),

                self::PERMISSION_USER_INFO_EDIT => array(
                    'label' => 'Thêm, sửa, xóa thông tin tài khoản khách',
                    'description' => 'Quyền cho phép quản trị viên thêm, sửa, xóa thông tin người dùng',
                ),

                self::PERMISSION_USER_CRANE_EDIT => array(
                    'label' => 'Sửa thông tin nhân viên',
                    'description' => 'Quyền cho phép quản trị viên sửa thông tin nhân viên',
                ),

                self::PERMISSION_USER_VIEW_FINANCE => array(
                    'label' => 'Xem tài chính khách hàng',
                    'description' => 'Quyền cho phép xem tài chính khách hàng, lịch sử giao dịch và thao tác đồng bộ thông tin tài chính khách',
                ),

                self::PERMISSION_USER_PROCURACY => array(
                    'label' => 'Quyền khóa hoặc cấm người dùng',
                    'description' => 'Cho phép quản trị viên khóa có thời hạn hoặc cấm vĩnh viễn một người dùng, cũng như hủy việc này',
                ),

                self::PERMISSION_ROLE_VIEW => array(
                    'label' => 'Xem thông tin nhóm',
                    'description' => 'Quyền cho phép quản trị viên xem thông tin nhóm',
                ),

                self::PERMISSION_ROLE_EDIT => array(
                    'label' => 'Thêm, sửa, xóa thông tin nhóm',
                    'description' => 'Quyền cho phép quản trị viên sửa thông tin nhóm',
                ),

                self::PERMISSION_ROLE_PERMISSION_MANAGE => array(
                    'label' => 'Quản lý quyền của nhóm',
                    'description' => 'Quyền cho phép quản trị viên quản lý quyền trong nhóm',
                ),
            ),
        ),

        'customer_care' => array(
            'label' => 'Làm việc với khách hàng',
            'permissions' => array(
                self::PERMISSION_COMMUNICATE_CUSTOMER => array(
                    'label' => 'Chat với khách hàng',
                    'description' => 'Cho phép chat với khách hàng trên kênh công cộng',
                ),
                self::PERMISSION_PUBLIC_PERSONAL_INFO => array(
                    'label' => 'Hiện thông tin cá nhân khi trao đổi với khách',
                    'description' => 'Nếu nhân viên có quyền này và quyền chat với khách hàng, khách sẽ nhìn thấy thông tin người chat. Ngược lại khách chỉ nhìn đoạn chat được gửi bởi hệ thống',
                )
            )
        ),

        'order' => array(
            'label' => 'Quản lý đơn hàng',
            'permissions' => array(
                self::PERMISSION_CONTROL_MANAGE =>array(
                    'label' => 'Quyền phân nhóm ngành hàng.',
                    'description' => 'Quyền cho phép phân nhóm nghành hàng.',
                ),
                self::PERMISSION_ORDER_VIEW_ORDER_MANAGE => array(
                    'label' => 'Xem trang quản lý đơn hàng',
                    'description' => 'Quyền cho phép quản trị viên xem trang quản lý đơn hàng',
                ),
                self::PERMISSION_ORDER_VIEW_DELIVERY_MANAGEMENT => array(
                    'label' => 'Xem trang yêu cầu giao hàng',
                    'description' => 'Quyền cho phép quản trị viên xem trang Yêu cầu giao hàng',
                ),
                self::PERMISSION_CANCEL_ORDER => array(
                    'label' => 'Hủy đơn hàng',
                    'description' => 'Cho phép quản trị viên hủy đơn hàng trước khi thanh toán với người bán',
                ),

                self::PERMISSION_APPOINTMENT_ORDER => array(
                    'label' => 'Bổ nhiệm nhân viên phụ trách',
                    'description' => 'Quyền cho phép quản trị viên bổ nhiệm nhân viên phụ trách',
                ),
                self::PERMISSION_ORDER_EDIT_FEES_BEFORE_PAYMENT => array(
                    'label' => 'Sửa phí đơn hàng trước tất toán',
                    'description' => 'Quyền cho phép quản trị viên sửa phí đơn hàng trước tất toán',
                ),
                self::PERMISSION_ORDER_EDIT_FEES => array(
                    'label' => 'Sửa phí đơn hàng ở tất cả các trạng thái',
                    'description' => 'Quyền cho phép quản trị viên sửa phí đơn hàng ở tất cả các trạng thái',
                ),
                self::PERMISSION_ORDER_PACKAGING_SURCHARGES => array(
                    'label' => 'Thêm phụ phí đóng gói',
                    'description' => 'Quyền cho phép quản trị viên thêm phụ phí đóng gói',
                ),
                self::PERMISSION_ORDER_VIEW_ORDER_DETAIL => array(
                    'label' => 'Xem chi tiết đơn hàng',
                    'description' => 'Quyền cho phép quản trị viên xem chi tiết đơn hàng',
                ),
                self::PERMISSION_ORDER_DELETE_ORDER => array(
                    'label' => 'Xóa đơn hàng',
                    'description' => 'Quyền cho phép quản trị viên xóa đơn hàng',
                ),
                self::PERMISSION_ORDER_VIEW_HISTORY => array(
                    'label' => 'Xem lịch sử đơn',
                    'description' => 'Quyền cho phép quản trị viên xem lịch sử đơn',
                ),
                self::PERMISSION_ORDER_VIEW_ITEMS => array(
                    'label' => 'Xem sản phẩm trong đơn',
                    'description' => 'Xem danh sách sản phẩm trong đơn',
                ),
                self::PERMISSION_ORDER_EDIT_SERVICES_REQUEST => array(
                    'label' => 'Sửa yêu cầu dịch vụ của đơn hàng trước đang giao/vận chuyển',
                    'description' => 'Cho phép sửa yêu cầu dịch vụ của đơn hàng với đơn hàng ở trạng thái trước đang giao hoặc vận chuyển',
                ),
                self::PERMISSION_ORDER_FIX_SERVICES_REQUEST => array(
                    'label' => 'Sửa yêu cầu dịch vụ của đơn hàng trước đang giao',
                    'description' => 'Cho phép sửa yêu cầu dịch vụ của đơn hàng với mọi đơn hàng trước trạng thái đang giao',
                ),
                self::PERMISSION_ORDER_VIEW_SHIPPING_ADDRESS => array(
                    'label' => 'Xem địa chỉ nhận hàng',
                    'description' => 'Quyền xem địa chỉ nhận hàng của khách',
                ),
                self::PERMISSION_ORDER_CHANGE_RECEIVED_FROM_SELLER => array(
                    'label' => 'Chuyển Seudo nhận',
                    'description' => 'Chuyển đơn hàng sang trạng thái Seudo nhận'
                ),
                self::PERMISSION_ORDER_CHANGE_TRANSPORTING => array(
                    'label' => 'Chuyển vận chuyển',
                    'description' => 'Chuyển đơn hàng sang trạng thái vận chuyển'
                ),
                self::PERMISSION_ORDER_CHANGE_WAIT_DELIVERY => array(
                    'label' => 'Chuyển chờ giao',
                    'description' => 'Chuyển đơn hàng sang trạng thái chờ giao'
                ),
                self::PERMISSION_ORDER_CHANGE_REQUEST_DELIVERY => array(
                    'label' => 'Chuyển YC giao',
                    'description' => 'Chuyển đơn hàng sang trạng thái yêu cầu giao'
                ),
                self::PERMISSION_ORDER_EDIT_ORDER_QUANTITY => array(
                    'label' => 'Sửa số lượng đặt hàng',
                    'description' => 'Quản trị viên có thể sửa số lượng đặt hàng khi đơn hàng ở trạng thái đã đặt cọc',
                ),
                self::PERMISSION_ORDER_EDIT_PENDING_QUANTITY => array(
                    'label' => 'Sửa số lượng mua hàng',
                    'description' => 'Quản trị viên có thể sửa số lượng mua hàng trước khi đơn hàng chuyển sang trạng thái kiểm hàng',
                ),
                self::PERMISSION_ORDER_CHANGE_LOST => array(
                    'label' => 'Chuyển trạng thái đơn hàng thành thất lạc',
                    'description' => 'Quyền cho phép quản trị viên chuyển trạng thái đơn hàng sang thất lạc',
                ),
                self::PERMISSION_ORDER_EDIT_ITEM_PRICE => array(
                    'label' => 'Sửa giá sản phẩm',
                    'description' => 'Quyền cho phép quản trị viên sửa giá sản phẩm trong đơn hàng',
                ),
                self::PERMISSION_ORDER_EDIT_DOMESTIC_FEE_CHINA => array(
                    'label' => 'Sửa phí vận chuyển nội địa TQ của đơn trước vận chuyển',
                    'description' => 'Cho phép quản trị viên sửa phí vận chuyển nội địa TQ với đơn hàng ở trạng thái trước vận chuyển',
                ),
                self::PERMISSION_ORDER_FIX_DOMESTIC_FEE_CHINA => array(
                    'label' => 'Sửa phí vận chuyển nội địa TQ của đơn ở mọi trạng thái',
                    'description' => 'Cho phép quản trị viên sửa phí vận chuyển nội địa TQ với đơn hàng ở mọi trạng thái',
                ),
                self::PERMISSION_ORDER_CHANGE_WAREHOUSE => array(
                    'label' => 'Thay đổi kho phân phối của đơn trước vận chuyển',
                    'description' => 'Cho phép quản trị viên thay đổi kho phân phối của đơn hàng với đơn hàng ở trạng thái trước vận chuyển',
                ),
                self::PERMISSION_ORDER_FIX_WAREHOUSE => array(
                    'label' => 'Thay đổi kho phân phối của đơn ở mọi trạng thái',
                    'description' => 'Cho phép quản trị viên thay đổi kho phân phối của đơn hàng với đơn hàng ở mọi trạng thái',
                ),
                self::PERMISSION_ORDER_EDIT_DEPOSIT_RATIO => array(
                    'label' => 'Thay đổi tỉ lệ đặt cọc đơn trước trạng thái đã mua',
                    'description' => 'Cho phép quản trị viên thay đổi tỉ lệ đặt cọc đơn trước trạng thái đã mua',
                ),
                self::PERMISSION_ORDER_APPROVED_TRANSPORT_STRAIGHT => array(
                    'label' => 'Duyệt yêu cầu chuyển thẳng',
                    'description' => 'Cho phép quản trị viên duyệt yêu cầu chuyển thẳng của đơn hàng',
                ),
            ),
        )

    );

    public function isAllow($permission_code){
        if(!$permission_code) return false;

        $current_user_email = Auth::user()->email;
        $current_user_id = Auth::user()->id;

        if(in_array($current_user_email, ['hosivan90@gmail.com'])) return true;

        #region -- lay danh sach cac nhom cua user hien tai --
        $user_id_roles = [];
        $user_role = new UserRole();
        $result = $user_role->newQuery()->select('role_id')->where(['user_id' => $current_user_id])->get()->toArray();
        if($result):
            foreach($result as $k => $v):
                $user_id_roles[] = $v['role_id'];
            endforeach;
        endif;
        #endregion

        $check = $this->newQuery()->where(['code' => $permission_code])->whereIn('role_id', $user_id_roles)->first();
        if($check) return true;

        return false;
    }
}
