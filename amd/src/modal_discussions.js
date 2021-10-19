define(['jquery', 'core/notification', 'core/custom_interaction_events', 'core/modal', 'core/modal_registry'],
        function($, Notification, CustomEvents, Modal, ModalRegistry) {

    var registered = false;
    var SELECTORS = {
        LOGIN_BUTTON: '[data-action="login"]',
    };

    /**
     * Constructor for the Modal.
     *
     * @param {object} root The root jQuery element for the modal
     */
    var ModalLogin = function(root) {
        Modal.call(this, root);

        if (!this.getFooter().find(SELECTORS.LOGIN_BUTTON).length) {
            Notification.exception({message: 'No login button found'});
        }

    };

    ModalLogin.TYPE = 'your_module-login';
    ModalLogin.prototype = Object.create(Modal.prototype);
    ModalLogin.prototype.constructor = ModalLogin;

    /**
     * Set up all of the event handling for the modal.
     *
     * @method registerEventListeners
     */
    ModalLogin.prototype.registerEventListeners = function() {
        // Apply parent event listeners.
        Modal.prototype.registerEventListeners.call(this);

        this.getModal().on(CustomEvents.events.activate, SELECTORS.LOGIN_BUTTON, function(e, data) {
            // Add your logic for when the login button is clicked. This could include the form validation,
            // loading animations, error handling etc.
        }.bind(this));

    };

    // Automatically register with the modal registry the first time this module is imported so that you can create modals
    // of this type using the modal factory.
    if (!registered) {
        ModalRegistry.register(ModalLogin.TYPE, ModalLogin, 'local_repositoryciae/discussionsform');
        registered = true;
    }

    return ModalLogin;
});