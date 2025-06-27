<?php
namespace AdvancedTextAnimations\Integrations\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Align;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Text Animation Widget
 */
class TextAnimation extends Widget_Base {
    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'ata-text-animation';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Text Animation', 'advanced-text-animations');
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-animation-text';
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['advanced-text-animations'];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'advanced-text-animations'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'text',
            [
                'label' => __('Text', 'advanced-text-animations'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Enter your text here', 'advanced-text-animations'),
            ]
        );

        $this->add_control(
            'animation_engine',
            [
                'label' => __('Animation Engine', 'advanced-text-animations'),
                'type' => Controls_Manager::CHOOSE,
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
                'type' => Controls_Manager::SELECT,
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
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 0,
                'description' => __('Set a base delay for the animation. For most effects, this is the max random delay. For Wave, this is the animation duration.', 'advanced-text-animations'),
            ]
        );

        // CSS Animations
        // For each animation_type control, add a static description placeholder
        $this->add_control(
            'animation_type_css_character',
            [
                'label' => __('Animation Type', 'advanced-text-animations'),
                'type' => Controls_Manager::SELECT,
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
                'type' => Controls_Manager::SELECT,
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
                'type' => Controls_Manager::SELECT,
                'default' => 'lines-slide-up',
                'options' => $this->get_animation_options('css', 'lines'),
                'condition' => [
                    'animation_engine' => 'css',
                    'animation_mode' => 'lines',
                ],
                'description' => '<span class="ata-anim-desc"></span>',
            ]
        );

        // GSAP Animations
        $this->add_control(
            'animation_type_gsap_character',
            [
                'label' => __('Animation Type', 'advanced-text-animations'),
                'type' => Controls_Manager::SELECT,
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
                'type' => Controls_Manager::COLOR,
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
                'type' => Controls_Manager::SELECT,
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
                'type' => Controls_Manager::COLOR,
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
                'type' => Controls_Manager::SELECT,
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
                'type' => Controls_Manager::COLOR,
                'default' => '#353535',
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_mode' => 'lines',
                    'animation_type_gsap_lines' => 'reveal-gsap',
                ],
            ]
        );

        $this->add_control(
            'wrapper_tag',
            [
                'label' => __('Wrapper Tag', 'advanced-text-animations'),
                'type' => Controls_Manager::SELECT,
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

        // GSAP Animation Settings (stagger, yoyo, repeat)
        $this->add_control(
            'gsap_stagger',
            [
                'label' => __('Stagger', 'advanced-text-animations'),
                'type' => Controls_Manager::SWITCHER,
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
                'type' => Controls_Manager::SWITCHER,
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
                'type' => Controls_Manager::NUMBER,
                'default' => -1,
                'min' => -1,
                'step' => 1,
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_type_gsap_character!' => ['rainbow', 'scramble'],
                    'animation_type_gsap_words!' => ['rainbow', 'scramble'],
                    'animation_type_gsap_lines!' => ['rainbow', 'scramble'],
                ],
                'description' => __('Number of times to repeat the animation. -1 = infinite.', 'advanced-text-animations'),
            ]
        );

        // Add a hidden field with animation descriptions for JS
        $this->add_control(
            'animation_type_desc_map',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => json_encode([
                    'bounce-text' => __('Each character/word bounces up and down, creating a playful effect.', 'advanced-text-animations'),
                    'fade-text' => __('Characters/words/lines fade in and out, creating a blinking effect.', 'advanced-text-animations'),
                    'wave-text' => __('Characters/words move up and down in a wave pattern, each with a slight delay.', 'advanced-text-animations'),
                    'rotate-text' => __('Each character spins 360 degrees on its own axis.', 'advanced-text-animations'),
                    'pulse-text' => __('Characters/words/lines gently grow and shrink, creating a pulsing effect.', 'advanced-text-animations'),
                    'glitch-text' => __('Characters/words/lines appear to glitch with shifting colored shadows.', 'advanced-text-animations'),
                    'rainbow-text' => __('Each character/word/line cycles through a rainbow of colors.', 'advanced-text-animations'),
                    'shake-text' => __('Characters/words shake side to side, as if vibrating.', 'advanced-text-animations'),
                    'slide-text' => __('Characters/words/lines slide in and out from the left, fading as they go.', 'advanced-text-animations'),
                    'blink-text' => __('Characters/words/lines blink on and off, like a cursor.', 'advanced-text-animations'),
                    'reveal-gsap' => __('Text reveal effect inspired by GSAP CodePen demo.', 'advanced-text-animations'),
                    'running-text' => __('Running text/marquee effect with dual color, inspired by GSAP demo.', 'advanced-text-animations'),
                    'scroll-reveal' => __('Scroll-triggered text reveal with background color, inspired by GSAP demo.', 'advanced-text-animations'),
                ]),
            ]
        );

        $this->end_controls_section();

        // --- STYLE TAB ---
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Text', 'advanced-text-animations'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .ata-animated-text, {{WRAPPER}} .ata-animated-text span, {{WRAPPER}} .ata-animated-text div',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .ata-animated-text, {{WRAPPER}} .ata-animated-text span, {{WRAPPER}} .ata-animated-text div',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'text_stroke',
                'selector' => '{{WRAPPER}} .ata-animated-text, {{WRAPPER}} .ata-animated-text span, {{WRAPPER}} .ata-animated-text div',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'advanced-text-animations'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Only apply to the wrapper, not inner spans/divs
                    '{{WRAPPER}} .ata-animated-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        // Add Text Initial Color (Scroll Reveal) immediately after Text Color
        $this->add_control(
            'scroll_reveal_initial_color',
            [
                'label' => __('Text Initial Color (Scroll Reveal)', 'advanced-text-animations'),
                'type' => Controls_Manager::COLOR,
                'default' => '#aaa',
                // Show if any GSAP animation type is scroll-reveal, regardless of mode
                'condition' => [
                    'animation_engine' => 'gsap',
                    // Any of the three animation type controls set to scroll-reveal
                    [ 'animation_type_gsap_character' => 'scroll-reveal' ],
                    [ 'animation_type_gsap_words' => 'scroll-reveal' ],
                    [ 'animation_type_gsap_lines' => 'scroll-reveal' ],
                ],
            ]
        );
        // Show for GSAP scroll-reveal in character mode
        $this->add_control(
            'scroll_reveal_initial_color_character',
            [
                'label' => __('Text Initial Color (Scroll Reveal)', 'advanced-text-animations'),
                'type' => Controls_Manager::COLOR,
                'default' => '#aaa',
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_mode' => 'character',
                    'animation_type_gsap_character' => 'scroll-reveal',
                ],
            ]
        );
        // Show for GSAP scroll-reveal in words mode
        $this->add_control(
            'scroll_reveal_initial_color_words',
            [
                'label' => __('Text Initial Color (Scroll Reveal)', 'advanced-text-animations'),
                'type' => Controls_Manager::COLOR,
                'default' => '#aaa',
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_mode' => 'words',
                    'animation_type_gsap_words' => 'scroll-reveal',
                ],
            ]
        );
        // Show for GSAP scroll-reveal in lines mode
        $this->add_control(
            'scroll_reveal_initial_color_lines',
            [
                'label' => __('Text Initial Color (Scroll Reveal)', 'advanced-text-animations'),
                'type' => Controls_Manager::COLOR,
                'default' => '#aaa',
                'condition' => [
                    'animation_engine' => 'gsap',
                    'animation_mode' => 'lines',
                    'animation_type_gsap_lines' => 'scroll-reveal',
                ],
            ]
        );

        $this->add_control(
            'text_align',
            [
                'label' => __('Alignment', 'advanced-text-animations'),
                'type' => Controls_Manager::CHOOSE,
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
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ata-animated-text' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get animation options based on engine and mode
     *
     * @param string $engine
     * @param string $mode
     * @return array
     */
    private function get_animation_options($engine = 'css', $mode = 'words') {
        if ($engine === 'css') {
            $options = [
                'character' => [
                    'bounce-text' => __('Infinite Bounce', 'advanced-text-animations'),
                    'fade-text' => __('Fade In/Out', 'advanced-text-animations'),
                    'wave-text' => __('Wave Effect', 'advanced-text-animations'),
                    'rotate-text' => __('Rotating Characters', 'advanced-text-animations'),
                    'pulse-text' => __('Pulse', 'advanced-text-animations'),
                    'glitch-text' => __('Glitch Effect', 'advanced-text-animations'),
                    'rainbow-text' => __('Rainbow Color Cycle', 'advanced-text-animations'),
                    'shake-text' => __('Shake', 'advanced-text-animations'),
                    'slide-text' => __('Slide In/Out', 'advanced-text-animations'),
                    'blink-text' => __('Blinking', 'advanced-text-animations'),
                ],
                'words' => [
                    'bounce-text' => __('Infinite Bounce', 'advanced-text-animations'),
                    'fade-text' => __('Fade In/Out', 'advanced-text-animations'),
                    'wave-text' => __('Wave Effect', 'advanced-text-animations'),
                    'pulse-text' => __('Pulse', 'advanced-text-animations'),
                    'glitch-text' => __('Glitch Effect', 'advanced-text-animations'),
                    'rainbow-text' => __('Rainbow Color Cycle', 'advanced-text-animations'),
                    'shake-text' => __('Shake', 'advanced-text-animations'),
                    'slide-text' => __('Slide In/Out', 'advanced-text-animations'),
                    'blink-text' => __('Blinking', 'advanced-text-animations'),
                ],
                'lines' => [
                    'fade-text' => __('Fade In/Out', 'advanced-text-animations'),
                    'pulse-text' => __('Pulse', 'advanced-text-animations'),
                    'glitch-text' => __('Glitch Effect', 'advanced-text-animations'),
                    'rainbow-text' => __('Rainbow Color Cycle', 'advanced-text-animations'),
                    'slide-text' => __('Slide In/Out', 'advanced-text-animations'),
                    'blink-text' => __('Blinking', 'advanced-text-animations'),
                ],
            ];
            return $options[$mode] ?? [];
        }
        // GSAP
        $options = [
            'character' => [
                'infinite-bounce' => __('Infinite Bounce', 'advanced-text-animations'),
                'typewriter' => __('Typewriter Effect', 'advanced-text-animations'),
                'infinite-wave' => __('Infinite Wave/Stagger', 'advanced-text-animations'),
                'scramble' => __('Scramble Effect', 'advanced-text-animations'),
                'rotate' => __('Rotating Characters', 'advanced-text-animations'),
                'pulse' => __('Pulse', 'advanced-text-animations'),
                'glitch' => __('Glitch Effect', 'advanced-text-animations'),
                'rainbow' => __('Rainbow Color Cycle', 'advanced-text-animations'),
                'shake' => __('Shake', 'advanced-text-animations'),
                'sliding-entrance' => __('Sliding Entrance', 'advanced-text-animations'),
                'reveal-gsap' => __('Text Reveal', 'advanced-text-animations'),
                'scroll-reveal' => __('Scroll Reveal', 'advanced-text-animations'),
            ],
            'words' => [
                'infinite-bounce' => __('Infinite Bounce', 'advanced-text-animations'),
                'infinite-wave' => __('Infinite Wave/Stagger', 'advanced-text-animations'),
                'pulse' => __('Pulse', 'advanced-text-animations'),
                'glitch' => __('Glitch Effect', 'advanced-text-animations'),
                'rainbow' => __('Rainbow Color Cycle', 'advanced-text-animations'),
                'shake' => __('Shake', 'advanced-text-animations'),
                'sliding-entrance' => __('Sliding Entrance', 'advanced-text-animations'),
                'reveal-gsap' => __('Text Reveal', 'advanced-text-animations'),
                'scroll-reveal' => __('Scroll Reveal', 'advanced-text-animations'),
            ],
            'lines' => [
                'infinite-bounce' => __('Infinite Bounce', 'advanced-text-animations'),
                'pulse' => __('Pulse', 'advanced-text-animations'),
                'glitch' => __('Glitch Effect', 'advanced-text-animations'),
                'rainbow' => __('Rainbow Color Cycle', 'advanced-text-animations'),
                'sliding-entrance' => __('Sliding Entrance', 'advanced-text-animations'),
                'reveal-gsap' => __('Text Reveal', 'advanced-text-animations'),
                'scroll-reveal' => __('Scroll Reveal', 'advanced-text-animations'),
            ],
        ];
        return $options[$mode] ?? [];
    }

    private function get_animation_description($engine, $mode, $type) {
        $descriptions = [
            'bounce-text' => __('Each character/word bounces up and down, creating a playful effect.', 'advanced-text-animations'),
            'fade-text' => __('Characters/words/lines fade in and out, creating a blinking effect.', 'advanced-text-animations'),
            'wave-text' => __('Characters/words move up and down in a wave pattern, each with a slight delay.', 'advanced-text-animations'),
            'rotate-text' => __('Each character spins 360 degrees on its own axis.', 'advanced-text-animations'),
            'pulse-text' => __('Characters/words/lines gently grow and shrink, creating a pulsing effect.', 'advanced-text-animations'),
            'glitch-text' => __('Characters/words/lines appear to glitch with shifting colored shadows.', 'advanced-text-animations'),
            'rainbow-text' => __('Each character/word/line cycles through a rainbow of colors.', 'advanced-text-animations'),
            'shake-text' => __('Characters/words shake side to side, as if vibrating.', 'advanced-text-animations'),
            'slide-text' => __('Characters/words/lines slide in and out from the left, fading as they go.', 'advanced-text-animations'),
            'blink-text' => __('Characters/words/lines blink on and off, like a cursor.', 'advanced-text-animations'),
            'reveal-gsap' => __('Text reveal effect inspired by GSAP CodePen demo.', 'advanced-text-animations'),
            'running-text' => __('Running text/marquee effect with dual color, inspired by GSAP demo.', 'advanced-text-animations'),
            'scroll-reveal' => __('Scroll-triggered text reveal with background color, inspired by GSAP demo.', 'advanced-text-animations'),
        ];
        return $descriptions[$type] ?? '';
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $engine = $settings['animation_engine'] ?? 'css';
        $mode = $settings['animation_mode'] ?? 'words';
        $animation_type = $settings['animation_type_' . $engine . '_' . $mode] ?? '';
        $tag = $settings['wrapper_tag'] ?? 'div';
        $delay_ms = isset($settings['animation_delay']) ? intval($settings['animation_delay']) : 0;
        $delay_s = $delay_ms > 0 ? ($delay_ms / 1000) : 0;
        $is_scroll_reveal = ($engine === 'gsap' && $animation_type === 'scroll-reveal');
        $wrapper_classes = 'ata-animated-text' . ($engine === 'gsap' ? ' ata-anim-gsap' : '');
        if ($is_scroll_reveal) {
            $wrapper_classes .= ' ata-scroll-reveal';
        }
        $this->add_render_attribute('wrapper', [
            'class' => $wrapper_classes,
            'data-animation' => $animation_type,
            'data-engine' => $engine,
            'data-mode' => $mode,
            'data-ata-anim-type' => $animation_type,
            'data-ata-anim-mode' => $mode,
            'data-ata-anim-delay' => $delay_s,
            'data-ata-anim-stagger' => $settings['gsap_stagger'] ?? 'yes',
            'data-ata-anim-yoyo' => $settings['gsap_yoyo'] ?? 'yes',
            'data-ata-anim-repeat' => isset($settings['gsap_repeat']) ? $settings['gsap_repeat'] : -1,
            'data-reveal-bg-color' => (
                $engine === 'gsap' && $mode === 'character' && !empty($settings['reveal_bg_color']) ? $settings['reveal_bg_color'] :
                ($engine === 'gsap' && $mode === 'words' && !empty($settings['reveal_bg_color_words']) ? $settings['reveal_bg_color_words'] :
                ($engine === 'gsap' && $mode === 'lines' && !empty($settings['reveal_bg_color_lines']) ? $settings['reveal_bg_color_lines'] : ''))
            ),
            'data-running-bg-color' => (
                $engine === 'gsap' && $mode === 'character' && !empty($settings['running_bg_color']) ? $settings['running_bg_color'] :
                ($engine === 'gsap' && $mode === 'words' && !empty($settings['running_bg_color_words']) ? $settings['running_bg_color_words'] :
                ($engine === 'gsap' && $mode === 'lines' && !empty($settings['running_bg_color_lines']) ? $settings['running_bg_color_lines'] : ''))
            ),
            'data-scroll-reveal-bg-color' => (
                $engine === 'gsap' && $mode === 'character' && !empty($settings['scroll_reveal_bg_color']) ? $settings['scroll_reveal_bg_color'] :
                ($engine === 'gsap' && $mode === 'words' && !empty($settings['scroll_reveal_bg_color_words']) ? $settings['scroll_reveal_bg_color_words'] :
                ($engine === 'gsap' && $mode === 'lines' && !empty($settings['scroll_reveal_bg_color_lines']) ? $settings['scroll_reveal_bg_color_lines'] : ''))
            ),
            'data-scroll-reveal-initial-color' => isset($settings['scroll_reveal_initial_color']) && $settings['scroll_reveal_initial_color'] !== '' ? $settings['scroll_reveal_initial_color'] : '#aaa',
            'data-scroll-reveal-text-color' => isset($settings['text_color']) && $settings['text_color'] !== '' ? $settings['text_color'] : '#222',
        ]);
        $text = $settings['text'];
        $output = '';
        // Output a style block for initial color if scroll-reveal
        if ($is_scroll_reveal) {
            $initial_color = '';
            if ($mode === 'character') {
                $initial_color = $settings['scroll_reveal_initial_color_character'] ?? '#aaa';
            } elseif ($mode === 'words') {
                $initial_color = $settings['scroll_reveal_initial_color_words'] ?? '#aaa';
            } elseif ($mode === 'lines') {
                $initial_color = $settings['scroll_reveal_initial_color_lines'] ?? '#aaa';
            }
        }
        if ($engine === 'css' || $is_scroll_reveal) {
            if ($mode === 'character') {
                $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
                foreach ($chars as $i => $char) {
                    $display_char = ($char === ' ') ? '&nbsp;' : esc_html($char);
                    $style = ($is_scroll_reveal && !empty($initial_color)) ? ' style="color: ' . esc_attr($initial_color) . ';"' : '';
                    $output .= '<span class="ata-split-char"' . $style . '>' . $display_char . '</span>';
                }
            } elseif ($mode === 'words') {
                $words = preg_split('/(\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
                foreach ($words as $i => $word) {
                    if (trim($word) === '') {
                        $output .= $word;
                    } else {
                        $style = ($is_scroll_reveal && !empty($initial_color)) ? ' style="color: ' . esc_attr($initial_color) . ';"' : '';
                        $output .= '<span class="ata-split-word"' . $style . '>' . esc_html($word) . '</span>';
                    }
                }
            } elseif ($mode === 'lines') {
                $lines = preg_split('/\r\n|\r|\n/', $text);
                foreach ($lines as $i => $line) {
                    $style = ($is_scroll_reveal && !empty($initial_color)) ? ' style="color: ' . esc_attr($initial_color) . ';"' : '';
                    $output .= '<div class="ata-split-line"' . $style . '>' . esc_html($line) . '</div>';
                }
            }
        } else {
            // For GSAP (other types), output only the raw text (no splitting)
            $output = esc_html($text);
        }
        ?>
        <<?php echo esc_attr($tag); ?> <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <?php echo $output; ?>
        </<?php echo esc_attr($tag); ?>>
        <?php
        // Remove frontend description output
    }
}
// Enqueue custom JS for live description update in the footer
add_action('wp_footer', function() {
    if (is_admin()) {
        ?>
        <script>
        jQuery(document).on('input change', '[data-setting^="animation_type_"]', function() {
            var $select = jQuery(this);
            var val = $select.val();
            var $desc = $select.closest('.elementor-control-content').find('.ata-anim-desc');
            var descMap = {};
            try {
                descMap = JSON.parse(jQuery('[data-setting="animation_type_desc_map"]').val() || '{}');
            } catch(e) {}
            if ($desc.length && descMap[val]) {
                $desc.text(descMap[val]);
            } else if ($desc.length) {
                $desc.text('');
            }
        });
        jQuery(function(){
            jQuery('[data-setting^="animation_type_"]').trigger('change');
        });
        </script>
        <?php
    }
});
// Enqueue the JS for the editor in the footer
add_action('wp_footer', function() {
    if (is_admin()) {
        wp_enqueue_script('ata-anim-desc', ATA_URL . 'assets/js/elementor-anim-desc.js', ['jquery'], ATA_VERSION, true);
    }
});
// Add JS to toggle GSAP preview message visibility in the editor
add_action('elementor/editor/after_enqueue_scripts', function() {
    ?>
    <script>
    jQuery(document).on('change', '[data-setting="animation_engine"]', function() {
        var val = jQuery(this).val();
        var msg = jQuery(this).closest('.elementor-control-content').find('.ata-gsap-preview-msg');
        if (val === 'gsap') {
            msg.show();
        } else {
            msg.hide();
        }
    });
    jQuery(function(){
        jQuery('[data-setting="animation_engine"]').trigger('change');
    });
    </script>
    <?php
});
