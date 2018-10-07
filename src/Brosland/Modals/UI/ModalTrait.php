<?php
declare(strict_types=1);

namespace Brosland\Modals\UI;

use Nette\Application\UI\Presenter;

trait ModalTrait
{
    public function updateModal(Presenter $presenter): void
    {
        $presenter->getTemplate()->modal = $activeModal = Modal::getActiveModal($presenter);

        if ($presenter->isAjax()) {
            $redrawModal = $activeModal !== null && $activeModal->isOpenRequired();
            $presenter->redrawControl('modal', $redrawModal);
        }
    }
}