<?php
declare(strict_types=1);

namespace Brosland\Modals\UI;

use Nette\Application\UI\Presenter;
use Nette\Application\UI\Template;
use Nette\InvalidArgumentException;
use Nette\Utils\Random;
use RuntimeException;

trait ModalManagerTrait
{
    private static string $COOKIE_PREFIX = 'brosland_modals__';

    /** @persistent */
    public ?string $modal = null;
    private ?Modal $activeModal = null;
    private bool $initialized = false;

    public abstract function getPresenter(): ?Presenter;

    public abstract function getTemplate(): Template;

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

    public function updateModal(): void
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

    private function init(): void
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