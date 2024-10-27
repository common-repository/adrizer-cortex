
/*global jQuery:false*/

jQuery(document).ready(function () {
    CORTEX_JS.init();
    // Run tab open/close event
    CORTEX_Tab.event();
});

// Init all fields functions (invoked from ajax)
var CORTEX_JS = {
    init: function () {
        // Run tab open/close
        CORTEX_Tab.init();
        // Load colorpicker if field exists
        CORTEX_ColorPicker.init();
    }
};


var CORTEX_ColorPicker = {
    init: function () {
        var $colorPicker = jQuery('.cortex-colorpicker');
        if ($colorPicker.length > 0) {

            $colorPicker.wpColorPicker();

        }
    }
};

var CORTEX_Tab = {
    init: function () {
        // display the tab chosen for initial display in content
        jQuery('.cortex-tab.selected').each(function () {
            CORTEX_Tab.check(jQuery(this));
        });
    },
    event: function () {
        jQuery(document).on('click', '.cortex-tab', function () {
            CORTEX_Tab.check(jQuery(this));
        });
    },
    check: function (elem) {
        var chosen_tab_name = elem.data('target');
        elem.siblings().removeClass('selected');
        elem.addClass('selected');
        elem.closest('.cortex-inner').find('.cortex-tab-content').removeClass('cortex-tab-show').hide();
        elem.closest('.cortex-inner').find('.cortex-tab-content.' + chosen_tab_name + '').addClass('cortex-tab-show').show();
    }
};