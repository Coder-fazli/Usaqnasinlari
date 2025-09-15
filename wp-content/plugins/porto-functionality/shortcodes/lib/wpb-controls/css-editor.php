<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryCssEditor
 * 
 * @since 7.2.0
 */
class WPBakeryCssEditor {
    /**
     * @var array
     */
    protected $settings = array();
    /**
     * @var string
     */
    protected $value = '';
    /**
     * @var array
     */
    protected $positions = array(
        'top',
        'right',
        'bottom',
        'left',
    );
    public $params = array();

    /**
     * Setters/Getters {{
     *
     * @param null $settings
     *
     * @return array
     */
    public function settings( $settings = null ) {
        if ( is_array( $settings ) ) {
            $this->settings = $settings;
        }

        return $this->settings;
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function setting( $key ) {
        return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : '';
    }

    /**
     * @param null $value
     *
     * @return string
     */
    public function value( $value = null ) {
        if ( is_string( $value ) ) {
            $this->value = $value;
        }

        return $this->value;
    }

    /**
     * @param null $values
     *
     * @return array
     */
    public function params( $values = null ) {
        if ( is_array( $values ) ) {
            $this->params = $values;
        }

        return $this->params;
    }

    // }}

    /**
     * vc_filter: vc_css_editor - hook to override output of this method
     * @return mixed
     */
    public function render() {
        $output = '<div class="vc_css-editor vc_row vc_ui-flex-row" data-css-editor="true">';
        $output .= $this->onionLayout();
        $output .= sprintf( '<div class="vc_col-xs-5 vc_settings"><label>%s</label><div class="color-group"><input type="text" name="border_color" value="" class="vc_color-control"></div><label>%s</label><div class="vc_border-style"><select name="border_style" class="vc_border-style">%s</select></div><label>%s</label><div class="vc_border-radius"><select name="border_radius" class="vc_border-radius">%s</select></div><label>%s</label><div class="color-group"><input type="text" name="background_color" value="" class="vc_color-control"></div><div class="vc_background-image">%s<div class="vc_clearfix"></div></div><div class="vc_background-style"><select name="background_style" class="vc_background-style">%s</select></div><label>%s</label><label class="vc_checkbox"><input type="checkbox" name="simply" class="vc_simplify" value=""> %s</label></div>', esc_html__( 'Border color', 'js_composer' ), esc_html__( 'Border style', 'js_composer' ), $this->getBorderStyleOptions(), esc_html__( 'Border radius', 'js_composer' ), $this->getBorderRadiusOptions(), esc_html__( 'Background', 'js_composer' ), $this->getBackgroundImageControl(), $this->getBackgroundStyleOptions(), esc_html__( 'Box controls', 'js_composer' ), esc_html__( 'Simplify controls', 'js_composer' ) );

        $output .= sprintf( '<input name="%s" class="wpb_vc_param_value  %s %s_field" type="hidden" value="%s"/>', esc_attr( $this->setting( 'param_name' ) ), esc_attr( $this->setting( 'param_name' ) ), esc_attr( $this->setting( 'type' ) ), esc_attr( $this->value() ) );

        $output .= '</div><div class="vc_clearfix"></div>';
        $custom_tag = 'script';
        $output .= '<' . $custom_tag . ' type="text/html" id="vc_css-editor-image-block"><li class="added"><div class="inner" style="width: 80px; height: 80px; overflow: hidden;text-align: center;"><img src="{{ img.url }}?id={{ img.id }}" data-image-id="{{ img.id }}" class="vc_ce-image<# if (!_.isUndefined(img.css_class)) {#> {{ img.css_class }}<# }#>">  </div><a href="#" class="vc_icon-remove"><i class="vc-composer-icon vc-c-icon-close"></i></a></li></' . $custom_tag . '>';

        return apply_filters( 'vc_css_editor', $output );
    }

    /**
     * @return string
     */
    public function getBackgroundImageControl() {
        $value = sprintf( '<ul class="vc_image"></ul><a href="#" class="vc_add-image"><i class="vc-composer-icon vc-c-icon-add"></i>%s</a>', esc_html__( 'Add image', 'js_composer' ) );

        return apply_filters( 'vc_css_editor_background_image_control', $value );
    }

    /**
     * @return string
     */
    public function getBorderRadiusOptions() {
        $radiuses = apply_filters( 'vc_css_editor_border_radius_options_data', array(
            '' => esc_html__( 'None', 'js_composer' ),
            '1px' => '1px',
            '2px' => '2px',
            '3px' => '3px',
            '4px' => '4px',
            '5px' => '5px',
            '10px' => '10px',
            '15px' => '15px',
            '20px' => '20px',
            '25px' => '25px',
            '30px' => '30px',
            '35px' => '35px',
        ) );

        $output = '';
        foreach ( $radiuses as $radius => $title ) {
            $output .= '<option value="' . $radius . '">' . $title . '</option>';
        }

        return $output;
    }

    /**
     * @return string
     */
    public function getBorderStyleOptions() {
        $output = '<option value="">' . esc_html__( 'Theme defaults', 'js_composer' ) . '</option>';
        $styles = apply_filters( 'vc_css_editor_border_style_options_data', array(
            esc_html__( 'solid', 'js_composer' ),
            esc_html__( 'dotted', 'js_composer' ),
            esc_html__( 'dashed', 'js_composer' ),
            esc_html__( 'none', 'js_composer' ),
            esc_html__( 'hidden', 'js_composer' ),
            esc_html__( 'double', 'js_composer' ),
            esc_html__( 'groove', 'js_composer' ),
            esc_html__( 'ridge', 'js_composer' ),
            esc_html__( 'inset', 'js_composer' ),
            esc_html__( 'outset', 'js_composer' ),
            esc_html__( 'initial', 'js_composer' ),
            esc_html__( 'inherit', 'js_composer' ),
        ) );
        foreach ( $styles as $style ) {
            $output .= '<option value="' . $style . '">' . ucfirst( $style ) . '</option>';
        }

        return $output;
    }

    /**
     * @return string
     */
    public function getBackgroundStyleOptions() {
        $output = '<option value="">' . esc_html__( 'Theme defaults', 'js_composer' ) . '</option>';
        $styles = apply_filters( 'vc_css_editor_background_style_options_data', array(
            esc_html__( 'Cover', 'js_composer' ) => 'cover',
            esc_html__( 'Contain', 'js_composer' ) => 'contain',
            esc_html__( 'No Repeat', 'js_composer' ) => 'no-repeat',
            esc_html__( 'Repeat', 'js_composer' ) => 'repeat',
        ) );
        foreach ( $styles as $name => $style ) {
            $output .= '<option value="' . $style . '">' . $name . '</option>';
        }

        return $output;
    }

    /**
     * @return string
     */
    public function onionLayout() {

        $output = '<div class="porto-layout-onion-tabs"><span data-width="desktop" class="active" data-size="100%"><i class="vc-composer-icon vc-c-icon-layout_large-desktop"></i><span>Default</span></span><span data-width="xxl" data-size="' . esc_attr( porto_get_xl_width(false) - 1 ) . '"><i class="vc-composer-icon vc-c-icon-layout_default"></i><span><=' . esc_attr( porto_get_xl_width(false) -1 ) . 'px</span></span><span data-width="xl" data-size="' . esc_attr( porto_get_xl_width() - 1 ) . '"><i class="vc-composer-icon vc-c-icon-layout_default"></i><span><=' . esc_attr( porto_get_xl_width() - 1 ) . 'px</span></span><span data-width="lg" title="<= 991px" data-size="991px"><i class="vc-composer-icon vc-c-icon-layout_portrait-tablets"></i><span><=991px</span></span><span data-width="md" data-size="767px"><i class="vc-composer-icon vc-c-icon-layout_landscape-smartphones"></i><span><=767px</span></span><span data-width="xs" data-size="575px"><i class="vc-composer-icon vc-c-icon-layout_portrait-smartphones"></i><span><=575px</span></span></div>';

        $output .= '<div class="porto-layout-tab-content">';
        // Desktop
        $output .= sprintf( '<div data-width="desktop" class="vc_layout-onion vc_col-xs-7 active"><div class="vc_margin">%s<div class="vc_border">%s<div class="vc_padding">%s<div class="vc_content"><i></i></div></div></div></div></div>', $this->layerControls( 'margin' ), $this->layerControls( 'border', 'width' ), $this->layerControls( 'padding' ) );

        // Width - xxl
        $output .= sprintf( '<div data-width="xxl" data-size="porto-xxl" class="vc_layout-onion vc_col-xs-7"><div class="vc_margin">%s<div class="vc_border">%s<div class="vc_padding">%s<div class="vc_content"><i></i></div></div></div></div></div>', $this->layerControls( 'margin', '', 'xxl' ), $this->layerControls( 'border', 'width', 'xxl' ), $this->layerControls( 'padding', '', 'xxl' ) );

        // Width - xl
        $output .= sprintf( '<div data-width="xl" data-size="porto-xl" class="vc_layout-onion vc_col-xs-7"><div class="vc_margin">%s<div class="vc_border">%s<div class="vc_padding">%s<div class="vc_content"><i></i></div></div></div></div></div>',$this->layerControls( 'margin', '', 'xl' ), $this->layerControls( 'border', 'width', 'xl' ), $this->layerControls( 'padding', '', 'xl' ) );

        // Width - lg
        $output .= sprintf( '<div data-width="lg" data-size="991" class="vc_layout-onion vc_col-xs-7"><div class="vc_margin">%s<div class="vc_border">%s<div class="vc_padding">%s<div class="vc_content"><i></i></div></div></div></div></div>', $this->layerControls( 'margin', '', 'lg' ), $this->layerControls( 'border', 'width', 'lg' ), $this->layerControls( 'padding', '','lg' ) );

        // Width - md
        $output .= sprintf( '<div data-width="md" data-size="767" class="vc_layout-onion vc_col-xs-7"><div class="vc_margin">%s<div class="vc_border">%s<div class="vc_padding">%s<div class="vc_content"><i></i></div></div></div></div></div>', $this->layerControls( 'margin', '', 'md' ), $this->layerControls( 'border', 'width', 'md' ), $this->layerControls( 'padding', '','md' ) );

        // Width - xs
        $output .= sprintf( '<div data-width="xs" data-size="575" class="vc_layout-onion vc_col-xs-7"><div class="vc_margin">%s<div class="vc_border">%s<div class="vc_padding">%s<div class="vc_content"><i></i></div></div></div></div></div>', $this->layerControls( 'margin', '','xs' ), $this->layerControls( 'border', 'width', 'xs' ), $this->layerControls( 'padding', '','xs' ) );
        $output .= '</div>';

        return apply_filters( 'vc_css_editor_onion_layout', $output );
    }

    /**
     * @param $name
     * @param string $prefix
     *
     * @return string
     */
    protected function layerControls( $name, $prefix = '', $media = '' ) {
        $output = '<label>' . esc_html( $name ) . '</label>';
        
        foreach ( $this->positions as $pos ) {
            $output .= sprintf( '<input type="text" name="%s%s_%s%s%s" data-name="%s%s-%s%s" class="vc_%s" placeholder="-" data-attribute="%s" value="" data-width="%s">', '' !== $media ? esc_attr( $this->setting( 'param_name' ) ) . '_' : '', esc_attr( $name ), esc_attr( $pos ), '' !== $prefix ? '_' . esc_attr( $prefix ) : '', '' !== $media ? '_' . esc_attr( $media ) : '', esc_attr( $name ), '' !== $prefix ? '-' . esc_attr( $prefix ) : '', esc_attr( $pos ), '' !== $media ? '-' . esc_attr( $media ) : '', esc_attr( $pos ) , esc_attr( $name ) . ( '' !== $media ? '-' . esc_attr( $media ) : '' ), esc_attr( $media ) );
        }

        return apply_filters( 'vc_css_editor_layer_controls', $output );
    }
}
