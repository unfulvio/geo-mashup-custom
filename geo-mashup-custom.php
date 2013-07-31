<?php /*
Plugin Name: GeoMashup Custom
Plugin URI: https://github.com/kuching/geo-mashup-custom
Description: Extends Geo Mashup plugin with better handling of markers and support for polylines.
Version: 1.0
Authors: @kuching, @alexandre67fr
Author URI: https://github.com/kuching/geo-mashup-custom
Minimum WordPress Version Required: 3.5
*/

// Geo Mashup Custom settings and options
include_once dirname(__FILE__) . '/options.php';
 
if ( !class_exists( 'GeoMashupCustom' ) ) {
    
/**
 * The Geo Mashup Custom class
 * 
 * Provides a home for customization files for the Geo Mashup plugin so they aren't deleted during Geo Mashup upgrades. When this plugin is active, Geo Mashup will use these files and you can <a href="?geo_mashup_custom_list=1">list current custom files</a> here. Subfolders are okay for your own use, but won't be listed.
 * 
 * Copyright (c) 2005-2009 Dylan Kuhn
 * @link http://www.cyberhobo.net/ 
 */    
class GeoMashupCustom {
	var $files = array();
	var $found_files;
	var $dir_path;
	var $url_path;
	var $basename;
	var $warnings = '';

	/**
	 * PHP4 Constructor
	 */
	function GeoMashupCustom() {

		// Initialize members
		$this->dir_path = dirname( __FILE__ );
		$this->basename = plugin_basename( __FILE__ );
		$dir_name = substr( $this->basename, 0, strpos( $this->basename, '/' ) );
		$this->url_path = trailingslashit( WP_PLUGIN_URL ) . $dir_name;
		load_plugin_textdomain( 'GeoMashupCustom', 'content/plugins/'.$dir_name, $dir_name );
		
		// Inventory custom files
		if ( $dir_handle = @ opendir( $this->dir_path ) ) {
			$self_file = basename( __FILE__ );
			while ( ( $custom_file = readdir( $dir_handle ) ) !== false ) {
				if ( $self_file != $custom_file && !strpos( $custom_file, '-sample' ) && !is_dir( $custom_file ) ) {
					$this->files[$custom_file] = trailingslashit( $this->url_path ) . $custom_file;
				}
			}
		}

		// Scan Geo Mashup after it has been loaded
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		// Output messages
		add_action( 'after_plugin_row_' . $this->basename, array( $this, 'after_plugin_row' ), 10, 2 );
	}

	/**
	 * Once all plugins are loaded, we can examine Geo Mashup.
	 */
	function plugins_loaded() {
		if ( defined( 'GEO_MASHUP_DIR_PATH' ) ) {
			// Check version
			if ( GEO_MASHUP_VERSION <= '1.2.4' ) {
				$this->warnings .= __( 'Custom files can be stored safely in this plugin folder, but will only be used by versions of Geo Mashup later than 1.2.4.', 'GeoMashupCustom' ) .
					'<br/>';
			}
			$this->found_files = get_option( 'geo_mashup_custom_found_files' );
			if ( empty( $this->found_files ) ) {
				$this->found_files = $this->rescue_files();
				update_option( 'geo_mashup_custom_found_files', $this->found_files );
			}
		}
	}

	/**
	 * Rescue known custom files from the Geo Mashup folder.
	 */
	function rescue_files() {
		$results = array( 'ok' => array(), 'failed' => array() );
		$check_files = array( 'custom.js', 'map-style.css', 'info-window.php', 'full-post.php', 'user.php', 'comment.php' );
		foreach( $check_files as $file ) {
			if ( !isset( $this->files[$file] ) ) {
				$endangered_file = trailingslashit( GEO_MASHUP_DIR_PATH ) . $file;
				if ( is_readable( $endangered_file ) ) {
					$safe_file = trailingslashit( $this->dir_path ) . $file; 
					if ( copy( $endangered_file, $safe_file ) ) {
						$this->file[$file] = trailingslashit( $this->url_path ) . $file;
						array_push( $results['ok'], $file );
					} else {
						array_push( $results['failed'], $file );
					}
				}
			}
		}
		return $results;
	}

	/**
	 * Display any messages after the plugin row.
	 * 
	 * @param object $plugin_data Plugin data.
	 * @param string $context 'active', 'inactive', etc.
	 */
	function after_plugin_row( $plugin_data = null, $context = '' ) {
		if ( !empty( $_GET['geo_mashup_custom_list'] ) ) {
			echo '<tr><td colspan="5">' . __( 'Current custom files: ', 'GeoMashupCustom') .
				implode( ', ', array_keys( $this->files ) ) . '</td></tr>';
		}
		if ( !empty( $this->warnings ) ) {
			echo '<tr><td colspan="5">' . $this->warnings . '</td></tr>';
		}
	}

	/**
	 * Get the URL of a custom file if it exists.
	 *
	 * @param string $file The custom file to check for.
	 * @return URL or false if the file is not found.
	 */
	function file_url( $file ) {
		$url = false;
		if ( isset( $this->files[$file] ) ) {
			$url = $this->files[$file];
		}
		return $url;
	}

} // end Geo Mashup Custom class

// Instantiate
$geo_mashup_custom = new GeoMashupCustom();

include dirname(__FILE__) . '/maps.php';

} // endif Geo Mashup Custom class exists