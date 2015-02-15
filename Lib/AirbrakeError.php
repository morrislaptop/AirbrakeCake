<?php
use Airbrake\Configuration as AirbrakeConfiguration;
use Airbrake\Client as AirbrakeClient;
use Airbrake\Notice as AirbrakeNotice;

App::uses('Router', 'Routing');

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

			if (php_sapi_name() !== 'cli') {
				$request = Router::getRequest();

				if ($request) {
					$options['component'] = $request->params['controller';
					$options['action'] = $request->params['action'];
				}
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
		list($error, $log) = self::mapErrorCode($code);

		$backtrace = debug_backtrace();
		if (count($backtrace) > 1) {
			array_shift($backtrace);
		}

		$notice = new Notice();
		$notice->load(array(
			'errorClass' => $error,
			'backtrace' => $backtrace,
			'errorMessage' => $description,
			'extraParams' => null
		));

		$client = static::getAirbrake();
		$client->notify($notice);

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
