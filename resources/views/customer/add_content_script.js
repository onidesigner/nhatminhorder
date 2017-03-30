var Common = {
    resizeImage: function(url){
        for(var i = 30; i < 150; i++){
            url = url.replace(i + 'x' + i + '.jpg', "150x150.jpg");
        }
        return url;
    }
};

var taobao = function(){
    this.init_data = null;

    this.init = function () {

    };

    /**
     * Ten san pham
     * @returns {string}
     */
    this.getProductName = function(){
        var product_name = '';
        try{
            product_name = document.querySelectorAll('meta[property="og:title"]')[0].getAttribute('content');
        }catch (e){

        }
        return product_name;
    };

    this.getPrice = function(){

    };

    this.getPricePromotion = function(){

    };

    this.getProperty = function(){

    };

    this.getProductImage = function(){

    };

    /**
     * duong dan chi tiet san pham site TQ
     * @returns {string}
     */
    this.getProductDetailUrl = function(){
        return window.location.href;
    };

    /**
     * id san pham site trung quoc
     * @returns {string}
     */
    this.getProductId = function(){
        var item_id = '';
        try{
            item_id = document.querySelectorAll('input[name="item_id"]')[0].value;
        }catch(e){

        }
        return item_id;
    };

    /**
     * cho biet san pham thuoc site nao?
     * @returns {string}
     */
    this.getSite = function(){
        return 'taobao';
    };

    this.getShopId = function(){

    };

    this.getQuantity = function(){

    };

    this.getDataToSend = function () {
        return {
            product_name: this.getProductName(),
            price: this.getPrice(),
            price_promotion: this.getPricePromotion(),
            property: this.getProperty(),
            product_image: this.getProductImage(),
            product_detail_url: this.getProductDetailUrl(),
            product_id: this.getProductId(),
            site: this.getSite(),
            shop_id: this.getShopId(),
            quantity: this.getQuantity(),
        };
    };

};
var tmall = function(){
    this.init_data = null;

    this.init = function () {

    };

    this.getProductName = function(){

    };

    this.getPrice = function(){

    };

    this.getPricePromotion = function(){

    };

    this.getProperty = function(){

    };

    this.getProductImage = function(){

    };

    this.getProductDetailUrl = function(){
        return window.location.href;
    };

    this.getProductId = function(){

    };

    this.getSite = function(){
        return 'tmall';
    };

    this.getShopId = function(){

    };

    this.getQuantity = function(){

    };

    this.getDataToSend = function () {
        return {
            product_name: this.getProductName(),
            price: this.getPrice(),
            price_promotion: this.getPricePromotion(),
            property: this.getProperty(),
            product_image: this.getProductImage(),
            product_detail_url: this.getProductDetailUrl(),
            product_id: this.getProductId(),
            site: this.getSite(),
            shop_id: this.getShopId(),
            quantity: this.getQuantity(),
        };
    };
};

var alibaba = function(){
    this.init_data = null;

    this.init = function(){
        if(this.init_data){
            return this.init_data;
        }

        var self = this;
        try{
            var scripts = document.querySelectorAll("script");
            for(var i = 0; i < scripts.length; i++){
                var html = scripts[i].textContent;
                var res = html.search("iDetailConfig");
                if(res != -1){
                    eval(html);
                    self.init_data = {
                        iDetailConfig:iDetailConfig,
                        iDetailData:iDetailData
                    };

                    break;
                }
            }
        }catch(e){

        }
        return this.init_data;
    };

    this.product_id_increment = 0;

    /**
     * @desc Neu khong co doan html hien thi tong so san pham khach chon thi la san pham khong co thuoc tinh va nguoc lai
     * @returns {boolean}
     */
    this.isEmptyProperty = function () {
        if(!document.querySelectorAll('.list-total .amount .value').length){
            return true;
        }
        return false;
    };

    this.getTotalQuantityChoose = function () {
        var total_quantity = 0;
        try{
            total_quantity = document.querySelectorAll('.list-total .amount .value')[0].textContent;
        }catch (e){

        }
        return total_quantity;
    };

    this.validateBeforeSubmit = function () {
        if(this.isEmptyProperty()){
            if(this.getQuantity() > 0){
                return true;
            }
        }else{
            if(this.getTotalQuantityChoose() > 0){
                return true;
            }
        }
        return false;
    };

    this.setProductId = function(id){
        this.product_id_increment = id;
    };

    this.getProductId = function(){
        return this.product_id_increment;
    };

    this.getProductPriceRange = function () {
        var product_price_range = [];
        var $dom = document.querySelectorAll('#mod-detail-price .price td');
        for(var i = 0; i < $dom.length; i++){
            var data_range = $dom[i].getAttribute('data-range');
            if(data_range){
                product_price_range.push(JSON.parse(data_range));
            }
        }
        return product_price_range;
    };

    this.getProductName = function(){
        var product_name = '';
        try{
            product_name = document.querySelectorAll('.d-title')[0].textContent;
        }catch (e){

        }
        return product_name;
    };

    this.getPriceWithQuantityAndPriceRange = function (quantity, price_range) {
        var price = 0;
        try{
            for(var i = 0; i < price_range.length; i++){
                var is_end = (i + 1) == price_range.length;
                var begin = price_range[i].begin;
                var end = price_range[i].end;
                begin = parseInt(begin);
                end = parseInt(end);

                if(is_end){
                    if(quantity >= begin){
                        price = price_range[i].price;
                        break;
                    }
                }else{
                    if(quantity >= begin && quantity <= end){
                        price = price_range[i].price;
                        break;
                    }
                }
            }
        }catch (e){

        }

        try{
            if(!price){
                price = price_range[0].price;
            }
        }catch (e){

        }

        return price;
    };

    this.getProductPrice = function(){
        var price = 0;
        try{
            price = document.querySelectorAll('.table-sku tr')[this.getProductId()].getElementsByClassName('price')[0].getElementsByClassName('value')[0].textContent.trim();
        }catch (e){

        }
        if(this.isEmptyProperty()){
            price = this.getPriceWithQuantityAndPriceRange(this.getQuantity(), this.getProductPriceRange());
        }
        return price;
    };

    this.getProductPricePromotion = function(){
        return this.getProductPrice();
    };

    this.getProperty = function(){
        var property = [];

        //property1
        try{
            var property_item = document.querySelectorAll('.list-leading .unit-detail-spec-operator > a.selected')[0].getAttribute('title').trim();
            if(property_item){
                property.push(property_item);
            }
        }catch (e){

        }

        //property2
        try{
            var property_item2 = JSON.parse(document.querySelectorAll('.table-sku tr')[this.getProductId()].getAttribute('data-sku-config')).skuName;
            if(property_item2){
                property.push(property_item2);
            }
        }catch (e){
            console.log(e.message);
        }

        if(this.isEmptyProperty()){
            return '';
        }

        console.log(property);
        return property.length ? property.join(';') : '';
    };

    this.getQuantity = function(){
        var quantity = 0;
        try{
            quantity = document.querySelectorAll('.table-sku tr')[this.getProductId()].getElementsByClassName('amount')[0].getElementsByClassName('amount-input')[0].value;
        }catch (e){

        }

        if(this.isEmptyProperty()){
            try{
                quantity = document.querySelectorAll('.amount-input')[0].value;
            }catch (e){

            }
        }

        return quantity;
    };

    this.getProductImage = function(){
        var product_image = '';
        try{
            product_image = document.querySelectorAll('.box-img')[0].getElementsByTagName('img')[0].getAttribute('src');
        }catch (e){

        }
        return product_image;
    };

    /**
     * @desc Nếu có ảnh ở thuộc tính nào thì lấy ảnh ở thuộc tính đó, các thuộc tính bao gồm: table-sku, list-leading
     * @returns {string}
     */
    this.getProductImageModel = function () {
        var product_image_model = '';
        try{
            product_image_model = document.querySelectorAll('.table-sku tr')[this.getProductId()].getElementsByClassName('box-img')[0].getElementsByTagName('img')[0].getAttribute('src');
            if(!product_image_model){
                product_image_model = document.querySelectorAll('.table-sku tr')[this.getProductId()].getElementsByClassName('box-img')[0].getElementsByTagName('img')[0].getAttribute('data-lazy-src');
            }
        }catch (e){

        }

        if(!product_image_model){
            try{
                product_image_model = document.querySelectorAll('.list-leading .image.selected img')[0].getAttribute('src');
            }catch (e){

            }
        }

        if(product_image_model){
            product_image_model = Common.resizeImage(product_image_model);
        }

        if(!product_image_model){
            return this.getProductImage();
        }
        return product_image_model;
    };

    this.getProductDetailUrl = function(){
        return window.location.href;
    };

    this.getItemId = function(){
        var item_id = '';
        try{
            item_id = document.querySelectorAll('[name="b2c_auction"]')[0].getAttribute('content');
        }catch (e){

        }
        return item_id;
    };

    this.getSite = function(){
        return '1688';
    };

    this.getShopId = function(){
        //==== step 1: Lấy thông tin trên dom
        try{
            var dataUnitConfigString = document.querySelectorAll('.apply-btn')[0].getAttribute('data-unit-config');
            var dataUnitConfigJSON = JSON.parse(dataUnitConfigString);
            return dataUnitConfigJSON.sellerId;
        }catch (e){

        }
        //==== step 2: Lấy thông tin dữ liệu trả về trên trang
        try{
            return this.init_data.iDetailConfig.userId;
        }catch (e){

        }
        return '';
    };

    this.getDataToSend = function () {
        return {
            title_origin: this.getProductName(),
            price_origin: this.getProductPrice(),
            price_promotion: this.getProductPricePromotion(),
            property: this.getProperty(),
            image_origin: this.getProductImage(),
            image_model: this.getProductImageModel(),
            link_origin: this.getProductDetailUrl(),
            item_id: this.getItemId(),
            site: this.getSite(),
            shop_id: this.getShopId(),
            quantity: this.getQuantity(),
            price_range: this.getProductPriceRange(),
        };
    };
};

document.getElementById('_add-to-cart').addEventListener('click', function(e) {
    addToCart(e);
});

var factory = function () {
    var _class;

    var url = window.location.href;
    if(url.match(/taobao.com/)){
        _class = new taobao();
    }
    if(url.match(/tmall.com|tmall.hk|yao.95095.com/)){
        _class = new tmall();
    }
    if(url.match(/1688.com|alibaba/)){
        _class = new alibaba();
    }
    return _class;
};

var _className = new factory();
_className.init();

function addToCart(e){
    var current_site = _className.getSite();
    if(!_className.validateBeforeSubmit()){
        alert('Vui lòng chọn đầy đủ thuộc tính của sản phẩm trước khi cho vào giỏ. Xin Cám Ơn!');
        return false;
    }

    if(current_site == 1688){

        var is_empty_property = _className.isEmptyProperty();
        if(is_empty_property){
            var data = _className.getDataToSend();
            console.log(data);
            sendAjax(data);
        }else{
            var $dom = document.querySelectorAll('.obj-sku .amount-input');
            for(var i = 0; i < $dom.length; i++){
                var amount_input = $dom[i].value;
                if(amount_input > 0){
                    _className.setProductId(i);
                    var data = _className.getDataToSend();
                    console.log(data);
                    sendAjax(data);
                }
            }
        }

    }else{
        //site: taobao, tmall
    }
}

function sendAjax(data){
    chrome.runtime.sendMessage({
        action: "request_server",
        method: 'get',
        data: data,
        url: 'http://nhatminh247.dev:8000/cart/add',
        callback: 'after_request_server',
    });
}
