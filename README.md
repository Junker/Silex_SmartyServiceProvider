SmartyServiceProvider
===================

The *SmartyServiceProvider* provides integration with the [Smarty] (http://www.smarty.net) template engine.

Parameters
----------

* **smarty.dir** (string, optional): Path to the directory containing Smarty distribution (a one with folders libs and demo).
If not given, provider assumes, that smarty class is already loaded.

* **smarty.instance** (\Smarty, optional): An instance of Smarty class. If not given, will be created by the provider.

* **smarty.options** (array, optional): An associative array of smarty class variables to set.
Check out the [Smarty documentation] (http://www.smarty.net/docs/en/api.variables.tpl) for more information.

* **smarty.configure** (callable, optional): A callable which takes one argument - Smarty class instance.
It is called during provider's register() method. You can use this option to make some custom smarty configuration, for example.

Services
--------

* **smarty**: The ``Smarty`` instance. The main way of interacting with Smarty.

Registering
-----------

Make sure you place a copy of [Smarty] (http://www.smarty.net) in the ``vendor/Smarty`` directory:

```php
use Junker\Silex\Provider\SmartyServiceProvider;
define('SMARTY_PATH', __DIR__ . '/../../../../vendor/Smarty');
        
$app->register(new SmartyServiceProvider(), array(
          'smarty.dir' => SMARTY_PATH,
          'smarty.options' => array(
                'template_dir' => SMARTY_PATH . '/demo/templates',
                'compile_dir' => SMARTY_PATH . '/demo/templates_c',
                'config_dir' => SMARTY_PATH . '/demo/configs',
                'cache_dir' => SMARTY_PATH . '/demo/cache',),));
```

**Note:**

Smarty is not compiled into the ``silex.phar`` file.
You have to add your own copy of [Smarty] (http://www.smarty.net) to your application.

Usage
-----

The Smarty provider provides a ``smarty`` service:

```php
$app->get('/hello/{name}', function ($name) use ($app) {
    return $app['smarty']->display('hello.tpl', [
        'name' => $name,
    ]);
});

$smarty2 = $app['smarty.new_instance']();

```

This will render a file named ``hello.tpl`` in the configured templates folder you passed in ``smarty.options``.

In any Smarty template, the ``app`` variable refers to the Application object.
So you can access any services from within your view. For example to access
``$app['request']->getHost()``, just put this in your template:

```
{$app.request->getHost()}
```

Traits
```php
class Application extends \Silex\Application
{
	use \Junker\Silex\Application\SmartyTrait;
}

$app->render('hello.tpl', [
        'name' => $name,
    ]);


Template usage
--------------
```smarty
{path _name='app.login'}
same as $app['url_generator']->generate('app.login');

{path _name='app.product' id=11}
same as $app['url_generator']->generate('app.product', ['id' => 11]);

{url _name='app.login'}
same as $app['url_generator']->generate('app.login', UrlGeneratorInterface::ABSOLUTE_URL);

{trans _name='err.access_forbidden'}
same as $app['translator']->trans('err.access_forbidden');

{trans _name='warn.my_city' city='London'}
same as $app['translator']->trans('warn.my_city', ['%city%' => 'London']);

{trans}hello{/trans}
same as $app['translator']->trans('hello');

{transChoice _name='days' _count=5}
same as $app['translator']->transChoice('days', 5, ['%count%' => 5]);

```
