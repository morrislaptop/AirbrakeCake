<?php
/**
 * Bootstraps the Airbrake plugin.
 * Before loading the plugin, please set the required API key:
 *
 * Configure::write('AirbrakeCake.apiKey', '<API KEY>');
 */

	App::uses('AirbrakeError', 'AirbrakeCake.Lib');

/**
 * Sets the ErrorHandler and ExceptionHandler to
 * AirbrakeError.
 */
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