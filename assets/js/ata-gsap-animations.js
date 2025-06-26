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

            // Common GSAP options
            var gsapOpts = {
                repeat: -1,
                yoyo: true,
                stagger: animDelay,
                delay: 0 // can be set per target if needed
            };

            switch (animType) {
                case 'infinite-bounce':
                    gsap.to(targets, {
                        y: -15,
                        duration: 1,
                        ...gsapOpts,
                        ease: 'sine.inOut',
                    });
                    break;
                case 'typewriter':
                    if (typeof TextPlugin !== 'undefined' && animMode === 'character') {
                        gsap.to($el[0], {
                            duration: 2,
                            text: { value: $el.text(), delimiter: '' },
                            repeat: -1,
                            repeatDelay: 1,
                            ease: 'none',
                            delay: animDelay
                        });
                    }
                    break;
                case 'infinite-wave':
                    if (animMode === 'character' || animMode === 'words') {
                        gsap.from(targets, {
                            y: 20,
                            duration: 0.5,
                            ...gsapOpts,
                            ease: 'sine.inOut',
                        });
                    }
                    break;
                case 'scramble':
                    if (typeof ScrambleTextPlugin !== 'undefined' && animMode === 'character') {
                        gsap.to($el[0], {
                            duration: 2,
                            scrambleText: { text: $el.text(), chars: '0123456789', revealDelay: 0.5 },
                            repeat: -1,
                            yoyo: true,
                            delay: animDelay
                        });
                    }
                    break;
                case 'rotate':
                    if (animMode === 'character') {
                        gsap.to(targets, {
                            rotation: 360,
                            duration: 2,
                            ...gsapOpts,
                            ease: 'none',
                        });
                    }
                    break;
                case 'pulse':
                    gsap.to(targets, {
                        scale: 1.1,
                        duration: 1,
                        ...gsapOpts,
                        ease: 'sine.inOut',
                    });
                    break;
                case 'glitch':
                    gsap.to(targets, {
                        x: function() { return Math.random() * 5 - 2.5; },
                        y: function() { return Math.random() * 5 - 2.5; },
                        duration: 0.1,
                        repeat: -1,
                        yoyo: true,
                        ease: 'none',
                        stagger: animDelay,
                        delay: 0
                    });
                    break;
                case 'rainbow':
                    // Use a single GSAP ticker for all targets for synchronized color cycling
                    var baseHue = 0;
                    var phaseStep = 360 / targets.length;
                    if (!window._ataRainbowTicker) {
                        window._ataRainbowTicker = true;
                        gsap.ticker.add(function() {
                            baseHue = (baseHue + 1) % 360;
                            $('.ata-anim-gsap[data-ata-anim-type="rainbow"]').each(function() {
                                var $rainbowEl = $(this);
                                var rainbowMode = $rainbowEl.data('ata-anim-mode');
                                var rainbowSplit = new SplitText($rainbowEl[0], { type: rainbowMode });
                                var rainbowTargets = (rainbowMode === 'character' || rainbowMode === 'chars') ? rainbowSplit.chars : (rainbowMode === 'words') ? rainbowSplit.words : (rainbowMode === 'lines') ? rainbowSplit.lines : [$rainbowEl[0]];
                                rainbowTargets.forEach(function(target, i) {
                                    var phase = (i * phaseStep) + (parseFloat($rainbowEl.data('ata-anim-delay')) || 0) * 360;
                                    target.style.color = 'hsl(' + ((baseHue + phase) % 360) + ', 100%, 50%)';
                                });
                            });
                        });
                    }
                    break;
                case 'shake':
                    if (animMode === 'character' || animMode === 'words') {
                        gsap.to(targets, {
                            x: function() { return Math.random() * 10 - 5; },
                            duration: 0.2,
                            repeat: -1,
                            yoyo: true,
                            ease: 'sine.inOut',
                            stagger: animDelay,
                            delay: 0
                        });
                    }
                    break;
                case 'sliding-entrance':
                    gsap.to(targets, {
                        x: 100,
                        opacity: 0,
                        duration: 1,
                        ...gsapOpts,
                        ease: 'sine.inOut',
                    });
                    break;
            }
        });
    }

    $(document).ready(function() {
        gsapReady(runGSAPAnimations);
    });

})(jQuery);
