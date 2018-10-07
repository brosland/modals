<?php
declare(strict_types=1);

namespace Brosland\Modals\UI\Confirmation;

use Brosland\Modals\UI\Modal;

/**
 * @method onConfirm(self $modal): void
 */
class ConfirmationModal extends Modal
{
    /**
     * @var callable[] function (ConfirmationModal $modal) {...}
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
        parent::__construct();

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

        $this->template->title = $this->title;
        $this->template->question = $this->question;

        $this->template->setFile(__DIR__ . '/ConfirmationModal.latte');
        $this->template->render();
    }

    // factories **************************************************************/

    protected function createComponentForm(): ConfirmationForm
    {
        $form = $this->confirmationFormFactory->create();
        $form['cancel']->onClick[] = function () {
            $this->close();
        };
        $form['confirm']->onClick[] = function () {
            $this->onConfirm($this);
        };

        return $form;
    }
}

interface ConfirmationModalFactory
{
    function create(): ConfirmationModal;
}