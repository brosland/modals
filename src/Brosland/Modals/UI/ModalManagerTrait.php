<?php
declare(strict_types=1);

namespace Brosland\Modals\UI;

use Nette\Application\UI\Presenter;
use Nette\Application\UI\Template;

trait ModalManagerTrait
{
	private ?Modal $activeModal = null;
	private bool $closeActiveModal = false;

	public abstract function getPresenter(): ?Presenter;

	public abstract function getTemplate(): Template;

	public function getActiveModal(): ?Modal
	{
		return $this->activeModal;
	}

	public function setActiveModal(Modal $modal = null): void
	{
		if ($this->activeModal !== null && $this->activeModal !== $modal) {
			$this->closeActiveModal = true;
		}

		$this->activeModal = $modal;
	}

	public function updateModal(): void
	{
		$template = $this->getTemplate();
		$template->modal = $this->activeModal;

		/** @var Presenter $presenter */
		$presenter = $this->getPresenter();

		if ($presenter->isAjax()) {
			$presenter->redrawControl('modal', (bool)$this->activeModal?->isOpenRequired());

			if ($this->closeActiveModal) {
				$presenter->getPayload()->brosland_modals__closeModal = true;
			}
		}
	}
}