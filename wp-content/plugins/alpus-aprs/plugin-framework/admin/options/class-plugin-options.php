<?php
/**
 * Alpus Plugin Options
 *
 * @author AlpusTheme
 * @package Alpus Plugin Framework
 * @version 1.0.0
 */

// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'Alpus_Plugin_Options' ) ) {
	class Alpus_Plugin_Options {
		/**
		 * Alpus_Plugin_Options Instance
		 *
		 * @since 1.0
		 */
		public static $instance = null;


		/**
		 * Get Alpus_Plugin_Options Instance
		 *
		 * @since 1.0
		 * @return Alpus_Plugin_Options instance
		 */
		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {

		}

		/**
		 * Get Option
		 *
		 * @param string $key Option name
		 * @param mixed $default Default Value
		 *
		 * @since 1.0
		 */
		public static function get_option( $key, $default = '' ) {
			if ( empty( $default ) ) {
				global $alpus_pf_options;
				if ( ! empty( $alpus_pf_options[ $key ] ) ) {
					$default = $alpus_pf_options[ $key ];
				}
			}
			if ( empty( $key ) ) {
				return $default;
			}
			$value = get_option( $key, null );

			if ( is_array( $value ) ) {
				$value = wp_unslash( $value );
			} elseif ( ! is_null( $value ) ) {
				$value = stripslashes( $value );
			}

			return ( null === $value ) ? $default : $value;
		}

		/**
		 * @since 1.0
		 */
		public static function print_field( $option_id, $plugin_option, $is_table = true ) {
			$plugin_option['id'] = $option_id;

			if ( empty( $plugin_option['default'] ) ) {
				global $alpus_pf_options;
				if ( ! empty( $alpus_pf_options[ $option_id ] ) ) {
					$plugin_option['default'] = $alpus_pf_options[ $option_id ];
				}
			}

			extract( // @codingStandardsIgnoreLine
				shortcode_atts(
					array(
						'type'        => '',
						'id'          => '',
						'title'       => '',
						'subtitle'    => '',
						'class'       => '',
						'css'         => '',
						'default'     => '',
						'desc'        => '',
						'placeholder' => '',
						'options'     => '',
						'value'       => self::get_option( isset( $plugin_option['id'] ) ? $plugin_option['id'] : '', isset( $plugin_option['default'] ) ? $plugin_option['default'] : '' ),
						'step'        => 1,
						'dependency'  => '',
						'label'       => '',
						'html'        => '',
					),
					$plugin_option
				)
			);

			$attrs = array();

			if ( ! empty( $dependency ) ) {
				$class .= ' display-condition';

				$condition_value = isset( $dependency['value'] ) ? $dependency['value'] : '';

				if ( is_array( $condition_value ) ) {
					$condition_value = json_encode( $condition_value );
				}

				$attrs[] = 'data-condition-option="' . esc_attr( isset( $dependency['option'] ) ? $dependency['option'] : '' ) . '"';
				$attrs[] = 'data-condition-operator="' . esc_attr( isset( $dependency['operator'] ) ? $dependency['operator'] : '' ) . '"';
				$attrs[] = 'data-condition-value="' . esc_attr( $condition_value ) . '"';
				$attrs[] = 'data-condition-id="' . $id . '"';
			}

			// Switch based on type.
			switch ( $type ) {
				// Section Start
				case 'section_start':
					if ( ! empty( $title ) ) {
						echo '<h2 class="alpus-plugin-option-title">' . esc_html( $title ) . '</h2>';
					}
					if ( ! empty( $desc ) ) {
						echo '<p class="alpus-plugin-option-desc" id="' . esc_attr( sanitize_title( $id ) ) . '-desc">';
						echo alpus_strip_script_tags( wptexturize( $desc ) );
						echo '</p>';
					}
					echo '<table class="form-table">' . "\n\n";
					break;

				// Section End
				case 'section_end':
					echo '</table>';
					break;

				// Text & Number
				case 'text':
				case 'number':
				case 'password':
					?>
					<?php if ( $is_table ) : ?>
						<tr class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<th scope="row">
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</th>
							<td>
					<?php else : ?>
						<div class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<div>
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</div>
							<div>
					<?php endif; ?>
							<input
								name="<?php echo esc_attr( ! empty( $plugin_option['name'] ) ? $plugin_option['name'] : $id ); ?>"
								id="<?php echo esc_attr( $id ); ?>"
								type="<?php echo esc_attr( $type ); ?>"
								style="<?php echo esc_attr( $css ); ?>"
								value="<?php echo esc_attr( $value ); ?>"
								placeholder="<?php echo esc_attr( $placeholder ); ?>"
								/>
							<p class="alpus-plugin-option-desc" id="<?php echo esc_attr( sanitize_title( $id ) ); ?>-desc"><?php echo wptexturize( $desc ); ?></p>
					<?php if ( $is_table ) : ?>
							</td>
						</tr>
					<?php else : ?>						
							</div>
						</div>
					<?php endif; ?>	
					<?php
					break;

				case 'textarea':
					?>
					<?php if ( $is_table ) : ?>
						<tr class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<th scope="row">
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</th>
							<td>
					<?php else : ?>
						<div class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<div>
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</div>
							<div>
					<?php endif; ?>
							<textarea
								name="<?php echo esc_attr( ! empty( $plugin_option['name'] ) ? $plugin_option['name'] : $id ); ?>"
								id="<?php echo esc_attr( $id ); ?>"
								style="<?php echo esc_attr( $css ); ?>"
								placeholder="<?php echo esc_attr( $placeholder ); ?>"
								rows="10" cols="100"
							><?php echo alpus_strip_script_tags( $value ); ?></textarea>
							<p class="alpus-plugin-option-desc" id="<?php echo esc_attr( sanitize_title( $id ) ); ?>-desc"><?php echo wptexturize( $desc ); ?></p>
					<?php if ( $is_table ) : ?>
							</td>
						</tr>
					<?php else : ?>						
							</div>
						</div>
					<?php endif; ?>	
					<?php
					break;

				// Select & Multi Select
				case 'select':
				case 'multiselect':
					?>
					<?php if ( $is_table ) : ?>
						<tr class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<th scope="row">
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</th>
							<td>
					<?php else : ?>
						<div class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<div>
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</div>
							<div>
					<?php endif; ?>
							<select
								name="<?php echo esc_attr( ! empty( $plugin_option['name'] ) ? $plugin_option['name'] : $id ); ?><?php echo ( 'multiselect' === $type ) ? '[]' : ''; ?>"
								id="<?php echo esc_attr( $id ); ?>"
								style="<?php echo esc_attr( $css ); ?>"
								<?php echo 'multiselect' === $type ? 'multiple="multiple"' : ''; ?>
								<?php echo implode( ' ', $attrs ); ?>
								>
								<?php
								foreach ( $options as $key => $val ) {
									?>
									<option value="<?php echo esc_attr( $key ); ?>"
										<?php

										if ( is_array( $value ) ) {
											selected( in_array( (string) $key, $value, true ), true );
										} else {
											selected( $value, (string) $key );
										}

										?>
									><?php echo esc_html( $val ); ?></option>
									<?php
								}
								?>
							</select>
							<p class="alpus-plugin-option-desc" id="<?php echo esc_attr( sanitize_title( $id ) ); ?>-desc"><?php echo wptexturize( $desc ); ?></p>
					<?php if ( $is_table ) : ?>
							</td>
						</tr>
					<?php else : ?>						
							</div>
						</div>
					<?php endif; ?>	
					<?php
					break;

				// Radio inputs.
				case 'radio':
					?>
					<?php if ( $is_table ) : ?>
						<tr class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<th scope="row">
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</th>
							<td>
					<?php else : ?>
						<div class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<div>
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</div>
							<div>
					<?php endif; ?>
							<fieldset>
								<ul>
								<?php
								foreach ( $options as $key => $val ) {
									?>
									<li>
										<label><input
											name="<?php echo esc_attr( ! empty( $plugin_option['name'] ) ? $plugin_option['name'] : $id ); ?>"
											value="<?php echo esc_attr( $key ); ?>"
											type="radio"
											style="<?php echo esc_attr( $css ); ?>"
											<?php checked( $key, $value ); ?>
											/> <?php echo esc_html( $val ); ?></label>
									</li>
									<?php
								}
								?>
								</ul>
							</fieldset>
							<p class="alpus-plugin-option-desc" id="<?php echo esc_attr( sanitize_title( $id ) ); ?>-desc"><?php echo wptexturize( $desc ); ?></p>
					<?php if ( $is_table ) : ?>
							</td>
						</tr>
					<?php else : ?>						
							</div>
						</div>
					<?php endif; ?>	
					<?php
					break;

				// Checkbox inputs.
				case 'checkbox':
					if ( ! is_array( $value ) ) {
						$value = json_decode( $value, true );
					}
					?>
					<?php if ( $is_table ) : ?>
						<tr class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<th scope="row">
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</th>
							<td>
					<?php else : ?>
						<div class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<div>
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</div>
							<div>
					<?php endif; ?>
							<fieldset>
								<ul>
								<?php
								foreach ( $options as $key => $val ) {
									?>
									<li>
										<label><input
											name="<?php echo esc_attr( ! empty( $plugin_option['name'] ) ? $plugin_option['name'] : $id ); ?>[]"
											value="<?php echo esc_attr( $key ); ?>"
											type="checkbox"
											style="<?php echo esc_attr( $css ); ?>"
											<?php echo is_array( $value ) ? ( in_array( $key, $value ) ? ' checked' : '' ) : ( $key == $value ? ' checked' : '' ); ?>
											/> <?php echo esc_html( $val ); ?></label>
									</li>
									<?php
								}
								?>
								</ul>
							</fieldset>
							<p class="alpus-plugin-option-desc" id="<?php echo esc_attr( sanitize_title( $id ) ); ?>-desc"><?php echo wptexturize( $desc ); ?></p>
					<?php if ( $is_table ) : ?>
							</td>
						</tr>
					<?php else : ?>						
							</div>
						</div>
					<?php endif; ?>		
					<?php
					break;

				// Switcher input.
				case 'switcher':
					?>
					<?php if ( $is_table ) : ?>
						<tr class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<th scope="row">
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</th>
							<td>
					<?php else : ?>
						<div class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<div>
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</div>
							<div>
					<?php endif; ?>
						<label for="<?php echo esc_attr( $id ); ?>">
							<input
								class="alpus-switcher"
								name="<?php echo esc_attr( ! empty( $plugin_option['name'] ) ? $plugin_option['name'] : $id ); ?>"
								id="<?php echo esc_attr( $id ); ?>"
								type="checkbox"
								value="1"
								<?php checked( $value, 'yes' ); ?>
							/>
						</label>
						<p class="alpus-plugin-option-desc" id="<?php echo esc_attr( sanitize_title( $id ) ); ?>-desc"><?php echo alpus_strip_script_tags( wptexturize( $desc ) ); ?></p>
					<?php if ( $is_table ) : ?>
							</td>
						</tr>
					<?php else : ?>						
							</div>
						</div>
					<?php endif; ?>						
					<?php
					break;

				// Colorpicker
				case 'color':
					wp_enqueue_script( 'wp-color-picker' );
					wp_enqueue_style( 'wp-color-picker' );
					?>
					<?php if ( $is_table ) : ?>
					<tr class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
						<th scope="row">
							<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
						</th>
						<td>
					<?php else : ?>
					<div class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
						<div>
							<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
						</div>
						<div>
					<?php endif; ?>
							<input type="text" class="alpus-color-picker" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( ! empty( $plugin_option['name'] ) ? $plugin_option['name'] : $id ); ?>" value="<?php echo esc_attr( $value ); ?>">
							<p class="alpus-plugin-option-desc" id="<?php echo esc_attr( sanitize_title( $id ) ); ?>-desc"><?php echo wptexturize( $desc ); ?></p>
					<?php if ( $is_table ) : ?>
						</td>
					</tr>
					<?php else : ?>
						</div>
					</div>
					<?php endif; ?>
					<?php
					break;

				// Range
				case 'range':
					wp_enqueue_script( 'jquery-ui-slider' );
					$min  = isset( $options['min'] ) ? $options['min'] : 0;
					$max  = isset( $options['max'] ) ? $options['max'] : 100;
					$step = isset( $options['step'] ) ? $options['step'] : 1;

					$range_attrs = array(
						'min'   => $min,
						'max'   => $max,
						'step'  => $step,
						'value' => $value,
					);
					?>
					<?php if ( $is_table ) : ?>
						<tr class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<th scope="row">
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</th>
							<td>
					<?php else : ?>
						<div class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<div>
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</div>
							<div>
					<?php endif; ?>
							<div class="wp-slider-wrapper">
								<div class="wp-slider" data-range="<?php echo esc_attr( json_encode( $range_attrs ) ); ?>"></div>
								<input type="number" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( ! empty( $plugin_option['name'] ) ? $plugin_option['name'] : $id ); ?>" value="<?php echo esc_attr( $value ); ?>" min="<?php echo esc_attr( $min ); ?>" max="<?php echo esc_attr( $max ); ?>">
							</div>
							<p class="alpus-plugin-option-desc" id="<?php echo esc_attr( sanitize_title( $id ) ); ?>-desc"><?php echo wptexturize( $desc ); ?></p>
					<?php if ( $is_table ) : ?>
						</td>
					</tr>
					<?php else : ?>
						</div>
					</div>
					<?php endif; ?>
					<?php
					break;

				case 'button_set':
					?>
					<?php if ( $is_table ) : ?>
						<tr class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<th scope="row">
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</th>
							<td>
					<?php else : ?>
						<div class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<div>
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</div>
							<div>
					<?php endif; ?>
							<div class="alpus-button-set">
								<?php
								foreach ( $options as $key => $val ) {
									?>
									<div class="alpus-set-item<?php echo $key == $value ? ' active' : ''; ?>" data-value="<?php echo esc_attr( $key ); ?>">
										<span><?php echo esc_html( $val ); ?></span>
									</div>
									<?php
								}
								?>
							</div>
							<input
								name="<?php echo esc_attr( ! empty( $plugin_option['name'] ) ? $plugin_option['name'] : $id ); ?>"
								id="<?php echo esc_attr( $id ); ?>"
								type="text"
								value="<?php echo esc_attr( $value ); ?>"
								hidden
								/>
							<p class="alpus-plugin-option-desc" id="<?php echo esc_attr( sanitize_title( $id ) ); ?>-desc"><?php echo wptexturize( $desc ); ?></p>
					<?php if ( $is_table ) : ?>
							</td>
						</tr>
					<?php else : ?>						
							</div>
						</div>
					<?php endif; ?>	
					<?php
					break;

				case 'image_select':
					?>
					<?php if ( $is_table ) : ?>
							<tr class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
								<th scope="row">
									<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
								</th>
								<td>
						<?php else : ?>
							<div class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
								<div>
									<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
								</div>
								<div>
						<?php endif; ?>
								<div class="alpus-button-set alpus-image-select">
								<?php
								foreach ( $options as $key => $val ) {
									?>
										<div class="alpus-set-item<?php echo $key == $value ? ' active' : ''; ?>" data-value="<?php echo esc_attr( $key ); ?>">
											<img src="<?php echo esc_attr( $val ); ?>" />
										</div>
										<?php
								}
								?>
								</div>
								<input
									name="<?php echo esc_attr( ! empty( $plugin_option['name'] ) ? $plugin_option['name'] : $id ); ?>"
									id="<?php echo esc_attr( $id ); ?>"
									type="text"
									value="<?php echo esc_attr( $value ); ?>"
									hidden
									/>
								<p class="alpus-plugin-option-desc" id="<?php echo esc_attr( sanitize_title( $id ) ); ?>-desc"><?php echo wptexturize( $desc ); ?></p>
						<?php if ( $is_table ) : ?>
								</td>
							</tr>
						<?php else : ?>						
								</div>
							</div>
						<?php endif; ?>	
						<?php
					break;

				case 'upload':
					$file = $value;
					?>
					<?php if ( $is_table ) : ?>
						<tr class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
					<?php else : ?>
						<div class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
					<?php endif; ?>
					<?php if ( $is_table ) : ?>
						<th scope="row">
					<?php else : ?>
						<div>
					<?php endif; ?>
							<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
					<?php if ( $is_table ) : ?>
						</th>
						<td>
					<?php else : ?>							
						</div>
						<div>
					<?php endif; ?>							
							<div class="alpus-upload-img-preview">
								<?php if ( preg_match( '/(jpg|jpeg|png|gif|ico|svg)$/', $file ) ) : ?>
									<img src="<?php echo esc_url( $file ); ?>" />
								<?php endif ?>
							</div>
							<input type="text"
									id="<?php echo esc_attr( $id ); ?>"
									name="<?php echo esc_attr( ! empty( $plugin_option['name'] ) ? $plugin_option['name'] : $id ); ?>"
									value="<?php echo esc_attr( $value ); ?>"
									class="alpus-upload-img-url"
								<?php if ( isset( $default ) ) : ?>
									data-std="<?php echo esc_attr( $default ); ?>"
								<?php endif; ?>
							/>

							<button class="alpus-upload-button button button-primary" id="<?php echo esc_attr( $id ); ?>-button"><?php esc_html_e( 'Upload', 'alpus-plugin-framework' ); ?></button>
							<button type="button"
									id="<?php echo esc_attr( $id ); ?>-button-reset"
									class="alpus-upload-button-reset button-secondary"
									data-default="<?php echo isset( $default ) ? esc_attr( $default ) : ''; ?>"
							><?php esc_html_e( 'Reset', 'alpus-plugin-framework' ); ?></button>

							<p class="alpus-plugin-option-desc" id="<?php echo esc_attr( sanitize_title( $id ) ); ?>-desc"><?php echo wptexturize( $desc ); ?></p>
					<?php if ( $is_table ) : ?>
						</td>
					</tr>
					<?php else : ?>	
						</div>
					</div>
					<?php endif; ?>		
					<?php
					break;
				case 'repeater':
					$add_button        = ! empty( $plugin_option['add_button'] ) ? $plugin_option['add_button'] : '';
					$show_add_button   = ! empty( $plugin_option['add_button'] );
					$add_button_closed = isset( $plugin_option['add_button_closed'] ) ? $plugin_option['add_button_closed'] : '';
					$values            = maybe_unserialize( $value );
					$ajax_nonce        = wp_create_nonce( 'alpus-repeater' );
					$elements          = $plugin_option['elements'];
					$save_button       = $plugin_option['save_button'];
					$delete_button     = $plugin_option['delete_button'];
					if ( empty( $values ) && ! $show_add_button && $elements ) {
						$values = array();
						foreach ( $elements as $element_id => $element ) {
							$values[0][ $element_id ] = $element['default'];
						}
					}

					?>
					<tr class="repeater-element <?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
						<td colspan="2">
							<div class="alpus-repeater-wrapper" id="<?php echo esc_attr( $id ); ?>" data-nonce="<?php echo esc_attr( $ajax_nonce ); ?>">

								<?php if ( ! empty( $label ) ) : ?>
									<label for="<?php esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>
								<?php endif; ?>
								<?php if ( $show_add_button ) : ?>
									<button class="button-secondary alpus-add-button alpus-add-box-button"
											data-box_id="<?php echo esc_attr( $id ); ?>_add_box"
											data-closed_label="<?php echo esc_attr( $add_button_closed ); ?>"
											data-opened_label="<?php echo esc_attr( $add_button ); ?>"><?php echo esc_html( $add_button ); ?></button>
									<div id="<?php echo esc_attr( $id ); ?>_add_box" class="alpus-add-box"></div>
									<script type="text/template" id="tmpl-alpus-repeater-element-add-box-content-<?php echo esc_attr( $id ); ?>">
										<?php foreach ( $elements as $element_id => $element ) : ?>
											<?php

											$element['value'] = isset( $element['default'] ) ? $element['default'] : '';
											$element['id']    = 'new_' . $element_id;
											$element['name']  = $id . '[{{{data.index}}}][' . $element['id'] . ']';
											$class_element    = isset( $element['class_row'] ) ? $element['class_row'] : '';
											if ( ! empty( $element['dependency']['option'] ) ) {
												$element['dependency']['option'] = 'new_' . $element['dependency']['option'];
											}
											if ( ! empty( $element['dependency']['target-id'] ) ) {
												$element['dependency']['target-id'] = 'new_' . $element['dependency']['target-id'];
											}

											if ( ! empty( $element['required'] ) ) {
												$class_element .= ' alpus--required';
											}
											?>
											<div class="alpus-add-box-row alpus-repeater-content-row <?php echo esc_attr( $class_element ); ?> <?php echo '{{{data.index}}}'; ?>" >
												<?php self::print_field( $element['id'], $element, false ); ?>
											</div>
										<?php endforeach; ?>

										<?php if ( ! empty( $save_button ) ) : ?>
											<div class="alpus-add-box-buttons">
												<button class="button-primary alpus-save-button">
													<?php echo esc_html( $save_button['name'] ); ?>
												</button>
											</div>
										<?php endif; ?>
									</script>
								<?php endif; ?>

								<div class="alpus-repeater-elements">
									<?php if ( $values ) : ?>
										<?php foreach ( $values as $i => $value ) : ?>

											<div id="<?php echo esc_attr( $id ); ?>_<?php echo esc_attr( $i ); ?>"
													class="alpus-repeater-row <?php echo ! empty( $subtitle ) ? 'with-subtitle' : ''; ?> <?php echo esc_attr( $class ); ?>"
													data-item_key="<?php echo esc_attr( $i ); ?>"
											>
												<div class="alpus-repeater-title">
													<h3>
														<span class="title" data-title_format="<?php echo esc_attr( $title ); ?>"><?php echo wp_kses_post( $title ); ?></span>
														<?php if ( ! empty( $subtitle ) ) : ?>
															<div class="subtitle" data-subtitle_format="<?php echo esc_attr( $subtitle ); ?>"><?php echo wp_kses_post( $subtitle ); ?></div>
														<?php endif; ?>
													</h3>
													<span class="alpus-repeater"><span class="dashicons-before dashicons-arrow-down-alt2 ui-sortable-handle"></span></span>

												</div>
												<div class="alpus-repeater-content">
													<?php if ( $elements && count( $elements ) > 0 ) : ?>
														<?php foreach ( $elements as $element_id => $element ) : ?>
															<?php
															// $element['title']     = $element['name'];
															$element['name']      = $id . "[$i][" . $element_id . ']';
															$element['value']     = isset( $value[ $element_id ] ) ? $value[ $element_id ] : ( isset( $element['default'] ) ? $element['default'] : '' );
															$element['id']        = $element_id . '_' . $i;
															$element['class_row'] = isset( $element['class_row'] ) ? $element['class_row'] : '';

															if ( ! empty( $element['dependency']['option'] ) ) {
																$element['dependency']['option'] = $element['dependency']['option'] . '_' . $i;
															}
															if ( ! empty( $element['dependency']['target-id'] ) ) {
																$element['dependency']['target-id'] = $element['dependency']['target-id'] . '_' . $i;
															}

															if ( ! empty( $element['required'] ) ) {
																$element['class_row'] .= ' alpus--required';
															}
															?>
															<div class="alpus-repeater-content-row <?php echo esc_attr( $element['class_row'] . ' ' . $element['type'] ); ?>" >
																<?php self::print_field( $element['id'], $element, false ); ?>
															</div>
														<?php endforeach; ?>
													<?php endif; ?>
													<div class="alpus-repeater-content-buttons">
														<div class="spinner"></div>
														<?php if ( $save_button && ! empty( $save_button['id'] ) ) : ?>
															<?php
															$save_button_class = isset( $save_button['class'] ) ? $save_button['class'] : '';
															$save_button_name  = isset( $save_button['name'] ) ? $save_button['name'] : '';
															?>
															<button id="<?php echo esc_attr( $save_button['id'] ); ?>" class="button-primary alpus-save-button <?php echo esc_attr( $save_button_class ); ?>">
																<?php echo esc_html( $save_button_name ); ?>
															</button>
														<?php endif; ?>
														<?php if ( $delete_button && ! empty( $delete_button['id'] ) ) : ?>
															<?php
															$delete_button_class = isset( $delete_button['class'] ) ? $delete_button['class'] : '';
															$delete_button_name  = isset( $delete_button['name'] ) ? $delete_button['name'] : '';
															?>
															<button id="<?php echo esc_attr( $delete_button['id'] ); ?>"
																	class="button-secondary alpus-delete-button <?php echo esc_attr( $delete_button_class ); ?>">
																<?php echo esc_html( $delete_button_name ); ?>
															</button>
														<?php endif; ?>
													</div>
												</div>

											</div>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>
								<script type="text/template" id="tmpl-alpus-repeater-element-item-<?php echo esc_attr( $id ); ?>">
									<div id="<?php echo esc_attr( $id ); ?>_{{{data.index}}}"
											class="alpus-repeater-row highlight <?php echo ! empty( $subtitle ) ? 'with-subtitle' : ''; ?> <?php echo esc_attr( $class ); ?>"
											data-item_key="{{{data.index}}}"
									>
										<div class="alpus-repeater-title">
											<h3>
												<span class="title" data-title_format="<?php echo esc_attr( $title ); ?>"><?php echo wp_kses_post( $title ); ?></span>
												<div class="subtitle" data-subtitle_format="<?php echo esc_attr( $subtitle ); ?>"><?php echo wp_kses_post( $subtitle ); ?></div>
											</h3>
											<span class="alpus-repeater"><span class="dashicons-before dashicons-arrow-down-alt2"></span></span>

										</div>
										<div class="alpus-repeater-content">
											<?php if ( $elements && count( $elements ) > 0 ) : ?>
												<?php foreach ( $elements as $element_id => $element ) : ?>
													<?php

													$element['name'] = $id . '[{{{data.index}}}][' . $element_id . ']';
													$element['id']   = $element_id . '_{{{data.index}}}';
													$class_element   = isset( $element['class_row'] ) ? $element['class_row'] : '';

													if ( ! empty( $element['dependency']['option'] ) ) {
														$element['dependency']['option'] = $element['dependency']['option'] . '_{{{data.index}}}';
													}
													if ( ! empty( $element['dependency']['target-id'] ) ) {
														$element['dependency']['target-id'] = $element['dependency']['target-id'] . '_{{{data.index}}}';
													}

													if ( ! empty( $element['required'] ) ) {
														$class_element .= ' alpus--required';
													}

													?>
													<div class="alpus-repeater-content-row <?php echo esc_attr( $class_element . ' ' . $element['type'] ); ?>" >
														<?php self::print_field( $element['id'], $element, false ); ?>
													</div>
												<?php endforeach; ?>
											<?php endif; ?>
											<div class="alpus-repeater-content-buttons">
												<div class="spinner"></div>
												<?php if ( $save_button && ! empty( $save_button['id'] ) ) : ?>
													<?php
													$save_button_class = isset( $save_button['class'] ) ? $save_button['class'] : '';
													$save_button_name  = isset( $save_button['name'] ) ? $save_button['name'] : '';
													?>
													<button id="<?php echo esc_attr( $save_button['id'] ); ?>" class="button-primary alpus-save-button <?php echo esc_attr( $save_button_class ); ?>">
														<?php echo esc_html( $save_button_name ); ?>
													</button>
												<?php endif; ?>
												<?php if ( $delete_button && ! empty( $delete_button['id'] ) ) : ?>
													<?php
													$delete_button_class = isset( $delete_button['class'] ) ? $delete_button['class'] : '';
													$delete_button_name  = isset( $delete_button['name'] ) ? $delete_button['name'] : '';
													?>
													<button id="<?php echo esc_attr( $delete_button['id'] ); ?>" class="button-secondary alpus-delete-button <?php echo esc_attr( $delete_button_class ); ?>">
														<?php echo esc_html( $delete_button_name ); ?>
													</button>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</script>
							</div>
					</td>
					</tr>
					<?php
					break;
				case 'action':
					if ( $is_table ) : ?>
						<tr class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<th scope="row">
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</th>
							<td>
					<?php else : ?>
						<div class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<div>
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</div>
							<div>
					<?php endif; ?>
							<button class="button-primary alpus-plugin-action-button"><?php echo esc_html( $label ); ?></button>
							<p class="alpus-plugin-option-desc" id="<?php echo esc_attr( sanitize_title( $id ) ); ?>-desc"><?php echo wptexturize( $desc ); ?></p>
					<?php if ( $is_table ) : ?>
							</td>
						</tr>
					<?php else : ?>						
							</div>
						</div>
					<?php 
					endif;
					break;
				case 'html':
					if ( $is_table ) : ?>
						<tr class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<th scope="row">
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</th>
							<td>
					<?php else : ?>
						<div class="<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attrs ); ?>>
							<div>
								<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
							</div>
							<div>
					<?php endif; ?>
							<div class="alpus-plugin-custom-html"><?php echo wp_kses_post( $html ); ?></div>
							<p class="alpus-plugin-option-desc" id="<?php echo esc_attr( sanitize_title( $id ) ); ?>-desc"><?php echo wptexturize( $desc ); ?></p>
					<?php if ( $is_table ) : ?>
							</td>
						</tr>
					<?php else : ?>						
							</div>
						</div>
					<?php
					endif;
					break;
				default:
					do_action( 'alpus_plugin_option_' . $type, $value );
					break;
			}
		}

		/**
		 * Print admin fields.
		 *
		 * @param array $plugin_options Plugin options
		 *
		 * @since 1.0
		 */
		public static function print_fields( $plugin_options ) {
			foreach ( $plugin_options as $option_id => $plugin_option ) {
				if ( empty( $plugin_option['type'] ) ) {
					continue;
				}
				self::print_field( $option_id, $plugin_option );
			}
		}

		/**
		 * Sanitize the option.
		 *
		 * @since 1.0
		 */
		public static function sanitize_option( $options, $value ) {

			if ( $value && ! empty( $options ) ) {

				if ( isset( $value['box_id'] ) ) {
					unset( $value['box_id'] );
				}

				foreach ( $value as $index => $single_repeater ) {

					foreach ( $options as $option_id => $option ) {
						$option_value = isset( $value[ $index ][ $option_id ] ) ? $value[ $index ][ $option_id ] : false;
						// We don't need to un-slash the value, since it's already un-slashed.

						$ret_val = '';
						switch ( $option['type'] ) {
							case 'switcher':
								$ret_val = '1' === $option_value || 'yes' === $option_value ? 'yes' : 'no';
								break;
							case 'checkbox':
								$allowed_values = empty( $option['options'] ) ? array() : array_map( 'strval', array_keys( $option['options'] ) );
								if ( empty( $option['default'] ) && empty( $allowed_values ) ) {
									$ret_val = null;
									break;
								}

								if ( is_array( $option_value ) ) {
									$ret_val = array_filter(
										$option_value,
										function( $checked ) use ( $allowed_values ) {
											return in_array( $checked, $allowed_values );
										}
									);
									$ret_val = json_encode( $ret_val );
								} else {
									$ret_val = '';
								}
								break;
							case 'select':
								$allowed_values = empty( $option['options'] ) ? array() : array_map( 'strval', array_keys( $option['options'] ) );
								if ( empty( $option['default'] ) && empty( $allowed_values ) ) {
									$ret_val = null;
									break;
								}
								$default = ( empty( $option['default'] ) ? $allowed_values[0] : $option['default'] );
								$ret_val = in_array( $option_value, $allowed_values, true ) ? $option_value : $default;
								break;
							default:
								$ret_val = $option_value;
								break;
						}

						$value[ $index ][ $option_id ] = $ret_val;
					}
				}
			}

			return $value;
		}

		/**
		 * Save options
		 *
		 * @param array $options Options for save
		 * @since 1.0
		 */
		public static function save( $options, $data = null ) {
			if ( is_null( $data ) ) {
				$data = $_POST; // WPCS: input var okay, CSRF ok.
			}

			if ( empty( $data ) ) {
				return false;
			}

			$update_options = array();

			foreach ( $options as $key => $option ) {

				if ( ! isset( $option['type'] ) ) {
					continue;
				}

				$option_name  = $key;
				$option_value = isset( $data[ $key ] ) ? wp_unslash( $data[ $key ] ) : null;

				// Preprocess the value base on option type
				switch ( $option['type'] ) {
					case 'switcher':
						$value = '1' === $option_value || 'yes' === $option_value ? 'yes' : 'no';
						break;
					case 'checkbox':
						$allowed_values = empty( $option['options'] ) ? array() : array_map( 'strval', array_keys( $option['options'] ) );
						if ( empty( $option['default'] ) && empty( $allowed_values ) ) {
							$value = null;
							break;
						}

						if ( is_array( $option_value ) ) {
							$value = array_filter(
								$option_value,
								function( $checked ) use ( $allowed_values ) {
									return in_array( $checked, $allowed_values );
								}
							);
							$value = json_encode( $value );
						} else {
							$value = '';
						}
						break;
					case 'select':
						$allowed_values = empty( $option['options'] ) ? array() : array_map( 'strval', array_keys( $option['options'] ) );
						if ( empty( $option['default'] ) && empty( $allowed_values ) ) {
							$value = null;
							break;
						}
						$default = ( empty( $option['default'] ) ? $allowed_values[0] : $option['default'] );
						$value   = in_array( $option_value, $allowed_values, true ) ? $option_value : $default;
						break;
					case 'repeater':
						$value = null;
						break;
					default:
						$value = $option_value;
						break;
				}

				if ( is_null( $value ) ) {
					continue;
				}

				$update_options[ $option_name ] = $value;
			}
			// Save all options in our array.
			foreach ( $update_options as $name => $value ) {
				update_option( $name, $value );
			}
		}

		/**
		 * Reset options
		 *
		 * @param array $options Options for reset
		 * @since 1.0
		 */
		public static function reset( $options ) {
			foreach ( $options as $key => $option ) {
				if ( ! isset( $option['type'] ) || 'section_start' == $option['type'] || 'section_end' == $option['type'] ) {
					continue;
				}

				global $alpus_pf_options;
				if ( isset( $option['default'] ) ) {
					update_option( $key, $option['default'] );
				} elseif ( isset( $alpus_pf_options[ $key ] ) ) {
					update_option( $key, $alpus_pf_options[ $key ] );
				} else {
					delete_option( $key );
				}
			}
		}
	}
}
