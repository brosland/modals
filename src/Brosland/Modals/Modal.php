<?php

namespace Brosland\Modals;

use Nette\Application\UI\Presenter;

abstract class Modal extends \Brosland\UI\Control
{

	/**
	 * @var Modal
	 */
	private static $ACTIVE_MODAL = NULL;
	/**
	 * @var boolean
	 */
	private static $CLOSE_REQUIRED = FALSE;
	/**
	 * @var \Closure[]
	 */
	public $onClose = [];
	/**
	 * @persistent
	 * @var boolean
	 */
	public $visible = FALSE;
	/**
	 * @var boolean
	 */
	private $openRequired = FALSE;


	/**
	 * @return Modal
	 */
	public static function getActiveModal()
	{
		return self::$ACTIVE_MODAL;
	}

	/**
	 * @return boolean
	 */
	public static function isCloseRequired()
	{
		return self::$CLOSE_REQUIRED;
	}

	/**
	 * @return boolean
	 */
	public function isOpenRequired()
	{
		return $this->openRequired;
	}

	/**
	 * @param boolean $visible
	 * @return self
	 */
	public function setVisible($visible = TRUE, $openRequired = FALSE)
	{
		if ($visible)
		{
			if (self::$ACTIVE_MODAL !== NULL && self::$ACTIVE_MODAL !== $this)
			{
				self::$ACTIVE_MODAL->setVisible(FALSE);
			}

			self::$ACTIVE_MODAL = $this;
		}
		else
		{
			if (self::$ACTIVE_MODAL == $this)
			{
				self::$ACTIVE_MODAL = NULL;
			}

			self::$CLOSE_REQUIRED = TRUE;
		}

		$this->visible = $visible;
		$this->openRequired = $openRequired;

		return $this;
	}

	public function handleClose()
	{
		$this->setVisible(FALSE);
		$this->onClose($this);
	}

	/**
	 * Forces control or its snippet to repaint.
	 * @return void
	 */
	public function redrawControl($snippet = NULL, $redraw = TRUE)
	{
		parent::redrawControl($snippet, $redraw);

		$this->setVisible(TRUE);
	}

	/**
	 * @param IComponent $component
	 */
	protected function attached($component)
	{
		parent::attached($component);

		if (!$component instanceof Presenter)
		{
			return;
		}

		if ($this->visible)
		{
			self::$ACTIVE_MODAL = $this;
		}
	}

	protected function beforeRender()
	{
		$this->template->setFile(__DIR__ . '/templates/Modal/default.latte');
	}

	public function render()
	{
		$this->beforeRender();

		$this->template->render();
	}
}