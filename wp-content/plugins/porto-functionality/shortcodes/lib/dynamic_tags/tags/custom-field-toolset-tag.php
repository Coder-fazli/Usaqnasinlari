<?php
/**
 * Porto Dynamic Toolset Tags class
 *
 * @author     P-THEMES
 * @version    2.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Porto_El_Custom_Field_Toolset_Tag extends Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'porto-custom-field-toolset';
	}

	public function get_title() {
		return esc_html__( 'Toolset', 'porto-functionality' );
	}

	public function get_group() {
		return Porto_El_Dynamic_Tags::PORTO_GROUP;
	}

	public function get_categories() {
		return array(
			Porto_El_Dynamic_Tags::TEXT_CATEGORY,
			Porto_El_Dynamic_Tags::NUMBER_CATEGORY,
			Porto_El_Dynamic_Tags::POST_META_CATEGORY,
			Porto_El_Dynamic_Tags::COLOR_CATEGORY,
		);
	}

	protected function register_controls() {

		$this->add_control(
			'dynamic_field_source',
			array(
				'label'   => esc_html__( 'Source', 'porto-functionality' ),
				'type'    => Elementor\Controls_Manager::HIDDEN,
				'default' => 'toolset',
			)
		);
		/**
		 * Fires before set current post type.
		 *
		 * @since 2.9.0
		 */
		do_action( 'porto_dynamic_before_render' );
		//Add toolset field
		do_action( 'porto_dynamic_el_extra_fields', $this, 'field', 'toolset' );

		do_action( 'porto_dynamic_after_render' );

	}
	public function is_settings_required() {
		return true;
	}

	public function render() {
		if ( is_404() ) {
			return;
		}
		// Toolset Embedded version loads its bootstrap later
		if ( ! function_exists( 'types_render_field' ) ) {
			return;
		}

		/**
		 * Fires before set current post type.
		 *
		 * @since 2.9.0
		 */
		do_action( 'porto_dynamic_before_render' );

		$post_id = get_the_ID();
		$atts    = $this->get_settings();
		$ret     = '';

		$option = 'dynamic_toolset_field';
		$key    = isset( $atts[ $option ] ) ? $atts[ $option ] : false;
		if ( empty( $key ) ) {
			return;
		}

		list( $field_group, $field_key ) = explode( ':', $key );

		$field = wpcf_admin_fields_get_field( $field_key );

		if ( $field && ! empty( $field['type'] ) ) {
			$ret = '';
			switch ( $field['type'] ) {
				case 'google_address':
					$ret = types_render_field( $field_key, [ 'format' => 'FIELD_ADDRESS' ] );
					break;
				case 'email':
				case 'embed':
					$ret = types_render_field( $field_key, [ 'output' => 'raw' ] );
					break;
				case 'date':
					$timestamp = types_render_field( $field_key, [
						'output' => 'raw',
						'style' => 'text',
					] );
					if ( empty( $timestamp ) ) {
						$ret = '';
					} else {
						$ret = date_i18n( 'F j, Y', $timestamp );
					}
					break;
				default:
					$ret = types_render_field( $field_key );
					break;
			} // End switch().
		} else {
			// Field settings has been deleted or not available.
			$ret = types_render_field( $field_key );
		} // End if().

		/**
		 * Filters the content for dynamic extra fields.
		 *
		 * @since 2.9.0
		 */
		// $ret = apply_filters( 'porto_dynamic_el_extra_fields_content', null, $atts, 'field' );

		echo porto_strip_script_tags( $ret );

		do_action( 'porto_dynamic_after_render' );
	}
}
