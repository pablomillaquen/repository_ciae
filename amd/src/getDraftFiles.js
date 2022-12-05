import $ from "jquery";
import { getFiles } from "local_repositoryciae/ajaxcalls";
export const init = (discussionId) => {
    function getCollabFiles(discussionId){
        getFiles(discussionId);
    }
    getCollabFiles(discussionId);

    $('#linkid').on('change',function(){
        let value = $('#linkid').val();
        $('input[name=link]').val(value);
        console.log(value);
    });
};