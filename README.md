# manage_plugin_tool
Utility script for displaying, deactivating, and reactivating plugins in OJS/OMP.

Developer: Erwan Bourrand

## Description
manage_plugin.php is a command-line utility script designed to help administrators of Open Journal Systems (OJS) or Open Monograph Press (OMP) installations manage their plugins directly from the terminal. The script allows you to:

-List all plugins and their enabled status.

-Disable a specified plugin.

-Enable a specified plugin.

This tool is particularly useful when a plugin is causing issues that prevent access to the web interface, allowing you to disable problematic plugins without needing to access the administrative dashboard.

## Installation
Download the manage_plugin.php script inside the ***/tools*** directory, all the commands below must be executed from inside this directory.

Make the script executable :

    chmod +x manage_plugin.php

## Usage
### 1. List Plugins

To display a list of all plugins by name and their enabled status:

    php manage_plugin.php list

### 2. Disable a Plugin

To disable a specific plugin:

    php manage_plugin.php disable <plugin_name>

Replace <plugin_name> with the exact name of the plugin you wish to disable.

### 3. Enable a Plugin

To enable a specific plugin:

    php manage_plugin.php enable <plugin_name>

Replace <plugin_name> with the exact name of the plugin you wish to enable.

## TO DO

#### - Automatically Clear Cache After Plugin Changes:
After disabling a faulty plugin, the ***Website Settings/Installed*** Plugins interface might still not load correctly unless the cache is cleared. The cache can currently be cleared using the Administration panel interface. Implement a way to automatically clear the cache after each change made in the database.

#### - Support for Other Database Systems:
Currently, the script only supports MySQL and MySQLi. Extend compatibility to support other database systems.

#### - Handling Critical Plugin Errors:
The primary purpose of this tool is to disable a faulty plugin that causes the infamous "blank page" issue across the website. However, there may be cases where simply disabling the plugin is not enough—for example, when the plugin code contains critical syntax errors, such as a missing semicolon. Even if the plugin is disabled and the cache is cleared, the ***Website Settings/Installed Plugins*** list may still fail to load because the system attempts to access each plugin’s main class (whether enabled or not) to retrieve their names and descriptions. In such cases, the ultimate solution may be to manually delete the faulty plugin directory and remove the corresponding entries from the ***plugin_settings*** and ***versions*** tables.
