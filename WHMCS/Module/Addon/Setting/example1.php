<?php

/**
 * WHMCS Class API example of how to get all settings for an addon module
 * 
 * @author Shaun Reitan
 * 
 * @see https://github.com/ShaunR/WHMCS-Class-API-Examples
 * 
 * @see https://classdocs.whmcs.com/7.8/WHMCS/Module/Addon/Setting.html
 *
 * @example: php example1.php -p /path/to/whmcs --module addon_module_name
 * 
 */


use WHMCS\Module\Addon\Setting;

// Opts
$opts = getopt("p:m:h", [ 'path:', 'module:', 'help::'], $optind);

// Default
$path = rtrim(__DIR__, '/');

foreach ($opts as $opt => $value) {
    switch ($opt) {

        // WHMCS Path
        case 'p':
        case 'path':
            $path = rtrim($value, '/');
            break;

        // Dry Run
        case 'm':
        case 'module':
            $module = $value;
            break;

        // Help Me!
        case 'h':
        case 'help':
            echo $argv[0] . " <options>\n\n";
            echo "OPTIONS\n";
            echo "  --path          Path to WHMCS installation (default: " . __DIR__ . " )\n";
            echo "  --module        Module name\n";
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
if (!isset($module)) {
    echo "Module name is missing, use --help for usage information\n";
    exit(1);
}

// Init WHMCS
try {
    require_once $path . "/init.php";
} catch (\Exception $e) {
    echo "WHMCS init failed, " . $e->getMessage() . "\n";
    exit(1);
}

if (isset($module)) {
    // Get module settings
    $settings = Setting::where('module', $module);
} else {
    $settings = Setting::all();
}

// Print out setting and value
foreach($settings->get() as $setting) {
    echo $setting->module . " : " . $setting->setting . " " . $setting->value . "\n";
}
