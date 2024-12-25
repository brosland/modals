<?php
declare(strict_types=1);

namespace Brosland\Modals\UI;

use Nette\ComponentModel\IComponent;

interface ModalManager extends IComponent
{
	function getActiveModal(): ?Modal;

	function setActiveModal(?Modal $modal): void;
}