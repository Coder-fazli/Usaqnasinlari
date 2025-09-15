<?php

/**
 * Initialize Porto Elementor Page Builder
 *
 * @since 1.6.0
 */
use Elementor\Plugin;
if ( ! class_exists( 'Porto_Elementor_Init' ) ) :

	class Porto_Elementor_Init {

		private $widgets = array(
			'blog',
			'portfolio',
			'ultimate_heading',
			'info_box',
			'recent_posts',
			'stat_counter',
			'button',
			'modal',
			'sidebar_menu',
			'members',
			'recent_members',
			'pricing_table',
			'recent_portfolios',
			'circular_bar',
			'events',
			'fancytext',
			'countdown',
			'faqs',
			'google_map',
			'portfolios_category',
			'hotspot',
			'floating',
			'page_header',
			'social_icons',
			'image_comparison',
			'image_gallery',
			'360degree_image_viewer',
			'steps',
			'sticky_nav',
			'posts_grid',
			'scroll_progress',
			'contact_form',
			'cursor_effect',
			'tag_cloud',
			/* 6.6.0 */
			'content_switcher',
		);

		private $woo_widgets = array(
			'products',
			'product_categories',
			'one_page_category_products',
			'products_filter',
			'archive_category',
		);

		private $porto_metas = array(
			'porto_default',
			'porto_layout',
			'porto_sidebar',
			'porto_sidebar2',
			'porto_header_type',
			'porto_header_side_pos',
			'porto_header_side_width',
			'porto_disable_sticky_sidebar',
			'porto_container',
			'porto_custom_css',
			'porto_custom_js_body',
		);

		/**
		 * The FlexBox Container Elements with El 3.6.4
		 * 
		 * @since 2.5.0
		 */
		private $container_elements = array(
			'slider',
		);

		/**
		 * Determines whether elementor flexbox feature option is activated.
		 * 
		 * @since 2.5.0
		 */
		public $is_flexbox_container = false;

		/**
		 * Register Elementor Widgets
		 *
		 * @since 2.3.0 Added the link of 'go to template' about porto builders including header, footer in elementor preview editor.
		 */
		public function __construct() {
			if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
				return;
			}
			if ( ! apply_filters( 'porto_legacy_mode', true ) ) { // if soft mode
				$this->widgets     = array_diff( $this->widgets, array( 'blog', 'portfolio', 'recent_posts', 'members', 'recent_members', 'recent_portfolios', 'events', 'portfolios_category' ) );
				$this->woo_widgets = array_diff( $this->woo_widgets, array( 'products', 'product_categories' ) );
			}
			// Include Partials
			// Mouse parallax
			include_once 'partials/addon.php';


			add_action( 'elementor/editor/footer', function() {
				ob_start();
				?>
				<script type="text/template" id="tmpl-porto-elementor-studio-notice">
					<a href="#" id="porto-panel-studio" class="elementor-button elementor-button-default"><i class="porto-icon-studio" aria-hidden="true"></i><?php esc_html_e( 'Porto Studio', 'porto-functionality' ); ?></a>
				</script>
				<?php
				echo ob_get_clean();
			} );
			// register categories
			add_action(
				'elementor/elements/categories_registered',
				function( $self ) {
					$self->add_category(
						'porto-elements',
						array(
							'title'  => esc_html__( 'Porto', 'porto-functionality' ),
							'active' => true,
						)
					);
				}
			);

			// register custom section element
			add_action(
				'elementor/elements/elements_registered',
				function() {
					include_once dirname( PORTO_META_BOXES_PATH ) . '/elementor/tabs/porto-elementor-custom-tabs.php';

					include_once dirname( PORTO_META_BOXES_PATH ) . '/elementor/elements/porto_section.php';
					Plugin::$instance->elements_manager->unregister_element_type( 'section' );
					Plugin::$instance->elements_manager->register_element_type( new Porto_Elementor_Section() );

					include_once dirname( PORTO_META_BOXES_PATH ) . '/elementor/elements/porto_column.php';
					Plugin::$instance->elements_manager->unregister_element_type( 'column' );
					Plugin::$instance->elements_manager->register_element_type( new Porto_Elementor_Column() );

					// Flexbox Container
					// $this->is_flexbox_container = Plugin::$instance->experiments->is_feature_active( 'container' );
					// if ( $this->is_flexbox_container ) {
					// 	foreach ( $this->container_elements as $element ) {
					// 		$name = $element;
		
					// 		if ( false !== strpos( $name, '_content' ) ) {
					// 			$name = str_replace( '_content', '', $name );
					// 		}
					// 		include_once dirname( PORTO_META_BOXES_PATH ) . '/elementor/flexbox/' . $name . '/' . str_replace( '_', '-', $element ) . '.php';
					// 		$class_name = 'Porto_Elementor_' . ucwords( str_replace( '-', '_', $element ), '_' ) . '_Widget';
					// 		Plugin::$instance->elements_manager->register_element_type( new $class_name() );
					// 	}
					// }
					if ( Plugin::$instance->experiments->is_feature_active( 'container' ) ) {
						include_once dirname( PORTO_META_BOXES_PATH ) . '/elementor/elements/porto_container.php';
						Plugin::$instance->elements_manager->unregister_element_type( 'container' );
						Plugin::$instance->elements_manager->register_element_type( new Porto_Elementor_Container() );
					}
				}
			);
			// Add elements to registered widget types
			// add_action( 'elementor/document/config', function( $config, $id  ) {
			// 	if ( ! empty( $this->container_elements ) && $this->is_flexbox_container ) {
			// 		foreach ( $this->container_elements as $key ) {
			// 				$config['widgets'][ $key ] = Plugin::$instance->elements_manager->get_element_types( $key )->get_config();
			// 		}
			// 	}
			// 	return $config;
			// }, 10, 2 );
			// register porto widgets
			add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ), 10, 1 );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_elementor_widgets_js' ), 1008 );

			// register custom controls
			add_action( 'elementor/controls/register', array( $this, 'register_custom_control' ), 10, 1 );
			
			// Animation for Reveal with Color 
			add_filter( 'elementor/controls/animations/additional_animations', array( $this, 'elementor_add_animation' ), 99, 1 );
			add_action( 'elementor/element/after_add_attributes', array( $this, 'add_render_attributes' ) );

			// register rest apis
			require_once( dirname( PORTO_META_BOXES_PATH ) . '/elementor/restapi/ajaxselect2.php' );

			if ( is_admin() ) {
				add_action(
					'elementor/editor/after_enqueue_scripts',
					function() {

						$admin_vars = array(
							'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
							'nonce'    => wp_create_nonce( 'porto-elementor-nonce' ),
						);

						// Critical, Merged, Shortcode optimized
						global $porto_settings_optimize;
						$alert_model = false;
						if ( ! empty( $porto_settings_optimize['shortcodes_to_remove'] ) ) {
							$both_option = true;
							$alert_model = 'shortcode';
						}
						if ( ! empty( $porto_settings_optimize['critical_css'] ) || ! empty( $porto_settings_optimize['merge_stylesheets'] ) ) {
							if ( 'shortcode' == $alert_model ) {
								$alert_model = 'both';
							} else {
								$alert_model = 'critical';
							}
						}
						if ( $alert_model ) {
							$admin_vars['optimize_page_shortcode'] = esc_url( admin_url( 'admin.php?page=porto-speed-optimize-wizard&step=shortcodes' ) );
							$admin_vars['optimize_page_advanced']  = esc_url( admin_url( 'admin.php?page=porto-speed-optimize-wizard&step=advanced' ) );
							$admin_vars['optimize_page']           = esc_url( admin_url( 'admin.php?page=porto-speed-optimize-wizard' ) );
						}
						$admin_vars['alert_model'] = $alert_model;
						if ( defined( 'PORTO_VERSION' ) ) {
							wp_enqueue_style( 'font-awesome', PORTO_CSS . '/font-awesome.min.css', false, PORTO_VERSION, 'all' );
						}
						wp_enqueue_script( 'porto-elementor-admin', plugin_dir_url( __FILE__ ) . 'assets/admin.js', array( 'porto-admin' ), PORTO_FUNC_VERSION, true );
						wp_localize_script( 'porto-elementor-admin', 'porto_elementor_vars', $admin_vars );
					}
				);

				// update default colors in color picker
				add_filter(
					'elementor/editor/localize_settings',
					function( $config ) {
						global $porto_settings;
						if ( ! get_option( 'elementor_disable_color_schemes', false ) || empty( $porto_settings ) || empty( $porto_settings['skin-color'] ) ) {
							return $config;
						}
						try {
							if ( isset( $config['schemes'] ) && ! empty( $config['schemes']['items']['color-picker'] ) ) {
								$default_colors             = $config['schemes']['items']['color-picker']['items'];
								$default_colors[1]['value'] = esc_js( $porto_settings['skin-color'] );
								if ( isset( $porto_settings['secondary-color'] ) ) {
									$default_colors[2]['value'] = esc_js( $porto_settings['secondary-color'] );
								}
								if ( isset( $porto_settings['tertiary-color'] ) ) {
									$default_colors[3]['value'] = esc_js( $porto_settings['tertiary-color'] );
								}
								if ( isset( $porto_settings['quaternary-color'] ) ) {
									$default_colors[4]['value'] = esc_js( $porto_settings['quaternary-color'] );
								}
								$default_colors[5]['value'] = ! empty( $porto_settings['body-font']['color'] ) ? esc_js( $porto_settings['body-font']['color'] ) : '#777';
								if ( ! empty( $porto_settings['h2-font']['color'] ) ) {
									$default_colors[6]['value'] = esc_js( $porto_settings['h2-font']['color'] );
								}
								if ( isset( $porto_settings['dark-color'] ) ) {
									$default_colors[7]['value'] = esc_js( $porto_settings['dark-color'] );
								}
								if ( isset( $porto_settings['light-color'] ) ) {
									$default_colors[8]['value'] = esc_js( $porto_settings['light-color'] );
								}
								$config['schemes']['items']['color-picker']['items'] = $default_colors;
							}
						} catch ( Exception $e ) {
						}
						return $config;
					}
				);

			}

			add_action(
				'elementor/documents/register_controls',
				function( $document ) {
					if ( ! $document instanceof Elementor\Core\DocumentTypes\PageBase && ! $document instanceof Elementor\Modules\Library\Documents\Page ) {
						return;
					}

					$document->start_controls_section(
						'porto_settings',
						array(
							'label' => __( 'Porto Settings', 'porto-functionality' ),
							'tab'   => Elementor\Controls_Manager::TAB_SETTINGS,
						)
					);

					$document->add_control(
						'porto_settings_apply',
						array(
							'type'        => Elementor\Controls_Manager::BUTTON,
							'label'       => __( 'Update changes to page', 'porto-functionality' ),
							'text'        => __( 'Apply', 'porto-functionality' ),
							'button_type' => 'default porto-elementor-btn-reload elementor-button-success',
						)
					);

					if ( 'porto_builder' == $document->get_post()->post_type && $document->get_post()->ID ) {
						$builder_type = get_post_meta( $document->get_post()->ID, 'porto_builder_type', true );
						if ( 'header' == $builder_type ) {
							$document->add_control(
								'porto_header_type',
								array(
									'type'        => Elementor\Controls_Manager::SELECT,
									'label'       => __( 'Header Type', 'porto-functionality' ),
									'description' => sprintf( __( 'After changed, you should save theme options in the redux panel. You can change %1$sSide Header Position%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'header-side-position' ) . '" target="_blank">', '</a>' ),
									'separator'   => 'before',
									'options'     => array(
										'default'  => __( 'Default', 'porto-functionality' ),
										'absolute' => __( 'Absolute(Fixed) Header', 'porto-functionality' ),
										'side'     => __( 'Side Header', 'porto-functionality' ),
									),
								)
							);
							$document->add_control(
								'porto_header_side_pos',
								array(
									'type'        => Elementor\Controls_Manager::SELECT,
									'label'       => __( 'Side Header Position', 'porto-functionality' ),
									'options'     => array(
										''      => __( 'Left (Right on RTL)', 'porto-functionality' ),
										'right' => __( 'Right (Left on RTL)', 'porto-functionality' ),
									),
									'condition' => array(
										'porto_header_type' => 'side',
									),
								)
							);
							$document->add_control(
								'porto_header_side_width',
								array(
									'type'        => Elementor\Controls_Manager::NUMBER,
									'label'       => __( 'Side Header Width (px)', 'porto-functionality' ),
									'placeholder' => '255',
									'condition' => array(
										'porto_header_type' => 'side',
									),
								)
							);
						} elseif ( 'product' == $builder_type ) {
							$document->add_control(
								'porto_disable_sticky_sidebar',
								array(
									'type'  => Elementor\Controls_Manager::SWITCHER,
									'label' => __( 'Disable Sticky Sidebar', 'porto-functionality' ),
									'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'sticky-sidebar' ) . '" target="_blank">', '</a>' ),
								)
							);
						}
					}

					if ( 'porto_builder' == $document->get_post()->post_type ) {
						$document->add_control(
							'porto_container',
							array(
								'type'         => Elementor\Controls_Manager::SELECT,
								'label'        => __( 'Wrap as Container', 'porto-functionality' ),
								'default'      => 'no',
								'save_default' => true,
								'options'      => array(
									'no'    => __( 'No Wrapper', 'porto-functionality' ),
									'yes'   => __( 'Inner Container', 'porto-functionality' ),
									'fluid' => __( 'Fluid Container', 'porto-functionality' ),
								),
							)
						);
					} else {

						$document->add_control(
							'porto_default',
							array(
								'type'        => Elementor\Controls_Manager::SWITCHER,
								'label'       => __( 'Page Layout', 'porto-functionality' ),
								'description' => __( 'Use selected layout and sidebar options.', 'porto-functionality' ),
							)
						);

						$document->add_control(
							'porto_layout',
							array(
								'type'      => Elementor\Controls_Manager::SELECT,
								'label'     => __( 'Layout', 'porto-functionality' ),
								'options'   => porto_ct_layouts(),
								'condition' => array(
									'porto_default' => 'yes',
								),
							)
						);

						$document->add_control(
							'porto_sidebar',
							array(
								'type'        => Elementor\Controls_Manager::SELECT,
								'label'       => __( 'Sidebar', 'porto-functionality' ),
								'description' => __( '<strong>Note</strong>: You can create the sidebar under <strong>Appearance > Sidebars</strong>', 'porto-functionality' ),
								'options'     => porto_ct_sidebars(),
								'default'     => '',
								'condition'   => array(
									'porto_default' => 'yes',
									'porto_layout!' => array( 'widewidth', 'fullwidth' ),
								),
							)
						);

						$document->add_control(
							'porto_sidebar2',
							array(
								'type'        => Elementor\Controls_Manager::SELECT,
								'label'       => __( 'Sidebar 2', 'porto-functionality' ),
								'description' => __( '<strong>Note</strong>: You can create the sidebar under <strong>Appearance > Sidebars</strong>', 'porto-functionality' ),
								'options'     => porto_ct_sidebars(),
								'default'     => '',
								'condition'   => array(
									'porto_default' => 'yes',
									'porto_layout'  => array( 'wide-both-sidebar', 'both-sidebar' ),
								),
							)
						);
					}

					$document->add_control(
						'porto_custom_css',
						array(
							'type'  => Elementor\Controls_Manager::TEXTAREA,
							'rows'  => 20,
							'label' => __( 'Custom CSS', 'porto-functionality' ),
							'description'  => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'css-code' ) . '" target="_blank">', '</a>' ),
						)
					);

					$document->end_controls_section();

					// Porto Editor Area
					if ( 'porto_builder' == $document->get_post()->post_type ) {
						$document->start_controls_section(
							'porto_edit_area',
							array(
								'label' => esc_html__( 'Porto Editor Area', 'porto-functionality' ),
								'tab'   => Elementor\Controls_Manager::TAB_SETTINGS,
							)
						);

							$document->add_control(
								'porto_edit_area_width',
								array(
									'label'       => esc_html__( 'Edit Area Width', 'porto-functionality' ),
									'description' => esc_html__( "Control edit area width for this template's usage.", 'porto-functionality' ),
									'type'        => Elementor\Controls_Manager::SLIDER,
									'size_units'  => array(
										'px',
										'%',
										'vw',
									),
									'range'       => array(
										'px' => array(
											'step' => 1,
											'min'  => 100,
											'max'  => 500,
										),
										'%'  => array(
											'step' => 1,
											'min'  => 0,
											'max'  => 100,
										),
										'vw' => array(
											'step' => 1,
											'min'  => 0,
											'max'  => 100,
										),
									),
									'separator'   => 'after',
								)
							);

						$document->end_controls_section();
					}

					if ( 'porto_builder' == $document->get_post()->post_type && $document->get_post()->ID && 'popup' == get_post_meta( $document->get_post()->ID, 'porto_builder_type', true ) ) {

						$document->start_controls_section(
							'porto_popup_settings',
							array(
								'label' => esc_html__( 'Porto Popup Settings', 'porto-functionality' ),
								'tab'   => Elementor\Controls_Manager::TAB_SETTINGS,
							)
						);
						$document->add_control(
							'popup_width',
							array(
								'type'    => Elementor\Controls_Manager::NUMBER,
								'label'   => esc_html__( 'Popup Width (px)', 'porto-functionality' ),
								'default' => 740,
							)
						);

						$document->add_control(
							'popup_animation',
							array(
								'type'    => Elementor\Controls_Manager::SELECT,
								'label'   => esc_html__( 'Popup Animation', 'porto-functionality' ),
								'options' => array(
									'mfp-fade'       => __( 'Fade', 'porto-functionality' ),
									'my-mfp-zoom-in' => __( 'Zoom in', 'porto-functionality' ),
								),
								'default' => 'mfp-fade',
							)
						);

						$document->add_control(
							'enable_exit_intent',
							array(
								'type'        => Elementor\Controls_Manager::SWITCHER,
								'label'       => __( 'Exit Intent Popup', 'porto-functionality' ),
								'description' => __( 'Use as Exit Intent Popup', 'porto-functionality' ),
							)
						);

						$document->add_control(
							'load_duration',
							array(
								'type'    => Elementor\Controls_Manager::NUMBER,
								'label'   => esc_html__( 'Load Duration (ms)', 'porto-functionality' ),
								'default' => 4000,
								'condition' => array(
									'enable_exit_intent' => '',
								),
							)
						);

						$document->add_control(
							'popup_pos_horizontal',
							array(
								'label'   => esc_html__( 'Horizontal Offset (%)', 'porto-functionality' ),
								'type'    => Elementor\Controls_Manager::NUMBER,
								'default' => 50,
							)
						);
						$document->add_control(
							'popup_pos_vertical',
							array(
								'label'   => esc_html__( 'Vertical Offset (%)', 'porto-functionality' ),
								'type'    => Elementor\Controls_Manager::NUMBER,
								'default' => 50,
							)
						);
						$document->add_control(
							'disable_popup_period',
							array(
								'type'        => Elementor\Controls_Manager::SELECT,
								'label'       => __( 'Popup Disable Period', 'porto-functionality' ),
								'options'     => array(
									'0' => __( 'Select', 'porto-functionality' ),
									'1' => __( 'Day', 'porto-functionality' ),
									'7' => __( 'Week', 'porto-functionality' ),
								),
								'default'     => '0',
								'description' => esc_html__( 'The popup will only appear once during the selected period.', 'porto-functionality' ),
							)
						);
						$document->end_controls_section();
					}

					/** 
					 * Remove default elementor page template
					 * 
					 * @since 2.6.0
					 */
					$el_templates = Plugin::$instance->modules_manager->get_modules( 'page-templates' );
					if ( $el_templates ) {
						remove_action( 'elementor/documents/register_controls', [ $el_templates, 'action_register_template_control' ] );
						$my_theme = wp_get_theme();
						$my_post_type = $document->get_main_post()->post_type;
						$my_template = array();
						$my_template = apply_filters( 'theme_templates', $my_template, $my_theme, null, $my_post_type );
						$my_template = apply_filters( "theme_{$my_post_type}_templates", $my_template, $my_theme, null, $my_post_type );
						$document->start_injection( [
							'of' => 'post_status',
							'fallback' => [
								'of' => 'post_title',
							],
						] );
				
						$control_options = [
							'options' => $my_template,
						];
				
						$el_templates->add_template_controls( $document, 'template', $control_options );
				
						$document->end_injection();
					}
				},
				2
			);

			// Force generate elementor block css
			if ( wp_doing_ajax() ) {
				add_action(
					'elementor/document/before_save',
					function( $self, $data ) {
						if ( empty( $data['settings'] ) || empty( $_REQUEST['editor_post_id'] ) ) {
							return;
						}

						$is_imported = false;
						$post_id     = absint( $_REQUEST['editor_post_id'] );
						foreach ( $this->porto_metas as $meta ) {
							if ( ! empty( $data['settings'][ $meta ] ) ) {
								$is_imported = true;
								$val         = porto_strip_script_tags( $data['settings'][ $meta ] );
								if ( 'porto_default' == $meta && 'yes' == $val ) {
									$val = 'default';
								} elseif ( 'porto_disable_sticky_sidebar' == $meta && 'yes' == $val ) {
									$val = 'disable_sticky_sidebar';
								}

								update_post_meta( $post_id, str_replace( 'porto_', '', $meta ), wp_slash( $val ) );
							} else {
								delete_post_meta( $post_id, str_replace( 'porto_', '', $meta ) );
							}
						}

						// Popup
						if ( isset( $post_id ) && 'popup' == get_post_meta( $post_id, 'porto_builder_type', true ) ) {
							$popup_options                  = array();
							$popup_options['width']         = wp_slash( '' != $data['settings']['popup_width'] ? $data['settings']['popup_width'] : 740 );
							$popup_options['animation']     = ! empty( $data['settings']['popup_animation'] ) ? wp_slash( $data['settings']['popup_animation'] ) : 'mfp-fade';
							$popup_options['load_duration'] = wp_slash( '' != $data['settings']['load_duration'] ? $data['settings']['load_duration'] : 4000 );

							$popup_options['horizontal'] = wp_slash( isset( $data['settings']['popup_pos_horizontal'] ) ? $data['settings']['popup_pos_horizontal'] : 50 );
							$popup_options['vertical']   = wp_slash( isset( $data['settings']['popup_pos_vertical'] ) ? $data['settings']['popup_pos_vertical'] : 50 );

							if ( ! empty( $data['settings']['enable_exit_intent'] ) ) {
								$popup_options['enable_exit_intent'] = $data['settings']['enable_exit_intent'];
							}

							if ( ! empty( $data['settings']['disable_popup_period'] ) ) {
								$popup_options['popup_period'] = $data['settings']['disable_popup_period'];
							}
							if ( empty( $popup_options ) ) {
								delete_post_meta( $post_id, 'popup_options' );
							} else {
								update_post_meta( $post_id, 'popup_options', wp_slash( $popup_options ) );
							}
						}
					},
					10,
					2
				);
				add_action(
					'elementor/document/after_save',
					function( $self, $data ) {
						if ( empty( $_REQUEST['editor_post_id'] ) ) {
							return;
						}
						$post_id = absint( $_REQUEST['editor_post_id'] );

						// save used blocks
						if ( ! empty( $data['elements'] ) ) {
							// check breadcrumbs element
							$elements_str = json_encode( $data['elements'] );
							preg_match( '/"breadcrumbs_type":"([^"]*)"/', $elements_str, $matches );
							if ( ! empty( $matches ) && isset( $matches[1] ) ) {
								update_post_meta( $post_id, 'porto_page_header_shortcode_type', (int) $matches[1] );
							} else {
								delete_post_meta( $post_id, 'porto_page_header_shortcode_type' );
							}
							// end check breadcrumbs element

							$block_slugs = $this->get_elementor_object_by_id( $data['elements'] );
							$used_blocks = get_theme_mod( '_used_blocks', array() );
							if ( ! isset( $used_blocks['el'] ) ) {
								$used_blocks['el'] = array();
							}
							if ( ! isset( $used_blocks['el']['post_c'] ) ) {
								$used_blocks['el']['post_c'] = array();
							}
							if ( ! empty( $block_slugs ) ) {
								$used_blocks['el']['post_c'][ $post_id ] = array_map( 'intval', $block_slugs );
							} else {
								unset( $used_blocks['el']['post_c'][ $post_id ] );
							}
							set_theme_mod( '_used_blocks', $used_blocks );
						}

						if ( current_user_can( 'unfiltered_html' ) || empty( $data['settings'] ) || empty( $_REQUEST['editor_post_id'] ) ) {
							return;
						}

						if ( ! empty( $data['settings']['porto_custom_css'] ) ) {
							$elementor_settings = get_post_meta( $post_id, '_elementor_page_settings', true );
							if ( is_array( $elementor_settings ) ) {
								$elementor_settings['porto_custom_css'] = porto_strip_script_tags( get_post_meta( $post_id, 'custom_css', true ) );
								update_post_meta( $post_id, '_elementor_page_settings', $elementor_settings );
							}
						}
					},
					10,
					2
				);
				add_action( 'save_post', array( $this, 'generate_block_temp_css_onsave' ), 99, 2 );
				add_action( 'elementor/document/after_save', array( $this, 'rename_block_temp_css_onsave' ), 99, 2 );
				if ( ! empty( $_REQUEST['action'] ) && 'elementor_clear_cache' == $_REQUEST['action'] ) {
					add_action( 'elementor/core/files/clear_cache', array( $this, 'generate_blocks_css_after_clear_cache' ) );
				}
			}

			add_filter(
				'elementor/document/config',
				function( $config, $post_id ) {
					if ( empty( $config ) ) {
						$config = array();
					}
					if ( ! isset( $config['settings'] ) ) {
						$config['settings'] = array();
					}
					if ( ! isset( $config['settings']['settings'] ) ) {
						$config['settings']['settings'] = array();
					}
					$bt_type = get_post_meta( $post_id, 'porto_builder_type', true );
					foreach ( $this->porto_metas as $meta ) {
						$val = get_post_meta( $post_id, str_replace( 'porto_', '', $meta ), true );
						if ( 'porto_default' == $meta && 'default' == $val ) {
							$val = 'yes';
						} elseif ( 'porto_disable_sticky_sidebar' == $meta && 'disable_sticky_sidebar' == $val ) {
							$val = 'yes';
						} elseif ( 'porto_container' == $meta && '' == $val ) {
							$val = 'no';
							if ( 'header' == $bt_type || 'footer' == $bt_type ) {
								$val = 'fluid';
							}
						}
						$config['settings']['settings'][ $meta ] = $val;
					}
					return $config;
				},
				10,
				2
			);

			add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_porto_icons' ), 10, 1 );

			// Add extra editor preview iframe css
			add_action(
				'wp_enqueue_scripts',
				function() {
					if ( isset( $_REQUEST['elementor-preview'] ) ) {
						if ( ! defined( 'ELEMENTOR_PRO_VERSION' )  ) {
							wp_enqueue_style( 'porto-editor-preview-without-pro', plugin_dir_url( __FILE__ ) . '/assets/preview-without-pro.css', array( 'editor-preview' ), PORTO_FUNC_VERSION );
						}
						wp_enqueue_style( 'porto-editor-preview', plugin_dir_url( __FILE__ ) . '/assets/preview.css', array( 'editor-preview' ), PORTO_FUNC_VERSION );
						wp_enqueue_style( 'wp-block-columns' );
					}
				}
			);
			
			// Add custom fonts
			$custom_fonts = get_option( 'porto_custom_fonts', array() );
			if ( ! empty( $custom_fonts ) ) {
				$fonts = array();
				foreach ( $custom_fonts as $c_fonts ) {
					if ( ! empty( $c_fonts ) ) {
						foreach ( $c_fonts as $c_font_name => $font_fields ) {
							$fonts[] = str_replace( '+', ' ', $c_font_name );
						}
					}
				}
				if ( ! empty( $fonts ) ) {
					add_filter(
						'elementor/fonts/groups',
						function( $font_groups ) {
							$font_groups['custom'] = esc_html__( 'Custom', 'porto-functionality' );
							return $font_groups;
						}
					);

					add_filter(
						'elementor/fonts/additional_fonts',
						function( $additional_fonts ) use ( $fonts ) {
							foreach ( $fonts as $c_font ) {
								$additional_fonts[ $c_font ] = 'custom';
							}
							return $additional_fonts;
						}
					);
				}
			}

		}

		// Register Elementor widgets
		public function register_elementor_widgets( $self ) {
			include_once dirname( PORTO_META_BOXES_PATH ) . '/elementor/tabs/porto-elementor-custom-tabs.php';
			$self->unregister( 'common' );
			include_once dirname( PORTO_META_BOXES_PATH ) . '/elementor/widgets/common.php';
			$self->register( new Porto_Elementor_Common_Widget( array(), array( 'widget_name' => 'common' ) ) );
			foreach ( $this->widgets as $widget ) {
				if ( 'portfolio' == $widget && ! post_type_exists( 'portfolio' ) ) {
					continue;
				}
				if ( 'members' == $widget && ! post_type_exists( 'member' ) ) {
					continue;
				}
				if ( 'faqs' == $widget && ! post_type_exists( 'faq' ) ) {
					continue;
				}
				include dirname( PORTO_META_BOXES_PATH ) . '/elementor/widgets/' . $widget . '.php';
				$class_name = 'Porto_Elementor_' . ucfirst( $widget ) . '_Widget';
				$self->register( new $class_name( array(), array( 'widget_name' => $class_name ) ) );
			}
			if ( class_exists( 'Woocommerce' ) ) {
				foreach ( $this->woo_widgets as $widget ) {
					include dirname( PORTO_META_BOXES_PATH ) . '/elementor/widgets/' . $widget . '.php';
					$class_name = 'Porto_Elementor_' . ucfirst( $widget ) . '_Widget';
					$self->register( new $class_name( array(), array( 'widget_name' => $class_name ) ) );
				}
			}
		}

		public function load_elementor_widgets_js() {
			if ( ( isset( $_REQUEST['action'] ) && 'elementor' == $_REQUEST['action'] ) || isset( $_REQUEST['elementor-preview'] ) ) {

				wp_register_script( 'porto-elementor-widgets-js', plugin_dir_url( __FILE__ ) . 'assets/elementor.js', array( 'jquery' ), PORTO_FUNC_VERSION, true );
				
				$masonry_layouts  = porto_sh_commons( 'masonry_layouts' );
				$creative_layouts = array();
				for ( $index = 1; $index <= count( $masonry_layouts ); $index++ ) {
					$layout = porto_creative_grid_layout( '' . $index );
					if ( is_array( $layout ) ) {
						$creative_layouts[ $index ] = array();
						foreach ( $layout as $pl ) {
							$creative_layouts[ $index ][] = esc_js( 'grid-col-' . $pl['width'] . ' grid-col-md-' . $pl['width_md'] . ( isset( $pl['width_lg'] ) ? ' grid-col-lg-' . $pl['width_lg'] : '' ) . ( isset( $pl['height'] ) ? ' grid-height-' . $pl['height'] : '' ) );
						}
					}
				}

				wp_enqueue_script( 'skrollr' );
				// enqueue gsap, scroll trigger for scrollInViewport, Horizontal Scroller, cursor Shape Effect
				wp_enqueue_script( 'porto-gsap' );
				wp_enqueue_script( 'porto-scroll-trigger' );

				wp_enqueue_script( 'porto-elementor-widgets-js' );

				$admin_vars = array(
					'creative_layouts'  => $creative_layouts,
					'gmt_offset'        => get_option( 'gmt_offset' ),
					'js_assets_url'     => defined( 'PORTO_VERSION' ) ? PORTO_JS : '',
					'shortcodes_url'    => PORTO_SHORTCODES_URL,
					'section_tab_title' => esc_html__( 'Column Title', 'porto-functionlity' ),
				);
				global $porto_settings;
				if ( ! empty( $porto_settings ) ) {
					$admin_vars['container_width'] = (int) $porto_settings['container-width'];
					$admin_vars['grid_spacing']    = (int) $porto_settings['grid-gutter-width'];
				}
				
				wp_localize_script(
					'porto-elementor-widgets-js',
					'porto_elementor_vars',
					$admin_vars
				);
			}
		}

		public function register_custom_control( $self ) {
			$controls = array( 'image_choose', 'porto_ajaxselect2' );

			foreach ( $controls as $control ) {
				$file_name = str_replace( 'porto_', '', $control );
				include_once dirname( PORTO_META_BOXES_PATH ) . '/elementor/controls/control-' . $file_name . '.php';
				$class_name = 'Porto_Control_' . ucfirst( $file_name );
				$self->register( new $class_name( array(), array( 'control_name' => $class_name ) ) );
			}
		}

		public function add_porto_icons( $icons ) {
			$icons['porto-icons'] = array(
				'name'          => 'porto-icons',
				'label'         => __( 'Porto Icons', 'porto-functionality' ),
				'prefix'        => 'porto-icon-',
				'displayPrefix' => ' ',
				'labelIcon'     => 'porto-icon-country',
				'fetchJson'     => plugin_dir_url( __FILE__ ) . 'assets/porto-icons.js',
				'ver'           => PORTO_FUNC_VERSION,
				'native'        => false,
			);

			$icons['simple-line-icons'] = array(
				'name'          => 'simple-line-icons',
				'label'         => __( 'Simple Line Icons', 'porto-functionality' ),
				'prefix'        => 'Simple-Line-Icons-',
				'displayPrefix' => ' ',
				'labelIcon'     => 'Simple-Line-Icons-flag',
				'fetchJson'     => plugin_dir_url( __FILE__ ) . 'assets/simple-line-icons.js',
				'ver'           => PORTO_FUNC_VERSION,
				'native'        => false,
			);
			return $icons;
		}

		/**
		 * get block ids in shortcode and block widgets from the elementor data
		 */
		private function get_elementor_object_by_id( $objects ) {
			$result = array();

			$block_slugs = array();
			foreach ( $objects as $object ) {
				if ( ! empty( $object['elements'] ) ) {
					$result = array_merge( $result, $this->get_elementor_object_by_id( $object['elements'] ) );
				} elseif ( isset( $object['widgetType'] ) ) {
					if ( 'shortcode' == $object['widgetType'] && isset( $object['settings'] ) && ! empty( $object['settings']['shortcode'] ) && preg_match_all( '/\[porto_block\s[^]]*(id|name)="([^"]*)"/', $object['settings']['shortcode'], $matches ) && ! empty( $matches[2] ) ) {
						$block_slugs = array_merge( $block_slugs, $matches[2] );
					} elseif ( 'wp-widget-block-widget' == $object['widgetType'] && isset( $object['settings'] ) && isset( $object['settings']['wp'] ) && ! empty( $object['settings']['wp']['name'] ) ) {
						$block_slugs = array_merge( $block_slugs, array_map( 'trim', explode( ',', $object['settings']['wp']['name'] ) ) );
					}
				}
			}
			if ( ! empty( $block_slugs ) ) {
				$block_slugs = array_unique( $block_slugs );
				global $wpdb;
				foreach ( $block_slugs as $s ) {
					$where   = is_numeric( $s ) ? 'ID' : 'post_name';
					$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = 'porto_builder' AND $where = %s", sanitize_text_field( $s ) ) );
					if ( $post_id && get_post_meta( $post_id, '_elementor_edit_mode', true ) && get_post_meta( $post_id, '_elementor_data', true ) ) {
						$result[] = (int) $post_id;
					}
				}
			}
			return array_unique( $result );
		}

		/**
		 * Generate the block temp css on save.
		 * 
		 * @since 2.8.0
		 */
		public function generate_block_temp_css_onsave( $post_id, $post, $use_temp = true ) {
			if ( ! isset( $_REQUEST['editor_post_id'] ) ) {
				return;
			}
	
			if ( 'porto_builder' == $post->post_type ) {
				if ( 'internal' !== get_option( 'elementor_css_print_method' ) ) {
					$initial_responsive_controls_duplication_mode = Plugin::$instance->breakpoints->get_responsive_control_duplication_mode();
					Plugin::$instance->breakpoints->set_responsive_control_duplication_mode( 'on' );
	
					$upload        = wp_upload_dir();
					$upload_dir    = $upload['basedir'];
					$post_css_path = wp_normalize_path( $upload_dir . '/elementor/css/post-' . $post_id . ( $use_temp ? '-temp' : '' ) . '.css' );
	
					$css_file = new Elementor\Core\Files\CSS\Post( $post_id );
					
					$block_css = $css_file->get_content();
	
					if ( $block_css ) {

					// Save block css as elementor post css.
					// filesystem
					global $wp_filesystem;
					// Initialize the WordPress filesystem, no more using file_put_contents function
					if ( empty( $wp_filesystem ) ) {
						require_once ABSPATH . '/wp-admin/includes/file.php';
						WP_Filesystem();
					}
	
					// Fix elementor's "max-width: auto" error.
					$block_css = str_replace( 'max-width:auto', 'max-width:none', $block_css );
	
					$wp_filesystem->put_contents( $post_css_path, $block_css, FS_CHMOD_FILE );
					}
	
					Plugin::$instance->breakpoints->set_responsive_control_duplication_mode( $initial_responsive_controls_duplication_mode );
				}
			}
		}
	
		/**
		 * Generate blocks' css after clear cache on Elementor -> Tools
		 *
		 * @since 2.8.0
		 */
		public function generate_blocks_css_after_clear_cache() {
			$posts = get_posts(
				array(
					'post_type'   => 'porto_builder',
					'post_status' => 'publish',
					'numberposts' => 100,
					'meta_query'  => array(
						'relation' => 'AND',
						array(
							'meta_key' => '_elementor_edit_mode',
							'compare'  => 'EXISTS',
						),
						array(
							'meta_key' => '_elementor_data',
							'compare'  => 'EXISTS',
						),
					),
				)
			);
			if ( ! empty( $posts ) && is_array( $posts ) ) {
				add_filter( 'porto_elementor_load_builder_widgets', '__return_true' );

				$mode = get_option( 'elementor_css_print_method' );
				foreach ( $posts as $post ) {
					$_REQUEST['editor_post_id'] = $post->ID;
					$this->generate_block_temp_css_onsave( $post->ID, $post, false );
					if ( 'internal' !== $mode ) {
						$css_file = new Elementor\Core\Files\CSS\Post( $post->ID );
						$css_file->update();
					}
				}
				unset( $_REQUEST['editor_post_id'] );

				remove_filter( 'porto_elementor_load_builder_widgets', '__return_true' );
			}
		}
	
		/**
		 * Rename the block temp css on save.
		 * 
		 * @since 2.8.0
		 */
		public function rename_block_temp_css_onsave( $obj, $data ) {
			$post = $obj->get_post();
	
			if ( 'porto_builder' == $post->post_type ) {
				if ( 'internal' !== get_option( 'elementor_css_print_method' ) ) {
					$upload      = wp_upload_dir();
					$upload_dir  = $upload['basedir'];
					$origin_path = wp_normalize_path( $upload_dir . '/elementor/css/post-' . $post->ID . '-temp.css' );
					$dest_path   = wp_normalize_path( $upload_dir . '/elementor/css/post-' . $post->ID . '.css' );
	
					$css_file = new Elementor\Core\Files\CSS\Post( $post->ID );
					$css_file->update();
	
					// Save block css as elementor post css.
					if ( file_exists( $origin_path ) ) {
						// filesystem
						global $wp_filesystem;
						// Initialize the WordPress filesystem, no more using file_put_contents function
						if ( empty( $wp_filesystem ) ) {
							require_once ABSPATH . '/wp-admin/includes/file.php';
							WP_Filesystem();
						}
						$wp_filesystem->move( $origin_path, $dest_path, true );
					}
				}
			}
		}

		/**
		 * Add Porto custom animation to elementor
		 * 
		 * @since 6.12.0
		 */
		public function elementor_add_animation( $additional_animations ) {
			
			$additional_animations['Reveal with Color'] = array(
				'revealDirTop'   => 'Reveal with Top',
				'revealDirDown'  => 'Reveal with Down',
				'revealDirLeft'  => 'Reveal with Left',
				'revealDirRight' => 'Reveal with Right',
			);

			return $additional_animations;
		}

		/**
		 * Render custom attributes
		 * 
		 * @since 6.12.0
		 */
		public function add_render_attributes( $el ) {
			$settings = $el->get_settings_for_display();
			if ( ( ! empty( $settings['_animation'] ) && false !== strpos( $settings['_animation'], 'revealDir' ) ) || ( ! empty( $settings['_animation_tablet'] ) && false !== strpos( $settings['_animation_tablet'], 'revealDir' ) ) || ( ! empty( $settings['_animation_mobile'] ) && false !== strpos( $settings['_animation_mobile'], 'revealDir' ) ) ) {
				$el->add_render_attribute( '_wrapper', 'data-animation-reveal', '' );
			}
		}
	}
endif;

new Porto_Elementor_Init;
