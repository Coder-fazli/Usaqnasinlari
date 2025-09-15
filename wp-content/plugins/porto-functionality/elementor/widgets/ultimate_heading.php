<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Elementor Ultimate Heading Widget
 *
 * Porto Elementor widget to display headings.
 *
 * @since 1.5.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;

class Porto_Elementor_Ultimate_Heading_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'porto_ultimate_heading';
	}

	public function get_title() {
		return __( 'Porto Ultimate Heading', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'porto-elements' );
	}

	public function get_keywords() {
		return array( 'title', 'text', 'marquee', 'highlight', 'floating image', 'typewriter' );
	}

	public function get_icon() {
		return 'eicon-heading porto-elementor-widget-icon';
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	public function get_script_depends() {
		$depends = array();
		if ( isset( $_REQUEST['elementor-preview'] ) ) {
			$depends[] = 'porto-marquee';
		}
		return $depends;
	}

	public function get_custom_help_url() {
		return 'https://www.portotheme.com/wordpress/porto/documentation/effects-in-porto-heading/';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_ultimate_heading',
			array(
				'label' => __( 'Ultimate Heading', 'porto-functionality' ),
			)
		);

		$this->add_control(
			'main_heading',
			array(
				'label'       => __( 'Main Heading', 'porto-functionality' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => __( 'Title', 'porto-functionality' ),
			)
		);
		$this->add_control(
			'enable_marquee',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => __( 'Enable Marquee Effect', 'porto-functionality' ),
				'separator' => 'before',
				'condition' => array(
					'enable_typewriter!' => 'yes',
				),
			)
		);
		$this->add_control(
			'ov_hidden',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => __( 'Overflow Hidden?', 'porto-functionality' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .marquee'  => 'overflow: hidden;',
				),
				'condition' => array(
					'enable_marquee' => 'yes',
				),
			)
		);
		$this->add_control(
			'marquee_duration',
			array(
				'label'     => esc_html__( 'Duration (ms)', 'porto-functionality' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 999999,
				'step'      => 1000,
				'condition' => array(
					'enable_marquee' => 'yes',
				),
			)
		);		
		$this->add_control(
			'enable_typewriter',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => __( 'Enable Typewriter Effect', 'porto-functionality' ),
				'condition' => array(
					'enable_marquee!' => 'yes',
				),
			)
		);
		$this->add_control(
			'enable_typeword',
			array(
				'type'        => Controls_Manager::SWITCHER,
				'label'       => __( 'Effect By Words', 'porto-functionality' ),
				'description' => __( 'Animate the words one by one.', 'porto-functionality' ),
				'condition'   => array(
					'enable_typewriter' => 'yes',
				),
			)
		);
		$this->add_control(
			'typewriter_animation',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Animation Name', 'porto-functionality' ),
				'description' => __( 'e.g: typeWriter, fadeIn and so on.', 'porto-functionality' ),
				'default'     => 'fadeIn',
				'condition'   => array(
					'enable_typewriter' => 'yes',
				),
			)
		);
		$this->add_control(
			'typewriter_delay',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => __( 'Start Delay(ms)', 'porto-functionality' ),
				'condition' => array(
					'enable_typewriter' => 'yes',
				),
			)
		);
		$this->add_control(
			'typewriter_speed',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => __( 'Animation Speed(ms)', 'porto-functionality' ),
				'default'   => '50',
				'condition' => array(
					'enable_typewriter' => 'yes',
				),
			)
		);
		$this->add_control(
			'typewriter_width',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => __( 'Min width that can work(px).', 'porto-functionality' ),
				'condition' => array(
					'enable_typewriter' => 'yes',
				),
			)
		);

		$this->add_control(
			'content',
			array(
				'type'  => Controls_Manager::WYSIWYG,
				'label' => __( 'Sub Heading (Optional)', 'porto-functionality' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'heading_tag',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => __( 'Tag', 'porto-functionality' ),
				'options'     => array(
					'h2'  => __( 'Default', 'porto-functionality' ),
					'h1'  => __( 'H1', 'porto-functionality' ),
					'h3'  => __( 'H3', 'porto-functionality' ),
					'h4'  => __( 'H4', 'porto-functionality' ),
					'h5'  => __( 'H5', 'porto-functionality' ),
					'h6'  => __( 'H6', 'porto-functionality' ),
					'div' => __( 'div', 'porto-functionality' ),
				),
				'default'     => 'h2',
				'description' => __( 'Default is H2', 'porto-functionality' ),
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'type'    => Controls_Manager::CHOOSE,
				'label'   => __( 'Alignment', 'porto-functionality' ),
				'options' => array(
					'center'  => array(
						'title' => __( 'Center', 'porto-functionality' ),
						'icon'  => 'eicon-text-align-center',
					),
					'left'    => array(
						'title' => __( 'left', 'porto-functionality' ),
						'icon'  => 'eicon-text-align-left',
					),
					'right'   => array(
						'title' => __( 'Right', 'porto-functionality' ),
						'icon'  => 'eicon-text-align-right',
					),
					'inherit' => array(
						'title' => __( 'Inherit', 'porto-functionality' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default' => 'center',
			)
		);

		$this->add_control(
			'spacer',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => __( 'Separator', 'porto-functionality' ),
				'options'     => array(
					'no_spacer' => __( 'No Separator', 'porto-functionality' ),
					'line_only' => __( 'Line', 'porto-functionality' ),
				),
				'default'     => 'no_spacer',
				'description' => __( 'Horizontal line, icon or image to divide sections', 'porto-functionality' ),
			)
		);

		$this->add_control(
			'spacer_position',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => __( 'Separator Position', 'porto-functionality' ),
				'options'   => array(
					'top'    => __( 'Top', 'porto-functionality' ),
					'middle' => __( 'Between Heading & Sub-Heading', 'porto-functionality' ),
					'bottom' => __( 'Bottom', 'porto-functionality' ),
				),
				'default'   => 'top',
				'condition' => array(
					'spacer' => 'line_only',
				),
			)
		);

		$this->add_control(
			'line_width',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => __( 'Line Width(px) (optional)', 'porto-functionality' ),
				'condition' => array(
					'spacer' => 'line_only',
				),
			)
		);

		$this->add_control(
			'line_height',
			array(
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Line Height', 'porto-functionality' ),
				'range'     => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 100,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 1,
				),
				'condition' => array(
					'spacer' => 'line_only',
				),
			)
		);

		$this->add_control(
			'line_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Line Color', 'porto-functionality' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .porto-u-headings-line' => 'border-color: {{VALUE}};',
				),
				'default'   => '#333333',
				'condition' => array(
					'spacer' => 'line_only',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ultimate_heading_font_options',
			array(
				'label' => __( 'Style', 'porto-functionality' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'main_heading_typography',
				'scheme'   => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'label'    => __( 'Main Heading Typography', 'porto-functionality' ),
				'selector' => '{{WRAPPER}} .porto-u-main-heading > *',
			)
		);

		$this->add_control(
			'main_heading_color1',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Main Heading Color', 'porto-functionality' ),
				'selectors' => array(
					'{{WRAPPER}} .porto-u-main-heading > *' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Text_Stroke::get_type(),
			array(
				'name' => 'main_heading_stroke',
				'selector' => '{{WRAPPER}} .porto-u-main-heading > *',
			)
		);

		$this->add_control(
			'main_heading_margin_bottom',
			array(
				'type'  => Controls_Manager::TEXT,
				'label' => __( 'Heading Margin Bottom', 'porto-functionality' ),
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_heading_typography',
				'scheme'   => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'label'    => __( 'Sub Heading Typography', 'porto-functionality' ),
				'selector' => '{{WRAPPER}} .porto-u-sub-heading',
			)
		);

		$this->add_control(
			'sub_heading_color1',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Sub Heading Color', 'porto-functionality' ),
				'selectors' => array(
					'{{WRAPPER}} .porto-u-sub-heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sub_heading_margin_bottom',
			array(
				'type'  => Controls_Manager::TEXT,
				'label' => __( 'Sub Heading Margin Bottom', 'porto-functionality' ),
			)
		);

		$this->add_control(
			'spacer_margin_bottom',
			array(
				'type'      => Controls_Manager::TEXT,
				'label'     => __( 'Separator Margin Bottom', 'porto-functionality' ),
				'condition' => array(
					'spacer' => 'line_only',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ultimate_heading_floating_fields',
			array(
				'label' => __( 'Floating Animation', 'porto-functionality' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$floating_options = porto_update_vc_options_to_elementor( porto_shortcode_floating_fields() );
		$floating_options['floating_transition']['condition']['floating_start_pos'] = $floating_options['floating_horizontal']['condition']['floating_start_pos'] = $floating_options['floating_duration']['condition']['floating_start_pos'] = array( 'none', 'top', 'bottom' );
		$floating_options['floating_speed']['condition']['floating_circle']         = $floating_options['floating_transition']['condition']['floating_circle'] = $floating_options['floating_horizontal']['condition']['floating_circle'] = $floating_options['floating_duration']['condition']['floating_circle'] = '';

		foreach ( $floating_options as $key => $opt ) {
			unset( $opt['condition']['animation_type'] );
			$this->add_control( $key, $opt );
		}

		$this->add_control(
			'floating_img',
			array(
				'type'      => Controls_Manager::MEDIA,
				'label'     => __( 'Image Floating with Mouse Tracking', 'porto-functionality' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'floating_offset',
			array(
				'type'        => Controls_Manager::NUMBER,
				'label'       => __( 'Floating Offset', 'porto-functionality' ),
				'description' => __( 'Control the offset from the cursor.', 'porto-functionality' ),
				'default'     => '0',
				'condition'   => array(
					'floating_img[url]!' => '',
				),
			)
		);

		$this->end_controls_section();

		// Highlight Animation
		$this->start_controls_section(
			'section_highlight',
			array(
				'label'   => __( 'Highlight', 'porto-functionality' ),
				'tab'     => Controls_Manager::TAB_CONTENT,
				'default' => '',
			)
		);

		$this->add_control(
			'enable_highlight',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => __( 'Enable Highlight Animation', 'porto-functionality' ),
				'default' => '',
			)
		);

		$this->add_control(
			'enable_svghighlight',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => __( 'Enable shape Highlight', 'porto-functionality' ),
				'default'   => '',
				'condition' => array(
					'enable_highlight' => 'yes',
				),
			)
		);

		$this->add_control(
			'desc_highlight',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'For highlight, the main heading should have the HTML Mark Text element. For example, A<mark>B</mark>C.', 'porto-functionality' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					'enable_highlight'    => 'yes',
					'enable_svghighlight' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'        => 'hlight_bg',
				'types'       => array( 'classic', 'gradient' ),
				'exclude'     => array( 'image' ),
				'selector'    => '.elementor-element-{{ID}} .heading-highlight mark:before',
				'description' => __( 'Control the highlight background.', 'porto-functionality' ),
				'condition'   => array(
					'enable_highlight'    => 'yes',
					'enable_svghighlight' => '',
				),
			)
		);

		$this->add_control(
			'highlight_svg',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => __( 'Highlight Type', 'porto-functionality' ),
				'options'   => array(
					'scribble'         => __( 'Scribble', 'porto-functionality' ),
					'circle'           => __( 'Circle', 'porto-functionality' ),
					'underline_zigzag' => __( 'Underline Zigzag', 'porto-functionality' ),
					'curly'            => __( 'Curly', 'porto-functionality' ),
					'cross'            => __( 'Cross', 'porto-functionality' ),
					'underline'        => __( 'Underline', 'porto-functionality' ),
				),
				'default'   => 'scribble',
				'condition' => array(
					'enable_highlight'    => 'yes',
					'enable_svghighlight' => 'yes',
				),
			)
		);

		$this->add_control(
			'before_heading',
			array(
				'label'     => __( 'Before Heading', 'porto-functionality' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					'enable_highlight'    => 'yes',
					'enable_svghighlight' => 'yes',
				),
			)
		);

		$this->add_control(
			'after_heading',
			array(
				'label'     => __( 'After Heading', 'porto-functionality' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					'enable_highlight'    => 'yes',
					'enable_svghighlight' => 'yes',
				),
			)
		);

		$this->add_control(
			'animation_hlight_delay',
			array(
				'type'      => Controls_Manager::TEXT,
				'label'     => __( 'Highlight Animation Delay(ms)', 'porto-functionality' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .heading-highlight mark:before' => 'animation-delay: {{SIZE}}ms;',
					'.elementor-element-{{ID}} .svg-highlight svg path' => 'animation-delay: {{SIZE}}ms;',
				),
				'condition' => array(
					'enable_highlight' => 'yes',
				),
			)
		);

		$this->add_control(
			'shape_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Shape Color', 'porto-functionality' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .svg-highlight svg' => 'stroke: {{VALUE}};',
				),
				'separator' => 'before',
				'condition' => array(
					'enable_highlight'    => 'yes',
					'enable_svghighlight' => 'yes',
				),
			)
		);

		$this->add_control(
			'shape_width',
			array(
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Shape Width', 'porto-functionality' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .svg-highlight svg' => 'stroke-width: {{SIZE}};',
				),
				'condition' => array(
					'enable_highlight'    => 'yes',
					'enable_svghighlight' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'hlight_height',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => __( 'Height(%)', 'porto-functionality' ),
				'size_units'  => array(
					'%',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .heading-highlight mark:before' => 'height: {{SIZE}}%;',
				),
				'description' => __( 'Control the height of the highlight.', 'porto-functionality' ),
				'separator'   => 'before',
				'condition'   => array(
					'enable_highlight'    => 'yes',
					'enable_svghighlight' => '',
				),
			)
		);

		$this->add_responsive_control(
			'hlight_bottom',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => __( 'Vertical Position(%)', 'porto-functionality' ),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => -50,
						'max'  => 50,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .heading-highlight mark:before' => 'bottom: {{SIZE}}%;',
					'.elementor-element-{{ID}} .svg-highlight' => 'bottom: {{SIZE}}%;',
				),
				'description' => __( 'Control the bottom position of the highlight.', 'porto-functionality' ),
				'condition'   => array(
					'enable_highlight' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'hlight_shape_height',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => __( 'Shape height', 'porto-functionality' ),
				'range'       => array(
					'%'   => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					),
					'rem' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 10,
					),
				),
				'size_units'  => array(
					'%',
					'px',
					'rem',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .svg-highlight' => 'height: {{SIZE}}{{UNIT}};',
				),
				'description' => __( 'Control the height of the shape.', 'porto-functionality' ),
				'condition'   => array(
					'enable_highlight'    => 'yes',
					'enable_svghighlight' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'hlight_left',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => __( 'Horizontal Position(%)', 'porto-functionality' ),
				'size_units'  => array(
					'%',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .heading-highlight mark:before' => 'left: {{SIZE}}%;',
				),
				'description' => __( 'Control the left position of the highlight.', 'porto-functionality' ),
				'condition'   => array(
					'enable_highlight'    => 'yes',
					'enable_svghighlight' => '',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$atts                 = $this->get_settings_for_display();
		$atts['page_builder'] = 'elementor';
		if ( ! isset( $atts['enable_highlight'] ) || 'yes' != $atts['enable_highlight'] ) {
			$this->add_inline_editing_attributes( 'main_heading' );
			$main_heading_attrs_escaped = ' ' . $this->get_render_attribute_string( 'main_heading' );
		}

		if ( $template = porto_shortcode_template( 'porto_ultimate_heading' ) ) {
			include $template;
		}
	}

	protected function content_template() {
		?>
		<#
			view.addRenderAttribute( 'wrapper', 'class', 'porto-u-heading' );

			var spacer_style = '', line = '', custom_style = '', wrapper_class = 'elementor-element-' + view.$el.data('id');

			view.addRenderAttribute( 'wrapper', 'class', wrapper_class );

			custom_style = '.' + wrapper_class + '{text-align:' + settings.alignment + ';}';
			if ( settings.alignment_tablet ) {
				custom_style += ' @media(max-width:' + elementor.breakpoints.responsiveConfig.activeBreakpoints.tablet.value + 'px) {.' + wrapper_class + '{text-align:' + settings.alignment_tablet + '}}';
			}
			if ( settings.alignment_mobile ) {
				custom_style += ' @media(max-width: ' + elementor.breakpoints.responsiveConfig.activeBreakpoints.mobile.value + 'px) {.' + wrapper_class + '{text-align:' + settings.alignment_mobile + '}}';
			}

			if ( 'no_spacer' != settings.spacer && settings.spacer_margin_bottom ) {
				var unit = settings.spacer_margin_bottom.replace( /[0-9.]/, '' );
				if ( ! unit ){
					settings.spacer_margin_bottom += 'px';
				}
				spacer_style += 'margin-bottom: ' + settings.spacer_margin_bottom+ ';';
			}
			if ( 'line_only' == settings.spacer && settings.line_height && settings.line_height.size ) {
				var wrap_width = settings.line_width,
					line_style_inline  = 'border-style: solid;';
				line_style_inline += 'border-bottom-width:' + settings.line_height.size + 'px;';
				line_style_inline += 'width:' + settings.line_width + ( 'auto' == settings.line_width ? ';' : 'px;' );

				if ( 'center' != settings.alignment ) {
					if ( 'inherit' == settings.alignment ) {
						custom_style += ' .' + wrapper_class + ' .porto-u-headings-line{float:left;}';
					} else {
						custom_style += ' .' + wrapper_class + ' .porto-u-headings-line{' + 'float:' + settings.alignment + ';}';
					}
				}
				if ( settings.alignment_tablet ) {
					custom_style += ' @media(max-width:' + elementor.breakpoints.responsiveConfig.activeBreakpoints.tablet.value + 'px){.' + wrapper_class + ' .porto-u-headings-line{';
					if ( 'center' == settings.alignment_tablet ) {
						custom_style += 'float: unset;';
					} else if ( 'inherit' != settings.alignment_tablet ) {
						custom_style += 'float: ' + settings.alignment_tablet + ';';
					}
					custom_style += '}}';
				}
				if ( settings.alignment_mobile ) {					
					custom_style += ' @media(max-width: ' + elementor.breakpoints.responsiveConfig.activeBreakpoints.mobile.value + 'px){.' + wrapper_class + ' .porto-u-headings-line{';
					if ( 'center' == settings.alignment_mobile ) {
						custom_style += 'float: unset;';
					} else if ( 'inherit' != settings.alignment_mobile ) {
						custom_style += 'float: ' + settings.alignment_mobile + ';';
					} 
					custom_style += '}}';
				}

				spacer_style += 'height:' + settings.line_height.size + 'px;';
				line = '<span class="porto-u-headings-line" style="' + line_style_inline + '"></span>';
			}
			view.addRenderAttribute( 'spacer', 'class', 'porto-u-heading-spacer' );
			view.addRenderAttribute( 'spacer', 'class', settings.spacer );
			view.addRenderAttribute( 'spacer', 'style', spacer_style );

			if ( settings.main_heading_margin_bottom || '0' == settings.main_heading_margin_bottom ) {
				var unit = settings.main_heading_margin_bottom.replace( /[0-9.]/, '' );
				if ( ! unit ) {
					settings.main_heading_margin_bottom += 'px';
				}
				view.addRenderAttribute( 'main_heading', 'style', 'margin-bottom:' + settings.main_heading_margin_bottom );
			}
			if ( settings.main_heading ) {
				view.addRenderAttribute( 'main_heading_wrap', 'class', 'porto-u-main-heading' );
				if ( settings.enable_highlight ) {
					if ( settings.enable_svghighlight ) {
						svg_shape = '';
						if ( 'scribble' == settings.highlight_svg ) {
							svg_shape = '<svg class="highlight-scribble" viewBox="0 0 760 60" preserveAspectRatio="none"><path d="M19,49C75.19,30.63,152,21,317.26,17.27c55.43-.41,198.33-2.08,407.85,12.53"></path></svg>';
						} else if ( 'circle' == settings.highlight_svg ) {
							svg_shape = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M284.72,15.61C276.85,14.43,2-2.85,2,80.46c0,34.09,45.22,58.86,196.31,62.81C719.59,154.18,467-74.85,109,29.15"></path></svg>';
						} else if ( 'underline_zigzag' == settings.highlight_svg ) {
							svg_shape = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M9.3,127.3c49.3-3,150.7-7.6,199.7-7.4c121.9,0.4,189.9,0.4,282.3,7.2C380.1,129.6,181.2,130.6,70,139 c82.6-2.9,254.2-1,335.9,1.3c-56,1.4-137.2-0.3-197.1,9"></path></svg>';
						}  else if ( 'curly' == settings.highlight_svg ) {
							svg_shape = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 30" preserveAspectRatio="none"><path d="M1.15,18C64.07,44.13,108.42,1.4,169.63,3.1,182.11,3.76,191.39,6.58,201,10c71.41,33.39,112-8.7,188.65-7,35.22,1.74,69.81,22.6,103,17"></path></svg>';
						} else if ( 'cross' == settings.highlight_svg ) {
							svg_shape = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M1.61,3.49C115.8,11.6,253.39,36.14,398.43,97.15c31.14,13.1,60.53,27,88.18,41.34"></path><path d="M486.61,3.49C372.42,11.6,234.84,36.14,89.79,97.15c-31.14,13.1-60.52,27-88.18,41.34"></path></svg>';
						} else if ( 'underline' == settings.highlight_svg ) {
							svg_shape = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 100" preserveAspectRatio="none"><path d="M4,74.8h499.3"></path></svg>';
						}
						settings.main_heading = '<span class="heading-highlight-before">' + settings.before_heading + '</span><span class="heading-highlight-svg">' + settings.main_heading + '<span class="svg-highlight ' + settings.highlight_svg + '">' + svg_shape + '</span></span><span class="heading-highlight-after">' + settings.after_heading + '</span>';
					} else {
						view.addRenderAttribute( 'main_heading_wrap', 'class', 'heading-highlight' );
						view.addRenderAttribute( 'main_heading_wrap', 'data-appear-animation', 'highlightProgress' );
					}
				} else {
					view.addInlineEditingAttributes( 'main_heading' );
				}
			}

			if ( settings.enable_typewriter ) {
				var typewriter = {
					startDelay: 0,
					minWindowWidth: 0,
					animationSpeed: 50
				}
				if( settings.typewriter_delay ) {
					typewriter[ 'startDelay' ] = parseInt( settings.typewriter_delay, 10 );
				}
				if( settings.typewriter_speed ) {
					typewriter[ 'animationSpeed' ] = parseInt( settings.typewriter_speed, 10 );
				}
				if( settings.typewriter_width ) {
					typewriter[ 'minWindowWidth' ] = parseInt( settings.typewriter_width, 10 );
				}
				if( settings.typewriter_animation ) {
					typewriter[ 'animationName' ] = settings.typewriter_animation;
				}
				if ( settings.enable_typeword ) {
					typewriter['contentType'] = 'word';
					view.addRenderAttribute( 'main_heading', 'data-plugin-animated-words', '' );
				} else {
					view.addRenderAttribute( 'main_heading', 'data-plugin-animated-letters', '' );
				}
				view.addRenderAttribute( 'main_heading', 'data-plugin-options', JSON.stringify( typewriter ) );
			}
			if ( settings.enable_marquee ) {
				view.addRenderAttribute( 'main_heading', 'class', [ 'marquee' ] );
				view.addRenderAttribute( 'main_heading', 'data-duration', settings.marquee_duration ? settings.marquee_duration : '70000' );
				view.addRenderAttribute( 'main_heading', 'data-gap', '10' );
				view.addRenderAttribute( 'main_heading', 'data-duplicated', 'true' );
				view.addRenderAttribute( 'main_heading', 'data-startVisible', 'true' );
				view.addRenderAttribute( 'main_heading', 'data-clone-element', '5' );
			}
			if ( settings.floating_img.id || settings.floating_img.url ) {
				view.addRenderAttribute( 'wrapper', 'class', 'thumb-info-floating-element-wrapper' );
				var imgfloating = { 'offset': 0 };
				if ( settings.floating_offset ) {
					imgfloating['offset'] = settings.floating_offset;
				}
				view.addRenderAttribute( 'wrapper', 'data-plugin-tfloating', JSON.stringify( imgfloating ) );
			}

			if ( settings.sub_heading_margin_bottom || '0' == settings.sub_heading_margin_bottom ) {
				var unit = settings.sub_heading_margin_bottom.replace( /[0-9.]/, '' );
				if ( ! unit ) {
					settings.sub_heading_margin_bottom += 'px';
				}
				view.addRenderAttribute( 'sub_heading', 'style', 'margin-bottom:' + settings.sub_heading_margin_bottom );
			}

			let extra_attr = '';
			if ( typeof porto_elementor_add_floating_options != 'undefined' ) {
				extra_attr = porto_elementor_add_floating_options( settings );
			}
		#>
		<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}{{ extra_attr }}>
		<# if ( custom_style ){ #>
			<style>{{{custom_style}}}</style>
		<# } #>
		<# if ( 'top' == settings.spacer_position ) { #>
			<div {{{ view.getRenderAttributeString( 'spacer' ) }}}>{{{ line }}}</div>
		<# } #>
		<# if ( settings.main_heading ) {#>
			<div {{{ view.getRenderAttributeString( 'main_heading_wrap' ) }}}"><{{{ settings.heading_tag }}} {{{ view.getRenderAttributeString( 'main_heading' ) }}}>{{{ settings.main_heading }}}</{{{ settings.heading_tag }}}></div>
		<# } #>
		<# if ( 'middle' == settings.spacer_position ) { #>
			<div {{{ view.getRenderAttributeString( 'spacer' ) }}}>{{{ line }}}</div>
		<# } #>
		<# if ( settings.content ) { #>
			<div class="porto-u-sub-heading" {{{ view.getRenderAttributeString( 'sub_heading' ) }}}>{{{ settings.content }}}</div>
		<# } #>
		<# if ( 'bottom' == settings.spacer_position ) { #>
			<div {{{ view.getRenderAttributeString( 'spacer' ) }}}>{{{ line }}}</div>
		<# } #>
		<# if ( settings.floating_img.id || settings.floating_img.url ) { #>
			<span class="thumb-info-floating-element d-none"><img src="{{{settings.floating_img.url}}}"/></span>
		<# } #>
		</div>
		<?php
	}
}
