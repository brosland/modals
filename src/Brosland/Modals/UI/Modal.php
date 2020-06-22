<?php

namespace Brosland\Modals\UI;

use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Http\SessionSection;

/**
 * @method void onClose(self $modal)
 */
abstract class Modal extends Control
{
    /** @var string */
    public static $VERSION = 'v4';
    /** @var callable[] */
    public $onClose = [];
    /** @var bool */
    private $openRequired = false;

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->getModalManager()->getActiveModal() === $this;
    }

    /**
     * @return bool
     */
    public function isOpenRequired()
    {
        return $this->openRequired;
    }

    /**
     * @return void
     */
    public function open()
    {
        $modalManager = $this->getModalManager();
        $activeModal = $modalManager->getActiveModal();

        if ($activeModal !== $this && $activeModal !== null) {
            $activeModal->close();
        }

        $modalManager->setActiveModal($this);

        $this->openRequired = true;
    }

    /**
     * @return void
     */
    public function close()
    {
        $modalManager = $this->getModalManager();

        if ($modalManager->getActiveModal() === $this) {
            $modalManager->setActiveModal(null);

            $this->openRequired = false;
            $this->getSession()['close'] = true;

            $this->onClose($this);
        }
    }

    /**
     * @return void
     */
    public function handleClose()
    {
        if ($this->isActive()) {
            $this->close();
        }
    }

    /**
     * @return void
     */
    public function render()
    {
        $this->beforeRender();
        $this->getTemplate()->render();
    }

    /**
     * @return void
     */
    protected function beforeRender()
    {
        /** @var Template $template */
        $template = $this->getTemplate();
        $template->add('modalTemplate', __DIR__ . '/Modal.' . self::$VERSION . '.latte');
    }

    /**
     * @return ModalManager
     */
    private function getModalManager()
    {
        /** @var ModalManager $control */
        $control = $this->lookup(ModalManager::class);

        return $control;
    }

    /**
     * @return SessionSection<mixed>
     */
    private function getSession()
    {
        /** @var SessionSection<mixed> $section */
        $section = $this->presenter->getSession(self::class);

        return $section;
    }
}