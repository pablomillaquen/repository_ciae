import $ from "jquery";
export const init = () => {
  var searchText = "";
  var order = 0;

  $('#btn-search').on('click', function(){
    searchText = $('#searchText').val();
    order = $('#order_select').val();
    filteringDiscussions();
  });

  $('#order_select').on('change',function(){
    searchText = $('#searchText').val();
    order = $('#order_select').val();
    filteringDiscussions();
  });

  function filteringDiscussions(){
    window.location.replace("/local/repositoryciae/sharedfiles.php?filter="+searchText+"&order="+order);
  }
};