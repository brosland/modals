<?php
declare(strict_types=1);

namespace Brosland\Modals\UI;

interface ModalManager
{
	function getActiveModal(): ?Modal;

	function setActiveModal(?Modal $modal): void;
}