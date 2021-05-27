import $ from 'jquery';
import naja from 'naja';

export default class Modal {
    /**
     * @param {naja} naja
     */
    constructor(naja) {
        this.naja = naja;
        this.$modal = null;

        this.naja.addEventListener('init', this.init.bind(this));
        this.naja.addEventListener(
            'success',
            (event) => {
                if (this.$modal != null && event.response['brosland_modals__closeModal']) {
                    this.$modal.data('closed', true);
                    this.close();
                }
            }
        );

        this.naja.snippetHandler.addEventListener(
            'afterUpdate',
            (event) => {
                this.setup(document.getElementById(event.snippet.id));
            }
        );
    }

    /**
     * @return {void}
     */
    init() {
        this.setup(document.body);
    }

    /**
     * @param {HTMLElement} element
     * @return {void}
     */
    setup(element) {
        const modalElement = element.querySelector('.modal');

        if (modalElement) {
            this.open(modalElement);
        }
    }

    /**
     * @param {HTMLElement} element
     * @return {void}
     */
    open(element) {
        this.close(); // close previous modal

        this.$modal = $(element);
        this.$modal.modal(); // init modal

        this.$modal.on(
            'hide.bs.modal',
            (e) => {
                if (!this.$modal.data('closed') && this.$modal.data('on-close-url') != null) {
                    this.naja.makeRequest('POST', this.$modal.data('on-close-url'));
                }
            }
        );

        this.$modal.on(
            'hidden.bs.modal',
            (e) => {
                this.$modal.modal('dispose');
            }
        );

        this.$modal.modal('show');
    }

    /**
     * @return {void}
     */
    close() {
        if (this.$modal) {
            this.$modal.modal('hide');
        }
    }
};