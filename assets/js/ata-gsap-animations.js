// Ensure GSAP is available globally
if (typeof window.gsap === 'undefined' && typeof gsap !== 'undefined') {
    window.gsap = gsap;
}

// GSAP-based animations
(function($) {
    'use strict';

    // Helper: Load GSAP and plugins if not already loaded
    function gsapReady(callback) {
        if (typeof window.gsap === 'undefined') {
            console.error('[ATA] GSAP is not loaded!');
            return;
        }
        if (typeof window.SplitText === 'undefined') {
            console.error('[ATA] SplitText is not loaded!');
            return;
        }
        callback();
    }

    // Utility: Show GSAP preview message in Elementor editor/preview
    function ataShowGSAPPreviewMessage($el) {
        if (!$el.find('.ata-gsap-preview-msg').length) {
            $el.append('<div class="ata-gsap-preview-msg" style="color:#c00;font-size:13px;margin-top:8px;">GSAP animation preview is not available in the editor. Please check the frontend for the live animation.</div>');
        }
    }

    // Utility: Robust SplitText with retries and visibility check
    function runSplitTextWithRetry($el, splitType, maxRetries, delay, callback) {
        var retries = 0;
        function isVisible(el) {
            return !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length);
        }
        function trySplit() {
            if (!isVisible($el[0])) {
                if (retries < maxRetries) {
                    retries++;
                    setTimeout(trySplit, delay);
                }
                return;
            }
            try {
                var splitOptions = { type: splitType };
                if (splitType === 'chars') splitOptions.tag = 'span';
                var split = new SplitText($el[0], splitOptions);
                $el.data('ata-split', split);
                callback(split);
            } catch (e) {
                if (retries < maxRetries) {
                    retries++;
                    setTimeout(trySplit, delay);
                }
            }
        }
        trySplit();
    }

    // Register ScrollTrigger if available
    if (window.gsap && window.gsap.registerPlugin && window.ScrollTrigger) {
        window.gsap.registerPlugin(window.ScrollTrigger);
    }

    function runGSAPAnimations() {
        var $targets = $('.ata-anim-gsap');
        $targets.each(function() {
            var $el = $(this);
            // Clean up previous SplitText splits if any
            if ($el.data('ata-split')) {
                try { $el.data('ata-split').revert(); } catch (e) {}
                $el.removeData('ata-split');
            }
            var animType = $el.data('ata-anim-type');
            var animMode = $el.data('ata-anim-mode');
            var animEngine = $el.data('engine') || $el.data('ata-anim-engine');
            // If in Elementor editor/preview and GSAP is selected, show message and skip animation
            if (window.elementorFrontend && window.elementorFrontend.isEditMode && window.elementorFrontend.isEditMode() && animEngine === 'gsap') {
                ataShowGSAPPreviewMessage($el);
                return;
            }
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
            // --- SCROLLTRIGGER WRAP ---
            if (window.ScrollTrigger) {
                ScrollTrigger.create({
                    trigger: $el[0],
                    start: 'top 90%',
                    once: false,
                    onEnter: function() {
                        runSplitTextWithRetry($el, splitType, 10, 100, function(split) {
                            ataRunGSAPAnimationType($el, split, animType, animMode);
                        });
                    },
                    onEnterBack: function() {
                        runSplitTextWithRetry($el, splitType, 10, 100, function(split) {
                            ataRunGSAPAnimationType($el, split, animType, animMode);
                        });
                    },
                    onLeave: function() {
                        // Optionally revert or pause animation here
                    }
                });
            } else {
                runSplitTextWithRetry($el, splitType, 10, 100, function(split) {
                    ataRunGSAPAnimationType($el, split, animType, animMode);
                });
            }
        });
    }

    // Extracted animation logic for ScrollTrigger
    function ataRunGSAPAnimationType($el, split, animType, animMode) {
        var targets = [];
        var splitType = (animMode === 'character') ? 'chars' : (animMode === 'words') ? 'words' : (animMode === 'lines') ? 'lines' : animMode;
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
                stagger: gsapStagger ? 0.05 : 0,
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
                        delay: 0
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
                            delay: 0
                        });
                    });
                } else if (typeof window.ScrambleTextPlugin !== 'undefined') {
                    gsap.to($el[0], {
                        duration: 2,
                        scrambleText: { text: $el.text(), chars: '0123456789', revealDelay: 0.5 },
                        repeat: -1,
                        yoyo: true,
                        delay: 0
                    });
                }
                break;
            case 'rotate':
                if (animMode === 'character') {
                    gsap.to(targets, {
                        rotation: 360,
                        duration: 2,
                        repeat: gsapRepeat,
                        yoyo: gsapYoyo,
                        transformOrigin: '50% 50%',
                        ease: 'none',
                        stagger: gsapStagger ? 0.05 : 0,
                        delay: 0
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
                    stagger: gsapStagger ? 0.05 : 0,
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
                                var phase = (i * localPhaseStep);
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
            case 'reveal-gsap':
                // GSAP text reveal animation inspired by CodePen
                var revealTargets = targets;
                // Get background color from Elementor settings
                var bgColor = $el.data('reveal-bg-color') || '#353535';
                // Add background spans for the reveal effect
                revealTargets.forEach(function(target) {
                    if (!target.classList.contains('ata-reveal-word')) {
                        var wordSpan = document.createElement('span');
                        wordSpan.className = 'ata-reveal-word';
                        wordSpan.style.position = 'relative';
                        wordSpan.style.display = 'inline-block';
                        wordSpan.style.overflow = 'hidden';
                        wordSpan.style.verticalAlign = 'bottom';
                        wordSpan.textContent = target.textContent;
                        target.textContent = '';
                        target.appendChild(wordSpan);
                        var bgSpan = document.createElement('span');
                        bgSpan.className = 'ata-reveal-bg';
                        bgSpan.style.position = 'absolute';
                        bgSpan.style.left = 0;
                        bgSpan.style.top = 0;
                        bgSpan.style.width = '100%';
                        bgSpan.style.height = '100%';
                        bgSpan.style.background = bgColor;
                        bgSpan.style.transform = 'scaleX(0)';
                        bgSpan.style.transformOrigin = 'left';
                        bgSpan.style.zIndex = 2;
                        wordSpan.appendChild(bgSpan);
                    }
                });
                var wordSpans = $el.find('.ata-reveal-word');
                var bgSpans = $el.find('.ata-reveal-bg');
                var repeatCount = typeof $el.data('ata-anim-repeat') !== 'undefined' ? parseInt($el.data('ata-anim-repeat')) : 0;
                function playRevealTimeline() {
                    var tl = gsap.timeline({
                        onComplete: function() {
                            if (repeatCount === -1 || repeatCount > 1) {
                                if (repeatCount > 1) repeatCount--;
                                setTimeout(playRevealTimeline, 500);
                            }
                        }
                    });
                    tl.set(wordSpans, { opacity: 0 });
                    tl.set(bgSpans, { scaleX: 0, transformOrigin: 'left' });
                    tl.to(bgSpans, { scaleX: 1, duration: 0.2, stagger: 0.1, transformOrigin: 'left', ease: 'power1.in' })
                      .to(wordSpans, { opacity: 1, duration: 0.1, stagger: 0.1 }, '-=0.1')
                      .to(bgSpans, { scaleX: 0, duration: 0.2, stagger: 0.1, transformOrigin: 'right', ease: 'power1.out' });
                }
                playRevealTimeline();
                break;
            case 'scroll-reveal':
                // Improved: Animate all chars/words together with stagger, yoyo, and delay from settings (repeat removed)
                var chars = $el.find('.ata-split-char').toArray();
                var words = $el.find('.ata-split-word').toArray();
                var isCharMode = chars.length > 0;
                var targets = isCharMode ? chars : words;
                var startColor = $el.data('scroll-reveal-initial-color') || '#aaa';
                var endColor = $el.data('scroll-reveal-text-color') || '#222';
                var gsapStagger = $el.data('ata-anim-stagger') === 'yes';
                var gsapYoyo = $el.data('ata-anim-yoyo') === 'yes';
                var animDelay = $el.data('ata-anim-delay') || 0;
                var duration = 1;
                // Always reset all to initial color
                gsap.set(targets, { color: startColor });
                ScrollTrigger.create({
                    trigger: $el[0],
                    start: 'top 80%',
                    once: false,
                    onEnter: function() {
                        gsap.to(targets, {
                            color: endColor,
                            duration: duration,
                            delay: animDelay,
                            stagger: gsapStagger ? 0.05 : 0,
                            ease: 'power2.out',
                            overwrite: 'auto',
                            yoyo: false
                        });
                    },
                    onEnterBack: function() {
                        if (gsapYoyo) {
                            gsap.to(targets, {
                                color: startColor,
                                duration: duration,
                                delay: animDelay,
                                stagger: gsapStagger ? 0.05 : 0,
                                ease: 'power2.out',
                                overwrite: 'auto',
                                yoyo: true
                            });
                        } else {
                            gsap.to(targets, {
                                color: endColor,
                                duration: duration,
                                delay: animDelay,
                                stagger: gsapStagger ? 0.05 : 0,
                                ease: 'power2.out',
                                overwrite: 'auto',
                                yoyo: false
                            });
                        }
                    },
                    onLeave: function() {
                        // Optionally reset color when leaving viewport
                    }
                });
                break;
        }
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

    // Elementor integration: robust hooks for editor/preview
    function ataInitElementorHooks() {
        if (window.elementorFrontend && window.elementorFrontend.hooks) {
            // Global hook for all widgets
            window.elementorFrontend.hooks.addAction('frontend/element_ready/global', function(scope, $scope){
                setTimeout(function() {
                    gsapReady(runGSAPAnimations);
                }, 50);
            });
            // Widget-specific hook (replace with your widget name if different)
            window.elementorFrontend.hooks.addAction('frontend/element_ready/advanced-text-animations.default', function(scope, $scope){
                setTimeout(function() {
                    gsapReady(runGSAPAnimations);
                }, 50);
            });
            // Also handle popups and dynamic content
            window.elementorFrontend.hooks.addAction('elementor/popup/show', function(){
                setTimeout(function() {
                    gsapReady(runGSAPAnimations);
                }, 50);
            });
            window.elementorFrontend.hooks.addAction('elementor/popup/hide', function(){
                setTimeout(function() {
                    gsapReady(runGSAPAnimations);
                }, 50);
            });
        }
    }

    // Ensure hooks are initialized in all contexts
    if (window.elementorFrontend) {
        if (window.elementorFrontend.isEditMode && window.elementorFrontend.isEditMode()) {
            jQuery(window).on('elementor/frontend/init', function() {
                ataInitElementorHooks();
                setTimeout(function() {
                    gsapReady(runGSAPAnimations);
                }, 50);
            });
        } else {
            ataInitElementorHooks();
            jQuery(function(){
                setTimeout(function() {
                    gsapReady(runGSAPAnimations);
                }, 50);
            });
        }
    } else {
        jQuery(function(){
            setTimeout(function() {
                gsapReady(runGSAPAnimations);
            }, 50);
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
