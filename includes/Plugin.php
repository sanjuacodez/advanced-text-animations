<?php
namespace AdvancedTextAnimations;

/**
 * Main plugin class
 */
class Plugin {
    /**
     * Plugin instance.
     *
     * @var Plugin
     */
    private static $instance = null;

    /**
     * Animation engine (css or gsap)
     *
     * @var string
     */
    private $animation_engine = 'css';

    /**
     * Get plugin instance.
     *
     * @return Plugin
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        $this->init_hooks();
        $this->load_integrations();
    }

    /**
     * Initialize plugin hooks
     */
    private function init_hooks() {
        add_action('init', [$this, 'load_textdomain']);
        add_action('admin_init', [$this, 'init_admin']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        // Guarantee jQuery is available everywhere
        add_action('wp_enqueue_scripts', function() { wp_enqueue_script('jquery'); }, 0);
        add_action('admin_enqueue_scripts', function() { wp_enqueue_script('jquery'); }, 0);
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_debug']);
        add_action('elementor/editor/after_enqueue_scripts', function() { wp_enqueue_script('jquery'); }, 0);
    }

    public function enqueue_preview_debug() {
        wp_add_inline_script('ata-gsap-animations', "console.log('[ATA] Animation JS running in Elementor PREVIEW iframe');");
    }

    /**
     * Load text domain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'advanced-text-animations',
            false,
            dirname(ATA_BASENAME) . '/languages/'
        );
    }

    /**
     * Initialize admin
     */
    public function init_admin() {
        require_once ATA_PATH . 'includes/admin/Admin.php';
        new Admin\Admin();
    }

    /**
     * Load builder integrations
     */
    private function load_integrations() {
        // Load Elementor integration if Elementor is active
        if (did_action('elementor/loaded')) {
            require_once ATA_PATH . 'includes/integrations/elementor/Elementor.php';
            new Integrations\Elementor\Elementor();
        }

        // Add more builder integrations here in the future
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue core styles
        wp_enqueue_style(
            'ata-core',
            ATA_URL . 'assets/css/ata-core.css',
            [],
            ATA_VERSION
        );
        // Always enqueue both CSS and GSAP assets for widget-level control
        wp_enqueue_style(
            'ata-css-animations',
            ATA_URL . 'assets/css/ata-css-animations.css',
            [],
            ATA_VERSION
        );
        wp_enqueue_script(
            'gsap',
            ATA_URL . 'assets/gsap/gsap.min.js',
            [],
            ATA_VERSION,
            true
        );
        wp_enqueue_script(
            'gsap-text',
            ATA_URL . 'assets/gsap/TextPlugin.min.js',
            ['gsap'],
            ATA_VERSION,
            true
        );
        wp_enqueue_script(
            'gsap-scramble',
            ATA_URL . 'assets/gsap/ScrambleTextPlugin.min.js',
            ['gsap'],
            ATA_VERSION,
            true
        );
        wp_enqueue_script(
            'gsap-split',
            ATA_URL . 'assets/gsap/SplitText.min.js',
            ['gsap'],
            ATA_VERSION,
            true
        );
        wp_enqueue_script(
            'gsap-scrolltrigger',
            ATA_URL . 'assets/gsap/ScrollTrigger.min.js',
            ['gsap'],
            ATA_VERSION,
            true
        );
        wp_enqueue_script(
            'ata-gsap-animations',
            ATA_URL . 'assets/js/ata-gsap-animations.js',
            ['jquery', 'gsap', 'gsap-text', 'gsap-scramble', 'gsap-split', 'gsap-scrolltrigger'],
            ATA_VERSION,
            true
        );
    }
}
