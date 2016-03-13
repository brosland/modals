<?php

namespace Brosland\Modals;

use Nette\Application\UI\Presenter;

trait ModalTrait
{

	/**
	 * @param Presenter $presenter
	 */
	public function updateModal(Presenter $presenter)
	{
		$presenter->getTemplate()->modal = $activeModal = Modal::getActiveModal();

		if ($presenter->isAjax())
		{
			// close the previous modal
			if (Modal::isCloseRequired())
			{
				$presenter->getPayload()->closeModal = TRUE;
			}

			$redrawModal = ($activeModal && $activeModal->isOpenRequired()) || Modal::isCloseRequired();
			$presenter->redrawControl('modal', $redrawModal);
		}
	}
}