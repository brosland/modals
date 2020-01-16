<?php
declare(strict_types=1);

namespace Brosland\Modals\UI\Confirmation;

interface ConfirmationFormFactory
{
    function create(): ConfirmationForm;
}