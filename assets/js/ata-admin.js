(function($) {
    'use strict';

    $(document).ready(function() {
        // Handle engine toggle
        $('.ata-engine-toggle input[type="radio"]').on('change', function() {
            const engine = $(this).val();
            $('.ata-engine-info').removeClass('active');
            $(`.ata-engine-${engine}`).addClass('active');
        });
    });
})(jQuery);
