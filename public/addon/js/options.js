var a = [ { value: "http://seudo.vn/", text: "seudo.vn" }, { value: "http://cn.seudo.vn/", text: "cn.seudo.vn" } ];
var b = [ { value: "https://seudo.vn/", text: "seudo.vn" }, { value: "https://cn.seudo.vn/", text: "cn.seudo.vn" } ];

//render HTML
init();

function init(){
    document.getElementById("_domain").innerHTML = "";

    var data = site_using_https ? b : a;
    if(data.length){
        for(var i = 0; i < data.length; i++){
            var value = data[i].value;
            var text = data[i].text;
            if(text && value){
                var option = document.createElement("option");
                option.text = text;
                option.value = value;

                document.getElementById("_domain").appendChild(option);
            }
        }
    }

}

function save_options(){
    //console.info("save_options");
    var domain = document.getElementById('_domain').value;
    var is_translate = document.getElementById('_chk-translate').checked;

    chrome.storage.sync.set({
        domain: domain,
        is_translate: is_translate
    }, function() {
        // Update status to let user know options were saved.
        var message = document.getElementById('_alert-success');
        message.textContent = 'Lưu cài đặt thành công.';
        setTimeout(function() {
            message.textContent = '';
        }, 750);
    });
}

function restore_options() {
    // Use default value color = 'red' and likesColor = true.
    chrome.storage.sync.get({
        domain: site_using_https ? 'https://seudo.vn/' : 'http://seudo.vn/',
        is_translate: true
    }, function(items) {

        //console.info('value: ' + value);
        document.getElementById('_domain').value = items.domain;
        document.getElementById('_chk-translate').checked = items.is_translate;

    });
}

document.addEventListener('DOMContentLoaded', restore_options);
document.getElementById('_from').addEventListener('change',
    save_options);



