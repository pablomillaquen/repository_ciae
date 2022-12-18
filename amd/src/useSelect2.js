import $ from 'jquery';
import 'select2';                       // globally assign select2 fn to $ element
import '../css/select2.css';  // optional if you have css loader

export const init = $(() => {
  $('.select2-enable').select2();
});