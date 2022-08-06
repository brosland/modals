<?php
declare(strict_types=1);

namespace Brosland\Modals\UI\Confirmation;

interface ConfirmationModalFactory
{
	function create(): ConfirmationModal;
}