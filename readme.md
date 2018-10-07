Brosland / Modals
=================

List of components:
- **Modal** - abstract class for a modal
- **ConfirmationModal** - simple modal for a confirmation

### Installation

The best way to install is using [Composer](http://getcomposer.org/):

```sh
$ composer require brosland/modals
```

1\.	Add ModalTrait to your base presenter and override method ```beforeRender```
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

2\.	Add placeholder for a modal to your base layout
```html
{snippet modal}{ifset $modal}{control $modal}{/ifset}{/snippet}
```

3\. Install [Webpack](https://webpack.js.org/) and [Naja](https://github.com/jiripudil/Naja).

If you don't prefer one of them you will need to implement own client side (for inspiration look at [this](https://github.com/brosland/modals/blob/development/client-side/Brosland_ModalsNajaExtension.js)). 
Else you can continue to the next step.
 
4\. Copy `client-side/Brosland_ModalsNajaExtension.js` to your directory with Javascript files and register it in `Naja`.

5\. Don't forget to rebuild Webpack bundles.


### Example
You can find example code [here](https://github.com/brosland/modals-test).