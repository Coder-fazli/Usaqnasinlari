<?php
/**
 * Toolset plugin compatibility for dynamic tags.
 *
 * @author     P-THEMES
 * @since      2.9.0
 */

defined( 'ABSPATH' ) || die;
if ( ! class_exists( 'Porto_Func_Toolset' ) ) {
	class Porto_Func_Toolset {

		protected static $instance = null;

		/**
		 * Constructor
		 *
		 * @since 2.9.0
		 */
		public function __construct() {
			add_filter( 'porto_gutenberg_editor_vars', array( $this, 'add_dynamic_field_vars' ) );
			if ( defined( 'ELEMENTOR_VERSION' ) ) {
				add_filter( 'porto_dynamic_el_tags', array( $this, 'toolset_add_tags' ) );
			}
		}
		/**
		 * @return Porto_Func_Toolset
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( empty( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function toolset_get_meta( $key ) {
			if ( ! $key ) {
				return null;
			}
			$post_id    = get_the_ID();
			$meta_value = get_post_meta( $post_id, $key, true );
			if ( class_exists( 'Types_Field_Gateway_Wordpress_Post' ) ) {
				$field_toolset = ( new Types_Field_Gateway_Wordpress_Post() )->get_field_by_id( substr( $key, 5 ) );
				if ( ! empty( $field_toolset['type'] ) && 'date' == $field_toolset['type'] ) {
					$meta_value = date_i18n( 'F j, Y', $meta_value );
				}
			}
			if ( ! $meta_value ) {
				return null;
			}
			return $meta_value;
		}

        /**
         * Toolset image mapping
         * 
         * @since 2.9.0
         */
        public function toolset_image_mapping( $field, $single = true ) {
            if ( 'image' !== $field['type'] ) {
                return false;
            }
    
            $limit = $single ? '0' : '1';
            if ( empty( $field['data'] ) || $limit !== $field['data']['repetitive'] ) {
                return false;
            }
    
            return true;
        }
    
		/**
		 * Returns support toolset types
		 *
		 * @return array
		 */
		public function get_toolset_types() {

			return array(
				'textfield'        => array( 'field' ),
				'colorpicker'      => array( 'field' ),
				'phone'            => array( 'field' ),
				'textarea'         => array( 'field' ),
				'checkbox'         => array( 'field' ),
				'select'           => array( 'field' ),
				'numeric'          => array( 'field' ),
				'email'            => array( 'field' ),
				'embed'            => array( 'field' ),
				'google_address'   => array( 'field' ),
				'wysiwyg'          => array( 'field' ),
				'radio'            => array( 'field' ),
				'url'              => array( 'link' ),
				'image'            => array( 'image' ),
				'video'            => array( 'image' ),
				'audio'            => array( 'image' ),
				'file'             => array( 'image' ),
				'date'             => array( 'field' ),
			);

		}

        /**
         * Valid field type
         * 
         * @since 2.9.0
         */
        public function valid_field_type( $type, $field ) {
            // Only file field with single image value
            if ( 'image' == $type && $this->toolset_image_mapping( $field ) ) {
                return true;
            }
    
            // Only file with multiple images allowed
            if ( 'gallery' == $type && $this->toolset_image_mapping( $field, false ) ) {
                return true;
            }
    
            // Any other type
            if ( isset( $this->get_toolset_types()[ $field['type'] ] ) && in_array( $type, $this->get_toolset_types()[ $field['type'] ] ) ) {
                return true;
            }
    
            return false;
        }

		/**
		 * Retrieve Toolset Field groups
		 *
		 * @return array
		 * @since 2.9.0
		 */
		public function get_toolset_groups( $widget ) {
			if ( is_404() ) {
				return;
			}

			$toolset_groups = array();
			if ( function_exists( 'wpcf_admin_fields_get_groups' ) ) {
				global $post;
				if ( $post && PortoBuilders::BUILDER_SLUG == get_post_type( $post ) && 'type' == get_post_meta( $post->ID, PortoBuilders::BUILDER_TAXONOMY_SLUG, true ) ) {
					$content_type = get_post_meta( $post->ID, 'content_type', true );
					if ( 'term' == $content_type ) {
						$term = get_post_meta( $post->ID, 'content_type_term', true );
						if ( $term ) {
							$toolset_groups = wpcf_admin_fields_get_groups_by_term( $term );
						}
					}
				}
                
				if ( $post && empty( $toolset_groups ) ) {
					$toolset_groups = wpcf_admin_get_groups_by_post_type( $post->post_type );
				}
			}
			$data = array();
			
			foreach ( $toolset_groups as $group ) {

				if ( function_exists( 'wpcf_admin_fields_get_fields_by_group' ) ) {
					$fields = wpcf_admin_fields_get_fields_by_group( $group['id'] );
				} else {
					$fields = array();
				}
				
				if ( empty( $fields ) ) {
					continue;
				}
				$options = array();

				foreach ( $fields as $field_key => $field ) {
                    if ( ! is_array( $field ) || empty( $field['type'] ) ) {
                        continue;
                    }
    
                    if ( ! $this->valid_field_type( $widget, $field ) ) {
                        continue;
                    }

					$key             = $group['slug'] . ':' . $field_key;
					$options[ $key ] = array(
						'type'  => $field['type'],
						'label' => $field['name'],
					);
					
				}
				
				if ( empty( $options ) ) {
					continue;
				}

				$data[] = array(
					'label'   => $group['name'],
					'options' => $options,
				);
			}

			return $data;

		}

		/**
		 * Retrieve Toolset meta fields
		 *
		 * @return array
		 * @since 2.9.0
		 */
		public function add_dynamic_field_vars( $block_vars = array() ) {
			foreach ( Porto_Func_Dynamic_Tags_Content::get_instance()->features as $field_type ) {
				$meta_fields = array();
				$group_data  = $this->get_toolset_groups( $field_type );

				if ( empty( $group_data ) ) {
					continue;
				}

				foreach ( $group_data as $data ) {
					$field     = array();
					$data_temp = $data['options'];

					foreach ( $data_temp as $key => $value ) {
						$field[ $key ] = isset( $value['label'] ) ? $value['label'] : '';
					}

					$field = array_filter( $field );

					$meta_fields[] = array(
						'label'   => $data['label'],
						'options' => $field,
					);
				}

				if ( ! isset( $block_vars['toolset'] ) ) {
					$block_vars['toolset'] = array();
				}
				$block_vars['toolset'][ $field_type ] = $meta_fields;
			}
			return $block_vars;
		}
		/**
		 * Add Dynamic Toolset Tags
		 *
		 * @since 2.9.0
		 */
		public function toolset_add_tags( $tags ) {
			array_push( $tags, 'Porto_El_Custom_Field_Toolset_Tag', 'Porto_El_Custom_Link_Toolset_Tag', 'Porto_El_Custom_Image_Toolset_Tag' );
			return $tags;
		}
	}

	Porto_Func_Toolset::get_instance();
}
