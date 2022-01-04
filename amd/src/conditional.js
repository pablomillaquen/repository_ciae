import $ from "jquery";
//import { getform, updateform, editor } from "local_jsonform/ajaxcalls";

export const init = (lang) => {
    var culturalcontent = $('#id_culturalcontent').val();
    sendData();

    $('#id_grades').on('change',function(){
        sendData();
    });
    function sendData(){
        event.preventDefault();
        var grade = $('#id_grades').val();
        var $dropdown = $("#id_culturalcontent");
        $.getJSON("culturalcontent.json", function(data) {
            var items = [];
            var itemsByLang = [];
            $.each(data, function(key, val) {
                if (key == lang){
                    $dropdown.empty();
                    items.push(val);
                    $.each(items[0], function(k, v) {
                        if (k == grade){
                            itemsByLang.push(v);
                            $.each(itemsByLang[0], function(clave, valor) {
                                if(clave == culturalcontent){
                                    $dropdown.append($("<option selected></option>").attr("value", clave).text(valor));
                                }else{
                                    $dropdown.append($("<option />").val(clave).text(valor));
                                }
                            });
                        }
                    });
                }
            });
        });
    }
};