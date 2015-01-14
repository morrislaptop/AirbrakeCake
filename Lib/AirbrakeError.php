<?php
use Airbrake\Configuration as AirbrakeConfiguration;
use Airbrake\Client as AirbrakeClient;

class AirbrakeError extends ErrorHandler
{

	/**
	 * Creates a new Airbrake instance, or returns an instance created earlier.
	 * You can pass options to Airbrake\Configuration by setting the AirbrakeCake.options
	 * configuration property.
	 *
	 * For example to set the environment name:
	 *
	 * ```
	 * Configure::write('AirbrakeCake.options', array(
	 * 	'environmentName' => 'staging'
	 * ));
	 * ```
	 *
	 * @return Airbrake\Client
	 */
	public static function getAirbrake() {
		static $client = null;

		if ($client === null) {
			$apiKey  = Configure::read('AirbrakeCake.apiKey');
			$options = Configure::read('AirbrakeCake.options');

			if (!$options) {
				$options = array();
			}

			$config = new AirbrakeConfiguration($apiKey, $options);
			$client = new AirbrakeClient($config);
		}

		return $client;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function handleError($code, $description, $file = null, $line = null, $context = null) {
		$client = static::getAirbrake();
		$client->notifyOnError($description);

		return parent::handleError($code, $description, $file, $line, $context);
	}

	/**
	 * {@inheritDoc}
	 */
	public static function handleException(Exception $exception) {
		$client = static::getAirbrake();
		$client->notifyOnException($exception);

		return parent::handleException($exception);
	}
}
