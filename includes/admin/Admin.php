<?php
namespace AdvancedTextAnimations\Admin;

/**
 * Admin class
 */
class Admin {
    /**
     * Constructor.
     */    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if ('settings_page_advanced-text-animations' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'ata-admin',
            ATA_URL . 'assets/css/ata-admin.css',
            [],
            ATA_VERSION
        );

        wp_enqueue_script(
            'ata-admin',
            ATA_URL . 'assets/js/ata-admin.js',
            ['jquery'],
            ATA_VERSION,
            true
        );
    }

    /**
     * Add admin menu
     */
    public function add_menu() {
        add_options_page(
            __('Advanced Text Animations', 'advanced-text-animations'),
            __('Text Animations', 'advanced-text-animations'),
            'manage_options',
            'advanced-text-animations',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('ata_settings', 'ata_animation_engine');

        add_settings_section(
            'ata_main_section',
            __('General Settings', 'advanced-text-animations'),
            [$this, 'render_section_info'],
            'advanced-text-animations'
        );

        add_settings_field(
            'ata_animation_engine',
            __('Animation Engine', 'advanced-text-animations'),
            [$this, 'render_engine_field'],
            'advanced-text-animations',
            'ata_main_section'
        );
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('ata_settings');
                do_settings_sections('advanced-text-animations');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render section info
     */
    public function render_section_info() {
        echo esc_html__('Configure the animation engine and other settings.', 'advanced-text-animations');
    }

    /**
     * Render engine selection field
     */    public function render_engine_field() {
        $engine = get_option('ata_animation_engine', 'css');
        ?>
        <div class="ata-engine-selector">
            <div class="ata-engine-toggle">
                <input type="radio" id="ata_engine_css" name="ata_animation_engine" value="css" <?php checked($engine, 'css'); ?>>
                <label for="ata_engine_css" class="ata-engine-button">
                    <span class="dashicons dashicons-editor-code"></span>
                    <?php esc_html_e('CSS', 'advanced-text-animations'); ?>
                </label>

                <input type="radio" id="ata_engine_gsap" name="ata_animation_engine" value="gsap" <?php checked($engine, 'gsap'); ?>>
                <label for="ata_engine_gsap" class="ata-engine-button">
                    <span class="dashicons dashicons-performance"></span>
                    <?php esc_html_e('GSAP', 'advanced-text-animations'); ?>
                </label>
            </div>

            <div class="ata-engine-info ata-engine-css <?php echo $engine === 'css' ? 'active' : ''; ?>">
                <p><?php esc_html_e('CSS animations are lightweight and perfect for simple animations. They work without any external dependencies.', 'advanced-text-animations'); ?></p>
                <ul>
                    <li><?php esc_html_e('• Words Slide Up/Down', 'advanced-text-animations'); ?></li>
                    <li><?php esc_html_e('• Letters Slide Up/Down', 'advanced-text-animations'); ?></li>
                    <li><?php esc_html_e('• Lines Slide Up/Down', 'advanced-text-animations'); ?></li>
                    <li><?php esc_html_e('• Words/Letters/Lines Fade In', 'advanced-text-animations'); ?></li>
                    <li><?php esc_html_e('• Words/Letters/Lines Slide Left/Right', 'advanced-text-animations'); ?></li>
                </ul>
            </div>

            <div class="ata-engine-info ata-engine-gsap <?php echo $engine === 'gsap' ? 'active' : ''; ?>">
                <p><?php esc_html_e('GSAP provides more complex and smooth animations with better browser support. Recommended for advanced animations.', 'advanced-text-animations'); ?></p>
                <ul>
                    <li><?php esc_html_e('• All CSS animations with smoother transitions', 'advanced-text-animations'); ?></li>
                    <li><?php esc_html_e('• Advanced Text Split animations', 'advanced-text-animations'); ?></li>
                    <li><?php esc_html_e('• Scroll-triggered animations', 'advanced-text-animations'); ?></li>
                    <li><?php esc_html_e('• Complex text transformations', 'advanced-text-animations'); ?></li>
                    <li><?php esc_html_e('• Timeline-based sequences', 'advanced-text-animations'); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }
}
