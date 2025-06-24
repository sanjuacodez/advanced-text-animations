document.addEventListener('DOMContentLoaded', function() {
    var descMap = {
        'infinite-bounce': 'Characters, words, or lines continuously bounce up and down.',
        'typewriter': 'Text appears as if being typed, character by character, and can loop infinitely by clearing and retyping.',
        'infinite-wave': 'Characters or words animate in a wave pattern, each with a slight delay.',
        'scramble': 'Text scrambles and resolves, looping infinitely.',
        'rotate': 'Each character spins continuously.',
        'pulse': 'Text gently scales in and out.',
        'glitch': 'Text appears to glitch with shifting shadows or position.',
        'rainbow': 'Text color cycles through a rainbow.',
        'shake': 'Characters or words shake side to side.',
        'sliding-entrance': 'Text slides in and out, looping infinitely.'
    };
    function updateDesc(select) {
        var val = select.value;
        var controlContent = select.closest('.elementor-control-content');
        if (!controlContent) return;
        var desc = controlContent.querySelector('.ata-anim-desc');
        if (desc && descMap[val]) {
            desc.textContent = descMap[val];
        } else if (desc) {
            desc.textContent = '';
        }
    }
    document.querySelectorAll('[data-setting^="animation_type_"]').forEach(function(select) {
        select.addEventListener('input', function() { updateDesc(select); });
        select.addEventListener('change', function() { updateDesc(select); });
        updateDesc(select);
    });
});
