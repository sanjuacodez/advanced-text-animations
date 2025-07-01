<?php
// Controls for CSS animation engine
namespace AdvancedTextAnimations\Integrations\Elementor\Widgets;

if (!defined('ABSPATH')) exit;

trait TextAnimation_Controls_CSS {
    protected function add_css_controls() {
        $this->start_controls_section(
            'css_animation_section',
            [
                'label' => __('CSS Animation', 'advanced-text-animations'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [ 'animation_engine' => 'css' ],
            ]
        );
        $this->add_control(
            'animation_type_css_character',
            [
                'label' => __('Animation Type', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'letters-slide-up',
                'options' => $this->get_animation_options('css', 'character'),
                'condition' => [
                    'animation_engine' => 'css',
                    'animation_mode' => 'character',
                ],
                'description' => '<span class="ata-anim-desc"></span>',
            ]
        );
        $this->add_control(
            'animation_type_css_words',
            [
                'label' => __('Animation Type', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'words-slide-up',
                'options' => $this->get_animation_options('css', 'words'),
                'condition' => [
                    'animation_engine' => 'css',
                    'animation_mode' => 'words',
                ],
                'description' => '<span class="ata-anim-desc"></span>',
            ]
        );
        $this->add_control(
            'animation_type_css_lines',
            [
                'label' => __('Animation Type', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'lines-slide-up',
                'options' => $this->get_animation_options('css', 'lines'),
                'condition' => [
                    'animation_engine' => 'css',
                    'animation_mode' => 'lines',
                ],
                'description' => '<span class="ata-anim-desc"></span>',
            ]
        );
        $this->add_control(
            'css_animation_speed',
            [
                'label' => __('Animation Speed (seconds)', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0.1,
                'max' => 10,
                'step' => 0.1,
                'default' => 1,
                'description' => __('Set the speed of the animation (e.g., 1 = 1s). Applies to all CSS animations.', 'advanced-text-animations'),
                'condition' => [ 'animation_engine' => 'css' ],
            ]
        );
        $this->add_control(
            'css_animation_repeat',
            [
                'label' => __('Animation Repeat', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'infinite' => __('Infinite', 'advanced-text-animations'),
                    '1' => __('Once', 'advanced-text-animations'),
                ],
                'default' => 'infinite',
                'description' => __('Should the animation repeat forever or only once?', 'advanced-text-animations'),
                'condition' => [ 'animation_engine' => 'css' ],
            ]
        );
        $this->end_controls_section();
    }
}
