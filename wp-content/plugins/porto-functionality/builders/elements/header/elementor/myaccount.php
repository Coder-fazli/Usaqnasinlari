<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Porto Header Builder My Account Icon widget
 *
 * @since 6.0
 */

use Elementor\Controls_Manager;

class Porto_Elementor_HB_Myaccount_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'porto_hb_myaccount';
	}

	public function get_title() {
		return __( 'Porto My Account', 'porto-functionality' );
	}

	public function get_categories() {
		return array( 'porto-hb' );
	}

	public function get_keywords() {
		return array( 'account', 'my account', 'icon', 'user' );
	}

	public function get_icon() {
		return 'porto-icon-user-2 porto-elementor-widget-icon';
	}

	public function get_custom_help_url() {
		return 'https://www.portotheme.com/wordpress/porto/documentation/porto-my-account-icon-element/';
	}

	protected function register_controls() {
		$left  = is_rtl() ? 'right' : 'left';
		$right = is_rtl() ? 'left' : 'right';
		$this->start_controls_section(
			'section_hb_myaccount',
			array(
				'label' => __( 'My Account Icon', 'porto-functionality' ),
			)
		);

			$this->add_control(
				'icon_cl',
				array(
					'type'                   => Controls_Manager::ICONS,
					'label'                  => __( 'Icon', 'porto-functionality' ),
					'fa4compatibility'       => 'icon',
					'skin'                   => 'inline',
					'exclude_inline_options' => array( 'svg' ),
					'label_block'            => false,
					'default'                => array(
						'value'   => '',
						'library' => '',
					),
				)
			);

			$this->add_responsive_control(
				'size',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => __( 'Icon Size', 'porto-functionality' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 72,
						),
						'em' => array(
							'step' => 0.1,
							'min'  => 0.1,
							'max'  => 5,
						),
					),
					'size_units' => array(
						'px',
						'em',
					),
					'selectors'  => array(
						'#header .elementor-element-{{ID}} .my-account' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'separator'  => 'after',
				)
			);

			$this->add_control(
				'color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Color', 'porto-functionality' ),
					'selectors' => array(
						'#header .elementor-element-{{ID}} .my-account' => 'color: {{VALUE}};',
					),
					'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'header-link-color' ) . '" target="_blank">', '</a>' ),					
				)
			);
			$this->add_control(
				'sticky_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Color In Sticky', 'porto-functionality' ),
					'selectors' => array(
						'#header.sticky-header .elementor-element-{{ID}} .my-account' => 'color: {{VALUE}};',
					),
					'separator' => 'after',
				)
			);
			$this->add_control(
				'hover_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Hover Color', 'porto-functionality' ),
					'selectors' => array(
						'#header .elementor-element-{{ID}} .my-account:hover' => 'color: {{VALUE}};',
					),
					'description' => sprintf( __( 'You can change %1$sglobal%2$s value in theme option.', 'porto-functionality' ), '<a href="' . porto_get_theme_option_url( 'header-link-color' ) . '" target="_blank">', '</a>' ),						
				)
			);
			$this->add_control(
				'sticky_hover_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => __( 'Hover Color In Sticky', 'porto-functionality' ),
					'selectors' => array(
						'#header.sticky-header .elementor-element-{{ID}} .my-account:hover' => 'color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_hb_dropdown',
			array(
				'label' => __( 'My Account Dropdown', 'porto-functionality' ),
			)
		);

			$this->add_control(
				'account_dropdown',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => __( 'Show Account Dropdown', 'porto-functionality' ),
					'description' => sprintf( __( 'When the user is logged in, the Menu that is located in the %1$sAccount Menu%2$s will be shown.', 'porto-functionality' ), '<a href="' . esc_url( admin_url( 'nav-menus.php#locations-account_menu' ) ) . '" target="_blank">', '</a>' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .account-dropdown > li.has-sub > a::after' => 'font-size: 12px;vertical-align: middle;',
						'.elementor-element-{{ID}} .account-dropdown > li.menu-item > a'      => 'padding: 0;',
						'.elementor-element-{{ID}} .account-dropdown > li.menu-item > a > i'  => 'width: auto;',
					),
					'render_type' => 'template',
				)
			);

			$this->add_control(
				'spacing',
				array(
					'type'      => Controls_Manager::SLIDER,
					'label'     => __( 'Between Spacing (px)', 'porto-functionality' ),
					'range'     => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 50,
						),
					),
					'selectors' => array(
						'#header .elementor-element-{{ID}} .account-dropdown > li.menu-item > a > i' => "margin-{$right}: {{SIZE}}{{UNIT}};",
					),
					'condition' => array(
						'account_dropdown' => 'yes',
					),
				)
			);

			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'      => 'account_menu_font',
					'scheme'    => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'label'     => __( 'Account Dropdown Font', 'porto-functionality' ),
					'selector'  => '#header .elementor-element-{{ID}} .sub-menu li.menu-item > a',
					'condition' => array(
						'account_dropdown' => 'yes',
					),
				)
			);

			$this->add_control(
				'account_dropdown_bgc',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Background Color', 'porto-functionality' ),
					'description' => __( 'Controls the background color for account dropdown.', 'porto-functionality' ),
					'default'     => '#fff',
					'selectors'   => array(
						'.elementor-element-{{ID}} .account-dropdown .narrow ul.sub-menu' => 'background-color: {{VALUE}};',
						'.elementor-element-{{ID}} .account-dropdown>li.has-sub:before, .elementor-element-{{ID}} .account-dropdown>li.has-sub:after' => 'border-bottom-color: {{VALUE}};',
					),
					'condition'   => array(
						'account_dropdown' => 'yes',
					),
				)
			);

			$this->add_control(
				'account_dropdown_hbgc',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Hover Background Color', 'porto-functionality' ),
					'description' => __( 'Controls the background color for account dropdown item on hover.', 'porto-functionality' ),
					'default'     => '#f3f3f3',
					'selectors'   => array(
						'.elementor-element-{{ID}} .account-dropdown .sub-menu li.menu-item:hover > a, .elementor-element-{{ID}} .account-dropdown .sub-menu li.menu-item.active > a, .elementor-element-{{ID}} .account-dropdown .sub-menu li.menu-item.is-active > a' => 'background-color: {{VALUE}};',
					),
					'condition'   => array(
						'account_dropdown' => 'yes',
					),
				)
			);

			$this->add_control(
				'account_dropdown_lc',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Link Color', 'porto-functionality' ),
					'description' => __( 'Controls the link color for account dropdown.', 'porto-functionality' ),
					'default'     => '#777',
					'selectors'   => array(
						'.elementor-element-{{ID}} .sub-menu li.menu-item:before, .elementor-element-{{ID}} .sub-menu li.menu-item > a' => 'color: {{VALUE}};',
					),
					'condition'   => array(
						'account_dropdown' => 'yes',
					),
				)
			);

			$this->add_control(
				'account_dropdown_hlc',
				array(
					'type'        => Controls_Manager::COLOR,
					'label'       => __( 'Link Hover Color', 'porto-functionality' ),
					'description' => __( 'Controls the link hover color for account dropdown.', 'porto-functionality' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .account-dropdown .sub-menu li.menu-item:hover > a, .elementor-element-{{ID}} .account-dropdown .sub-menu li.menu-item.active > a, .elementor-element-{{ID}} .account-dropdown .sub-menu li.menu-item.is-active > a' => 'color: {{VALUE}};',
					),
					'condition'   => array(
						'account_dropdown' => 'yes',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( class_exists( 'Woocommerce' ) ) {
			$icon_cl = 'porto-icon-user-2';
			if ( isset( $settings['icon_cl'] ) && ! empty( $settings['icon_cl']['value'] ) ) {
				if ( isset( $settings['icon_cl']['library'] ) && ! empty( $settings['icon_cl']['value']['id'] ) ) {
					$icon_cl = $settings['icon_cl']['value']['id'];
				} else {
					$icon_cl = $settings['icon_cl']['value'];
				}
			}
			global $porto_settings;
			if ( isset( $porto_settings['show-account-dropdown'] ) ) {
				$backup_account = $porto_settings['show-account-dropdown'];
			}
			$porto_settings['show-account-dropdown'] = ! empty( $settings['account_dropdown'] ) ? true : false;
			if ( function_exists( 'porto_account_menu' ) ) {
				porto_account_menu( '', $icon_cl );
			} else {
				if ( ! is_user_logged_in() && empty( $porto_settings['woo-account-login-style'] ) ) {
					$el_class .= ' porto-link-login';
				}
				echo '<a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '"' . ' title="' . esc_attr__( 'My Account', 'porto' ) . '" class="my-account"><i class="' . esc_attr( $icon_cl ) . '"></i></a>';
			}
			if ( isset( $backup_account ) ) {
				$porto_settings['show-account-dropdown'] = $backup_account;
			}
		}
	}
}
