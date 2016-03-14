<?php

namespace Brosland\Modals\DI;

use Nette\PhpGenerator\ClassType;

class ModalsExtension extends \Nette\DI\CompilerExtension
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
		$initialize->addBody(\Brosland\Modals\UI\Modal::class . '::$VERSION = ?;', [$config['version']]);
	}
}