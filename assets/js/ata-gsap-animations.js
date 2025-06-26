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
            // Map animation mode to SplitText type and target
            var splitType = '';
            var targets = [];
            if (animMode === 'character') {
                splitType = 'chars';
            } else if (animMode === 'words') {
                splitType = 'words';
            } else if (animMode === 'lines') {
                splitType = 'lines';
            } else {
                splitType = animMode;
            }
            var split = new SplitText($el[0], { type: splitType, tag: 'span' });
            if (splitType === 'chars') targets = split.chars;
            else if (splitType === 'words') targets = split.words;
            else if (splitType === 'lines') targets = split.lines;
            else targets = [$el[0]];

            // Read per-widget GSAP options from data attributes
            var gsapStagger = $el.data('ata-anim-stagger') === 'yes';
            var gsapYoyo = $el.data('ata-anim-yoyo') === 'yes';
            var gsapRepeat = typeof $el.data('ata-anim-repeat') !== 'undefined' ? parseInt($el.data('ata-anim-repeat')) : -1;

            // Only use these options for supported animation types
            var useGsapOpts = [
                'infinite-bounce', 'infinite-wave', 'rotate', 'pulse', 'glitch', 'shake', 'sliding-entrance'
            ];
            var gsapOpts = {};
            if (useGsapOpts.includes(animType)) {
                gsapOpts = {
                    repeat: gsapRepeat,
                    yoyo: gsapYoyo,
                    stagger: gsapStagger ? animDelay : 0,
                    delay: 0
                };
            }

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
                            repeat: gsapRepeat,
                            repeatDelay: 1,
                            yoyo: gsapYoyo,
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
                    if (typeof window.ScrambleTextPlugin !== 'undefined' && splitType === 'chars') {
                        targets.forEach(function(target) {
                            gsap.to(target, {
                                duration: 2,
                                scrambleText: { text: target.textContent, chars: '0123456789', revealDelay: 0.5 },
                                repeat: -1,
                                yoyo: true,
                                delay: animDelay
                            });
                        });
                    } else if (typeof window.ScrambleTextPlugin !== 'undefined') {
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
                        repeat: gsapRepeat,
                        yoyo: gsapYoyo,
                        ease: 'none',
                        stagger: gsapStagger ? animDelay : 0,
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
                                var rainbowSplitType = (rainbowMode === 'character') ? 'chars' : rainbowMode;
                                // Only split once per element, cache result
                                if (!$rainbowEl.data('ata-rainbow-split')) {
                                    $rainbowEl.data('ata-rainbow-split', new SplitText($rainbowEl[0], { type: rainbowSplitType }));
                                }
                                var rainbowSplit = $rainbowEl.data('ata-rainbow-split');
                                var rainbowTargets = (rainbowSplitType === 'chars') ? rainbowSplit.chars : (rainbowSplitType === 'words') ? rainbowSplit.words : (rainbowSplitType === 'lines') ? rainbowSplit.lines : [$rainbowEl[0]];
                                var localPhaseStep = 360 / rainbowTargets.length;
                                rainbowTargets.forEach(function(target, i) {
                                    var phase = (i * localPhaseStep) + (parseFloat($rainbowEl.data('ata-anim-delay')) || 0) * 360;
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
                            ...gsapOpts,
                            ease: 'sine.inOut',
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

    // Debug: Log GSAP and ScrambleTextPlugin presence
    if (window.gsap) {
        console.log('[ATA] GSAP version:', window.gsap.version);
    } else {
        console.warn('[ATA] GSAP is not loaded!');
    }
    if (window.ScrambleTextPlugin) {
        console.log('[ATA] ScrambleTextPlugin is loaded:', typeof window.ScrambleTextPlugin);
        if (window.gsap) {
            window.gsap.registerPlugin(window.ScrambleTextPlugin);
            console.log('[ATA] ScrambleTextPlugin registered with GSAP.');
        }
    } else {
        console.warn('[ATA] ScrambleTextPlugin is NOT loaded!');
    }

    // Wait for fonts to be loaded before running GSAP animations
    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(function() {
            gsapReady(runGSAPAnimations);
        });
    } else {
        // Fallback for older browsers
        $(document).ready(function() {
            gsapReady(runGSAPAnimations);
        });
    }

})(jQuery);
