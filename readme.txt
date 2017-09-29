=== Basic Member ===
Contributors: TNG Consulting Inc. (Michael Milette)
Donate link:
Tags:
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires at least: 3.1
Tested up to: 4.8.2
Stable tag: 0.1

Disable admin bar and dashboard and restricts 'subscriber' category posts to subscribers.

== Description ==

This is a very easy to use basic membership plugin. 

In addition to automatically disabling the admin bar and dashboard for subscribers, this plugin offers 3 levels of access for your content.

* Level 0: Unrestricted public access - all content that is not included in one of the special categories.
* Level 1: Subscriber restricted access. Just specify the category to be associated with the subscriber role to enable access to pages and posts in that category.
* Level 2: Subscriber+ restricted access. Just specify the category to be associated with the subscriber role to enable access to pages and posts in that category. Note that users with this role will also be able to access content in the associated subscriber category.

When trying to access restricted content, you will control whether they are sent to the login page (only if logged out), an information page of your choice or see an access denied type message.

To grant users access to restricted or premium content, simply give them a role of Subscriber, Subscriber+ to your registered users.

== Installation ==

Install just like any other WordPress plugin. See:
http://www.wpbeginner.com/beginners-guide/step-by-step-guide-to-install-a-wordpress-plugin-for-beginners/

The installation process will create a new role called Subscriber+. This will be a clone of the Subscriber role at the time of installation. If you make any changes to the Subscriber role, you will also need to make the same changes to the Subscriber+ role.

Once the plugin is installed, go to the Plugin > Basic Member settings page and choose which page/post categories will be associated with the Subscriber and Subscriber+ roles.

== Installation ==

Install just like any other WordPress plugin.

Note: The Subscriber+ role will automatically be removed at the same time but only of there are no users with that role.

== Frequently Asked Questions ==

Question: Why do I only see a category called "Uncategorized"?
Answer: You must create categories for your restricted and premium restricted content first and then return to the settings page to select the right category.
Tip: You can set the default category in WordPress by going to Dashboard > Settings > Writing.

Question: I am an Admnistrator, Author, Editor or Contributor. Why can I see all of the restricted (subscriber and subscriber+) content?
Answer: This is by design. Restricted content only applies to users with subscriber and subscriber+ roles.

Question: Where do I assign a Subscriber+ role?
Answer: In the user's profiles.

Question: I can't see the Subscriber+ role. How do I create it?
Answer: Just deactivate the plugin. The Subscriber+ role is created when you activate the plugin.

Question: My restricted pages are no longer restricted. Why what happened?
Answer: This will happen if you rename your categories. Select the new category names in the plugin settings.

Question: Where do I set the category for pages?
Answer: This feature is only available when the plugin is enabled.

== Copyright ==

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

This plugin is based on the "WordPress Plugin Template" for WordPress.
Copyright (C) 2017 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

== Screenshots ==


== Changelog ==

= 0.1 =
- Initial Revision
