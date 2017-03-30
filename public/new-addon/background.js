chrome.runtime.onMessage.addListener(
    function(request, sender, sendResponse) {
        switch (request.action)
        {
            case "request_server":
                request_server(request, sender, sendResponse);
                break;
            default :
                break;

        }
    }
);

function request_server(request, sender, sendResponse){
    // $.ajax({
    //     data: request.data == undefined ? {} : request.data,
    //     contentType: 'application/x-www-form-urlencoded',
    //     xhrFields: {
    //         withCredentials: true
    //     },
    //     headers: {'X-Requested-With': 'XMLHttpRequest'},
    //     success: function(res){
    //         chrome.tabs.sendMessage(sender.tab.id, { action: request.callback, response: res }, function(response) {
    //
    //         });
    //     },
    //     error: function(){
    //         chrome.tabs.sendMessage(sender.tab.id, { action: request.callback, response: null }, function(response) {
    //
    //         });
    //     }
    // });


    var method = request.method == undefined ? 'GET' : request.method;

    var request = new XMLHttpRequest();
    request.open(method, request.url, true);
    request.onload = function (e) {
        if (request.readyState === 4) {
            // Check if the get was successful.
            if (request.status === 200) {
                console.log(request.responseText);
            } else {
                console.error(request.statusText);
            }
        }
    };
}







