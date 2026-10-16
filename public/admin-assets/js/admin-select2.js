/**
 * Institutional Admin - Select2 Automation Script
 * Automatically binds and transforms standard select elements into modern Select2 components.
 */
(function($) {
    'use strict';

    function initSelect2(context) {
        if (!window.jQuery || typeof window.jQuery.fn.select2 !== 'function') {
            return;
        }

        var $scope = context ? $(context) : $(document);

        // Target .select2 explicitly, as well as general form select elements
        // (excluding swal2 alerts, modals with custom selectors, or explicit .no-select2)
        var selector = 'select.select2, select:not(.no-select2):not(.swal2-select):not([id^="swal"]):not([class*="swal2"])';

        $scope.find(selector).addBack(selector).each(function() {
            var $select = $(this);

            // Avoid double initialization
            if ($select.hasClass('select2-hidden-accessible') || $select.data('select2')) {
                return;
            }

            var placeholder = $select.attr('placeholder') || $select.data('placeholder') || null;
            var allowClear = Boolean($select.data('allow-clear') || false);
            var isSearchable = $select.data('search') !== false;

            var config = {
                width: '100%',
                placeholder: placeholder,
                allowClear: allowClear,
                dropdownAutoWidth: false
            };

            // Disable search box if options count is <= 6 or explicitly disabled
            var optionCount = $select.find('option').length;
            if (!isSearchable || optionCount <= 6) {
                config.minimumResultsForSearch = ($select.attr('id') === 'status' || optionCount <= 6) ? Infinity : 0;
            }

            // Bind select2
            $select.select2(config);

            // Ensure HTML5 validation and reactive listeners receive the change event
            $select.on('select2:select select2:unselect', function() {
                $select.trigger('change');
            });
        });
    }

    // Expose global helper
    window.initAdminSelect2 = initSelect2;

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initSelect2();
        });
    } else {
        initSelect2();
    }

    // Safety re-run after full window load
    window.addEventListener('load', function() {
        initSelect2();
    });
})(window.jQuery);
