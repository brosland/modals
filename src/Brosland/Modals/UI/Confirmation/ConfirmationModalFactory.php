<?php

namespace Brosland\Modals\UI\Confirmation;

interface ConfirmationModalFactory
{
    /**
     * @return ConfirmationModal
     */
    function create();
}