<?php
/**
 * AI Product Review Summary - Single Product Page
 * 
 * @since 1.1.0
 */

// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

require_once ALPUS_PLUGIN_FRAMEWORK_PATH . 'admin/options/class-plugin-options.php';

class Alpus_APRS_SP {
	/**
	 * The Constructor
     *
	 * @since 1.1.0
	 * @access public
	*/
	public function __construct() {
        $post_types = array( 'product_variation' );
        if ( ( 'post-new.php' == $GLOBALS['pagenow'] && isset( $_REQUEST['post_type'] ) && ! in_array( $_REQUEST['post_type'], $post_types ) ) || ( 'post.php' == $GLOBALS['pagenow'] && isset( $_REQUEST['post'] ) && ! in_array( get_post_type( $_REQUEST['post'] ), $post_types ) ) ) {
            add_filter( 'rwmb_meta_boxes', array( $this, 'add_meta_boxes' ) );
            // Backend JS & Styles
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ), 99 );
        }
        
        add_action( 'wp_ajax_alpus_aprs_sp_info', array( $this, 'get_current_status' ) );
        add_action( 'wp_ajax_alpus_aprs_sp_summary', array( $this, 'get_sp_summary' ) );
        add_action( 'wp_ajax_alpus_aprs_sp_ai_advice', array( $this, 'get_sp_advice' ) );
        add_action( 'wp_ajax_alpus_aprs_sp_ai_detail', array( $this, 'get_sp_ai_detail_advice' ) );
        add_action( 'wp_ajax_alpus_aprs_chat_query', array( $this, 'sp_ai_chat_query' ) );
        

        add_action( 'alpus_aprs_review_bg_generate', array( $this, 'generate_step' ), 1 );
	}

    /**
     * Get AI Chat Query Answer
     * 
     * @since 2.0.0
     * @access public
     */
    public function sp_ai_chat_query() {
        if ( ! empty( $_REQUEST['query'] ) ) {
            $query = sanitize_text_field( $_REQUEST['query'] );

            $result = apply_filters( 'alpus_aprs_sp_chat_query', false, $query );

            if ( ! empty( $result ) ) {
                wp_send_json_success( nl2br( trim( $result ) ) );
            }
        }

        die();
    }

    /**
     * Get Single Product AI Marketing Advice
     * 
     * @since 2.0.0
     * @access public
     */
    public function get_sp_ai_detail_advice() {
        if ( ! empty( $_REQUEST['id'] ) && ! empty( $_REQUEST['type'] ) ) {
            $id   = sanitize_text_field( $_REQUEST['id'] );
            $type = sanitize_text_field( $_REQUEST['type'] );

            $result = apply_filters( 'alpus_aprs_sp_ai_detail', false, $id, $type );

            if ( ! empty( $result ) ) {
                wp_send_json_success( nl2br( trim( $result ) ) );
            }
        }

        die();
    }

    /**
     * Get Single Product AI Advice
     * 
     * @since 2.0.0
     * @access public
     */
    public function get_sp_advice() {
        if ( ! empty( $_REQUEST['id'] ) ) {
            $id = sanitize_text_field( $_REQUEST['id'] );

            $result = base64_decode( get_post_meta( $id, 'alpus_aprs_ai_advice', true ) );

            if ( ! empty( $result ) ) {
                    
                wp_send_json_success( nl2br( trim( $result ) ) );
            }
        }

        die();
    }

    /**
     * Get Single Product Review Summary
     * 
     * @since 2.0.0
     * @access public
     */
    public function get_sp_summary() {
        if ( ! empty( $_REQUEST['id'] ) ) {
            $id = sanitize_text_field( $_REQUEST['id'] );

            $summary = json_decode( base64_decode( get_post_meta( $id, 'alpus_aprs_content_data', true ) ), true );

            if ( ! empty( $summary ) ) {
                $result = array(
                    'summary' => $summary['summary'],
                    'pros'    => nl2br( $summary['pros'] ),
                    'cons'    => nl2br( $summary['cons'] ),
                );
        
                wp_send_json_success( $result );
            }    
        }

        die();
    }

    /**
     * Render MetaBox Descriptin Section for APRS
     * 
     * @since 2.0.0
     * @access public
     */
    public function render_metabox_desc() {
        ob_start();
        ?>
        <div class="alpus-aprs-sp-status-wrapper">
            <div class="alpus-aprs-sp-summarize-status">
                <h4><?php echo esc_html__( 'Current Status:', 'alpus-aprs' ); ?></h4>
                <span class="current">0</span><?php echo esc_html__( 'of', 'alpus-aprs' ); ?><span class="total">0</span><?php echo esc_html__( 'Reviews are summarized.', 'alpus-aprs' ); ?>
            </div>
            <div class="alpus-aprs-sp-summarize-wrapper">
                <a href="#" class="alpus-aprs-sp-summary-view button" data-loading-text="<?php echo esc_attr__( 'Loading...', 'alpus-aprs' ); ?>"><?php echo esc_html__( 'View Summary', 'alpus-aprs' ); ?></a>
                <div class="alpus-aprs-sp-summarize-popup">
                    <div class="alpus-aprs-popup-header">
                        <h4 class="alpus-aprs-popup-title"><?php echo esc_html__( 'AI Advice for Product Sales', 'alpus-aprs' ); ?></h4>
                        <span class="alpus-aprs-popup-close">×</span>
                    </div>
                    <div class="alpus-aprs-popup-wrapper">
                        <div class="alpus-aprs-sp-summary">
                            <h5><?php echo esc_html__( 'Summary', 'alpus-aprs' ); ?></h5>
                            <p></p>
                        </div>
                        <div class="alpus-aprs-sp-pros">
                            <h5><?php echo esc_html__( 'Pros', 'alpus-aprs' ); ?></h5>
                            <p></p>
                        </div>
                        <div class="alpus-aprs-sp-cons">
                            <h5><?php echo esc_html__( 'Cons', 'alpus-aprs' ); ?></h5>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alpus-aprs-sp-ai-advice-wrapper">
                <h4><?php echo esc_html__( 'AI Sales Manager:', 'alpus-aprs' ); ?></h4>
                <a href="#" class="alpus-aprs-sp-ai-advice-view button" data-loading-text="<?php echo esc_attr__( 'Loading...', 'alpus-aprs' ); ?>"><?php echo esc_html__( 'Get Advice', 'alpus-aprs' ); ?></a>
                <div class="alpus-aprs-sp-ai-advice-popup">
                    <div class="alpus-aprs-popup-header">
                        <h4 class="alpus-aprs-popup-title"><?php echo esc_html__( 'AI Advice for Product Sales', 'alpus-aprs' ); ?></h4>
                        <span class="alpus-aprs-popup-close">×</span>
                    </div>
                    <div class="alpus-aprs-popup-wrapper">
                        <ul class="alpus-aprs-ai-questions">
                            <li class="alpus-aprs-ai-question active"><a href="#general"><?php echo esc_html__( 'General Guide', 'alpus-aprs' ); ?></a></li>
                            <li class="alpus-aprs-ai-question"><a href="#marketing"><?php echo esc_html__( 'How to build Marketing Strategy?', 'alpus-aprs' ); ?></a></li>
                            <li class="alpus-aprs-ai-question"><a href="#quality"><?php echo esc_html__( 'What is the best way to improve quality?', 'alpus-aprs' ); ?></a></li>
                            <li class="alpus-aprs-ai-question"><a href="#price"><?php echo esc_html__( 'How can we set the best price for this product?', 'alpus-aprs' ); ?></a></li>
                            <li class="alpus-aprs-ai-question"><a href="#chat"><?php echo esc_html__( 'Chat with AI', 'alpus-aprs' ); ?></a></li>
                        </ul>
                        <div class="alpus-aprs-ai-body">
                            <div class="loading">
                                <h2><?php echo esc_html__( 'Loading Data', 'alpus-aprs' ); ?></h2>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <div class="alpus-aprs-sp-ai-advice general show" id="alpus-aprs-general"></div>
                            <div class="alpus-aprs-sp-ai-advice marketing" id="alpus-aprs-marketing"></div>
                            <div class="alpus-aprs-sp-ai-advice quality" id="alpus-aprs-quality"></div>
                            <div class="alpus-aprs-sp-ai-advice price" id="alpus-aprs-price"></div>
                            <div class="alpus-aprs-sp-ai-advice chat" id="alpus-aprs-chat">
                                <div class="alpus-aprs-ai-chat-wrapper">
                                    <div class="alpus-aprs-ai-chat-list">

                                    </div>
                                    <div class="alpus-aprs-ai-chat-input-wrapper">
                                        <input type="text" class="alpus-aprs-ai-chat-input">
                                        <button class="alpus-aprs-ai-chat-send"><?php echo esc_html__( 'Send', 'alpus-aprs' ); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alpus-aprs-sp-tags-wrapper">
                <h4><?php echo esc_html__( 'Tags:', 'alpus-aprs' ); ?></h4>
                <ul class="alpus-aprs-sp-tags"></ul>
            </div>
        </div>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public function add_meta_boxes() {
        if ( isset( $_REQUEST['post_type'] ) ) {
            $post_type = $_REQUEST['post_type'];
        } else if ( isset( $_REQUEST['post'] ) ) {
            $post_type = get_post_type( $_REQUEST['post'] );
        }
        $meta_boxes[] = array(
            'id'         => 'alpus-aprs-status',
            'title'      => esc_html__( 'AI Product Review Summary', 'alpus-aprs' ),
            'post_types' => array( $post_type ),
            'context'    => 'side',
            'priority'   => 'high',
            'fields'     => array(
                array(
                    'id'    => 'alpus-aprs-sp-status',
                    'class' => 'alpus-aprs-sp-status',
                    'type'  => 'custom_html',
                    'std'  => $this->render_metabox_desc(),
                ),
            ),
        );
        return $meta_boxes;
    }

    /**
     * Get Current Status
     * 
     * @since 1.1.0
     */
    public function get_current_status() {
        if ( ! empty( $_REQUEST['id'] ) ) {
            $id = (int) sanitize_text_field( $_REQUEST['id'] );
            $product = wc_get_product( $id );
    
            // Get Summarized Review IDs.
            $summarized_reivews = get_post_meta( $id, 'alpus_aprs_summarized_reviews', true );

            if ( ! empty( $summarized_reivews ) ) {
                $summarized_reivews = json_decode( $summarized_reivews, true );
            } else {
                $summarized_reivews = array();
            }
    
            // Get Total Product Reviews Count
            $total_reviews_count = $product->get_review_count();

            // Get Summarized Tags
            $all_tags        = Alpus_Plugin_Options::get_option( 'alpus_aprs_tags' );
            $summarized_tags = get_post_meta( $id, 'alpus_aprs_sp_tag', false );
            $result_tags     = array();
            $all_tags_title  = false;

            if ( ! empty( $all_tags ) ) {
                $all_tags_title = explode( ', ', $all_tags );
                $temp = array();

                if ( ! empty( $all_tags_title ) && is_array( $all_tags_title ) ) {
                    foreach( $all_tags_title as $all_tag_title ) {
                        if ( in_array( sanitize_title( $all_tag_title ), $summarized_tags ) ) {
                            $result_tags[] = $all_tag_title;
                        }
                    }
                }
            }

            if ( empty( $result_tags ) ) {
                $result_tags = false;
            }
    
            $result = array(
                'current' => count( $summarized_reivews ),
                'total'   => $total_reviews_count,
                'tags'    => $result_tags,
            );

            wp_send_json_success( $result );
        }

        die();
    }

    /**
     * Start Background Summary
     * 
     * Start running background process for summarizing remaining reviews.
     * 
     * @since 1.1.0
     * 
     * @param $id Product ID
     */
    static public function start_background_summary( $id ) {
        // as_unschedule_all_actions( 'alpus_aprs_review_bg_generate', array(), '' );

        if ( false === as_has_scheduled_action( 'alpus_aprs_review_bg_generate' ) ) {
            update_option( 'alpus_aprs_bg_running_index', 0 );

            // Repeat generate step once in a day. - 86400 seconds
			as_schedule_recurring_action( strtotime( 'now' ), 5, 'alpus_aprs_review_bg_generate', array(), '', true );
		}

        // Get Background Process Running Product IDs.
        $running_ids = get_option( 'alpus_aprs_bg_running_ids', array() );

        // If it is not included in running process.
        if ( false === in_array( $id, $running_ids ) ) {
            $running_ids[] = $id;

            // Update Background Process Running Product IDs.
            update_option( 'alpus_aprs_bg_running_ids', $running_ids );
        }
    }

    /**
     * Generate Step
     * 
     * @since 1.1.0
     */
    static public function generate_step() {
        // Get Background Running Product IDs.
        $running_ids = get_option( 'alpus_aprs_bg_running_ids', array() );

        // Get current index.
        $current_index = get_option( 'alpus_aprs_bg_running_index', 0 );

        $id = $running_ids[ $current_index ];

        $product = wc_get_product( $id );
    
        // Get Summarized Review IDs.
        $summarized_reivews = get_post_meta( $id, 'alpus_aprs_summarized_reviews', true );

        if ( ! empty( $summarized_reivews ) ) {
            $summarized_reivews = json_decode( $summarized_reivews, true );
        } else {
            $summarized_reivews = array();
        }

        // Get Total Product Reviews Count
        $total_reviews_count = $product->get_review_count();
                
        if ( 10 <= ( $total_reviews_count - count( $summarized_reivews ) ) ) {
            // Summarize remain 10 reviews
            $result = apply_filters( 'alpus_aprs_summarize_remain_reviews', false, $id, $summarized_reivews );
        }
     
        $next_index = ( $current_index + 1 ) % count( $running_ids );

        update_option( 'alpus_aprs_bg_running_index', $next_index );
    }

    /**
     * Enqueue JS & Styles for Backend.
     * 
     * @since 1.1.0
     * @access public
     */
    public function enqueue_admin() {
        wp_enqueue_style( 'alpus-aprs-sp-admin', ALPUS_APRS_URI . 'assets/css/alpus-aprs-sp-admin.min.css', array(), ALPUS_APRS_VERSION );

        // Single Product Admin JS
        wp_enqueue_script( 'alpus-aprs-sp-admin', ALPUS_APRS_URI . 'assets/js/alpus-aprs-sp-admin' . ALPUS_PLUGIN_JS_SUFFIX, array( 'jquery-core' ), ALPUS_APRS_VERSION );
        wp_localize_script( 'alpus-aprs-sp-admin', 'alpus_aprs_sp_admin_vars', array(
            'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
            'nonce'    => wp_create_nonce( 'alpus-aprs-sp-admin' ),
        ) );
    }
}

new Alpus_APRS_SP;