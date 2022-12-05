import $ from "jquery";
import ajax from "core/ajax";
import { exception } from "core/notification";


const searchIndex = (selectedTypes, searchText, gradesSelected, selectOrder)=>{
    const promisesLoad = ajax.call([{
        methodname: "local_repositoryciae_loadjson",
        args: {selectedTypes, searchText, gradesSelected, selectOrder},
        fail: exception
    }]);
    promisesLoad[0].then(data => {
        if(data.length > 0){
            $('#data').val(data[0].data);
            JSON.parse(data[0].data);
        }else{
            return Error;
        }
        return(data.length > 0 ? data[0].data : "No data");
        window.console.log(data);
    });
};

const getFiles = (discussionId)=>{
    const promisesLoad = ajax.call([{
        methodname: "local_repositoryciae_load_discussion_files",
        args: {discussionId},
        fail: exception
    }]);
    promisesLoad[0].then(data => {
        console.log(data);
        if(data.length > 0){
            var $dropdown = $("#linkid");
            var i = 0;
            $.each(data, function() {
                $dropdown.append($("<option />").val(this.id).text(this.filename));
                if(i === 0) $('input[name=link]').val(this.id);
                i++;
            });
        }else{
            return Error;
        }
        return(data.length > 0 ? data[0].data : "No data");
    });
};

export { searchIndex, getFiles };