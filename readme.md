Brosland / Modals
=================

This package provides a simple management of modals for Nette framework (e.g. Bootstrap modals).

List of components:
- **Modal** - abstract class for a modal
- **ConfirmationModal** - simple modal for a confirmation

### Installation

The best way to install is using [Composer](http://getcomposer.org/):

```sh
$ composer require brosland/modals
```
1\. (optional) Register DI extension `ModalsExtension` in your neon config.
This step you can skip if you use Bootstrap 4.

```neon
brosland.modals:
	version: 'v5'

extensions:
	brosland.modals: Brosland\Modals\DI\ModalsExtension
```

2\.	Setup `ModalManager`: the best practice is to do it in your base presenter.
- add `ModalManagerTrait`
- implement interface `ModalManager`
- add update of modal into the method ```beforeRender```

```php
use Brosland\Modals\UI\ModalManager;
use Brosland\Modals\UI\ModalManagerTrait;
use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter implements ModalManager
{
	use ModalManagerTrait;

	protected function beforeRender(): void
	{
		parent::beforeRender();

		$this->updateModal();
	}

	// ...
```

3\.	Add placeholder for a modal to your base layout
```html
{snippet modal}{ifset $modal}{control $modal}{/ifset}{/snippet}
```

4\. Install [Webpack](https://webpack.js.org/) and [Naja](https://github.com/jiripudil/Naja).

5\. Copy `src/Brosland/Modals/UI/Modal.js` to your directory with Javascript files and register it in `Naja`.

6\. Don't forget to rebuild Webpack bundles.