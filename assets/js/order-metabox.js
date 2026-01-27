/**
 * Gophr Order Metabox JavaScript
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        $('.gophr-action-btn').on('click', function(e) {
            e.preventDefault();

            var $button = $(this);
            var action = $button.data('action');
            var orderId = $button.data('order-id');
            var nonce = $button.data('nonce');
            var originalText = $button.text();

            // Disable button and show processing state
            $button.prop('disabled', true).text(gophrMetabox.strings.processing);

            $.ajax({
                url: gophrMetabox.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'gophr_' + action,
                    order_id: orderId,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message);
                    } else {
                        alert(response.data.message || gophrMetabox.strings.error);
                    }
                },
                error: function() {
                    alert(gophrMetabox.strings.error);
                },
                complete: function() {
                    $button.prop('disabled', false).text(originalText);
                }
            });
        });
    });
})(jQuery);
