<?php

namespace Brosland\Modals\UI\Confirmation;

use Brosland\Modals\UI\Modal;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\Template;

/**
 * @method void onConfirm(self $modal)
 */
class ConfirmationModal extends Modal
{
    /** @var callable[] */
    public $onConfirm = [];
    /** @var string */
    private $title, $question;

    public function __construct()
    {
        parent::__construct();

        $this->title = $this->prefix('confirmation');
        $this->question = $this->prefix('question');
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $question
     * @return void
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @param string $value
     * @return string
     */
    public function prefix($value)
    {
        return 'brosland.' . self::class . '.' . $value;
    }

    /**
     * @return void
     */
    public function render()
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

    /**
     * @return Form
     */
    protected function createComponentForm()
    {
        $form = new Form();
        $form->addSubmit('confirm', $this->prefix('confirm'));
        $form->onSuccess[] = function () {
            $this->onConfirm($this);
        };

        return $form;
    }
}