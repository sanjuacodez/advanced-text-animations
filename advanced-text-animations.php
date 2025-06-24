<?php
/**
 * Plugin Name: Advanced Text Animations
 * Plugin URI: https://acowebs.com
 * Description: A modular text animation plugin supporting elementor page builder(in future multiple page builders) with CSS and GSAP animations.
 * Version: 1.0.0
 * Author: Sanju Shankar
 * Author URI: https://sanjayshankar.me
 * Text Domain: advanced-text-animations
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package AdvancedTextAnimations
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define plugin constants
define('ATA_VERSION', '1.0.0');
define('ATA_FILE', __FILE__);
define('ATA_PATH', plugin_dir_path(ATA_FILE));
define('ATA_URL', plugin_dir_url(ATA_FILE));
define('ATA_BASENAME', plugin_basename(ATA_FILE));

// Autoloader for plugin classes
spl_autoload_register(function ($class) {
    // Plugin namespace prefix
    $prefix = 'AdvancedTextAnimations\\';
    $base_dir = ATA_PATH . 'includes/';

    // Check if the class uses the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace namespace separators with directory separators
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Load the file if it exists
    if (file_exists($file)) {
        require $file;
    }
});

// Initialize the plugin
if (!class_exists('AdvancedTextAnimations\\Plugin')) {
    require_once ATA_PATH . 'includes/Plugin.php';
}

/**
 * Returns the main instance of the plugin.
 *
 * @return \AdvancedTextAnimations\Plugin
 */
function ATA() {
    return \AdvancedTextAnimations\Plugin::instance();
}

// Initialize the plugin
add_action('plugins_loaded', 'ATA');
