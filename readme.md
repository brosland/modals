Brosland / Modals
=================

List of components:
- **Modal** - abstract class for modal
- **ConfirmModal** - simple modal for confirmation

### Installation

The best way to install is using [Composer](http://getcomposer.org/):

```sh
$ composer require brosland/modals
```

1. add ModalTrait to your base presenter and override method ```beforeRender```
```php
abstract class BasePresenter extends \Nette\Application\UI\Presenter
{
	use \Brosland\Modals\ModalTrait;

	protected function beforeRender()
	{
		parent::beforeRender();

		$this->updateModal($this);
	}
	// ...
```

2. add placeholder for a modal to your base layout
```html
{snippet modal}{ifset $modal}{control $modal}{/ifset}{/snippet}
```

3. Copy `brosland.modals.js` to your directory with Javascript files (you can use [Bower](http://bower.io/) for this).

4. Link the file in your templates (usually in `app/@layout.latte`, after jQuery!).