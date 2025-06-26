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
        var $targets = $('.ata-anim-gsap');
        console.log('[ATA] Found', $targets.length, '.ata-anim-gsap elements');
        $targets.each(function(i, el) {
            console.log('[ATA] Element', i, el.outerHTML);
        });
        function runSplitTextWithRetry($el, splitType, maxRetries = 10, delay = 100, onSplit) {
            let attempt = 0;
            function trySplit() {
                // Log text content and childNodes before splitting
                console.log('[ATA] Before SplitText:', {
                    textContent: $el[0].textContent,
                    childNodes: $el[0].childNodes,
                    outerHTML: $el[0].outerHTML
                });
                // Force visible
                $el.css({display: 'block', visibility: 'visible', opacity: 1});
                if ($el.is(':visible') && $el[0].offsetParent !== null) {
                    try {
                        var split = new SplitText($el[0], { type: splitType, tag: 'span' });
                        $el.data('ata-split', split);
                        console.log('[ATA] SplitText created:', splitType, split, $el[0]);
                        if (onSplit) onSplit(split);
                    } catch (e) {
                        console.error('[ATA] SplitText error:', e, $el[0]);
                    }
                } else if (attempt < maxRetries) {
                    attempt++;
                    setTimeout(trySplit, delay);
                } else {
                    console.warn('[ATA] SplitText: Element never became visible:', $el[0]);
                }
            }
            trySplit();
        }

        $targets.each(function() {
            var $el = $(this);
            // Clean up previous SplitText splits if any
            if ($el.data('ata-split')) {
                try {
                    $el.data('ata-split').revert();
                } catch (e) {
                    console.warn('[ATA] Error reverting previous SplitText:', e);
                }
                $el.removeData('ata-split');
            }
            var animType = $el.data('ata-anim-type');
            var animMode = $el.data('ata-anim-mode');
            var animDelay = parseFloat($el.data('ata-anim-delay')) || 0.1;
            // Map animation mode to SplitText type and target
            var splitType = '';
            if (animMode === 'character') {
                splitType = 'chars';
            } else if (animMode === 'words') {
                splitType = 'words';
            } else if (animMode === 'lines') {
                splitType = 'lines';
            } else {
                splitType = animMode;
            }
            // Use visibility check and retry for SplitText
            runSplitTextWithRetry($el, splitType, 10, 100, function(split) {
                var targets = [];
                if (splitType === 'chars') targets = split.chars;
                else if (splitType === 'words') targets = split.words;
                else if (splitType === 'lines') targets = split.lines;
                else targets = [$el[0]];
                console.log('[ATA] SplitText targets:', targets.length, targets);
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

    // Debug: Confirm script is running in Elementor editor/preview
    if (window.elementorFrontend && window.elementorFrontend.isEditMode && window.elementorFrontend.isEditMode()) {
        console.log('[ATA] Animation JS running in Elementor editor/preview context');
    }

    // Elementor integration: ensure GSAP animations run after every widget render (frontend & editor)
    function ataInitElementorHooks() {
        // Run on every widget render (frontend & editor)
        if (window.elementorFrontend && window.elementorFrontend.hooks) {
            // For all widgets (global)
            window.elementorFrontend.hooks.addAction('frontend/element_ready/global', function(scope, $scope){
                gsapReady(runGSAPAnimations);
            });
            // For this specific widget (if you have a unique widget name, e.g., 'advanced-text-animations')
            window.elementorFrontend.hooks.addAction('frontend/element_ready/advanced-text-animations.default', function(scope, $scope){
                gsapReady(runGSAPAnimations);
            });
        }
    }

    // Run on frontend and in Elementor editor/preview
    if (window.elementorFrontend) {
        if (window.elementorFrontend.isEditMode()) {
            // In Elementor editor/preview, wait for init
            jQuery(window).on('elementor/frontend/init', function() {
                ataInitElementorHooks();
                // Also run once in case widgets are already present
                gsapReady(runGSAPAnimations);
            });
        } else {
            // On frontend, run hooks immediately
            ataInitElementorHooks();
            // Also run once on DOM ready
            jQuery(function(){
                gsapReady(runGSAPAnimations);
            });
        }
    } else {
        // Fallback: run on DOM ready (non-Elementor context)
        jQuery(function(){
            gsapReady(runGSAPAnimations);
        });
    }

    // Utility: MutationObserver fallback for dynamic DOM (Elementor editor/preview)
    function ataObserveGSAPElements() {
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1 && node.classList && node.classList.contains('ata-anim-gsap')) {
                            console.log('[ATA] MutationObserver: .ata-anim-gsap element added:', node);
                            gsapReady(runGSAPAnimations);
                        } else if (node.nodeType === 1) {
                            // Check descendants
                            var found = node.querySelectorAll && node.querySelectorAll('.ata-anim-gsap');
                            if (found && found.length) {
                                console.log('[ATA] MutationObserver: .ata-anim-gsap descendants added:', found.length);
                                gsapReady(runGSAPAnimations);
                            }
                        }
                    });
                }
            });
        });
        observer.observe(document.body, { childList: true, subtree: true });
        console.log('[ATA] MutationObserver for .ata-anim-gsap elements is active.');
    }

    // Log context for debugging
    (function(){
        var inIframe = window.self !== window.top;
        var isEditMode = window.elementorFrontend && window.elementorFrontend.isEditMode && window.elementorFrontend.isEditMode();
        console.log('[ATA] Context:', {
            inIframe: inIframe,
            isEditMode: isEditMode,
            url: window.location.href
        });
    })();

    // Robustly detect Elementor editor/preview and start MutationObserver
    (function(){
        var started = false;
        function startObserverIfNeeded() {
            if (started) return;
            started = true;
            ataObserveGSAPElements();
        }
        function isElementorPreviewUrl() {
            return window.location.href.match(/elementor-preview=\d+/) || window.location.pathname.match(/\/elementor-\d+/);
        }
        function pollForElementorFrontend() {
            if (window.elementorFrontend && typeof window.elementorFrontend.isEditMode === 'function') {
                if (window.elementorFrontend.isEditMode()) {
                    startObserverIfNeeded();
                }
            } else if (window.self !== window.top && isElementorPreviewUrl()) {
                // In iframe and looks like Elementor preview
                startObserverIfNeeded();
            } else {
                setTimeout(pollForElementorFrontend, 200);
            }
        }
        pollForElementorFrontend();
    })();

})(jQuery);
