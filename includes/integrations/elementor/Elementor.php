<?php
namespace AdvancedTextAnimations\Integrations\Elementor;

use Elementor\Plugin;

/**
 * Elementor integration class
 */
class Elementor {
    /**
     * Constructor.
     */
    public function __construct() {
        add_action('elementor/elements/categories_registered', [$this, 'add_elementor_category']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_frontend_scripts']);
        add_action('elementor/frontend/after_register_styles', [$this, 'register_frontend_styles']);
    }

    /**
     * Add Elementor widget category
     *
     * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
     */
    public function add_elementor_category($elements_manager) {
        $elements_manager->add_category(
            'advanced-text-animations',
            [
                'title' => __('Advanced Text Animations', 'advanced-text-animations'),
                'icon' => 'eicon-animation',
            ]
        );
    }

    /**
     * Register Elementor widgets
     */
    public function register_widgets() {
        require_once __DIR__ . '/widgets/TextAnimation.php';
        Plugin::instance()->widgets_manager->register(new Widgets\TextAnimation());
    }

    /**
     * Register frontend scripts
     */
    public function register_frontend_scripts() {
        // Scripts are registered in the main Plugin class
    }

    /**
     * Register frontend styles
     */
    public function register_frontend_styles() {
        // Styles are registered in the main Plugin class
    }
}
