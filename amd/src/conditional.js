import $ from "jquery";
import { searchOa } from "local_repositoryciae/ajaxcalls";

export const init = (lang) => {
    var culturalcontent = $('#id_culturalcontent2').val();
    sendData();
    var content = $('input[name=culturalcontent]').val();

    $('#id_grades').on('change',function(){
        sendData();
    });
    function sendData(){
        var grade = $('#id_grades').val();
        searchOa(grade);
        var $dropdown = $("#id_culturalcontent2");
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