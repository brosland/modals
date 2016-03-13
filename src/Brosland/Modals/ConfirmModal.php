<?php

namespace Brosland\Modals;

class ConfirmModal extends Modal
{

	/**
	 * @var \Closure[]
	 */
	public $onConfirmed = [];
	/**
	 * @var string|\Nette\Utils\Html
	 */
	private $title = 'frontend.confirmModal.confirmation', $content = NULL;


	/**
	 * @param string|\Nette\Utils\Html $title
	 * @return self
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @param string|\Nette\Utils\Html $content
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
		$this->template->setFile(__DIR__ . '/templates/ConfirmModal/default.latte');
	}
}