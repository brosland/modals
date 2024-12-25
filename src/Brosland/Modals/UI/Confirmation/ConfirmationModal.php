<?php
declare(strict_types=1);

namespace Brosland\Modals\UI\Confirmation;

use Brosland\Modals\UI\Modal;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;

/**
 * @method void onConfirm(self $modal)
 */
class ConfirmationModal extends Modal
{
	/** @var array<callable> */
	public array $onConfirm = [];
	/** @var string|mixed */
	private mixed $title, $question;
	public static string $CONFIRMATION_MODAL_TEMPLATE = __DIR__ . '/ConfirmationModal.latte';

	public function __construct()
	{
		parent::__construct();

		$this->title = $this->prefix('confirmation');
		$this->question = $this->prefix('question');
	}

	/**
	 * @param string|mixed $title
	 */
	public function setTitle(mixed $title): void
	{
		$this->title = $title;
	}

	/**
	 * @param string|mixed $question
	 */
	public function setQuestion(mixed $question): void
	{
		$this->question = $question;
	}

	protected function beforeRender(): void
	{
		parent::beforeRender();

		/** @var DefaultTemplate $template */
		$template = $this->getTemplate();
		$template->setFile(self::$CONFIRMATION_MODAL_TEMPLATE);

		$template->setParameters([
			'title' => $this->title,
			'question' => $this->question,
			'cancelLabel' => $this->prefix('cancel'),
			'confirmLabel' => $this->prefix('confirm')
		]);
	}

	private function prefix(string $value): string
	{
		return implode('.', ['//brosland', self::class, $value]);
	}

	// factories ***************************************************************

	protected function createComponentForm(): Form
	{
		$form = new Form();

		$cancelButton = $form->addSubmit('cancel');
		$cancelButton->onClick[] = fn() => $this->close();

		$confirmButton = $form->addSubmit('confirm');
		$confirmButton->onClick[] = fn() => $this->onConfirm($this);

		return $form;
	}
}