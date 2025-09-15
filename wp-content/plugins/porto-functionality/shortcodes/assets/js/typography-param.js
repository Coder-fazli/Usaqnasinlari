/**
 * Porto Typography
 *
 * @since 6.1.0
 */
jQuery('.porto-wpb-typography-container .porto-wpb-typography-toggle').on('click', function (e) {
    var $this = jQuery(this);
    $this.parent().toggleClass('show');
    $this.next().slideToggle(300);
});
jQuery(document.body).on('change', '.porto-wpb-typography-container .porto-vc-font-family', function (e) {
    var $this = jQuery(this),
        $control = $this.closest('.porto-wpb-typography-container'),
        $form = $control.next(),
        $variants = $control.find('.porto-vc-font-variants'),
        $status = $control.find('.porto-wpb-typography-toggle p'),
        font = $this.val();


    var data = {
        family: $this.val(),
        variant: $variants.val(),
        font_size: $control.find('.porto-vc-font-size').val(),
        line_height: $control.find('.porto-vc-line-height').val(),
        letter_spacing: $control.find('.porto-vc-letter-spacing').val(),
        text_transform: $control.find('.porto-vc-text-transform').val()
    };

    $form.val(JSON.stringify(data));

    $status.text(data.family + ' | ' + data.variant + ' | ' + data.font_size);
}).on('change', '.porto-wpb-typography-container .porto-vc-font-variants, .porto-wpb-typography-container .porto-vc-font-size, .porto-wpb-typography-container .porto-vc-letter-spacing, .porto-wpb-typography-container .porto-vc-line-height, .porto-wpb-typography-container .porto-vc-text-transform', function (e) {
    var $this = jQuery(this),
        $control = $this.closest('.porto-wpb-typography-container'),
        $status = $control.find('.porto-wpb-typography-toggle p'),
        $form = $control.next(),
        $font_size = '';

    if ( $this.hasClass( 'porto-vc-font-size' ) ) {
        var $typo_control = $this.closest( '.porto-wpb-typoraphy-form' );
        if ( $typo_control.hasClass( 'porto-responsive-control' ) ) {
            if (undefined == $typo_control.data('width')) {
                $this.data('xl', $this.val());
            } else {
                $this.data($typo_control.data('width'), $this.val());
            }
            $font_size = $this.data();
        } else {
            $font_size = $control.find('.porto-vc-font-size').val();
        }
    } else {
        var $fs_control = $control.find('.porto-vc-font-size');
        var $typo_control = $fs_control.closest( '.porto-wpb-typoraphy-form' );
        if ( $typo_control.hasClass( 'porto-responsive-control' ) ) {
            $font_size = $fs_control.data();
        } else {
            $font_size = $fs_control.val();
        }
    }
    var data = {
        family: $control.find('.porto-vc-font-family').val(),
        variant: $control.find('.porto-vc-font-variants').val(),
        font_size: $font_size,
        line_height: $control.find('.porto-vc-line-height').val(),
        letter_spacing: $control.find('.porto-vc-letter-spacing').val(),
        text_transform: $control.find('.porto-vc-text-transform').val()
    };

    $form.val(JSON.stringify(data));
    $status.text(data.family + ' | ' + data.variant + ' | ' + data.font_size);
});

jQuery('.porto-wpb-typoraphy-form .porto-responsive-toggle').on('click', function (e) {
    var $this = jQuery(this);
    $this.parent().toggleClass('show');
});

if (undefined == js_porto_admin_vars || undefined == js_porto_admin_vars.porto_typography_included || true != js_porto_admin_vars.porto_typography_included) {
    jQuery(document.body).on('click', '.porto-wpb-typoraphy-form .porto-responsive-span li', function (e) {
        var $this = jQuery(this),
            $dropdown = $this.closest('.porto-responsive-dropdown'),
            $toggle = $dropdown.find('.porto-responsive-toggle'),
            $control = $dropdown.parent(),
            $input = $control.find('.porto-vc-font-size');
        // Actions
        $this.addClass('active').siblings().removeClass('active');
        $dropdown.removeClass('show');
        $toggle.html($this.html());

        // Trigger
        var $sizeControl = jQuery('#vc_screen-size-control'),
            $uiPanel = $this.closest('.vc_ui-panel-window');
        if ($sizeControl.length > 0) {
            $sizeControl.find('[data-size="' + $this.data('size') + '"]').click();
        }
        if ($uiPanel.length > 0) {
            $uiPanel.find('.porto-responsive-span [data-width="' + $this.data('width') + '"]').trigger('responsive_changed');
        }

        // Responsive Data
        var width = $this.data('width');
        $control.data('width', width);
        $input.val($input.data(width) ? $input.data(width) : '');
    }).off('responsive_changed', '.porto-wpb-typoraphy-form .porto-responsive-span li').on('responsive_changed', '.porto-wpb-typoraphy-form .porto-responsive-span li', function (e) {
        var $this = jQuery(this),
            $dropdown = $this.closest('.porto-responsive-dropdown'),
            $toggle = $dropdown.find('.porto-responsive-toggle'),
            $control = $dropdown.parent(),
            $input = $control.find('.porto-vc-font-size');
        // Actions
        $this.addClass('active').siblings().removeClass('active');
        $dropdown.removeClass('show');
        $toggle.html($this.html());

        // Responsive Data
        var width = $this.data('width');
        $control.data('width', width);
        $input.val($input.data(width) ? $input.data(width) : '');
    })
    if (undefined == js_porto_admin_vars) {
        js_porto_admin_vars = {
            porto_typography_included: true,
        }
    } else {
        js_porto_admin_vars.porto_typography_included = true;
    }
}