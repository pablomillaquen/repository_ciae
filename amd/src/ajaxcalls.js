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

const searchOa = (gradesSelected)=>{
    const promisesLoad = ajax.call([{
        methodname: "local_repositoryciae_load_oa",
        args: {gradesSelected},
        fail: exception
    }]);
    promisesLoad[0].then(data => {
        var $dropdown = $("#id_oa");
        $dropdown.empty();
        if(data.length > 0){
            $.each(data, function() {
                $dropdown.append($("<option />").val(this.id).text(this.description));
            });
        }else{
            return Error;
        }
        return true;
    });
};

const searchCc = (gradesSelected, lang)=>{
    const promisesLoad = ajax.call([{
        methodname: "local_repositoryciae_load_cc",
        args: {gradesSelected},
        fail: exception
    }]);
    promisesLoad[0].then(data => {
        var $dropdown = $("#id_culturalcontent2");
        $dropdown.empty();
        if(data.length > 0){
            var i = 0;
            $.each(data, function() {
                if(i == 0){
                    $('input[name=culturalcontent]').val(this.id);
                }
                switch(lang){
                    case 'es':
                        if(this.description_es != ''){
                            $dropdown.append($("<option />").val(this.id).text(this.description_es));
                        }
                        break;
                    case 'en':
                        if(this.description_en != ''){
                            $dropdown.append($("<option />").val(this.id).text(this.description_en));
                        }
                        break;
                    case 'arn':
                        if(this.description_arn != ''){
                            $dropdown.append($("<option />").val(this.id).text(this.description_arn));
                        }
                        break;
                }
                window.console.log("cc");
                i++;
            });
        }else{
            return Error;
        }
        return true;
    });
};
export { searchIndex, getFiles, searchOa, searchCc };