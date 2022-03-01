/**
 * Add a create new group modal to the page.
 *
 * @module     core_group/newgroup
 * @class      NewGroup
 * @package    core_group
 * @copyright  2017 Damyon Wiese <damyon@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/str', 'core/modal_factory', 'core/modal_events', 'core/fragment', 'core/ajax', 'core/yui'],
function($, Str, ModalFactory, ModalEvents, Fragment, Ajax, Y) {

/**
* Constructor
*
* @param {String} selector used to find triggers for the new group modal.
* @param {int} contextid
*
* Each call to init gets it's own instance of this class.
*/
var NewCollab = function(selector, contextid) {
    this.contextid = contextid;
    console.log(selector);
    this.discussionid = 0;
    this.init(selector);
};

/**
* @var {Modal} modal
* @private
*/
NewCollab.prototype.modal = null;

/**
* @var {int} contextid
* @private
*/
NewCollab.prototype.contextid = -1;

/**
* Initialise the class.
*
* @param {String} selector used to find triggers for the new group modal.
* @private
* @return {Promise}
*/
NewCollab.prototype.init = function(selector) {
    var triggers = $(selector);
    // Fetch the title string.
    return Str.get_string('sharedmaterials', 'local_repositoryciae').then(function(title) {
         // Create the modal.
        return ModalFactory.create({
            type: ModalFactory.types.SAVE_CANCEL,
            title: title,
            body: this.getBody()
        }, triggers);
    }.bind(this)).then(function(modal) {
        // Keep a reference to the modal.
        this.modal = modal;

        // Forms are big, we want a big modal.
        this.modal.setLarge();

        // We want to reset the form every time it is opened.
        this.modal.getRoot().on(ModalEvents.hidden, function() {
            this.modal.setBody(this.getBody());
        }.bind(this));

        // We want to hide the submit buttons every time it is opened.
        this.modal.getRoot().on(ModalEvents.shown, function() {
            this.modal.getRoot().append('<style>[data-fieldtype=submit] { display: none ! important; }</style>');
        }.bind(this));


        // We catch the modal save event, and use it to submit the form inside the modal.
        // Triggering a form submission will give JS validation scripts a chance to check for errors.
        this.modal.getRoot().on(ModalEvents.save, this.submitFormAjax.bind(this));
        // We also catch the form submit event and use it to submit the form with ajax.
        //this.modal.getRoot().on('submit', 'form', this.submitFormAjax.bind(this));

        return this.modal;
    }.bind(this));
};

/**
* @method getBody
* @private
* @return {Promise}
*/
NewCollab.prototype.getBody = function(formdata) {
    if (typeof formdata === "undefined") {
        formdata = {};
    }
    // Get the content of the modal.
    var params = {jsonformdata: JSON.stringify(formdata)};
    console.log(params);
    return Fragment.loadFragment('local_repositoryciae', 'new_discussions_form', this.contextid, params);
};

/**
* Private method
*
* @method submitFormAjax
* @private
* @param {Event} e Form submission event.
*/
NewCollab.prototype.submitFormAjax = function(e) {
    // We don't want to do a real form submission.
    e.preventDefault();

    var sel = document.getElementById('id_discussions');
    var selectedId = sel.options[sel.selectedIndex].value;
    var selectedName = sel.options[sel.selectedIndex].text;
    document.getElementById('id_conversation').value = selectedId;
    document.getElementById('id_selected_conversation').innerHTML = selectedName;

    var selFile = document.getElementById('id_discussionfile');
    var selectedFileId = selFile.options[selFile.selectedIndex].value;
    var selectedFileName = selFile.options[selFile.selectedIndex].text;
    document.getElementById('id_link').value = selectedFileId;
    document.getElementById('id_selected_file').innerHTML = " / "+selectedFileName;

    this.modal.hide();
    $('[data-fieldtype=submit]').attr('style', 'display: block !important');
};

    return /** @alias module:core_group/newgroup */ {
    // Public variables and functions.
    /**
     * Attach event listeners to initialise this module.
     *
     * @method init
     * @param {string} selector The CSS selector used to find nodes that will trigger this module.
     * @param {int} contextid The contextid for the course.
     * @return {Promise}
     */
        init: function(selector, contextid) {
            return new NewCollab(selector, contextid);
        }
    };
});
