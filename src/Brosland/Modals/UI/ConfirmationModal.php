<?php

namespace Brosland\Modals\UI;

use Nette\Utils\Html;

class ConfirmationModal extends Modal
{

	/**
	 * @var callable[]
	 */
	public $onConfirmed = [];
	/**
	 * @var string|Html
	 */
	private $title = 'brosland.modals.ui.confirmationModal.confirmation';
	/**
	 * @var string|Html
	 */
	private $content = NULL;


	/**
	 * @param string|Html $title
	 * @return self
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @param string|Html $content
	 * @return self
	 */
	public function setContent($content)
	{
		$this->content = $content;

		return $this;
	}

	public function handleConfirm()
	{
		$this->onConfirmed($this);
	}

	protected function beforeRender()
	{
		parent::beforeRender();

		$this->template->title = $this->title;
		$this->template->content = $this->content;
		$this->template->parentTemplate = $this->template->getFile();
		$this->template->setFile(__DIR__ . '/templates/ConfirmationModal/default.latte');
	}
}

interface IConfirmationModalFactory
{

	/**
	 * @return ConfirmationModal
	 */
	function create();
}