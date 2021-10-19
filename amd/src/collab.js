import $ from "jquery";
import ModalFactory from "core/modal_factory";
//import Templates from "core/templates";
import ModalLogin from "local_repositoryciae/modal_discussions";
//import { getform, updateform, editor } from "local_jsonform/ajaxcalls";

var trigger = $('#id_search');

ModalFactory.create({type: ModalLogin.TYPE}, trigger);

// export const init = () => {
//     $('#id_search').click(function(){
//         searchFile();
//     });
//     function searchFile(){
//         ModalFactory.create({type: ModalLogin.TYPE});
//         // ModalFactory.create({
//         //     title: 'Comunidad - Materiales compartidos',
//         //     body: Templates.render('local_repository/discussionsform', {}),
//         //     footer: 'test footer content',
//         // })
//         // .then(function(modal) {
//         //     modal.show();
//         //     console.log("Hola");
//         //     // Do what you want with your new modal.
//         // });
//     }
// };