<?php

namespace Brosland\Modals\DI;

use Brosland\Modals\UI\Modal,
	Nette\DI\CompilerExtension,
	Nette\Reflection\ClassType;

class ModalsExtension extends CompilerExtension
{

	/**
	 * @var array
	 */
	private $defaults = [
		'version' => 'v3'
	];


	/**
	 * @param ClassType $class
	 */
	public function afterCompile(ClassType $class)
	{
		parent::afterCompile($class);

		$config = $this->getConfig($this->defaults);

		$initialize = $class->methods['initialize'];
		$initialize->addBody(Modal::class . '::$VERSION = ?;', [$config['version']]);
	}
}