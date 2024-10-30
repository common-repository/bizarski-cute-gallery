=== Bizarski Cute Gallery ===
Contributors: sparxdragon
Donate link: http://cuteplugins.com
Tags: gallery, fancybox, thumbnails, multiple, simple
Requires at least: 3.3
Tested up to: 3.4
Stable tag: 1.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple and robust gallery plugin for Wordpress. 

== Description ==

This is a simple and robust gallery plugin that displays the full-size images in a Fancybox window (jQuery). Image captions are supported. You can create and display multiple galleries inside a page or a post, using shortcodes. The thumbnails are customizable. You can also use the built-in widget to display a random image from a selected gallery, or from all galleries.

* [Docs](http://cuteplugins.com/wordpress-cute-gallery/)

= Bizarski Cute Gallery Plugin - Features =

This is a list of the main features that this plugin has. For feature suggestions, feel free to contact Bizarski. 

*Manage Galleries*

* Create separate galleries, each having a name, date, and description. 
* Add images to a gallery. They will be automatically resized and cropped.
* Add and edit image captions. 

*Display Galleries*

* Display a list of images from a gallery inside a page or a post. 
* Limit and offset the list of images for pagination.
* Decide on the number of images per row, thus zeroing the right margin of the last image.
* Display a random image from a gallery (or from all galleries) inside a widget. 

== Installation ==

1. Download, install, and activate the Bizarski Cute Gallery plugin.
2. From your Wordpress Dashboard, go to Cute Gallery > Manage Galleries/Images > New Gallery/Image > Follow the on-screen cues.
3. Go to a post/page, and enter one of the shortcodes to display one or more galleries. 

For more details, you can also have a look at the [plugin homepage](http://cuteplugins.com/wordpress-cute-gallery/).

== Screenshots ==

1. screenshot-1.jpg - Example of front end layout
2. screenshot-2.jpg - Adding images to a gallery in the back end

== Shortcodes ==

The Bizarski Cute Gallery plugin currently has only 1 shortcode. 

* *Display a list of all images from a gallery: [cutegallery_show id=1]*
* *Display 10 images after skipping 10: [cutegallery_show id=1 limit=10 offset=10]*
* *Display all images, 5 images per row: [cutegallery_show id=1 per_row=5]*

== Changelog == 

= 1.3.0 =
* Bugfix: Fixed incompatibility issues with Wordpress 3.5.
* Bugfix: Fixed issue with Datepicker script. 
* Changed: Improved filtering by gallery in the image management. 

= 1.2.0 =
* Changed: Moved file storage to Wordpress's "upload" folder. 
* NEW: Added filtering by gallery in the image management.

== Known issues ==

* Sometimes the Fancybox window appears behind the website menu. To fix this issue, go to your theme's CSS file and look for z-index rules that have a value higher than 1100. Change their value to 1099 and save the file.
* The plugin "Google Analytics for WordPress by Yoast" causes Fancybox to misbehave. 
* All versions below 1.3.0 will cause issues in the Dashboard of Wordpress 3.5 and above.
* Upgrading from version 1.0.0 to any newer version will delete all images.