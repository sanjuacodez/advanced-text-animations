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
    use TextAnimation_Render, TextAnimation_Controls_CSS, TextAnimation_Controls_GSAP, TextAnimation_Controls_Generic, TextAnimation_Utils;

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
        $this->add_generic_controls();
        $this->add_style_controls();
        $this->add_css_controls();
        $this->add_gsap_controls();
        // Style tab and other generic controls can remain here or be split further if needed.
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
                'sliding-entrance' => __('Sliding Entrance', 'advanced-text-animations'),
                'reveal-gsap' => __('Text Reveal', 'advanced-text-animations'),
                'scroll-reveal' => __('Scroll Reveal', 'advanced-text-animations'),
            ],
            'words' => [
                'infinite-bounce' => __('Infinite Bounce', 'advanced-text-animations'),
                'infinite-wave' => __('Infinite Wave/Stagger', 'advanced-text-animations'),
                'scramble' => __('Scramble Effect', 'advanced-text-animations'),
                'sliding-entrance' => __('Sliding Entrance', 'advanced-text-animations'),
                'reveal-gsap' => __('Text Reveal', 'advanced-text-animations'),
                'scroll-reveal' => __('Scroll Reveal', 'advanced-text-animations'),
            ],
            'lines' => [
                'infinite-bounce' => __('Infinite Bounce', 'advanced-text-animations'),
                'sliding-entrance' => __('Sliding Entrance', 'advanced-text-animations'),
                'reveal-gsap' => __('Text Reveal', 'advanced-text-animations'),
                // 'scroll-reveal' => __('Scroll Reveal', 'advanced-text-animations'), // Removed: not supported for lines mode
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
        $text = isset($settings['text']) ? $settings['text'] : '';
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
            'data-scroll-reveal-initial-color' => (
                $engine === 'gsap' && $mode === 'character' && !empty($settings['scroll_reveal_initial_color_character']) ? $settings['scroll_reveal_initial_color_character'] :
                ($engine === 'gsap' && $mode === 'words' && !empty($settings['scroll_reveal_initial_color_words']) ? $settings['scroll_reveal_initial_color_words'] :
                ($engine === 'gsap' && $mode === 'lines' && !empty($settings['scroll_reveal_initial_color_lines']) ? $settings['scroll_reveal_initial_color_lines'] : '#aaa'))
            ),
            'data-scroll-reveal-text-color' => isset($settings['text_color']) && $settings['text_color'] !== '' ? $settings['text_color'] : '#222',
        ]); 
        $output = $this->render_text_animation($settings, $engine, $mode, $animation_type, $tag, $delay_s, $is_scroll_reveal);
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
