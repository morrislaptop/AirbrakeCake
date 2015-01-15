CakePHP 2.0 Airbrake
============

A CakePHP plugin to use Airbrake for errors and exceptions.

Installation via Composer
=========================

```
composer require morrislaptop/cakephp-airbrake
```


app/Config/bootstrap.php
=========================

```php
Configure::write('AirbrakeCake.apiKey', '<API KEY>');
CakePlugin::load('AirbrakeCake', array(
	'bootstrap' => true
));
```
