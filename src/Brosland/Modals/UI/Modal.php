<?php
declare(strict_types=1);

namespace Brosland\Modals\UI;

use Nette\Application\UI\Control;

/**
 * @method void onClose(self $modal)
 */
abstract class Modal extends Control
{
    /**
     * @var string
     */
    public static $VERSION = 'v4';
    /**
     * @var callable[]
     */
    public $onClose = [];
    /**
     * @var bool
     */
    private $openRequired = false;


    public function isActive(): bool
    {
        return $this->getModalManager()->getActiveModal() === $this;
    }

    public function isOpenRequired(): bool
    {
        return $this->openRequired;
    }

    public function open(): void
    {
        $modalManager = $this->getModalManager();
        $activeModal = $modalManager->getActiveModal();

        if ($activeModal !== $this && $activeModal !== null) {
            $activeModal->close();
        }

        $modalManager->setActiveModal($this);

        $this->openRequired = true;
    }

    public function close(): void
    {
        $modalManager = $this->getModalManager();

        if ($modalManager->getActiveModal() === $this) {
            $modalManager->setActiveModal(null);

            $this->openRequired = false;

            if ($this->presenter->isAjax()) {
                $this->presenter->getPayload()->closeModal = true;
                $this->presenter->getPayload()->postGet = true;
                $this->presenter->getPayload()->url = $this->link('this');
            }

            $this->onClose($this);
        }
    }

    public function handleClose(): void
    {
        $this->close();
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/Modal.' . self::$VERSION . '.latte');
        $this->template->modalTemplate = $this->template->getFile();
    }

    private function getModalManager(): ModalManager
    {
        /** @var ModalManager $control */
        $control = $this->lookup(ModalManager::class);

        return $control;
    }
}