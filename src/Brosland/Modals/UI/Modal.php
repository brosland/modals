<?php
declare(strict_types=1);

namespace Brosland\Modals\UI;

use Nette\Application\UI\Control;
use Nette\Application\UI\Presenter;
use Nette\ComponentModel\IComponent;
use Tracy\Debugger;

/**
 * @method void onClose(self $modal)
 */
abstract class Modal extends Control
{
	public static string $VERSION = 'v5';

	/** @var array<callable> */
	public array $onClose = [];
	private bool $openRequired = false;
	private ModalManager $modalManager;

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
		/** @var string|null $signalReceiverName */
		[$signalReceiverName] = $presenter->getSignal();

		if ($signalReceiverName !== null) {
			$signalReceiver = $presenter->getComponent($signalReceiverName, false);

			while ($signalReceiver !== null) {
				if ($signalReceiver === $this) {
					$this->modalManager->setActiveModal($this);
					break;
				}

				$signalReceiver = $signalReceiver->getParent();
			}
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

	public function close(bool $remove = true): void
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
		$template = $this->getTemplate();
		$template->modalTemplate = __DIR__ . '/Modal.' . self::$VERSION . '.latte';
	}
}