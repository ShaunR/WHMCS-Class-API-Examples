<?php

/**
 * WHMCS Class API example showing how to retreive service details using
 * 
 * @author Shaun Reitan
 * 
 * @see https://github.com/ShaunR/WHMCS-Class-API-Examples
 * 
 * @see https://classdocs.whmcs.com/7.8/WHMCS/Service/Service.html
 *
 * @example: php example1.php -p /path/to/whmcs --service 1
 * 
 */


use WHMCS\Service\Service;

// Opts
$opts = getopt("p:s:h", [ 'path:', 'service:', 'help::'], $optind);

// Default
$path = rtrim(__DIR__, '/');

foreach ($opts as $opt => $value) {
    switch ($opt) {

        // WHMCS Path
        case 'p':
        case 'path':
            $path = rtrim($value, '/');
            break;

        // Service ID
        case 's':
        case 'service':
            $serviceId = $value;
            break;

        // Help Me!
        case 'h':
        case 'help':
            echo $argv[0] . " <options>\n\n";
            echo "OPTIONS\n";
            echo "  --path          Path to WHMCS installation (default: " . __DIR__ . " )\n";
            echo "  --service       Service Id\n";
            echo "  --help          Your looking at it!\n";
            exit(1);

        default:
            echo $opt . " is an unknown option, use --help for available options\n";
            exit(1);
    }
}

// Path exist?
if (!is_dir($path)) {
    echo $path . " does not exist or is not accessible\n";
    exit(1);
}

// WHMCS init.php exist in path?
if (!is_file($path . "/init.php")) {
    echo "init.php was not found in " . $path . " are you sure thats where your installed WHMCS?\n";
    exit(1);
}

// Service option is required
if (!isset($serviceId)) {
    echo "Service Id is missing, use --help for usage information\n";
    exit(1);
}

// Init WHMCS
try {
    require_once $path . "/init.php";
} catch (\Exception $e) {
    echo "WHMCS init failed, " . $e->getMessage() . "\n";
    exit(1);
}

// Lookup Service
$service = Service::find($serviceId);
if (is_null($service)) {
    echo "No service found with ID " . $serviceId . "\n";
    exit(1);
}

echo "Service ID " . $service->id . " has a status of " . $service->domainstatus . "\n";
