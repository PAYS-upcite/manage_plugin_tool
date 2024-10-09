#!/usr/bin/env php
<?php
// Path to your config.inc.php file
$configFile = '../config.inc.php';

// Check if the config.inc.php file exists
if (!file_exists($configFile)) {
    echo "Error: The file $configFile does not exist.\n";
    exit(1);
}

// Read the content of the configuration file
$configContent = file_get_contents($configFile);

// Function to extract a configuration value
function getConfigValue($content, $key) {
    if (preg_match("/^$key\s*=\s*([^\r\n]+)/m", $content, $matches)) {
        // Remove quotes if present
        return trim($matches[1], " \t\n\r\0\x0B\"'");
    }
    return null;
}

// Extract database information
$dbHost = getConfigValue($configContent, 'host');
$dbName = getConfigValue($configContent, 'name');
$dbUser = getConfigValue($configContent, 'username');
$dbPass = getConfigValue($configContent, 'password');
$dbDriver = getConfigValue($configContent, 'driver');

// Check that all information is present
if (!$dbHost || !$dbName || !$dbUser || !$dbPass || !$dbDriver) {
    echo "Error: Unable to read database parameters from $configFile.\n";
    exit(1);
}

// Check that the driver is supported
if ($dbDriver !== 'mysql' && $dbDriver !== 'mysqli') {
    echo "Error: This script only supports MySQL databases.\n";
    exit(1);
}

// Check command-line arguments
if ($argc < 2) {
    echo "Usage: php manage_plugin.php {disable|enable <plugin_name>|list}\n";
    exit(1);
}

$action = $argv[1];

// Function to connect to the database
function dbConnect($host, $user, $pass, $name) {
    $mysqli = new mysqli($host, $user, $pass, $name);

    if ($mysqli->connect_errno) {
        echo "Failed to connect to the database: " . $mysqli->connect_error . "\n";
        exit(1);
    }

    return $mysqli;
}

// Function to disable a plugin
function disablePlugin($mysqli, $pluginName) {
    echo "Disabling plugin: $pluginName\n";

    $stmt = $mysqli->prepare("UPDATE plugin_settings SET setting_value='0' WHERE plugin_name=? AND setting_name='enabled';");
    $stmt->bind_param('s', $pluginName);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Plugin '$pluginName' has been successfully disabled.\n";
        } else {
            echo "Plugin '$pluginName' was not found or is already disabled.\n";
        }
    } else {
        echo "Failed to disable plugin '$pluginName'. Error: " . $stmt->error . "\n";
    }

    $stmt->close();
}

// Function to enable a plugin
function enablePlugin($mysqli, $pluginName) {
    echo "Enabling plugin: $pluginName\n";

    $stmt = $mysqli->prepare("UPDATE plugin_settings SET setting_value='1' WHERE plugin_name=? AND setting_name='enabled';");
    $stmt->bind_param('s', $pluginName);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Plugin '$pluginName' has been successfully enabled.\n";
        } else {
            echo "Plugin '$pluginName' was not found or is already enabled.\n";
        }
    } else {
        echo "Failed to enable plugin '$pluginName'. Error: " . $stmt->error . "\n";
    }

    $stmt->close();
}

// Function to list plugins
function listPlugins($mysqli) {
    $result = $mysqli->query("SELECT plugin_name, setting_value FROM plugin_settings WHERE setting_name='enabled';");

    if ($result) {
        echo "List of plugins:\n";
        echo "+----------------------------+---------+\n";
        echo "| Plugin Name                | Enabled |\n";
        echo "+----------------------------+---------+\n";

        while ($row = $result->fetch_assoc()) {
            $pluginName = str_pad($row['plugin_name'], 28);
            $enabled = $row['setting_value'] == '1' ? 'Yes' : 'No ';
            echo "| $pluginName |   $enabled  |\n";
        }

        echo "+----------------------------+---------+\n";
    } else {
        echo "Error retrieving plugin list: " . $mysqli->error . "\n";
    }
}

// Execute the requested action
switch ($action) {
    case 'disable':
        if ($argc < 3) {
            echo "Please provide the name of the plugin to disable.\n";
            echo "Usage: php manage_plugin.php disable <plugin_name>\n";
            exit(1);
        }
        $pluginName = $argv[2];
        $mysqli = dbConnect($dbHost, $dbUser, $dbPass, $dbName);
        disablePlugin($mysqli, $pluginName);
        $mysqli->close();
        break;

    case 'enable':
        if ($argc < 3) {
            echo "Please provide the name of the plugin to enable.\n";
            echo "Usage: php manage_plugin.php enable <plugin_name>\n";
            exit(1);
        }
        $pluginName = $argv[2];
        $mysqli = dbConnect($dbHost, $dbUser, $dbPass, $dbName);
        enablePlugin($mysqli, $pluginName);
        $mysqli->close();
        break;

    case 'list':
        $mysqli = dbConnect($dbHost, $dbUser, $dbPass, $dbName);
        listPlugins($mysqli);
        $mysqli->close();
        break;

    default:
        echo "Invalid action. Usage: php manage_plugin.php {disable|enable <plugin_name>|list}\n";
        exit(1);
}
