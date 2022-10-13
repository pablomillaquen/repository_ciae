import $ from "jquery";
export const init = (page) => {
    var st = null;
    var g = null;
    var s = null;
    var sl = null;

    $('#btn-search').on('click', function(){
        filterSearchRepo();
    });
    $('#grades_select').on('change',function(){
        filterSearchRepo();
    });
    $('#select_order').on('change',function(){
        filterSearchRepo();
    });
    $('#theme_check').on('change',function(){
        filterSearchRepo();
    });
    function filterSearchRepo(){
        searchTypes();
        s = $('#searchText').val();
        g = $('#grades_select').val();
        sl = $('#select_order').val();
        window.location.replace("/local/repositoryciae/"+page+".php?search="+s+"&grades="+g+"&order="+sl+"&types="+st);
    }
    function searchTypes(){
        var selectedTypesArray = [];
        $('#theme_check input:checked').each(function() {
                selectedTypesArray.push($(this).attr('name'));
        });
        st = JSON.stringify(selectedTypesArray);
    }
};