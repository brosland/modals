import $ from 'jquery';
import naja from 'naja';

export default class ModalNajaExtension {
    /**
     * @param {naja} naja
     */
    constructor(naja) {
        this.$modal = null;

        naja.addEventListener('init', this.init.bind(this));
        naja.addEventListener(
            'success',
            (event) => {
                if (this.$modal != null && event.response['brosland_modals__closeModal']) {
                    this.$modal.data('closed', true);
                    this.close();
                }
            }
        );

        naja.snippetHandler.addEventListener(
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
                if (!$modal.data('closed') && $modal.data('on-close-url') != null) {
                    Naja.makeRequest('POST', $modal.data('on-close-url'));
                }
            }
        );

        this.$modal.on(
            'hidden.bs.modal',
            (e) => {
                $modal.modal('dispose');
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