<?php

namespace Brosland\Modals\UI;

use Nette\Application\UI\Control,
	Nette\Application\UI\Presenter,
	Nette\Utils\Html;

abstract class Modal extends Control
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
	 * @var string
	 */
	public static $VERSION = 'v3';
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
	 * @var Html
	 */
	private $elementPrototype = NULL;


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

	/**
	 * @return Html
	 */
	public function getElementPrototype()
	{
		if ($this->elementPrototype === NULL)
		{
			$this->elementPrototype = Html::el('div');
		}

		return $this->elementPrototype;
	}

	public function handleClose()
	{
		$this->setVisible(FALSE);
		$this->onClose($this);
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
		$elementPrototype = $this->getElementPrototype();
		$elementPrototype->class[] = 'modal fade';
		$elementPrototype->addAttributes([
			'tabindex' => -1,
			'role' => 'dialog',
			'aria-labelledby' => $this->getUniqueId() . '-label',
			'data-onclose' => $this->link('close!')
		]);

		$this->template->elementPrototype = $elementPrototype;
		$this->template->setFile(__DIR__ . '/templates/Modal/' . self::$VERSION . '.latte');
	}

	public function render()
	{
		$this->beforeRender();

		$this->template->render();
	}
}