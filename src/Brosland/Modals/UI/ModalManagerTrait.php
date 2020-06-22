<?php

namespace Brosland\Modals\UI;

use Nette\Application\UI\ITemplate;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\InvalidArgumentException;
use Nette\Utils\Random;
use RuntimeException;

trait ModalManagerTrait
{
    /**
     * @persistent
     * @var string|null
     */
    public $modal = null;
    /** @var bool */
    private $initialized = false;
    /** @var Modal|null */
    private $activeModal;
    /** @var string */
    private static $COOKIE_PREFIX = 'brosland_modals__';

    /**
     * @return Presenter|null
     */
    public abstract function getPresenter();

    /**
     * @return ITemplate
     */
    public abstract function getTemplate();

    /**
     * @return Modal|null
     */
    public function getActiveModal()
    {
        if (!$this->initialized) {
            $this->init();
        }

        return $this->activeModal;
    }

    /**
     * @return void
     */
    public function setActiveModal(Modal $modal = null)
    {
        if (!$this->initialized) {
            $this->init();
        }

        $presenter = $this->getPresenter();

        if ($presenter === null) {
            throw new RuntimeException('The component is not attached to the presenter.');
        }

        $httpResponse = $presenter->getHttpResponse();

        if ($this->modal !== null) {
            $httpResponse->deleteCookie(self::$COOKIE_PREFIX . $this->modal);

            $this->modal = null;
            $this->activeModal = null;
        }

        if ($modal !== null) {
            $this->modal = Random::generate(6);
            $this->activeModal = $modal;

            $httpResponse->setCookie(
                self::$COOKIE_PREFIX . $this->modal,
                $modal->getUniqueId(),
                '1 day'
            );
        }
    }

    /**
     * @return void
     */
    public function updateModal()
    {
        if (!$this->initialized) {
            $this->init();
        }

        /** @var Template $template */
        $template = $this->getTemplate();
        $template->add('modal', $this->activeModal);

        $presenter = $this->getPresenter();

        if ($presenter === null) {
            throw new RuntimeException('The component is not attached to the presenter.');
        }

        $session = $presenter->getSession(Modal::class);

        $closeModal = isset($session['close']);
        unset($session['close']);

        if ($presenter->isAjax()) {
            $presenter->redrawControl(
                'modal',
                $this->activeModal !== null && $this->activeModal->isOpenRequired()
            );

            if ($closeModal) {
                $presenter->getPayload()->brosland_modals__closeModal = true;
            }
        }
    }

    /**
     * @return void
     */
    private function init()
    {
        $this->initialized = true;

        if ($this->modal === null) {
            return;
        }

        $presenter = $this->getPresenter();

        if ($presenter === null) {
            throw new RuntimeException('The component is not attached to the presenter.');
        }

        $httpRequest = $presenter->getHttpRequest();
        $activeModalId = (string)$httpRequest->getCookie(self::$COOKIE_PREFIX . $this->modal);

        try {
            $control = $presenter->getComponent($activeModalId);

            if (!$control instanceof Modal) {
                throw new InvalidArgumentException();
            }

            $this->activeModal = $control;
        } catch (InvalidArgumentException $e) {
            $this->setActiveModal(null);
        }
    }
}