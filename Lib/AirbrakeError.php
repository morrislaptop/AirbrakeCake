<?php

App::import('Vendor', 'AirbrakeCake.Airbrake_Configuration', array('file' => 'php-airbrake' . DS . 'src' . DS . 'Airbrake'. DS . 'Configuration.php'));
App::import('Vendor', 'AirbrakeCake.Airbrake_Client', array('file' => 'php-airbrake' . DS . 'src' . DS . 'Airbrake'. DS . 'Client.php'));

class AirbrakeError extends ErrorHandler
{
    public static function handleError($code, $description, $file = null, $line = null, $context = null) 
    {
    	// Call Airbrake
		$apiKey  = Configure::read('AirbrakeCake.apiKey'); // This is required
		$options = array(); // This is optional

		$config = new Airbrake\Configuration($apiKey, $options);
		$client = new Airbrake\Client($config);
		$client->notifyOnError($description);
        
        // Fall back to cake
        return parent::handleError($code, $description, $file, $line, $context);
    }
    
    public static function handleException(Exception $exception)
    {
    	// Call Airbrake
		$apiKey  = Configure::read('AirbrakeCake.apiKey'); // This is required
		$options = array(); // This is optional

		$config = new Airbrake\Configuration($apiKey, $options);
		$client = new Airbrake\Client($config);
		$client->notifyOnException($exception);

    	// Fall back to Cake..
		return parent::handleException($exception);
    }
}