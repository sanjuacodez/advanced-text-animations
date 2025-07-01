<?php
// Controls for GSAP animation engine
namespace AdvancedTextAnimations\Integrations\Elementor\Widgets;

if (!defined('ABSPATH')) exit;

trait TextAnimation_Controls_GSAP {
    protected function add_gsap_controls() {
        $this->start_controls_section(
            'gsap_animation_section',
            [
                'label' => __('GSAP Animation', 'advanced-text-animations'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [ 'animation_engine' => 'gsap' ],
            ]
        );
        $this->add_control(
            'animation_type_gsap_character',
            [
                'label' => __('Animation Type', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'letters-slide-up',
                'options' => $this->get_animation_options('gsap', 'character'),
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_mode' => 'character',
                ],
                'description' => '<span class="ata-anim-desc"></span>',
            ]
        );
        $this->add_control(
            'reveal_bg_color',
            [
                'label' => __('Reveal Background Color', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#353535',
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_mode' => 'character',
                    'animation_type_gsap_character' => 'reveal-gsap',
                ],
            ]
        );
        $this->add_control(
            'animation_type_gsap_words',
            [
                'label' => __('Animation Type', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'words-slide-up',
                'options' => $this->get_animation_options('gsap', 'words'),
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_mode' => 'words',
                ],
                'description' => '<span class="ata-anim-desc"></span>',
            ]
        );
        $this->add_control(
            'reveal_bg_color_words',
            [
                'label' => __('Reveal Background Color', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#353535',
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_mode' => 'words',
                    'animation_type_gsap_words' => 'reveal-gsap',
                ],
            ]
        );
        $this->add_control(
            'animation_type_gsap_lines',
            [
                'label' => __('Animation Type', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'lines-slide-up',
                'options' => $this->get_animation_options('gsap', 'lines'),
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_mode' => 'lines',
                ],
                'description' => '<span class="ata-anim-desc"></span>',
            ]
        );
        $this->add_control(
            'reveal_bg_color_lines',
            [
                'label' => __('Reveal Background Color', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#353535',
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_mode' => 'lines',
                    'animation_type_gsap_lines' => 'reveal-gsap',
                ],
            ]
        );
        $this->add_control(
            'gsap_stagger',
            [
                'label' => __('Stagger', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'advanced-text-animations'),
                'label_off' => __('No', 'advanced-text-animations'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_type_gsap_character!' => ['rainbow', 'scramble'],
                    'animation_type_gsap_words!' => ['rainbow', 'scramble'],
                    'animation_type_gsap_lines!' => ['rainbow', 'scramble'],
                ],
                'description' => __('Enable staggered animation for characters/words/lines.', 'advanced-text-animations'),
            ]
        );
        $this->add_control(
            'gsap_yoyo',
            [
                'label' => __('Yoyo', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'advanced-text-animations'),
                'label_off' => __('No', 'advanced-text-animations'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_type_gsap_character!' => ['rainbow', 'scramble'],
                    'animation_type_gsap_words!' => ['rainbow', 'scramble'],
                    'animation_type_gsap_lines!' => ['rainbow', 'scramble'],
                ],
                'description' => __('Enable yoyo (back-and-forth) animation.', 'advanced-text-animations'),
            ]
        );
        $this->add_control(
            'gsap_repeat',
            [
                'label' => __('Repeat', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => -1,
                'min' => -1,
                'step' => 1,
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_type_gsap_character!' => ['rainbow', 'scramble', 'scroll-reveal'],
                    'animation_type_gsap_words!' => ['rainbow', 'scramble', 'scroll-reveal'],
                    'animation_type_gsap_lines!' => ['rainbow', 'scramble', 'scroll-reveal'],
                ],
                'description' => __('Number of times to repeat the animation. -1 = infinite.', 'advanced-text-animations'),
            ]
        );
        $this->add_control(
            'scroll_reveal_initial_color_character',
            [
                'label' => __('Scroll Reveal Initial Color', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#aaa',
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_mode' => 'character',
                    'animation_type_gsap_character' => 'scroll-reveal',
                ],
                'description' => __('The initial color for each character before scroll reveal animates to the text color.', 'advanced-text-animations'),
            ]
        );
        $this->add_control(
            'scroll_reveal_initial_color_words',
            [
                'label' => __('Scroll Reveal Initial Color (Words)', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#aaa',
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_mode' => 'words',
                    'animation_type_gsap_words' => 'scroll-reveal',
                ],
                'description' => __('The initial color for each word before scroll reveal animates to the text color.', 'advanced-text-animations'),
            ]
        );
        $this->end_controls_section();
    }
}
