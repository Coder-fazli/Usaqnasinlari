<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Elementor Custom Product Image Widget
 *
 * Porto Elementor widget to display images section on the single product page when using custom product layout
 *
 * @since 1.7.1
 */

use Elementor\Controls_Manager;

class Porto_Elementor_CP_Image_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'porto_cp_image';
	}

	public function get_title() {
		return __( 'Product Image', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'custom-product' );
	}

	public function get_custom_help_url() {
		return 'https://www.portotheme.com/wordpress/porto/documentation/single-product-builder-elements/';
	}

	public function get_keywords() {
		return array( 'product', 'image', 'media', 'thumbnail' );
	}

	public function get_icon() {
		return 'eicon-product-images porto-elementor-widget-icon';
	}

	protected function register_controls() {

		$left  = is_rtl() ? 'right' : 'left';
		$right = is_rtl() ? 'left' : 'right';

		$this->start_controls_section(
			'section_cp_image',
			array(
				'label' => __( 'Product Image', 'porto-functionality' ),
			)
		);

		if ( defined( 'WOO_VARIATION_GALLERY_PLUGIN_VERSION' ) ) {
			$this->add_control(
				'notice_gallery_plugin',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( __( 'If you use %1$s\'Additional Variation Images Gallery for WooCommerce plugin\'%2$s, you should change the layout %3$sHere%4$s. This plugin overrides the our theme layout, so follow options don\'t work.', 'porto-functionality' ), '<b>', '</b>', '<a href="' . admin_url( 'admin.php?page=getwooplugins-settings&tab=woo_variation_gallery' ) . '" target="_blank">', '</a>' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
				)
			);
		}

		$this->add_control(
			'notice_skin',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf( __( 'You can change the global value in %1$sPorto / Theme Options / WooCommerce / Product Image & Zoom%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'product-thumbs' ) . '" target="_blank" class="porto-text-underline">', '</a>' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

			$this->add_control(
				'style',
				array(
					'type'    => 'image_choose',
					'label'   => __( 'Style', 'porto-functionality' ),
					'options' => array(
						''                       => 'product-images/sp-image-default.jpg',
						'extended'               => 'product-images/sp-image-extended.jpg',
						'grid'                   => 'product-images/sp-image-grid.jpg',
						'full_width'             => 'product-images/sp-image-thumbs-on.jpg',
						'sticky_info'            => 'product-images/sp-image-list.jpg',
						'transparent'            => 'product-images/sp-image-left-1.jpg',
						'centered_vertical_zoom' => 'product-images/sp-image-left-2.jpg',
						'scatted'                => 'product-images/sp-image-scatted.jpg',
					),
				)
			);

			$this->add_control(
				'spacing',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Spacing', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 60,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 5,
						),
					),
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .product-layout-centered_vertical_zoom .product-thumbnails-inner .img-thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'style' => 'centered_vertical_zoom',
					),
				)
			);

			$this->add_control(
				'spacing2',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Spacing', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 60,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 5,
						),
					),
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .product-images-block .img-thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .product-layout-grid .product-images-block' => '--bs-gutter-x: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'style' => array( 'sticky_info', 'grid' ),
					),
				)
			);

			$this->add_control(
				'br_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Border Color', 'porto-functionality' ),
					'selectors' => array(
						'.elementor-element-{{ID}} .img-thumbnail .inner' => 'border-color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cp_thumbnail',
			array(
				'label' => __( 'Thumbnail Image', 'porto-functionality' ),
			)
		);

			$this->add_control(
				'notice_thumb_skin',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( __( 'You can change the thumbnail info in %1$sPorto / Theme Options / WooCommerce / Product Image & Zoom / Thumbnails Count%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'product-thumbs-count' ) . '" target="_blank" class="porto-text-underline">', '</a>' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
	
			$this->add_responsive_control(
				'thumbnail_width',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Thumbnail Width', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 172,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 5,
						),
					),
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .product-layout-centered_vertical_zoom .product-thumbnails' => 'width: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .product-layout-centered_vertical_zoom .product-images' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
					),
					'condition'  => array(
						'style' => 'centered_vertical_zoom',
					),
				)
			);

			$this->add_responsive_control(
				'thumbnail_img_width',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Thumbnail Image Width', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 172,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 5,
						),
					),
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .product-layout-centered_vertical_zoom .product-thumbnails .img-thumbnail' => 'width: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'style' => 'centered_vertical_zoom',
					),
				)
			);

			$this->start_controls_tabs( 
				'tabs_thumbnail',
				array(
					'condition'  => array(
						'style' => array( '', 'transparent', 'centered_vertical_zoom', 'full_width' ),
					),
				)
			);
				$this->start_controls_tab(
					'tab_thumbnail',
					array(
						'label' => esc_html__( 'Normal', 'porto-functionality' ),
					)
				);
					$this->add_control(
						'thumbnail_br_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Thumbnail Border Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .product-thumbs-slider.owl-carousel .img-thumbnail, .elementor-element-{{ID}} .product-layout-full_width .img-thumbnail, .elementor-element-{{ID}} .product-thumbs-vertical-slider img, .elementor-element-{{ID}} .product-layout-centered_vertical_zoom .img-thumbnail' => 'border-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_thumbnail_hover',
					array(
						'label' => esc_html__( 'Hover', 'porto-functinoality' ),
					)
				);
					$this->add_control(
						'thumbnail_hover_br_color',
						array(
							'type'      => Controls_Manager::COLOR,
							'label'     => __( 'Hover Border Color', 'porto-functionality' ),
							'selectors' => array(
								'.elementor-element-{{ID}} .product-thumbs-slider .owl-item.selected .img-thumbnail, html:not(.touch) .elementor-element-{{ID}} .product-thumbs-slider .owl-item:hover .img-thumbnail, .elementor-element-{{ID}} .product-layout-full_width .img-thumbnail.selected, .elementor-element-{{ID}} .product-thumbs-vertical-slider .slick-slide.selected img, .elementor-element-{{ID}} .product-layout-centered_vertical_zoom .img-thumbnail.selected' => 'border-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();
			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slide_options',
			array(
				'label'     => __( 'Slide Option', 'porto-functionality' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'style' => 'extended',
				),
			)
		);

			$this->add_control(
				'spacing1',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Spacing (px)', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 60,
						),
					),
					'size_units'  => array(
						'px',
					),
					'render_type' => 'template',
					'selectors'   => array(
						'.elementor-element-{{ID}} .product-image-slider' => '--porto-el-spacing: {{SIZE}}px;',
					),
				)
			);

			$this->add_control(
				'set_loop',
				array(
					'type'    => Controls_Manager::SELECT,
					'label'   => __( 'Enable Loop', 'porto-functionality' ),
					'options' => array(
						''    => __( 'Theme Options', 'porto-functionality' ),
						'yes' => __( 'Yes', 'porto-functionality' ),
						'no'  => __( 'No', 'porto-functionality' ),
					),
					'default' => '',
				)
			);

			$this->add_control(
				'center_mode',
				array(
					'type'      => Controls_Manager::SWITCHER,
					'label'     => __( 'Enable Center Mode', 'porto-functionality' ),
					'default'   => 'yes',
					'separator' => 'after',
				)
			);

			$this->add_control(
				'columns',
				array(
					'type'    => Controls_Manager::SELECT,
					'label'   => __( 'Columns', 'porto-functionality' ),
					'options' => porto_sh_commons( 'products_columns' ),
				)
			);

			$this->add_control(
				'columns_tablet',
				array(
					'type'    => Controls_Manager::SELECT,
					'label'   => __( 'Columns on tablet ( <= 991px )', 'porto-functionality' ),
					'default' => '',
					'options' => array(
						''  => __( 'Default', 'porto-functionality' ),
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
					),
				)
			);

			$this->add_control(
				'columns_mobile',
				array(
					'type'    => Controls_Manager::SELECT,
					'label'   => __( 'Columns on mobile ( <= 575px )', 'porto-functionality' ),
					'default' => '',
					'options' => array(
						''  => __( 'Default', 'porto-functionality' ),
						'1' => '1',
						'2' => '2',
						'3' => '3',
					),
				)
			);

			$this->add_control(
				'enable_flick',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => __( 'Enable Flick Type', 'porto-functionality' ),
					'separator'   => 'before',
					'description' => sprintf( __( 'This option shows the carousel at the container\'s width. %1$sRead More%2$s', 'porto-functionality' ), '<a href="https://www.portotheme.com/wordpress/porto/documentation/how-to-use-porto-flick-carousel" target="_blank">', '</a>' ),
				)
			);
			
			$this->add_control(
				'flick_opacity',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Opacity of Inactive item', 'porto-functionality' ),
					'range'      => array(
						'px'  => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 1,
						),
					),
					'default'    => array(
						'size' => '1',
						'unit' => 'px',
					),
					'size_units' => array(
						'px',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .owl-item:not(.active)' => 'opacity: {{SIZE}}',
					),
					'condition' => array(
						'enable_flick!' => '',
					),
				)
			);
			
		$this->end_controls_section();

		$this->start_controls_section(
			'section_popup_options',
			array(
				'label' => __( 'Popup Icon Style', 'porto-functionality' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'notice_zoom_skin',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( __( 'To show zoom icon, you should enable %1$sPorto / Theme Options / WooCommerce / Product Image & Zoom / Image Popup%2$s.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'product-image-popup' ) . '" target="_blank" class="porto-text-underline">', '</a>' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->add_control(
				'icon_pos',
				array(
					'type'      => Controls_Manager::SWITCHER,
					'label'     => __( 'Position of Popup Icon', 'porto-functionality' ),
					'label_on'  => $left,
					'label_off' => $right,
					'selectors' => array(
						'.elementor-element-{{ID}} .product-images .zoom'                => "{$left}: 4px;",
						'.elementor-element-{{ID}} .product-images .image-galley-viewer' => "{$left}: 4px;",
					),
				)
			);

			$this->add_control(
				'icon_cl',
				array(
					'type'                   => Controls_Manager::ICONS,
					'label'                  => __( 'Popup Icon', 'porto-functionality' ),
					'fa4compatibility'       => 'icon',
					'skin'                   => 'inline',
					'label_block'            => false,
					'exclude_inline_options' => array( 'svg' ),
				)
			);

			$this->add_control(
				'icon_bgc',
				array(
					'label'     => __( 'Icon Background', 'porto-functionality' ),
					'type'      => Controls_Manager::COLOR,
					'selectors'  => array(
						'.elementor-element-{{ID}} .product-images .zoom, .elementor-element-{{ID}} .product-images .img-thumbnail:hover .zoom' => 'background-color: {{VALUE}};',
						'.elementor-element-{{ID}} .product-images .image-galley-viewer, .elementor-element-{{ID}} .product-images .img-thumbnail:hover .image-galley-viewer' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'icon_bg_size',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Icon Background Size', 'porto-functionality' ),
					'range'      => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'em'  => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 10,
						),
						'rem' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 10,
						),
					),
					'default'    => array(
						'unit' => 'px',
					),
					'size_units' => array(
						'px',
						'em',
						'rem',
					),
					'condition'  => array(
						'icon_bgc!' => '',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .product-images .zoom'                  => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .product-images .zoom i'                => 'line-height: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .product-images .image-galley-viewer'   => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; --porto-product-action-width: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .product-images .image-galley-viewer i' => 'line-height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'icon_clr',
				array(
					'label'     => __( 'Icon Color', 'porto-functionality' ),
					'type'      => Controls_Manager::COLOR,
					'separator' => 'before',
					'selectors' => array(
						'.elementor-element-{{ID}} .product-images .zoom i'                => 'color: {{VALUE}};',
						'.elementor-element-{{ID}} .product-images .image-galley-viewer i' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'icon_fs',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Icon Size', 'porto-functionality' ),
					'range'      => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 50,
						),
						'em'  => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 5,
						),
						'rem' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 5,
						),
					),
					'default'    => array(
						'unit' => 'px',
					),
					'size_units' => array(
						'px',
						'em',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .product-images .zoom i'                => 'font-size: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .product-images .image-galley-viewer i' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'popup_br_width',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Border Width', 'porto-functionality' ),
					'range'      => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 20,
						),
						'rem' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 2,
						),
					),
					'default'    => array(
						'unit' => 'px',
					),
					'size_units' => array(
						'px',
						'rem',
					),
					'separator'  => 'before',
					'selectors'  => array(
						'.elementor-element-{{ID}} .product-images .zoom'                => 'border: {{SIZE}}{{UNIT}} solid; box-sizing: content-box;',
						'.elementor-element-{{ID}} .product-images .image-galley-viewer' => 'border: {{SIZE}}{{UNIT}} solid; box-sizing: content-box; --porto-product-action-border: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'popup_br_color',
				array(
					'label'     => __( 'Border Color', 'porto-functionality' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => array(
						'popup_br_width!' => '',
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .product-images .zoom'                => 'border-color: {{VALUE}};',
						'.elementor-element-{{ID}} .product-images .image-galley-viewer' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'popup_space',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Space from Corner', 'porto-functionality' ),
					'range'      => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 20,
						),
						'rem' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 2,
						),
					),
					'default'    => array(
						'unit' => 'px',
					),
					'size_units' => array(
						'px',
						'rem',
					),
					'separator'  => 'before',
					'selectors'  => array(
						'.elementor-element-{{ID}} .product-images .zoom'                             => 'margin: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .product-images .image-galley-viewer'              => 'margin: 0 {{SIZE}}{{UNIT}}; --porto-product-action-margin: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .product-images .image-galley-viewer.without-zoom' => 'margin-bottom: {{SIZE}}{{UNIT}};'
					),
				)
			);

		$this->end_controls_section();
	}

	public function get_style_depends() {
		$depends = array();
		if ( isset( $_REQUEST['elementor-preview'] ) ) {
			wp_register_style( 'porto-sp-layout', PORTO_CSS . '/theme/shop/single-product/builder' . ( is_rtl() ? '_rtl' : '' ) . '.css', false, PORTO_VERSION, 'all' );
			$depends[] = 'porto-sp-layout';
			
			wp_register_style( 'porto-sp-scatted-layout', PORTO_CSS . '/theme/shop/single-product/scatted' . ( is_rtl() ? '_rtl' : '' ) . '.css', false, PORTO_VERSION, 'all' );
			$depends[] = 'porto-sp-scatted-layout';
		}
		if ( isset( $_REQUEST['preview_id'] ) && isset( $_REQUEST['preview'] ) ) { // Elementor Preview Change Page
			wp_register_style( 'porto-sp-layout', PORTO_CSS . '/theme/shop/single-product/builder' . ( is_rtl() ? '_rtl' : '' ) . '.css', false, PORTO_VERSION, 'all' );
			$depends[] = 'porto-sp-layout';
		}
		return $depends;
	}


	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( class_exists( 'PortoCustomProduct' ) ) {
			$settings['page_builder'] = 'elementor';
			if ( ! empty( $settings['spacing1'] ) ) {
				$settings['spacing1'] = $settings['spacing1']['size'];
			}
			if ( isset( $settings['icon_cl'] ) && isset( $settings['icon_cl']['value'] ) ) {
				$settings['icon_cl'] = $settings['icon_cl']['value'];
			}
			echo PortoCustomProduct::get_instance()->shortcode_single_product_image( $settings );
		}
	}
}
