=== Video Conferencing with Zoom API ===
Contributors: j__3rk
Tags: zoom video conference, video conference, zoom, zoom video conferencing, web conferencing, online meetings
Donate link: https://deepenbajracharya.com.np
Requires at least: 4.5.2
Tested up to: 4.8
Stable tag: 2.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Video Conferencing using Zoom plugin gives you the power to manage Zoom Meetings, check reports and create users from your WordPress dashboard.

== Description ==
This is a simple plugin which gives you the extensive functionality to manage zoom meetings, users, reports from WordPress. From new update 2.0 it has a more friendly and clean design with lots of bug fixes. Users can now paginate through meetings and select users to view each users meetings. However, still webinar module is not integrated.

**Few Features:**

1. Manage Meetings
2. List/Add Users
3. Clean and Friendly UI
4. Shortcodes
5. Daily and Account Reports

**Limitations**

* Webinar module not integrated

**Use shortcode**

* [zoom_api_link meeting_id="meeting_ID" class="your_class" id="your_id" title="Text of Link"] -> You can show the link of your meeting in your site anywhere using the shortcode. Replace your meeting link in place of "meeting_ID".
* Added a button in tinymce where you can choose a meeting to add into your post.

**Find a Short Documentation or Guide on how to setup: [Here](https://deepenbajracharya.com.np/zoom-conference-wp-plugin-documentation/ "Documentation")**

**Using Action Hooks**

* 1. zvc_after_create_meeting( $meeting_id, $host_id ) *
Hook this method in your functions.php file in order to run a custom script after a meeting has been created.

* 2. zvc_after_update_meeting( $meeting_id ) *
Hook this method in your functions.php file in order to run a custom script after a meeting has been updated.

* 3. zvc_after_create_user( $created_id, $created_email ) *
Hook this method in your functions.php file in order to run a custom script after a user is created.

**Contribute on github: [Here](https://github.com/techies23/video-conference-zoom "Contribute")**

**Please consider giving a [5 star thumbs up](https://wordpress.org/support/plugin/video-conferencing-with-zoom-api/reviews/#new-post "5 star thumbs up") if you found this useful.**

Any additional features, suggestions related to translations you can contact me via [email](https://deepenbajracharya.com.np/say-hello/ "Deepen Bajracharya").

== Installation ==
Search for the plugin -> add new dialog and click install, or download and extract the plugin, and copy the the Zoom plugin folder into your wp-content/plugins directory and activate.

== Frequently Asked Questions ==
= How to show Zoom Meetings on Front =

* By using shortcode like [zoom_api_link meeting_id="meeting_ID" class="your_class" id="your_id" title="Text of Link"] you can show the link of your meeting in front.

== Screenshots ==
1. Meetings Listings. Select a User in order to list meetings for that user.
2. Add a Meeting.
3. Add Meeting into a post using tinymce shortcode button.
4. Users List Screen. Flush cache to clear the cache of users.
5. Reports Section.

== Changelog ==

= 2.0.3 =
* WordPress 4.8 Compatible

= 2.0.1 =
* Added: Translation Error Fixed
* Added: French Translation
* Added: 3 new hooks see under "Using Action Hook" in description page.

= 2.0.0 =
* Added: Datatables in order to view all listings
* Added: New shortcode button in tinymce section
* Added: Bulk delete
* Added: Redesigned Zoom Meetings section where meetings can be viewed based on users.
* Added: Redesigned add meetings section with alot of bug fixes and attractive UI.
* Changed: Easy datepicker
* Changed: Removed editing of users capability. Maybe in future again ?
* Removed: Single link shortcode ( [zoom_api_video_uri] )
* Bug Fix: Reports section causing to define error when viewing available reports
* Bug Fix: Error on reload after creating a meeting
* Bug Fix: Unknown error when trying to connect with api keys ( Rare Case )
* Changed: Total codebase of the plugin.
* Fixed: Few security issues such as no nonce validations.
* Alot of Major Bug Fixes but no breaking change except for a removed shortcode

= 1.3.1 =
* Minor Bug Fixes

= 1.3.0 =
* Added Pagination to meetings list
* Hidden API token fields
* Fixed various bugs and flaws

= 1.2.4 =
* WordPress 4.6 Compatible

= 1.2.3 =
* Validation Errors Added
* Minor Bug Fixes

= 1.2.2 =
* Minor Functions Change

= 1.2.1 =
* Bug Fixes
* Major Bug fix on problem when adding users
* Removed only system users on users adding section
* Added a shortcode which will print out zoom video link. [zoom_api_video_uri]

= 1.2.0 =
* Various Bug Fixes
* Validation Errors Fixed
* Translation Ready

= 1.1.1 =
* Increased Add Meeting Refresh time interval to 5 seconds.

= 1.1 =
* Added Reports
* Minor Bug fixes and Changes

= 1.0.2 =
* Minor Changes

= 1.0.1 =
* Minor UI Changes
* Removed the unecessary dropdown in Meeting Type since only Scheduled Meetings are allowed to be created.
* Added CSS Editor in Settings Page
* Alot of Minor Bug Fixes

= 1.0.0 =
* Initial Release

== Upgrade Notice ==

= 2.0.0 =
This is a major release. Kindly request to upgrade for better performance and lots of bug fixes.

= 1.2.3 =
Validation Errors Added

= 1.2.0 =
Crucial Security Patches





