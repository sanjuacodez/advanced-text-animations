<?php
// Generic controls for TextAnimation widget (content, mode, wrapper, etc)
namespace AdvancedTextAnimations\Integrations\Elementor\Widgets;

if (!defined('ABSPATH')) exit;

trait TextAnimation_Controls_Generic {
    protected function add_generic_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'advanced-text-animations'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'text',
            [
                'label' => __('Text', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Enter your text here', 'advanced-text-animations'),
            ]
        );
        $this->add_control(
            'animation_engine',
            [
                'label' => __('Animation Engine', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'css' => [
                        'title' => __('CSS', 'advanced-text-animations'),
                        'icon' => 'eicon-code',
                    ],
                    'gsap' => [
                        'title' => __('GSAP', 'advanced-text-animations'),
                        'icon' => 'eicon-animation',
                    ],
                ],
                'default' => 'css',
                'toggle' => false,
                'description' => '<span class="ata-gsap-preview-msg" style="display:none;color:#e67e22;font-size:13px;">' . __('GSAP animation preview is not available in the editor. Please check the frontend for the live animation.', 'advanced-text-animations') . '</span>',
            ]
        );
        $this->add_control(
            'animation_mode',
            [
                'label' => __('Animation Mode', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'character' => __('Character', 'advanced-text-animations'),
                    'words' => __('Words', 'advanced-text-animations'),
                    'lines' => __('Lines', 'advanced-text-animations'),
                ],
                'default' => 'words',
            ]
        );
        $this->add_control(
            'animation_delay',
            [
                'label' => __('Animation Delay (ms)', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 0,
                'description' => __('Set a base delay for the animation. For most effects, this is the max random delay. For Wave, this is the animation duration.', 'advanced-text-animations'),
            ]
        );
        $this->add_control(
            'wrapper_tag',
            [
                'label' => __('Wrapper Tag', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'p' => 'p',
                    'span' => 'span',
                ],
                'default' => 'div',
            ]
        );
        $this->end_controls_section();
    }

    protected function add_style_controls() {
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Text Style', 'advanced-text-animations'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ata-animated-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'text_color_hover',
            [
                'label' => __('Text Hover Color', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ata-animated-text:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .ata-animated-text',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .ata-animated-text',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'text_stroke',
                'selector' => '{{WRAPPER}} .ata-animated-text',
            ]
        );
        $this->add_responsive_control(
            'text_align',
            [
                'label' => __('Text Alignment', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'advanced-text-animations'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'advanced-text-animations'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'advanced-text-animations'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justify', 'advanced-text-animations'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ata-animated-text' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'text_blend_mode',
            [
                'label' => __('Blend Mode', 'advanced-text-animations'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => __('Default', 'advanced-text-animations'),
                    'normal' => 'Normal',
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'color-burn' => 'Color Burn',
                    'hard-light' => 'Hard Light',
                    'soft-light' => 'Soft Light',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'luminosity' => 'Luminosity',
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ata-animated-text' => 'mix-blend-mode: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }
}
