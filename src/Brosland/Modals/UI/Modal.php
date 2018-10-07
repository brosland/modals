<?php
declare(strict_types=1);

namespace Brosland\Modals\UI;

use Nette\Application\UI\Control;
use Nette\Application\UI\Presenter;

/**
 * @method onClose(self $modal): void
 */
abstract class Modal extends Control
{
    private const COOKIE_ACTIVE_MODAL = 'brosland_modals__active_modal';

    /**
     * @var string
     */
    public static $VERSION = 'v4';
    /**
     * @var bool
     */
    private static $INITIALIZED = false;
    /**
     * @var null|Modal
     */
    private static $ACTIVE_MODAL;
    /**
     * @var callable[]
     */
    public $onClose = [];
    /**
     * @var bool
     */
    private $openRequired = false;


    public static function getActiveModal(Presenter $presenter): ?Modal
    {
        if (!self::$INITIALIZED) {
            $httpRequest = $presenter->getHttpRequest();
            $activeModalId = $httpRequest->getCookie(self::COOKIE_ACTIVE_MODAL);

            if ($activeModalId !== null) {
                $control = $presenter->getComponent($activeModalId, false);

                if ($control instanceof Modal) {
                    self::$ACTIVE_MODAL = $control;
                }
            }

            self::$INITIALIZED = true;
        }

        return self::$ACTIVE_MODAL;
    }

    public function isActive(): bool
    {
        return self::getActiveModal($this->presenter) === $this;
    }

    public function isOpenRequired(): bool
    {
        return $this->openRequired;
    }

    public function open(): void
    {
        $activeModal = self::getActiveModal($this->presenter);

        if ($activeModal !== $this && $activeModal !== null) {
            $activeModal->close();
        }

        self::$ACTIVE_MODAL = $this;

        $this->openRequired = true;

        $httpResponse = $this->presenter->getHttpResponse();
        $httpResponse->setCookie(self::COOKIE_ACTIVE_MODAL, $this->getUniqueId(), '1 days');
    }

    public function close(): void
    {
        if (self::getActiveModal($this->presenter) === $this) {
            self::$ACTIVE_MODAL = null;

            $this->openRequired = false;

            $httpResponse = $this->presenter->getHttpResponse();
            $httpResponse->deleteCookie(self::COOKIE_ACTIVE_MODAL);

            if ($this->presenter->isAjax()) {
                $this->presenter->getPayload()->closeModal = true;
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
}