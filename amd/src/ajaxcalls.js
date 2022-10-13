import $ from "jquery";
import ajax from "core/ajax";
import { exception } from "core/notification";

//var result;
const searchIndex = (selectedTypes, searchText, gradesSelected, selectOrder)=>{
    const promisesLoad = ajax.call([{
        methodname: "local_repositoryciae_loadjson",
        args: {selectedTypes, searchText, gradesSelected, selectOrder},
        fail: exception
    }]);
    promisesLoad[0].then(data => {
        let actualData = null;
        if(data.length > 0){
            $('#data').val(data[0].data);
            actualData = JSON.parse(data[0].data);
        }else{
            return Error;
        }
        return(data.length > 0 ? data[0].data : "No data");
        window.console.log(data);
    });
};

export { searchIndex };