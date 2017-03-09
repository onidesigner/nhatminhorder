$(document).ready(function() {
    if ($('[data-toggle="tooltip"]').length) {
        $('[data-toggle="tooltip"]').tooltip();
    }

    if ($('[data-toggle="popover"]').length) {
        $('[data-toggle="popover"]').popover();
    }

    $('._autoNumericTemp').autoNumeric({aPad: false, mDec: 3, vMax: 9999999999999.99, aSep: '.', aDec: ','});
    if ($('._autoNumeric').length) {
        $('._autoNumeric').each(function (i) {
            var tagName = $(this).prop("tagName").toLowerCase();
            if (tagName == 'input') {
                $(this).autoNumeric({aPad: false, mDec: 3, vMax: 9999999999999.99, aSep: '.', aDec: ','});
            } else {
                var value = $(this).text().trim();
                $(this).text(formatNumber(value));
            }
        })
    }

    function formatNumber(value) {
        $('._autoNumericTemp').autoNumeric('set', value);
        return $('._autoNumericTemp').val();
    }

    $(document).ajaxStop(function(){
        NProgress.done();
    });

    $.ajaxSetup({
        beforeSend:function(){
            NProgress.start();
        },
        complete:function(){

        }
    });

    $('.modal').on('shown.bs.modal', function() {
        $("._autofocus").focus();
    });

    /**
     * created_by: vanhs
     * created_time: 04:27 22/06/2015
     * desc: Ham lay toan bo du lieu tu form, kieu object
     */
    $.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            var value = this.value;
            //if use autoNumeric
            var $this = $("[name='" + this.name + "']");
            if($this.hasClass("autoNumeric")) { value = $this.autoNumeric('get'); }

            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(value || '');
            } else {
                o[this.name] = value || '';
            }
        });
        return o;
    };

    /**
     * created_by: vanhs
     * created_time: 04:27 22/06/2015
     * desc: Ham do du lieu vao form, du lieu truyen vao la 1 object
     */
    $.fn.setFormData = function(data) {
        try{
            $.each(data, function (name, value) {
                var $self = $("[name='" + name + "']");
                if($self.length){
                    var tagName = $self.prop("tagName").toUpperCase();
                    var type = $self.prop("type").toUpperCase();
                    switch (tagName){
                        case "INPUT":
                            switch (type){
                                case "TEXT":
                                case "HIDDEN":
                                    $self.val(value);
                                    if($self.hasClass("autoNumeric")){
                                        $self.autoNumeric('set', value);
                                    }
                                    break;
                                case "RADIO":
                                    $self.filter("[value='" + value + "']").prop("checked", true);
                                    break;
                                case "CHECKBOX":
                                    if(typeof value == "string"){ value = [value]; }//convert value to array if value is string
                                    for(var i = 0; i < value.length; i++){
                                        console.info("vao day");
                                        $self.filter("[value='" + value[i] + "']").prop("checked", true);
                                    }
                                    break;
                                case "DATE":
                                    $self.val(value);
                                    if($self.hasClass("autoNumeric")){
                                        $self.autoNumeric('set', value);
                                    }
                                    break;
                                default:
                                    console.warn("Not support type: " + type);
                                    break;
                            }
                            break;
                        case "SELECT":
                            switch (type){
                                case "SELECT-ONE":
                                    $self.find("option[value='" + value + "']").prop("selected", true);
                                    break;
                                case "SELECT-MULTIPLE":
                                    if(typeof value == "string"){ value = [value]; }//convert value to array if value is string
                                    for(var j = 0; j < value.length; j++){
                                        $self.find("option[value='" + value[j] + "']").prop("selected", true);
                                    }
                                    break;
                                default:
                                    console.warn("Not support type: " + type);
                                    break;
                            }
                            break;
                        case "TEXTAREA":
                            $self.val(value);
                            break;
                        default:
                            console.warn("Not support tagName: " + tagName);
                            break;
                    }
                }
            });
        }catch (e){
            console.warn("Exception: " + e.message);
        }
    };

});