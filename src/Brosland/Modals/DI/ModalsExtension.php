<?php
declare(strict_types=1);

namespace Brosland\Modals\DI;

use Brosland\Modals\UI\Confirmation\ConfirmationModalFactory;
use Brosland\Modals\UI\Modal;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

final class ModalsExtension extends CompilerExtension
{
	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'modalTemplate' => Expect::string()->dynamic()
		])->castTo('array');
	}

	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		$builder = $this->getContainerBuilder();

		$builder->addFactoryDefinition($this->prefix('confirmationModalFactory'))
			->setImplement(ConfirmationModalFactory::class);
	}

	public function afterCompile(ClassType $class): void
	{
		parent::afterCompile($class);

		/** @var array<string,mixed> $config */
		$config = $this->getConfig();

		if (isset($config['modalTemplate'])) {
			$initialize = $class->getMethod('initialize');
			$initialize->addBody(Modal::class . '::$MODAL_TEMPLATE = ?;', [$config['modalTemplate']]);
		}
	}
}