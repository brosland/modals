import $ from 'jquery';
import naja from 'naja';

export default class ModalNajaExtension {
    constructor() {
        this.modal = null;
    }

    /**
     * @return {void}
     */
    initialize(naja) {
        naja.addEventListener(
            'init',
            () => this.setup(document.body)
        );
        naja.addEventListener(
            'complete',
            (event) => {
                if (this.modal != null && event.detail.payload['brosland_modals__closeModal']) {
                    this.modal.data('closed', true);
                    this.close();
                }
            }
        );
        naja.snippetHandler.addEventListener(
            'afterUpdate',
            (event) => this.setup(event.detail.snippet) // @todo check
        );
    }

    /**
     * @param {HTMLElement} html
     * @return {void}
     */
    setup(html) {
        const modalElement = html.querySelector('.modal');

        if (modalElement) {
            this.open(modalElement);
        }
    }

    /**
     * @param {HTMLElement} modal
     * @return {void}
     */
    open(modal) {
        this.close(); // close previous modal

        this.modal = modal;
        this.modal.addEventListener(
            'hide.bs.modal',
            () => {
                if (
                    !this.modal.getAttribute('data-closed') &&
                    this.modal.getAttribute('data-on-close-url') != null
                ) {
                    naja.makeRequest('POST', this.modal.getAttribute('data-on-close-url'));
                }
            }
        );
        this.modal.addEventListener(
            'hidden.bs.modal',
            () => $(this.modal).modal('dispose')
        );

        $(this.modal).modal(); // init modal
        $(this.modal).modal('show');
    }

    /**
     * @return {void}
     */
    close() {
        if (this.modal) {
            $(this.modal).modal('hide');
        }
    }
};