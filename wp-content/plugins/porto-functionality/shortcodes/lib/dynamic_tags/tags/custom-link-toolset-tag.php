<?php
/**
 * Porto Dynamic Toolset Tags Link class
 *
 * @author     P-THEMES
 * @version    2.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Porto_El_Custom_Link_Toolset_Tag extends Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'porto-custom-link-toolset';
	}

	public function get_title() {
		return esc_html__( 'Toolset Link', 'porto-functionality' );
	}

	public function get_group() {
		return Porto_El_Dynamic_Tags::PORTO_GROUP;
	}

	public function get_categories() {
		return array(
			Porto_El_Dynamic_Tags::URL_CATEGORY,
		);
	}

	public function register_advanced_section() {
		parent::register_advanced_section();

		$this->remove_control( 'before' );
		$this->remove_control( 'after' );
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
		//Add toolset link
		do_action( 'porto_dynamic_el_extra_fields', $this, 'link', 'toolset' );

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
		$option = 'dynamic_toolset_link';
		$key    = isset( $atts[ $option ] ) ? $atts[ $option ] : false;
		if ( empty( $key ) ) {
			return;
		}

		list( $field_group, $field_key ) = explode( ':', $key );

		$field = wpcf_admin_fields_get_field( $field_key );

		if ( $field && ! empty( $field['type'] ) ) {
			$ret = '';
			switch ( $field['type'] ) {
				case 'email':
					$ret = 'mailto:' . types_render_field( $field_key, [ 'output' => 'raw' ] );
					break;
				case 'image':
					$ret = types_render_field( $field_key, [ 'url' => true ] );
					break;
				default:
					$ret = types_render_field( $field_key, [ 'output' => 'raw' ] );
			} // End switch().
		}

		if ( empty( $ret ) && $this->get_settings( 'fallback' ) ) {
			$ret = $this->get_settings( 'fallback' );
		}

		/**
		 * Filters the content for dynamic extra links.
		 *
		 * @since 2.9.0
		 */
		// $ret = apply_filters( 'porto_dynamic_el_extra_fields_content', null, $atts, 'link' );

		echo porto_strip_script_tags( $ret );

		do_action( 'porto_dynamic_after_render' );
	}
}
