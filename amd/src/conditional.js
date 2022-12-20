import $ from "jquery";
import { searchOa, searchCc } from "local_repositoryciae/ajaxcalls";

export const init = (lang) => {
    var culturalcontent = $('#id_culturalcontent2').val();
    sendData(lang);
    var content = $('input[name=culturalcontent]').val();

    $('#id_grades').on('change',function(){
        sendData(lang);
    });
    $("#id_culturalcontent2").on('change', function(){
        var value = $("#id_culturalcontent2").val();
        $('input[name=culturalcontent]').val(value);
    });
    function sendData(lang){
        var grade = $('#id_grades').val();
        if(grade != null){
            searchOa(grade);
            searchCc(grade, lang);
        }
    }
};