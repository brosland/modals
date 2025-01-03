<?php
declare(strict_types=1);

namespace Brosland\Modals\UI;

use Nette\Application\UI\Control;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;

/**
 * @method void onClose(self $modal)
 */
abstract class Modal extends Control
{
	/** @var array<callable> */
	public array $onClose = [];
	private bool $openRequired = false;
	private ModalManager $modalManager;
	public static string $MODAL_TEMPLATE = __DIR__ . '/Modal.v5.latte';

	public function __construct()
	{
		$this->onAnchor[] = [$this, 'init'];
	}

	public function init(): void
	{
		/** @var ModalManager $modalManager */
		$modalManager = $this->lookup(ModalManager::class);
		$this->modalManager = $modalManager;

		/** @var Presenter $presenter */
		$presenter = $this->getPresenter();
		/** @var array<string>|null $signal */
		$signal = $presenter->getSignal();

		if ($signal !== null && str_starts_with($signal[0], $this->lookupPath())) {
			$this->modalManager->setActiveModal($this);
		}
	}

	public function isActive(): bool
	{
		return $this->modalManager->getActiveModal() === $this;
	}

	public function isOpenRequired(): bool
	{
		return $this->openRequired;
	}

	public function open(): void
	{
		$this->modalManager->setActiveModal($this);
		$this->openRequired = true;
	}

	public function close(bool $remove = false): void
	{
		if ($this->modalManager->getActiveModal() === $this) {
			$this->modalManager->setActiveModal(null);

			$this->openRequired = false;
			$this->onClose($this);
		}

		if ($remove) {
			$this->getParent()?->removeComponent($this);
		}
	}

	public function handleClose(): void
	{
		if ($this->isActive()) {
			$this->close();
		}
	}

	public function render(): void
	{
		$this->beforeRender();
		$this->getTemplate()->render();
	}

	protected function beforeRender(): void
	{
		/** @var DefaultTemplate $template */
		$template = $this->getTemplate();
		$template->setParameters(['modalTemplate' => self::$MODAL_TEMPLATE]);
	}
}