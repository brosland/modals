import {Modal} from 'bootstrap';

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
					event.detail.payload?.hasOwnProperty('brosland_modals__closeModal')
				) {
					this.modal.hide();
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
			if (this.modal !== null) {
				this.modal.hide();
			}

			this.modal = new Modal(modalElement);
			this.modal.show();

			modalElement.addEventListener(
				'hidden.bs.modal',
				(event) => {
					this.modal.dispose();
					this.modal = null;
				}
			);
		}
	}
};