<?php
/*
   Plugin Name: Basic Member
   Plugin URI: http://wordpress.org/extend/plugins/basic-member/
   Version: 0.1
   Author: TNG Consulting Inc. (Michael Milette)
   Description: Disable admin bar and dashboard and restricts 'subscriber' category posts to subscribers and 'subscriberplus' to subscriberplus.
   Text Domain: basic-member
   License: GPLv3
*/

/*
"Basic Member" Copyright (C) 2017 TNG Consulting Inc.   (www.tngconsulting.ca)

Basic Member plugin for WordPress is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Basic Member plugin for WordPress is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Basic Member. If not, see http://www.gnu.org/licenses/gpl-3.0.html
*/

/*
This plugin is based on the "WordPress Plugin Template" for WordPress.
Copyright (C) 2017 Michael Simpson  (email : michael.d.simpson@gmail.com)
http://plugin.michael-simpson.com/

WordPress Plugin Template is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

WordPress Plugin Template is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WordPress Plugin Template. If not, see http://www.gnu.org/licenses/gpl-3.0.html
*/

$BasicMember_minimalRequiredPhpVersion = '5.0';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function BasicMember_noticePhpVersionWrong() {
    global $BasicMember_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "Basic Member" requires a newer version of PHP to be running.',  'basic-member').
            '<br/>' . __('Minimal version of PHP required: ', 'basic-member') . '<strong>' . $BasicMember_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'basic-member') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function BasicMember_PhpVersionCheck() {
    global $BasicMember_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $BasicMember_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'BasicMember_noticePhpVersionWrong');
        return false;
    }
    return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function BasicMember_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('basic-member', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// Initialize i18n
add_action('plugins_loadedi','BasicMember_i18n_init');

// Run the version check.
// If it is successful, continue with initialization for this plugin
if (BasicMember_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('basic-member_init.php');
    BasicMember_init(__FILE__);
}
