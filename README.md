CakePHP 2.0 Airbrake
============

A CakePHP plugin to use Airbrake for errors and exceptions.

Installation
=========================
```
git submodule add git://github.com/morrislaptop/AirbrakeCake.git app/Plugin/AirbrakeCake
cd app/Plugin/AirbrakeCake
git submodule init
git submodule update
```
    

app/Config/bootstrap.php
=========================

```php
<?php
// Include our awesome error catcher..
CakePlugin::load('AirbrakeCake');
Configure::write('AirbrakeCake.apiKey', '<API KEY>');
App::uses('AirbrakeError', 'AirbrakeCake.Lib');
```

app/Config/core.php
=========================

```php
<?php
	Configure::write('Error', array(
		'handler' => 'AirbrakeError::handleError',
		'level' => E_ALL & ~E_DEPRECATED,
		'trace' => true
	));
	
	Configure::write('Exception', array(
		'handler' => 'AirbrakeError::handleException',
		'renderer' => 'ExceptionRenderer',
		'log' => true
	));
```