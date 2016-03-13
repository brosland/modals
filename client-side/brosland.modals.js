(function ($, undefined) {
	$.nette.ext('bootstrap', {
		init: function () {
			this.open($('body'));
		},
		success: function (payload) {
			if (payload.closeModal) {
				this.close();
			}

			if (payload.snippets) {
				var snippets = this.ext('snippets');

				for (var id in payload.snippets) {
					this.open(snippets.getElement(id));
				}
			}
		}
	}, {
		open: function ($el) {
			$el.find('.modal').each(function (i, element) {
				var $modal = $(element);
				$modal.on('hidden.bs.modal', function (e) {
					if ($modal.data('onclose')) {
						$.nette.ajax($modal.data('onclose'));
					}
				}).modal({});
			});
		},
		close: function () {
			$('.modal').modal('hide');
			$('.modal-backdrop').remove();
			$('body').removeClass('modal-open')
				.removeAttr('style');
		}
	});
})(jQuery);