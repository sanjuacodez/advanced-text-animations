# Advanced Text Animations

A modular WordPress plugin that provides beautiful text animations with support for multiple page builders.

## Features

- Support for multiple page builders (currently Elementor, with more coming soon)
- Two animation engines: CSS and GSAP
- Multiple animation types for characters, words, and lines
- Easy to extend with new builders and animation types
- Translation ready
- Follows WordPress coding standards

## Requirements

- WordPress 5.8 or later
- PHP 7.4 or later
- Elementor 3.0 or later (for Elementor integration)

## Installation

1. Upload the plugin files to `/wp-content/plugins/advanced-text-animations`
2. Activate the plugin through the WordPress plugins screen
3. Configure the animation engine in Settings > Text Animations
4. Start using the animations in your supported page builder

## Directory Structure

```
advanced-text-animations/
├── assets/
│   ├── css/
│   │   ├── ata-core.css
│   │   └── ata-css-animations.css
│   └── js/
│       └── ata-gsap-animations.js
├── includes/
│   ├── admin/
│   │   └── Admin.php
│   ├── animations/
│   ├── integrations/
│   │   └── elementor/
│   │       ├── Elementor.php
│   │       └── widgets/
│   │           └── TextAnimation.php
│   └── Plugin.php
├── languages/
├── advanced-text-animations.php
└── README.md
```

## Development

### Adding New Animations

1. For CSS animations, add them to `assets/css/ata-css-animations.css`
2. For GSAP animations, add them to `assets/js/ata-gsap-animations.js`
3. Register the new animation in the appropriate widget class

### Adding New Builder Support

1. Create a new directory under `includes/integrations/`
2. Create the integration class following the pattern in `Elementor.php`
3. Add widget support for the new builder
4. Register the integration in `Plugin.php`

## License

GPL v2 or later
