import $ from "jquery";
//import { sendFile } from "local_repositoryciae/ajaxcalls";

export const init = (id) => {
    var data = id;
    $('#download').click(function(){
        downloadFile();
    });
    function downloadFile(){
        event.preventDefault();
        window.open('downloadfile.php?id='+data, '_blank');
    }
};