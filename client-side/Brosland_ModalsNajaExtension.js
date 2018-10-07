import $ from 'jquery';
import Naja from 'naja';

export default class Brosland_ModalsNajaExtension {
	constructor(naja) {
		this.$modal = null;

		naja.addEventListener('init', this.init.bind(this));
		naja.addEventListener('success', (event) => {
			if (event.response['closeModal'] != null && this.$modal != null) {
				this.close();
			}
		});
		naja.snippetHandler.addEventListener('afterUpdate', (event) => {
			this.setup(document.getElementById(event.snippet.id));
		});
	}

	init() {
		this.setup(window.document.body);
	}

	/**
	 * @param {HTMLElement} element
	 */
	setup(element) {
		const $modalElement = $(element).find('.modal').first();

		if ($modalElement.length === 1) {
			if (this.$modal != null) {
				this.close();
			}

			this.open($modalElement);
		}
	}

	/**
	 * @param {jQuery} $modal
	 */
	open($modal) {
		$modal.modal(); // init modal
		$modal.on('hide.bs.modal', function (e) {
			if ($modal.data('on-close-url') != null) {
				Naja.makeRequest(
					'GET',
					$modal.data('on-close-url'),
					{'unique': false}
				);
			}
		});
		$modal.on('hidden.bs.modal', function (e) {
			$modal.modal('dispose');
		});

		this.$modal = $modal;
		this.$modal.modal('show');
	}

	close() {
		this.$modal.modal('hide');
	}
}