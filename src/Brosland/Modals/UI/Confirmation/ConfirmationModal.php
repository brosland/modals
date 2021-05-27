<?php
declare(strict_types=1);

namespace Brosland\Modals\UI\Confirmation;

use Brosland\Modals\UI\Modal;
use Nette\Application\UI\Form;

/**
 * @method void onConfirm(self $modal)
 */
class ConfirmationModal extends Modal
{
    /** @var callable[] */
    public array $onConfirm = [];
    private string $title, $question;

    public function __construct()
    {
        $this->title = $this->prefix('confirmation');
        $this->question = $this->prefix('question');
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setQuestion(string $question): void
    {
        $this->question = $question;
    }

    protected function beforeRender(): void
    {
        parent::beforeRender();

        $template = $this->getTemplate();
        $template->title = $this->title;
        $template->question = $this->question;
        $template->cancelLabel = $this->prefix('cancel');
        $template->confirmLabel = $this->prefix('confirm');
        $template->setFile(__DIR__ . '/ConfirmationModal.latte');
    }

    private function prefix(string $value): string
    {
        return implode('.', ['//brosland', self::class, $value]);
    }

    // factories ***************************************************************

    protected function createComponentForm(): Form
    {
        $form = new Form();

        $cancelButton = $form->addSubmit('cancel');
        $cancelButton->onClick[] = function (): void {
            $this->close();
        };

        $confirmButton = $form->addSubmit('confirm');
        $confirmButton->onClick[] = function (): void {
            $this->onConfirm($this);
        };

        return $form;
    }
}