var service_host,
    add_to_cart_url,
    cart_url,
    catalog_scalar_url,
    isTranslate,
    link_detail_cart,
    add_to_favorite_url,
    button_add_to_cart_url;

var site_using_https = false;

var site_name = "nhatminhorder.dev:8000";
// var site_name = "nhatminh247.vn";

chrome.storage.sync.get({
    domain: site_using_https ? 'https://' + site_name + '/' : 'http://' + site_name + '/',
    is_translate: true
}, function(items) {

    var value = items.domain;

    if(site_using_https && items.domain.search("https") == -1){
        value = items.domain.replace("http", "https");
    }

    if(!site_using_https && items.domain.search("https") != -1){
        value = items.domain.replace("https", "http");
    }

    chrome.storage.sync.set({
        domain: value
    }, function() {

    });

    service_host = value;
    service_host = "http://" + site_name + "/";

    //============================================================

    isTranslate = items.is_translate;
    add_to_cart_url = service_host+'cart/add';
    cart_url = service_host+'checkout.html';
    link_detail_cart = service_host + "gio-hang";
    add_to_favorite_url = service_host + "i/favoriteLink/saveLink";
    button_add_to_cart_url = service_host + "assets/images/add_on/icon-bkv1-small.png";

    var css_url = '';
    switch (items.domain){
        case 'http://cn.' + site_name + '/':
            catalog_scalar_url = 'http://cnlogistic.seudo.vn/api/catalog/scalar';
            css_url = 'css/cn_main.css';
            break;
        default :
            css_url = 'css/main.css';
            catalog_scalar_url = 'http://logistic.seudo.vn/api/catalog/scalar';
            break;
    }

    if(css_url){
        var NewStyles = document.createElement("link");
        NewStyles.rel = "stylesheet";
        NewStyles.type = "text/css";
        NewStyles.href = chrome.extension.getURL(css_url);
        document.head.appendChild(NewStyles);
    }
});
//======== chua lam ====
var translate_url = 'http://' + site_name + '/i/goodies_util/translate';
var isUsingSetting = false;
var translate_keyword_url = "";
var version_tool = "1.0";
var exchange_rate;
var link_store_review_guidelines = "#";
var web_service_url = "http://" + site_name + "/i/";
var exchange_rate_url = 'http://' + site_name + '/api/exchange';
