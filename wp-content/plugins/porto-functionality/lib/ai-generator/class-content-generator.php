<?php

/**
 * Porto Content Generator - generate content or excerpt by using GPT-3
 * 
 * @author     Porto Themes
 * @category   Porto Ai Engine
 * @since      2.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Porto_Content_Generator' ) ) :
	class Porto_Content_Generator {

		// Admin Post types that AI panel isn't showing
		public $exclude_types = array( 'porto_builder', 'ptu', 'cptm', 'yith-wcbm-badge', 'acf-field-group' );

		public function __construct() {
			add_action( 'admin_footer', array( $this, 'add_dialog' ), 99 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ), 20 );
			add_filter( 'porto_js_admin_vars', array( $this, 'add_generator_vars' ) );
			add_action( 'add_meta_boxes', array( $this, 'porto_add_ai_meta_boxes' ) );
		}

		/**
		 * Add AI meta Fields
		 * Except page of plugin which adds custom post type - Post Type Unlimted, Custom Post Type Maker
		 * 
		 * @since 2.8.0
		 */
		public function porto_add_ai_meta_boxes() {
			$screen = get_current_screen();
			if ( function_exists( 'add_meta_box' ) && $screen && 'post' == $screen->base && ! in_array( $screen->id, $this->exclude_types ) ) {
				add_meta_box( 'porto-api-engine', __( 'Porto AI Engine', 'porto-functionality' ), 'porto_add_ai_meta_box', $screen->id, 'side', 'high' );
			}
		}

		/**
		 * Add AI Dialog
		 * 
		 * @since 2.8.0
		 */
		public function add_dialog() {
			$screen = get_current_screen();
			if ( $screen && 'post' == $screen->base && ! in_array( $screen->id, $this->exclude_types ) ) {
				global $porto_settings;
				ob_start();
				$this->add_style();
				echo ob_get_clean();
				if ( ! empty( $porto_settings['ai-gpt-key'] ) ) :?>
					<div class="porto-dialog-wrapper porto-ai-dialog hide">
						<div class="porto-dialog-overlay">
						</div>
						<div class="porto-admin-dialog">
							<div class="porto-dialog-header">
								<h3 class="porto-dialog-title"></h3>
							</div>
							<div class="porto-dialog-content">
								<textarea class="output" id="ai-output"></textarea>
								<i class="porto-ajax-loader"></i>
							</div>
							<div class="porto-dialog-footer">
								<button class="button button-primary porto-dialog-btn btn-copy" name="Copy"><?php esc_html_e( 'Copy to Clipboard', 'porto-functionality' ); ?></button>
								<button class="button button-primary porto-dialog-btn btn-close" name="Close"><?php esc_html_e( 'Close', 'porto-functionality' ); ?></button>
							</div>
						</div>
					</div>
				<?php
				endif;
			}
		}

		/**
		 * Enqueue js for Ai engine
		 * 
		 * @since 2.8.0
		 */
		public function enqueue_script() {
			global $porto_settings;
			$screen = get_current_screen();		
			
			if ( ! empty( $porto_settings['ai-gpt-key'] ) && $screen && 'post' == $screen->base && ! in_array( $screen->id, $this->exclude_types ) ) {
				wp_enqueue_script( 'porto-ai-engine', plugin_dir_url( __FILE__ ) . 'ai-generator.min.js', array( 'jquery-core' ), PORTO_FUNC_VERSION, true );
			}
		}

		/**
		 * Add vars to js_porto_admin_vars
		 * 
		 * @since 2.8.0
		 */
		public function add_generator_vars( $vars ) {
			global $porto_settings;
			$screen = get_current_screen();
			if ( ! empty( $porto_settings['ai-gpt-key'] ) && $screen && 'post' == $screen->base && ! in_array( $screen->id, $this->exclude_types ) ) {
				$vars['ai_key'] = $porto_settings['ai-gpt-key'];
				if ( 'post' == $screen->id ) {
					$vars['post_type'] = 'blog';
				} else {
					$vars['post_type'] = $screen->id;
				}

			}
			return $vars;
		}

		/**
		 * Add styles for metabox and dialog
		 * 
		 * @since 2.8.0
		 */
		public function add_style() {
			?>
				<style>
					#porto-api-engine h3 { padding-top: 0 !important; margin: 0 0 10px 0 !important; font-size: 13px; text-transform: capitalize; }
					#porto-api-engine .inside .metabox .box-option {
						margin-right: 0;
						padding-top: 0;
					}
					#porto-api-engine .postbox-header { background-color: #2271b1 !important; }
					#porto-api-engine .postbox-header > *,
					#porto-api-engine .postbox-header .button,
					#porto-api-engine .postbox-header button,
					#porto-api-engine .postbox-header span{color: #fff !important;}
			<?php
			global $porto_settings;
			if ( ! empty( $porto_settings['ai-gpt-key'] ) ) {
				?>
					.porto-ai-dialog.loading i.porto-ajax-loader {
						display: block;
					}
					.porto-ai-dialog.loading .btn-copy,
					.porto-ai-dialog.loading .output {
						visibility: hidden;
						opacity: 0;
					}
					.porto-ai-dialog.loading .btn-close {
						cursor: no-drop;
						background-color: #222529;
					}
					.porto-ai-dialog .output {
						width: 100%;
						height: 250px;
						display: block;
						max-height: 300px;
					}
					.porto-ai-dialog.hide {
						display: none;
					}
					.porto-ai-dialog .button {
						line-height: 1.2 !important;
					}
					.porto-ai-dialog .porto-dialog-title { 
						font-size: 20px;
						line-height: 1.2;
					}
					#porto-api-engine .ai_generate { width: 100%; }
					#porto-api-engine .porto-meta-tab .metabox:first-child { display: none; }
					#porto-api-engine .porto-meta-tab select { 
						max-width: calc( 100% - 2px );
					}
					#porto-api-engine .porto-meta-tab .metabox { padding-left: 0; padding-right: 0; }
					#porto-api-engine .porto-meta-tab .metabox .metainner {
						padding: 0;
						width: 100%;
					}
					#user_word, #ai_topic { height: 100px; margin: 1px; width: calc( 100% - 2px ); }
					#ai_topic { height: 70px; }
					#user_word:focus { outline: 1px solid #08c; }
					
					/* Seo Plugin */
					.button-plugin-gen { margin: 0 0 5px 10px !important; height: 100% !important; }
					#aioseo-post-settings-meta-description-row .button-plugin-gen { margin: 0 0 0 auto !important; }
					/* Rank Math Seo */
					.rank-math-editor-general [for="rank-math-editor-description"] {
						display: inline-flex !important;
					}
					.aioseo-post-settings-modal #aioseo-post-settings-meta-description-row .add-tags {
						position: static;
						margin-bottom: 10px;
					}
					.rank-math-editor-general .is-primary { height: 24px !important; vertical-align: middle; }
				<?php
			} else {
				?>
					#porto-api-engine .porto-meta-tab .metabox:not(:first-child) { display: none; }
				<?php
			}
			echo '</style>';
		}
	}

	new Porto_Content_Generator();
endif;