Geo Mashup Custom Extension
==========================
#### WordPress plugin

Extends [GeoMashup](https://github.com/cyberhobo/wp-geo-mashup "GeoMashup on GitHub") WordPress plugin for better handling of markers (for both individual post types and post type archives) with the ability to load markers from a post taxonomy term, customisation of cluster markers, better support of Google Maps API V3.

This WordPress plugin is an expansion of the [original GeoMashup Custom plugin](https://code.google.com/p/wordpress-geo-mashup/wiki/Documentation#APIs_For_Developers "GeoMashup API") kindly provided by GeoMashup plugin author, [@cyberhobo](https://github.com/cyberhobo "Dylan Kuhn"), as a framework to extend and customise GeoMashup. 

*DISCLAIMER:* This is still work in progress, consider the plugin as a guideline on how to use and adapt the original GeoMashup Custom for your own theme or plugin development


## How to install

### Requirements
[Download](http://wordpress.org/plugins/geo-mashup/ "download GeoMashup from WordPress.org"), install and activate GeoMashup plugin first, before activating GeoMashup custom or you might bump into errors. The GeoMashup Custom plugin has been tested with GeoMashup up to 1.6.2 and might not work with plugin versions below 1.5.x. For some functions (to manage term meta) you will need [Advanced Custom Fields plugin](http://www.advancedcustomfields.com/ "ACF").

### Compatibility
This Geo Mashup Custom plugin has been tested up with WordPress 3.5.x - howerver, as long as GeoMashup works, this extension should work as well. It has been tested to be working well in environments along with other popular plugins - namely bbPress, BuddyPress, WooCommerce, Gravity Forms and many others.    

This extension is meant to work with GoogleMaps V3. GeoMashup plugin supports Bing Maps and other maps, however this extension has not been tested with these and might not work.

### Installation
[Download Geo Mashup Custom zip](https://github.com/kuching/geo-mashup-custom/archive/master.zip "download this repo") and unpack into your WordPress installation plugins directory (normally `www.yourdomain.com/wp-content/plugins/`). Make sure the plugin directory is named as `'geo-mashup-custom'` (as GitHub would probably rename it (`geo-mashup-custom-master` or other branch name). Activate the plugin from your WordPress dashboard plugin management page. 

## Usage

### Custom Markers
After installation is complete and the plugin has been activated, from your WordPress dashboard, look in the `Settings` menu. You will see a new menu item `GeoMashup Custom`. Click on it and you will be brought to the GeoMashup Custom settings page.


##### Markers set dynamically by post type
You can specify a marker icon for each post type - posts belonging to each type will be shown on GeoMashup maps with the marker you will have pointed to. The plugin will detected a list of your post types and the taxonomies associated with each. Select the post types which you want to print on your maps and for each one specify a URL pointing to an image marker. 
 

##### Markers set dynamically by taxonomy terms
For each post type selected, you can also select a related taxonomy. If you choose to do so, what the plugin will do is, when a map is being queried, to examine the post terms for the specified taxonomy. The plugin will look for a term meta that should contain a URL to the marker you want to use to represent the post on a GeoMashup map.

It's up to you how you want to manage or add term metas. WordPress does not do it natively via dashboard but you can add metadata to almost any object: [http://codex.wordpress.org/Metadata_API](http://codex.wordpress.org/Metadata_API). Advanced Customs Fields offers an excellent tool to manage term meta.

In GeoMashup Custom you only need to specify the name of the the term meta to look for. It's advisable to use only one term for the specified taxonomy; in case a post has two or more terms assigned, each containing a URL to a marker icon image, there might be inconsistencies.

##### Markers fallback  
If no marker is found for the specified term meta in the post term associated to the post to be pinned on a map, GeoMashup Custom will fallback on the parent term. If there's no term there either, it will further fallback on the marker specified globally for the post type.
There's an experimental fallback to look also for `.png` images in the `/markers` directory of `geo-mashup-custom` plugin directory. Images names should match corresponding post term slugs.

##### Static markers  
The rest of the options in GeoMashup Custom will allow you to specify URLs for other non-dynamic markers, such as markers for posts sharing the same coordinates, cluster markers (three levels), marker for a currently viewed post (to override any default one set using terms meta). The rest of the settings allow you to adjust the position and size of the markers as they appear on the map.

## Template files
With GeoMashup Custom, you can further customise the appearance of the maps queried with GeoMashup using theme template files.

##### Custom Info Window
You can customise the markup and style of the info window (the 'balloon' that pops out when a marker is clicked on the map) by copying the `info-window.php` file into your active WordPress theme root. This will override the default one. 


##### Custom Map Frame
You can customise the markup and style of the map frame similarly by placing a copy of `map-frame.php` into your active theme root. This will override the default one.

##### Other templates
Please refer to [GeoMashup documentation on how to customise other content](https://code.google.com/p/wordpress-geo-mashup/wiki/Documentation#Customize_the_info_window_and_other_content), it has template overrides too. 

## Displaying maps

Please refer to [GeoMashup documentation](https://code.google.com/p/wordpress-geo-mashup/wiki/Documentation) on how to show maps in your theme. This plugin extensions focuses on extending and utilising of GeoMashup for theme development and assumes you'll be using `echo GeoMashup::map( $args )` function method and query variables or options as described in the [GeoMashup tag reference docs](https://code.google.com/p/wordpress-geo-mashup/wiki/TagReference "GeoMashup tag reference").

## ToDo

* At the moment this plugin relies on an hardcoded Advanced Custom Field term meta to get the markers data which makes it not very usable
* Basic templates for the info-window and the map-frame look probably awful and have a lot of garbage and need cleanup (they were quickly recycled from an actual installation)
