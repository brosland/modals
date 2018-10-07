<?php
declare(strict_types=1);

namespace Brosland\Modals\DI;

use Brosland\Modals\UI\Modal;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;

final class ModalsExtension extends CompilerExtension
{
	/**
	 * @var array
	 */
	private $defaults = [
			'version' => 'v4'
	];


	public function afterCompile(ClassType $class): void
	{
		parent::afterCompile($class);

		$config = $this->getConfig($this->defaults);

		$initialize = $class->methods['initialize'];
		$initialize->addBody(Modal::class . '::$VERSION = ?;', [$config['version']]);
	}
}