<?php

namespace Brosland\Modals\DI;

use Brosland\Modals\UI\Modal;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;

final class ModalsExtension extends CompilerExtension
{
    /**
     * @return mixed[]
     */
    public function getConfig()
    {
        return parent::getConfig([
            'version' => 'v4'
        ]);
    }

    /**
	 * @param ClassType $class
	 */
	public function afterCompile(ClassType $class)
	{
		parent::afterCompile($class);

		$config = $this->getConfig();

		$initialize = $class->methods['initialize'];
		$initialize->addBody(Modal::class . '::$VERSION = ?;', [$config['version']]);
	}
}