<?php

/**
 * WHMCS class API example of how to retreive a WHMCS config setting
 * 
 * @author Shaun Reitan
 * 
 * @see https://github.com/ShaunR/WHMCS-Class-API-Examples
 * 
 * @see https://classdocs.whmcs.com/7.8/WHMCS/Config/Setting.html
 *
 * @example: php example1.php ---path /path/to/whmcs --setting DateFormat
 * 
 */


use WHMCS\Config\Setting;

// Opts
$opts = getopt("p:s:h", [ 'path:', 'setting:', 'help::'], $optind);

// Default
$path = rtrim(__DIR__, '/');

foreach ($opts as $opt => $value) {
    switch ($opt) {

        // WHMCS Path
        case 'p':
        case 'path':
            $path = rtrim($value, '/');
            break;

        // Setting
        case 's':
        case 'setting':
            $setting = $value;
            break;

        // Help Me!
        case 'h':
        case 'help':
            echo $argv[0] . " <options>\n\n";
            echo "OPTIONS\n";
            echo "  --path          Path to WHMCS installation (default: " . __DIR__ . " )\n";
            echo "  --setting       Setting name\n";
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

// Module name option is required
if (!isset($setting)) {
    echo "Setting name is missing, use --help for usage information\n";
    exit(1);
}

// Init WHMCS
try {
    require_once $path . "/init.php";
} catch (\Exception $e) {
    echo "WHMCS init failed, " . $e->getMessage() . "\n";
    exit(1);
}

// Get config setting
$value = Setting::getValue($setting);
if (is_null($value)) {
    echo "No value found for setting " . $setting . "\n";
    exit(1);
}

echo "Setting " . $setting . " has a value of " . $value . "\n";
