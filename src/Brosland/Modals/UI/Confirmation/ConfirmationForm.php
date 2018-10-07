<?php
declare(strict_types=1);

namespace Brosland\Modals\UI\Confirmation;

use Nette\Application\UI\Form;

class ConfirmationForm extends Form
{
    /**
     * @var string
     */
    private $prefix = 'brosland.modals.ui.confirmation.confirmationForm.';


    public function __construct()
    {
        parent::__construct();

        $this->addSubmit('cancel', $this->prefix . 'cancel')
            ->setValidationScope([]);

        $this->addSubmit('confirm', $this->prefix . 'confirm');
    }
}

interface ConfirmationFormFactory
{
    function create(): ConfirmationForm;
}