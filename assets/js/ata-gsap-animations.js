// GSAP-based animations
(function($) {
    'use strict';

    // Helper: Load GSAP and plugins if not already loaded
    function gsapReady(callback) {
        if (typeof window.gsap === 'undefined') {
            console.warn('[Advanced Text Animations] GSAP (gsap.min.js) is missing or not loaded!');
            return;
        }
        if (typeof window.SplitText === 'undefined') {
            console.warn('[Advanced Text Animations] GSAP SplitText plugin (SplitText.min.js) is missing or not loaded!');
            return;
        }
        callback();
    }

    function runGSAPAnimations() {
        $('.ata-anim-gsap').each(function() {
            var $el = $(this);
            var animType = $el.data('ata-anim-type');
            var animMode = $el.data('ata-anim-mode');
            var animDelay = parseFloat($el.data('ata-anim-delay')) || 0.1;
            var split = new SplitText($el[0], {
                type: animMode // 'chars', 'words', 'lines'
            });
            var targets = [];
            if (animMode === 'character' || animMode === 'chars') targets = split.chars;
            else if (animMode === 'words') targets = split.words;
            else if (animMode === 'lines') targets = split.lines;
            else targets = [$el[0]];

            switch (animType) {
                case 'infinite-bounce':
                    gsap.to(targets, {
                        y: -15,
                        duration: 1,
                        repeat: -1,
                        yoyo: true,
                        ease: 'sine.inOut',
                        stagger: animDelay
                    });
                    break;
                case 'typewriter':
                    if (typeof TextPlugin !== 'undefined' && animMode === 'character') {
                        gsap.to($el[0], {
                            duration: 2,
                            text: { value: $el.text(), delimiter: '' },
                            repeat: -1,
                            repeatDelay: 1,
                            ease: 'none'
                        });
                    }
                    break;
                case 'infinite-wave':
                    if (animMode === 'character' || animMode === 'words') {
                        gsap.from(targets, {
                            y: 20,
                            duration: 0.5,
                            repeat: -1,
                            yoyo: true,
                            ease: 'sine.inOut',
                            stagger: animDelay
                        });
                    }
                    break;
                case 'scramble':
                    if (typeof ScrambleTextPlugin !== 'undefined' && animMode === 'character') {
                        gsap.to($el[0], {
                            duration: 2,
                            scrambleText: { text: $el.text(), chars: '0123456789', revealDelay: 0.5 },
                            repeat: -1,
                            yoyo: true
                        });
                    }
                    break;
                case 'rotate':
                    if (animMode === 'character') {
                        gsap.to(targets, {
                            rotation: 360,
                            duration: 2,
                            repeat: -1,
                            ease: 'none'
                        });
                    }
                    break;
                case 'pulse':
                    gsap.to(targets, {
                        scale: 1.1,
                        duration: 1,
                        repeat: -1,
                        yoyo: true,
                        ease: 'sine.inOut',
                        stagger: animDelay
                    });
                    break;
                case 'glitch':
                    gsap.to(targets, {
                        x: function() { return Math.random() * 5 - 2.5; },
                        y: function() { return Math.random() * 5 - 2.5; },
                        duration: 0.1,
                        repeat: -1,
                        ease: 'none'
                    });
                    break;
                case 'rainbow':
                    gsap.to(targets, {
                        color: '#ff0000',
                        duration: 2,
                        repeat: -1,
                        yoyo: true,
                        stagger: animDelay,
                        modifiers: {
                            color: function(value, target, element, index) {
                                var hue = (Date.now() / 10 + index * 40) % 360;
                                return 'hsl(' + hue + ', 100%, 50%)';
                            }
                        }
                    });
                    break;
                case 'shake':
                    if (animMode === 'character' || animMode === 'words') {
                        gsap.to(targets, {
                            x: function() { return Math.random() * 10 - 5; },
                            duration: 0.2,
                            repeat: -1,
                            yoyo: true,
                            ease: 'sine.inOut',
                            stagger: 0.05
                        });
                    }
                    break;
                case 'sliding-entrance':
                    gsap.to(targets, {
                        x: 100,
                        opacity: 0,
                        duration: 1,
                        repeat: -1,
                        yoyo: true,
                        ease: 'sine.inOut',
                        stagger: animDelay
                    });
                    break;
            }
        });
    }

    $(document).ready(function() {
        gsapReady(runGSAPAnimations);
    });

})(jQuery);
