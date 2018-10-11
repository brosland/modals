<?php
declare(strict_types=1);

namespace Brosland\Modals\UI;

use Nette\Application\UI\Presenter;
use Nette\InvalidArgumentException;
use Nette\Utils\Random;

trait ModalManagerTrait
{
    /**
     * @persistent
     * @var null|string
     */
    public $modal = null;
    /**
     * @var bool
     */
    private $initialized = false;
    /**
     * @var null|Modal
     */
    private $activeModal;
    /**
     * @var string
     */
    private static $COOKIE_PREFIX = 'brosland_modals__';


    /**
     * @param bool $throw
     * @return Presenter
     */
    abstract function getPresenter($throw = true);

    public function getActiveModal(): ?Modal
    {
        if (!$this->initialized) {
            $this->init();
        }

        return $this->activeModal;
    }

    public function setActiveModal(Modal $modal = null): void
    {
        if (!$this->initialized) {
            $this->init();
        }

        $httpResponse = $this->getPresenter()->getHttpResponse();

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

    public function updateModal(): void
    {
        if (!$this->initialized) {
            $this->init();
        }

        $this->getPresenter()->getTemplate()->modal = $this->activeModal;

        if ($this->getPresenter()->isAjax()) {
            $redrawModal = $this->activeModal !== null &&
                $this->activeModal->isOpenRequired();

            $this->getPresenter()->redrawControl('modal', $redrawModal);
        }
    }

    private function init(): void
    {
        $this->initialized = true;

        if ($this->modal === null) {
            return;
        }

        $httpRequest = $this->getPresenter()->getHttpRequest();
        $activeModalId = (string)$httpRequest->getCookie(self::$COOKIE_PREFIX . $this->modal);

        try {
            $control = $this->getPresenter()->getComponent($activeModalId);

            if (!$control instanceof Modal) {
                throw new InvalidArgumentException();
            }

            $this->activeModal = $control;
        } catch (InvalidArgumentException $e) {
            $this->setActiveModal(null);
        }
    }
}