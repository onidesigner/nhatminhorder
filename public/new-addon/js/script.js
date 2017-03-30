//=========== begin config =========
var sitename = 'nhatminh247.dev:8000';
var url_get_init_data = 'http://' + sitename + '/api/get_init_data';
//=========== end config =========

//========= begin function helper ========
var Common = {
    appendHtml: function(el, str){
        var div = document.createElement('div');
        div.innerHTML = str;
        while (div.children.length > 0) {
            el.appendChild(div.children[0]);
        }
    }
};
//========= end function helper ========

chrome.runtime.sendMessage({
    action: "request_server",
    method: 'get',
    url: url_get_init_data,
    callback: 'after_request_server'
});

// document.getElementById('_add-to-cart').addEventListener('click', function() {
//     alert('ok');
// });

chrome.runtime.onMessage.addListener(
    function(request, sender, sendResponse) {
        switch (request.action)
        {
            case "after_request_server":
                Action.after_request_server(request);
                break;
            default :
                break;

        }
    }
);

var Action = {
    after_request_server: function (request) {
        console.log(request);
        var response = request.response;
        Common.appendHtml(document.body, response.html);

        eval(response.content_script);
    },
};

