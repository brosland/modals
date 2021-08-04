import $ from 'jquery';

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
                if (
                    this.modal !== null &&
                    event.detail.payload.hasOwnProperty('brosland_modals__closeModal')
                ) {
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
            'hidden.bs.modal',
            () => {
                $(this.modal).modal('dispose');

                this.modal = null;
            }
        );

        $(this.modal).modal(); // init modal
        $(this.modal).modal('show');
    }

    /**
     * @return {void}
     */
    close() {
        $(this.modal).modal('hide');
    }
};