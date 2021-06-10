<?php

require __DIR__ . '../../vendor/autoload.php';

/**
 * @param $class
 */
function vendorAutoloader($class)
{
    $vendorDir = WOOCOMMERCE_CONNECTOR_DIR_PATH . 'vendor' . DIRECTORY_SEPARATOR;


    /**
     * Autoloader for Monolog
     */
    if (strpos($class, 'Monolog\\') !== false) {

        $class = str_replace('Monolog\\', 'monolog/monolog/src/Monolog/', $class);

        $path = $vendorDir . $class  . '.php';

        requireClass($path);
    }

    /**
     * Autoloader for PSR Log
     */
    if (strpos($class, 'Psr\\Log\\') !== false) {

        $class = str_replace('Psr\\Log\\', 'psr/log/Psr/Log/', $class);

        $path = $vendorDir . $class  . '.php';

        requireClass($path);
    }

    /**
     * Autoloader for PSR http message
     */
    if (strpos($class, 'Psr\\Http\\Message\\') !== false) {

        $class = str_replace('Psr\\Http\\Message\\', 'psr/http-message/src/', $class);

        $path = $vendorDir . $class  . '.php';

        requireClass($path);
    }

    /**
     * Autoloader for Guzzle PSR7
     */
    if (strpos($class, 'GuzzleHttp\\Psr7\\') !== false) {

        $class = str_replace('GuzzleHttp\\Psr7\\', 'guzzlehttp/psr7/src/', $class);

        $path = $vendorDir . $class  . '.php';

        requireClass($path);
    }

    /**
     * Autoloader for Guzzle Promise
     */
    if (strpos($class, 'GuzzleHttp\\Promise\\') !== false) {

        $class = str_replace('GuzzleHttp\\Promise\\', 'guzzlehttp/promises/src/', $class);

        $path = $vendorDir . $class  . '.php';

        requireClass($path);
    }

    /**
     * Autoloader for GuzzleHTTP
     */
    if (strpos($class, 'GuzzleHttp\\') !== false) {

        $packages = [
            'guzzle',
            'promises',
            'psr7',
        ];

        foreach ($packages as $package) {
            $newClass = str_replace('GuzzleHttp\\', 'guzzlehttp/' . $package . '/src/', $class);

            $path = $vendorDir . $newClass  . '.php';

            require_once $vendorDir . 'guzzlehttp/' . $package . '/src/functions.php';

            requireClass($path);
        }

    }

    /**
     * Autoloader for Connector lib
     */
    if (strpos($class, 'ActiveAnts\\ConnectorLib\\') !== false) {

        $class = str_replace('ActiveAnts\\ConnectorLib\\', 'activeants/connector-lib/src/', $class);

        $path = $vendorDir . $class  . '.php';

        requireClass($path);
    }

    /**
     * Autoloader for Logger lib
     */
	if (strpos($class, 'ActiveAnts\\LoggerLib\\') !== false) {

		$class = str_replace('ActiveAnts\\LoggerLib\\', 'activeants/logger-lib/src/', $class);

		$path = $vendorDir . $class  . '.php';

        requireClass($path);
	} else {
        $path = $vendorDir . 'activeants' .
            DIRECTORY_SEPARATOR . 'djjob' . DIRECTORY_SEPARATOR . $class . '.php';

        requireClass($path);
    }
}

function requireClass($class)
{
    $class = str_replace('\\', '/', $class);

    if (file_exists($class)) {
        require_once $class;
    }
}

spl_autoload_register('vendorAutoloader');
