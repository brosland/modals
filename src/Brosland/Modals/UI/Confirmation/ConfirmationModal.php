<?php
declare(strict_types=1);

namespace Brosland\Modals\UI\Confirmation;

use Brosland\Modals\UI\Modal;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Forms\Controls\SubmitButton;

/**
 * @method void onConfirm(self $modal)
 */
class ConfirmationModal extends Modal
{
    /**
     * @var callable[] function (self $modal):void {...}
     */
    public $onConfirm = [];
    /**
     * @var string
     */
    private $prefix = 'brosland.modals.ui.confirmation.confirmationModal.';
    /**
     * @var string
     */
    private $title, $question;
    /**
     * @var ConfirmationFormFactory
     */
    private $confirmationFormFactory;

    public function __construct(ConfirmationFormFactory $confirmationFormFactory)
    {
        $this->confirmationFormFactory = $confirmationFormFactory;
        $this->title = $this->prefix . 'confirmation';
        $this->question = $this->prefix . 'question';
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setQuestion(string $question): void
    {
        $this->question = $question;
    }

    public function render(): void
    {
        parent::render();

        /** @var Template $template */
        $template = $this->getTemplate();
        $template->add('title', $this->title);
        $template->add('question', $this->question);
        $template->setFile(__DIR__ . '/ConfirmationModal.latte');
        $template->render();
    }

    // factories ***************************************************************

    protected function createComponentForm(): ConfirmationForm
    {
        $form = $this->confirmationFormFactory->create();

        /** @var SubmitButton $cancelButton */
        $cancelButton = $form['cancel'];
        $cancelButton->onClick[] = function (): void {
            $this->close();
        };

        /** @var SubmitButton $confirmButton */
        $confirmButton = $form['confirm'];
        $confirmButton->onClick[] = function (): void {
            $this->onConfirm($this);
        };

        return $form;
    }
}