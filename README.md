# manage_plugin_tool
Utility script for displaying, deactivating, and reactivating plugins in OJS/OMP.

## Description
manage_plugin.php is a command-line utility script designed to help administrators of Open Journal Systems (OJS) or Open Monograph Press (OMP) installations manage their plugins directly from the terminal. The script allows you to:

-List all plugins and their enabled status.
-Disable a specified plugin.
-Enable a specified plugin.

This tool is particularly useful when a plugin is causing issues that prevent access to the web interface, allowing you to disable problematic plugins without needing to access the administrative dashboard.

## Installation
Download the manage_plugin.php script inside the ***/tools*** directory.

Make the script executable :

    chmod +x tools/manage_plugin.php

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

- After disabling a faulty plugin the Website Settings/Installed Plugins interface still won't load unless data cache is cleared (data cache can be cleared using Administration panel interface), find a way to do it automatically after each change in the database.
- Only Mysql and Mysqli is supported at the moment, make the script compatible with other database systems.
