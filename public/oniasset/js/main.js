// decode price to number
function dePrice(x){
    return x = x.replace(/\./g, "");;
}
// encode number to price
function enPrice(x){
    x = parseFloat(x);
    x = x.toLocaleString(undefined, {minimumFractionDigits: 0});
    x = x.replace(/\,/g, "x");
    x = x.replace(/\./g, ",");
    x = x.replace(/\x/g, ".")
    return x;
}

/**
 * @author Onizuka Nghia
 * @param shopID {string}, isClick {boolean}
 * @returns {boolean}
 * @private
 */
function _removeShop(shopID, isClick){
    if(isClick) {
        swal({
            title: "Bạn muốn xóa?",
            text: "Sau khi xóa shop #" + shopID + ", bạn không thể hoàn tác!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Hủy bỏ",
            confirmButtonText: "Xóa Shop",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                $('#shop-' + shopID).remove();
                swal("Đã xóa thành công!", "Bạn đã xóa Shop #" + shopID + " thành công.", "success");
            } else {
                swal("Đã hủy", "Đã hủy bỏ thao tác xóa Shop #" + shopID + ".", "error");
                return false;
            }
        });
    }else{
        $('#shop-' + shopID).remove();
    }

    return true;
}


/**
 * @author Onizuka Nghia
 * @param itemID {string}, isClick {boolean}
 * @returns {boolean}
 * @private
 */
function _removeItem(itemID, shopID){
    swal({
        title: "Bạn muốn xóa?",
        text: "Sau khi xóa Item #" + itemID + ", bạn không thể hoàn tác!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        cancelButtonText: "Hủy bỏ",
        confirmButtonText: "Xóa Sản phẩm",
        closeOnConfirm: false
    }, function (isConfirm) {
        if (isConfirm) {
            $('#shop-item-' + itemID).remove();
            if($('#shop-'+ shopID +' .shop-item').length == 0) {
                _removeShop(shopID, false);
            }
            swal("Đã xóa thành công!", "Bạn đã xóa Item #" + itemID + " thành công.", "success");
        } else {
            swal("Đã hủy", "Đã hủy bỏ thao tác xóa Item #" + itemID + ".", "error");
            return false;
        }
    });

    return true;
}



$(document).ready(function() {
// Open close right sidebar
    $('.right-sidebar-toggle').on('click', function () {
        $('#right-sidebar').toggleClass('sidebar-open');
    });

// Tooltips demo
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    });
});
