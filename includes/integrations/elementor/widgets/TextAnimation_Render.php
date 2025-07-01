<?php
// Rendering logic for TextAnimation widget
namespace AdvancedTextAnimations\Integrations\Elementor\Widgets;

if (!defined('ABSPATH')) exit;

trait TextAnimation_Render {
    protected function render_text_animation($settings, $engine, $mode, $animation_type, $tag, $delay_s, $is_scroll_reveal) {
        $text = $settings['text'];
        $output = '';
        $initial_color = '';
        if ($is_scroll_reveal) {
            if ($mode === 'character') {
                $initial_color = $settings['scroll_reveal_initial_color_character'] ?? '#aaa';
            } elseif ($mode === 'words') {
                $initial_color = $settings['scroll_reveal_initial_color_words'] ?? '#aaa';
            } elseif ($mode === 'lines') {
                $initial_color = $settings['scroll_reveal_initial_color_lines'] ?? '#aaa';
            }
        }
        // Determine animation class for CSS engine
        $anim_class = '';
        if ($engine === 'css' && !empty($animation_type)) {
            $anim_class = esc_attr($animation_type);
        }
        // Use animation delay from content tab if set, otherwise fallback
        $delay_step = 0.08; // default seconds
        if (isset($settings['animation_delay']) && is_numeric($settings['animation_delay'])) {
            $delay_step = floatval($settings['animation_delay']) / 1000; // ms to s
        }
        // Animation speed and repeat from controls
        $anim_speed = isset($settings['css_animation_speed']) && is_numeric($settings['css_animation_speed']) ? floatval($settings['css_animation_speed']) : null;
        $anim_repeat = isset($settings['css_animation_repeat']) ? $settings['css_animation_repeat'] : null;
        $anim_speed_css = $anim_speed ? 'animation-duration: ' . $anim_speed . 's;' : '';
        $anim_repeat_css = $anim_repeat ? 'animation-iteration-count: ' . $anim_repeat . ';' : '';
        if ($engine === 'css' || $is_scroll_reveal) {
            if ($mode === 'character') {
                $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
                foreach ($chars as $i => $char) {
                    // Always wrap every character, including spaces, in a span
                    $display_char = ($char === ' ') ? '&nbsp;' : esc_html($char);
                    $style = '';
                    if ($is_scroll_reveal && !empty($initial_color)) {
                        $style .= 'color: ' . esc_attr($initial_color) . ';';
                    }
                    if (in_array($animation_type, ['wave-text','rainbow-text','fade-text','pulse-text','glitch-text','shake-text','slide-text','blink-text'])) {
                        $style .= 'animation-delay: ' . ($i * $delay_step) . 's;';
                    }
                    $style .= $anim_speed_css . $anim_repeat_css;
                    $style = $style ? ' style="' . trim($style) . '"' : '';
                    $class = 'ata-split-char' . ($anim_class ? ' ' . $anim_class : '');
                    $output .= '<span class="' . $class . '"' . $style . '>' . $display_char . '</span>';
                }
            } elseif ($mode === 'words') {
                $words = preg_split('/(\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
                $word_index = 0;
                foreach ($words as $i => $word) {
                    // Always wrap every word, including spaces, in a span
                    $is_space = preg_match('/^\s+$/u', $word);
                    $display_word = $is_space ? str_repeat('&nbsp;', mb_strlen($word, 'UTF-8')) : esc_html($word);
                    $style = '';
                    if ($is_scroll_reveal && !empty($initial_color)) {
                        $style .= 'color: ' . esc_attr($initial_color) . ';';
                    }
                    if (in_array($animation_type, ['wave-text','rainbow-text','fade-text','pulse-text','glitch-text','shake-text','slide-text','blink-text'])) {
                        $style .= 'animation-delay: ' . ($word_index * $delay_step) . 's;';
                    }
                    $style .= $anim_speed_css . $anim_repeat_css;
                    $style = $style ? ' style="' . trim($style) . '"' : '';
                    $class = 'ata-split-word' . ($anim_class ? ' ' . $anim_class : '');
                    $output .= '<span class="' . $class . '"' . $style . '>' . $display_word . '</span>';
                    $word_index++;
                }
            } elseif ($mode === 'lines') {
                $lines = preg_split('/\r\n|\r|\n/', $text);
                foreach ($lines as $i => $line) {
                    $style = '';
                    if ($is_scroll_reveal && !empty($initial_color)) {
                        $style .= 'color: ' . esc_attr($initial_color) . ';';
                    }
                    if (in_array($animation_type, ['wave-text','rainbow-text','fade-text','pulse-text','glitch-text','shake-text','slide-text','blink-text'])) {
                        $style .= 'animation-delay: ' . ($i * $delay_step) . 's;';
                    }
                    $style .= $anim_speed_css . $anim_repeat_css;
                    $style = $style ? ' style="' . trim($style) . '"' : '';
                    $class = 'ata-split-line' . ($anim_class ? ' ' . $anim_class : '');
                    $output .= '<div class="' . $class . '"' . $style . '>' . esc_html($line) . '</div>';
                }
            }
        } else {
            $output = esc_html($text);
        }
        return $output;
    }
}
