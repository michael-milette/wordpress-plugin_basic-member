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

In addition to automatically disabling the admin bar and dashboard, this plugin offers 3 levels of access for your content.

Level 0: Unrestricted public access.

Simply DO NOT APPLY either of the restricted or premium categories to your pages and posts and they will remain public, as if you had not even installed the plugin.

Level 1: Restricted Access.

Just add the chosen restricted category to your page or post to restrict their access.

Level 2: Premium Access.

Similar to Restricted Access, those with a premium role (any role above the basic role of level 1) will get access to content in the selected premium access category as well as the selected restricted access category.

You will also need to create a page with a slug called /prim. This is the page that will tell logged-in users what they need to do in order to access the restricted content. If the user is not logged-in, they will be redirected to the login page.

To grant users access to restricted or premium content, simply give them a role of Subscriber, Subscriber+ or the role you selected in the settings page.

== Installation ==

Install just like any other WordPress plugin. See:
http://www.wpbeginner.com/beginners-guide/step-by-step-guide-to-install-a-wordpress-plugin-for-beginners/

The installation process will create a new role called Subscriber+. This will be a clone of the Subscriber role at the time of installation. If you make any changes to the Subscriber role, you will also need to make the same changes to the Subscriber+ role.

Once the plugin is installed, go to the Plugin > Basic Member settings page and choose which page/post categories will be associated with the Restricted and and/or Premium access roles.

== Installation ==

Install just like any other WordPress plugin.

Note: The Subscriber+ role will automatically be removed at the same time but only of there are no users with that role.

== Frequently Asked Questions ==

Question: Why do I only see a category called "Uncategorized"?
Answer: You must create categories for your restricted and premium restricted content first and then return to the settings page to select the right category.
Tip: You can set the default category in WordPress by going to Dashboard > Settings > Writing.

Question: I am an Admnistrator, Author, Editor or Contributor. Why can I see all of the content?
Answer: This is by design. Restricted content only applies to subscriber and subscriber+ roles.

Question: Where do I assign a Subscriber+ role?
Answer: You need to edit user profiles.

Question: I can't see the Subscriber+ role. How do I create it?
Answer: Just deactivate the plugin. The Subscriber+ role will be re-created when you then re-activate the plugin.

Question: My restricted pages used to be restricted. Why are they not anymore?
Answer: This will happen if you rename your categories. Update the plugin settings to select the new category names.

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
