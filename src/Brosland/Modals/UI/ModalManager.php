<?php

namespace Brosland\Modals\UI;

interface ModalManager
{
    /**
     * @return Modal|null
     */
    function getActiveModal();

    /**
     * @param Modal|null $modal
     */
    function setActiveModal(Modal $modal = null);
}