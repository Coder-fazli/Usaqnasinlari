<?php

// Direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

require_once ALPUS_PLUGIN_FRAMEWORK_PATH . 'admin/options/class-plugin-options.php';
require_once ALPUS_APRS_PATH . 'inc/functions/single-product.php';
if ( 'yes' == Alpus_Plugin_Options::get_option( 'alpus_aprs_show_tooltip' ) ) { 
    require_once ALPUS_APRS_PATH . 'inc/functions/archive-product.php';
}

// If Option Enabled for Shop Filter.
if ( 'yes' == Alpus_Plugin_Options::get_option( 'alpus_aprs_tags_enable' ) && 'yes' == Alpus_Plugin_Options::get_option( 'alpus_aprs_tags_enable_filter' ) ) {
    require_once ALPUS_APRS_PATH . 'inc/functions/shop-filter.php';
}

class Alpus_APRS {

    private $modules = array();

	/**
	 * The Constructor
     *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
        // Fronend JS & Styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 99 );

        // Backend JS & Styles
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ), 99 );

        add_action( 'init', array( $this, 'init' ) );

        $layout_type = Alpus_Plugin_Options::get_option( 'alpus_aprs_layout_type' );

        if ( 'default' == $layout_type ) {
            add_action( 'woocommerce_single_product_summary', array( $this, 'template' ), 15 );
        } else if ( 'wide' == $layout_type ) {
            add_action( 'woocommerce_after_single_product_summary', array( $this, 'template' ), 0 );
        } else {
            add_shortcode( 'alpus_aprs_ai_summary', array( $this, 'template' ) );
        }

        // Ajax
        add_action( 'wp_ajax_alpus_apr_get_summary', array( $this, 'get_summary' ) );
        add_action( 'wp_ajax_nopriv_alpus_apr_get_summary', array( $this, 'get_summary' ) );

        // Backend Ajax
        add_action( 'wp_ajax_alpus_aprs_clear_cache', array( $this, 'clear_cache_all' ) );
        add_action( 'wp_ajax_nopriv_alpus_aprs_clear_cache', array( $this, 'clear_cache_all' ) );
        
        // Generate AI Product Review Summary.
        add_action( 'wp_ajax_alpus_aprs_generate_all', array( $this, 'generate_all' ) );
        add_action( 'wp_ajax_alpus_aprs_is_generating', array( $this, 'is_generating' ) );
        add_action( 'wp_ajax_alpus_aprs_generate_stop', array( $this, 'generate_stop' ) );

        // Update API Models
        add_action( 'wp_ajax_alpus_aprs_update_models', array( $this, 'update_models' ) );

        // Admin Bar
        add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_items' ), 9999 ); 

        // Background action
	    add_action( 'alpus_aprs_generate_all', array( $this, 'generate_step' ) );

        add_filter( 'alpus_aprs_summarize_remain_reviews', array( $this, 'summarize_remain_reviews' ), 10, 3 );

        // Get AI Consultant
        add_filter( 'alpus_aprs_sp_ai_detail', array( $this, 'get_detailed_consultant' ), 10, 3 );

        // Get AI Chat Query
        add_filter( 'alpus_aprs_sp_chat_query', array( $this, 'get_product_chat_query' ), 10, 2 );

        // Get Tags HTML
        add_filter( 'aprs_archive_product_summary_html', array( $this, 'get_product_tags_html' ), 10, 2);

        $this->clear_product_review_summary();
	}

    /**
     * Enqueue JS & Styles for Frontend.
     * 
     * @since 1.0.0
     * @access public
     */
    public function enqueue() {
        // Frontend Style
        wp_enqueue_style( 'alpus-aprs-frontend', ALPUS_APRS_URI . 'assets/css/alpus-aprs-frontend.min.css', array(), ALPUS_APRS_VERSION );

        // Frontend JS
        wp_enqueue_script( 'alpus-aprs-frontend', ALPUS_APRS_URI . 'assets/js/alpus-aprs-frontend.min.js', array( 'jquery-core', 'wp-i18n' ), ALPUS_APRS_VERSION, true );
        wp_localize_script( 'alpus-aprs-frontend', 'alpus_aprs_frontend_vars', array(
            'ajax_url'     => esc_url( admin_url( 'admin-ajax.php' ) ),
            'nonce'        => wp_create_nonce( 'alpus-aprs-frontend' ),
            'generate_all' => false === as_has_scheduled_action( 'alpus_aprs_generate_all' ) ? 'stop' : 'running',
        ) );
    }

    /**
     * Update Models
     * 
     * @since 1.0.0
     * @access public
     */
    public function update_models() {
        // Get API Key
        if ( empty( $_REQUEST['key'] )) {
            $api_key = get_option( 'alpus_aprs_api_key' );
        } else {
            $api_key = $_REQUEST['key'];
        }

        // OpenAI API URL
        $url = 'https://api.openai.com/v1/models';

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
        );
        
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
        $response = curl_exec( $ch );

        if ( curl_error( $ch ) ) {
            wp_send_json_error( $response );
        }
        
        curl_close( $ch );
        
        $models = json_decode( $response, true )['data'];

        $result = array();

        foreach( $models as $model ) {
            $result[ $model[ 'id' ] ] = $model[ 'id' ];
        }

        set_site_transient( 'alpus_aprs_api_models', $result, WEEK_IN_SECONDS );

        wp_send_json_success( $result );
    }

    /**
     * Enqueue JS & Styles for Backend
     * 
     * @since 1.0.0
     * @access public
     */
    public function enqueue_admin() {
        // Backend Style
        wp_enqueue_style( 'alpus-aprs-backend', ALPUS_APRS_URI . 'assets/css/alpus-aprs-backend.min.css', array(), '1.0' );

        // Backend JS
        wp_enqueue_script( 'alpus-aprs-backend', ALPUS_APRS_URI . 'assets/js/alpus-aprs-backend.min.js', array( 'jquery-core' ), '1.0', true );
        wp_localize_script( 'alpus-aprs-backend', 'alpus_aprs_backend_vars', array(
            'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
            'nonce'    => wp_create_nonce( 'alpus-aprs-backend' ),
        ) );
    }

    /**
     * Product Review Summary Template
     * 
     * @since 1.0.0
     * @access public
     */
    public function template() {
        $title = Alpus_Plugin_Options::get_option( 'alpus_aprs_summary_title' );

        require_once( 'templates/summary.php' );
    }

    /**
     * Get AI Summary by Product ID
     * 
     * @since 1.0.0
     * @since 1.1.0 - Summarize newly added reviews & Summarize all reviews step by step in background process.
     * @param $id 
     * @access public
     */
    public function get_summary_by_ID( $id ) {
        $aprs_sp = new Alpus_APRS_SP();
        // Get Saved Summary from Post Meta.
        $content = get_post_meta( $id, 'alpus_aprs_content', true );
        
        $expired     = ! get_site_transient( 'alpus_aprs_content_' . $id, false );
        $product_ids = get_option( 'alpus_aprs_generated_products', array() );

        /**
         * Fix duplicated products from previous version issue.
         * 
         * @since 2.1.0
         */
        $product_ids = array_unique( $product_ids );
        
        // Alpus_APRS_SP::start_background_summary( $id );

        if ( ! empty( $content ) && ! $expired ) {
            return $content;
        } else {
            delete_post_meta( $id, 'alpus_aprs_summarized_reviews' );
            $summarized_reivews = array();

            if ( ! in_array( $id, $product_ids ) && count( $product_ids ) >= 100 ) {
                return esc_html__( 'We apologize, but you have exceeded the limit for the free version of the AI product review summary plugin. Please purchase pro version from ', 'alpus-aprs' ) . '<a href="https://alpustheme.com/" target="_blank" rel="noopener noreferrer">AlpusTheme</a>.';
            }
            
            $args = array (
                'post_type' => 'product',       
                'status'    => 'approve',
                'type'      => 'review',
                'post_id'   => $id,
                'number'    => 100,
                'meta_key'  => 'rating',
                'orderby'   => 'meta_value_num',
                'order'     => 'DESC',
            );

            // Get Reviews from Product
            $reviews = get_comments( $args );

            if ( count( $reviews ) > 30 ) {
                $reviews = array_merge( array_slice( $reviews, 0, 24 ), array_slice( $reviews, -6 ) );
            }

            if ( empty( $reviews ) ) {
                return esc_html__( 'No Review...', 'alpus-aprs' );
            }

            $summaries  = array();
            $errors     = '';
            $word_count = 0;
            $comments   = '';

            $reivew_count = count( $reviews );

            for( $i = 0 ; $i < $reivew_count ; $i ++ ) {
                $summarized_reivews[] = $reviews[ $i ]->comment_ID;

                if ( strlen( $reviews[ $i ]->comment_content ) > 6000 ) {
                    continue;
                }

                $word_count += strlen( $reviews[ $i ]->comment_content );

                if ( $word_count < 6000 ) {
                    $comments .= '"' . $reviews[ $i ]->comment_content . '", ';
                } else {
                    $result = $this->get_summary_from_openai( $comments );

                    if ( ! empty( $result['status'] ) && true == $result['status'] ) {
                        $summaries[] = $result['content'];
                    } else {
                        $errors = $result['content'];
                    }

                    $word_count  = strlen( $reviews[ $i ]->comment_content );
                    $comments    = '"' . $reviews[ $i ]->comment_content . '", ';
                }
            }

            // Save summarized reviews
            update_post_meta( $id, 'alpus_aprs_summarized_reviews', json_encode( $summarized_reivews, true ) );

            if ( ! empty( $comments ) ) {
                $result = $this->get_summary_from_openai( $comments );

                if ( true == $result['status'] ) {
                    $summaries[] = $result['content'];
                } else {
                    $errors = $result['content'];
                }
            }

            if ( count( $summaries ) > 1 ) {
                $final_summary = $this->get_final_summary( $summaries );

                if ( isset( $final_summary['status'] ) && false == $final_summary['status'] ) {
                    $errors        = $final_summary['content'];
                    $final_summary = false;
                }

            } else if ( 1 == count( $summaries ) ) {
                $final_summary = $summaries[0];
            }

            if ( ! empty( $final_summary ) ) {
                // JSON decode from summary json data.
                if ( ! is_array( $final_summary ) ) {
                    $final_data = json_decode( $final_summary, true );
                } else {
                    $final_data = $final_summary;
                }

                if ( 1 == count( $summaries ) ) {
                    $final_data['pros'] = str_replace( ',', '<br/>- ', $final_data['pros'] );
                    $final_data['cons'] = str_replace( ',', '<br/>- ', $final_data['cons'] );

                    $result_html = '<span class="alpus-aprs-summary-label">Summary:</span>' . $final_data['summary'] . '<br/><br/><span class="alpus-aprs-pros-label">Pros:</span>- ' . $final_data['pros'] . '<br/><br/><span class="alpus-aprs-cons-label">Cons:</span>- ' . $final_data['cons'];
                } else {
                    $result_html = '<span class="alpus-aprs-summary-label">Summary:</span>' . $final_data['summary'] . '<br/><br/><span class="alpus-aprs-pros-label">Pros:</span>' . $final_data['pros'] . '<br/><br/><span class="alpus-aprs-cons-label">Cons:</span>' . $final_data['cons'];
                }

                update_post_meta( $id, 'alpus_aprs_content_data', base64_encode( json_encode( $final_data ) ) );

                update_post_meta( $id, 'alpus_aprs_content', $result_html );

                // Get Tags from summary.
                $tags = $this->get_product_tags( $id );

                if ( ! empty( $tags ) ) {
                    delete_post_meta( $id, 'alpus_aprs_sp_tag' );

                    foreach( $tags as $tag ) {
                        add_post_meta( $id, 'alpus_aprs_sp_tag', $tag );
                    }
                }

                // Get AI Advice for Product
                $advice = $this->get_product_advice( $id );

                if ( ! empty( $tags ) ) {
                    delete_post_meta( $id, 'alpus_aprs_ai_advice' );
                    update_post_meta( $id, 'alpus_aprs_ai_advice', base64_encode( $advice ) );
                }

                set_site_transient( 'alpus_aprs_content_' . $id, true, Alpus_Plugin_Options::get_option( 'alpus_aprs_clear_timeout' ) );

                // Product Limit
                if ( ! in_array( $id, $product_ids ) ) {
                    $product_ids[] = $id;
                }

                update_option( 'alpus_aprs_generated_products', $product_ids );

                // Start background running process for summarizing remaining reviews.
                // Alpus_APRS_SP::start_background_summary( $id );

                return $result_html;
            } else {
                return array(
                    'status' => false,
                    'error'  => $errors,
                );
            }
        }
    }

    /**
     * Get Final Summary
     * 
     * @since 1.0.0
     * @param $summaries Array
     * @access public
     */
    public function get_final_summary( $summaries ) {
        // Initialize values.
        $result_summaries = array();
        $summary_count    = count( $summaries );
        $error            = '';
        $pros             = array();
        $cons             = array();
        $descriptions     = array();

        // Split summary data into Summary, Pros and Cons.
        foreach( $summaries as $summary ) {
            // JSON decode from summary json data.
            $summary_data = json_decode( $summary, true );

            $descriptions[] = empty( $summary_data['summary'] ) ? '' : $summary_data['summary'];
            $pros[]         = empty( $summary_data['pros'] ) ? '' : $summary_data['pros'];
            $cons[]         = empty( $summary_data['cons'] ) ? '' : $summary_data['cons'];
        }

        // Summarize summary, pros and cons.
        $result_summary = $this->get_summarize_data( $descriptions, 'summary' );
        $result_pros    = $this->get_summarize_data( $pros, 'pros' );
        $result_cons    = $this->get_summarize_data( $cons, 'cons' );

        $result = array();

        // Get summary data.
        if ( isset( $result_summary['status'] ) && true == $result_summary['status'] ) {
            $result['summary'] = trim( $result_summary['content'] );
            
        } else if ( ! empty( $result_summary['error'] ) ) {
            return array(
                'status' => false,
                'error'  => $result_summary['error'],
            );
        }

        // Get pros data.
        if ( isset( $result_pros['status'] ) && true == $result_pros['status'] ) {
            $result['pros'] = trim( $result_pros['content'] );
        } else if ( ! empty( $result_pros['error'] ) ) {
            return array(
                'status' => false,
                'error'  => $result_pros['error'],
            );
        }

        // Get cons data.
        if ( isset( $result_cons['status'] ) && true == $result_cons['status'] ) {
            $result['cons'] = trim( $result_cons['content'] );
        } else if ( ! empty( $result_cons['error'] ) ) {
            return array(
                'status' => false,
                'error'  => $result_cons['error'],
            );
        }

        return $result;
    }

    /**
     * Summarize data
     * 
     * @since 1.0.0
     * @param $queries Array
     * @param $type String values: desc, pros, cons
     */
    public function get_summarize_data( $queries, $type = 'description' ) {
        // Initialize values.
        $result_data = array();
        $count       = count( $queries );
        $word_count  = 0;
        $query       = '';

        for( $i = 0 ; $i < $count ; $i ++ ) {
            // Array String.
            if ( is_array( $queries[ $i ] ) ) {
                $queries[ $i ] = implode( ', ', $queries[ $i ] ); 
            }

            $word_count += strlen( $queries[ $i ] );

            if ( $word_count < 4096 ) {
                $query .= '"' . $queries[ $i ] . '", ';
            } else {
                $result = $this->get_summary_from_openai( $query, $type );

                if ( ! empty( $result['status'] ) && true == $result['status'] ) {
                    $result_data[] = $result['content'];
                } else {
                    $errors = $result['content'];
                }

                $word_count = strlen( $queries[ $i ] );
                $query      = '"' . $queries[ $i ] . '", ';
            }
        }

        if ( ! empty( $query ) ) {
            $result = $this->get_summary_from_openai( $query, $type );

            if ( true == $result['status'] ) {
                $result_data[] = $result['content'];
            } else {
                $errors = $result['content'];
            }
        }

        // If result data count is more than 2, run summarize data function again with result data.
        if ( count( $result_data ) > 1 ) {
            $final_summary = $this->get_summarize_data( $result_data, $type );

            if ( true == $final_summary['status'] ) {
                $final_summary = $final_summary['content'];
            } else {
                $errors        = $final_summary['content'];
                $final_summary = false;
            }
        } else if ( 1 == count( $result_data ) ) {
            $final_summary = $result_data[0];
        }

        if ( ! empty( $final_summary ) ) {
            return array(
                'status'  => true,
                'content' => $final_summary,
            );
        }

        // Errors
        return array(
            'status' => false,
            'error'  => $error,
        );
    }

    /**
     * Get Summary from OpenAI
     * 
     * @since 1.0.0
     * @since 2.0.0 - Add new prompt for getting product tags. 
     * @param $comments Array
     * @access public
     */
    public function get_summary_from_openai( $contents, $type = false, $tags = array() ) {
        // OpenAI API credentials
        $api_key = Alpus_Plugin_Options::get_option( 'alpus_aprs_api_key' );
        // $model  = Alpus_Plugin_Options::get_option( 'alpus_aprs_text_model' );

        $model = 'gpt-3.5-turbo';

        // If JSON format
        $json_format = false;

        // Site Default Language Code
        $lang_name = get_bloginfo("language");

        // Input text for the API
        if ( false == $type ) {
            // $input_text = 'Please let me know summary, pros and cons of product reviews in detail from below comments as JSON format ' . ( empty( $lang_name ) ? '' : ( 'in language that its code is ' . $lang_name ) ) . ':\n';
            $input_text = 'Please let me know the more than 5 sentences of summary, pros main points and cons main points of product reviews from below comments in JSON format.
                For example: 
                {
                    "summary": "Lorem ipsum",
                    "pros": "Lorem ipsum, Lorem ipsum",
                    "cons": "Lorem ipsum, Lorem ipsum"
                }
            ';

            // Comments
            $input_text .= $contents;
            
            // Model for JSON object generation
            $model       = 'gpt-3.5-turbo-1106';
            $json_format = true;

        } else if ( is_string( $type ) ) {
            if ( 'pros' == $type || 'cons' == $type ) {
                $input_text = 'Please summarize below ' . $type . ' and get only 5 top points from them as list without title ' . ( empty( $lang_name ) ? '' : ( 'in language that its code is ' . $lang_name ) ) . ':
                    For example:
                    - A
                    - B
                    - C
                ';
            } else if ( 'summary' == $type ) {
                $input_text = 'Please summarize below summaries in detail more than 5 sentences without any prefix description ' . ( empty( $lang_name ) ? '' : ( 'in language that its code is ' . $lang_name ) ) . ':\n';
            } else if ( 'tags' == $type ) {
                $input_text = 'Please choose tags from list that only contains in below Product Summary as JSON format without any prefix description:\n' . implode( ', ', $tags ) . '.\n
                    Do not include any explanations, only provide a JSON response.

                    For example:
                    {
                        "A": true,
                        "B": false,
                        "C": true
                    }\n

                    Product Summary:\n
                ';
                
                // Model for JSON object generation
                $model       = 'gpt-3.5-turbo-1106';
                $json_format = true;
            } else if ( 'advice' == $type ) {
                $input_text = 'Could you give us advice for sales improvement from below product summary?\n';
            } else if ( 'marketing' == $type ) {
                $input_text = 'Could you let me know the Marketing strategy for below product in detail?\n';
            } else if ( 'quality' == $type ) {
                $input_text = 'Could you let me know how to improve its quality for below product in detail?\n';
            } else if ( 'price' == $type ) {
                $input_text = 'Could you let me know how to set the best price for below product in detail?\n';
            } else if ( 'chat' == $type ) {
                $input_text = '';   
            }

            // Comments
            $input_text .= $contents;
        } else {
            $input_text = 'Please summarize below descriptions and let me know its summary, pros and cons briefly. ' . ( empty( $lang_name ) ? '' : ( 'in language that its code is ' . $lang_name ) ) . ':';

            // Summaries
            foreach( $contents as $content ) {
                $input_text .= '"' . $content . '"\n';
            }
        }

        // API endpoint URL
        $apiUrl = 'https://api.openai.com/v1/chat/completions';

        $data = false;

        // Request payload
        if ( $json_format ) {
            $data = [
                'model'           => $model,
                'response_format' => array(
                    'type' => 'json_object',
                ),
                'messages'        => array(
                    array(
                        'role'    => 'system',
                        'content' => 'You are a helpful detailed assistant designed to output JSON.',
                    ),
                    array(
                        'role'    => 'user',
                        'content' => $input_text,
                    ),
                ),
                    
                'max_tokens'      => 2048,  // Maximum number of tokens in the generated response
            ];
        } else {
            $data = [
                'model'           => $model,
                'messages'        => array(
                    array(
                        'role'    => 'system',
                        'content' => 'You are a helpful detailed assistant.',
                    ),
                    array(
                        'role'    => 'user',
                        'content' => $input_text,
                    ),
                ),
                    
                'max_tokens'      => 2048,  // Maximum number of tokens in the generated response
            ];
        }
        

        // Headers
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
            'timeout: 20000',
            'Authorization: Bearer ' . $api_key,
        ];

        // Send POST request to the API
        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Execute the request
        $response = curl_exec($curl);

        // Check for errors
        if ($response === false) {
            return array(
                'status'  => false,
                'content' => curl_error( $curl ),
            );
        }

        // Close the curl session
        curl_close($curl);

        // Decode the response JSON
        $response_data = json_decode($response, true);

        // Check API Errors.
        if ( ! empty( $response_data['error'] ) ) {
            if ( 'rate_limit_exceeded' == $response_data['error']['code'] ) {
                // Wait 60s
                sleep(60);
                return $this->get_summary_from_openai( $contents, $type );
            } else {
                // Return Error Code.
                return array(
                    'status'  => false,
                    'content' => $response_data['error']['code'],
                );
            }
        }

        // Extract the generated text from the response
        $generated_text = isset( $response_data['choices'][0]['message']['content'] ) ? $response_data['choices'][0]['message']['content'] : '';

        error_log( $generated_text );

        // Output the generated text
        return array(
            'status'  => true,
            'content' => $generated_text,
        );
    }   

    /**
     * Get Summary Tags HTML
     * 
     * @since 2.0.0
     * @access public
     * 
     * @param $id Product ID
     * @return {string} HTML code for tags showing in Single Product page
     */
    public function get_summary_tags_html( $id ) {
        if ( 'no' == Alpus_Plugin_Options::get_option( 'alpus_aprs_tags_enable' ) || 'no' == Alpus_Plugin_Options::get_option( 'alpus_aprs_tags_enable_sp' ) ) {
            return '';
        }

        $tags = get_post_meta( $id, 'alpus_aprs_sp_tag' );

        $all_tags       = Alpus_Plugin_Options::get_option( 'alpus_aprs_tags' );
        $all_tags_title = false;
        $params         = false;

        if ( ! empty( $all_tags ) ) {
            $all_tags_title = explode( ', ', $all_tags );
            $temp = array();

            if ( ! empty( $all_tags_title ) && is_array( $all_tags_title ) ) {
                foreach( $all_tags_title as $all_tag_title ) {
                    $temp[] = sanitize_title( $all_tag_title );
                }

                $all_tags = $temp;
            }
            
        } else {
            $all_tags = array();
        }

        ob_start();
        ?>
        <div class="alpus-aprs-sp-tags-wrapper">
            <ul class="alpus-aprs-sp-tags">
            <?php
            if ( ! empty( $tags ) ) {
                foreach( $tags as $tag ) {
                    $pos = array_search( $tag, $all_tags );

                    if ( false !== $pos ) {
                    ?>
                        <li class="alpus-aprs-sp-tag"><?php echo esc_html( $all_tags_title[ $pos ] ); ?></li>
                    <?php
                    }
                }
            }
            ?>
            </ul>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * Get Summary by Ajax
     * 
     * @since 1.0.0
     * @access public
     */
    public function get_summary() {
        if ( empty( $_REQUEST['post_id'] ) ) {
            exit();
        }

        if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'alpus-aprs-frontend' ) ) {
            $id = $_REQUEST['post_id'];

            $summary = $this->get_summary_by_ID( $id );

            if ( ! is_array( $summary ) ) {
                $summary = $this->get_summary_tags_html( $id ) . nl2br( $summary );

                wp_send_json_success( $summary );
            } else {
                wp_send_json_error( $summary['error'] );
            }
        }

        exit();
    }

    /**
     * Clear Cach All
     * 
     * @since 1.0.0
     * @access public
     */
    public function clear_cache_all() {
        // Admin Role Only
        if ( ! is_admin() ) {
            exit();
        }

        $products = array();
        $page     = 1;

        do {
            $args = array(
                'status'            => array( 'draft', 'pending', 'private', 'publish' ),
                'type'              => array_merge( array_keys( wc_get_product_types() ) ),
                'limit'             => 10,
                'page'              => $page,
            );
            
            // Array of product objects
            $products = wc_get_products( $args );

            // Delete Product Review Summaries from Product Meta
            foreach( $products as $product ) {
                delete_post_meta( $product->get_id(), 'alpus_aprs_content' );
                delete_post_meta( $product->get_id(), 'alpus_aprs_content_data' );
                delete_post_meta( $product->get_id(), 'alpus_aprs_sp_tag' );
                delete_post_meta( $product->get_id(), 'alpus_aprs_consultant_marketing' );
                delete_post_meta( $product->get_id(), 'alpus_aprs_consultant_quality' );
                delete_post_meta( $product->get_id(), 'alpus_aprs_consultant_price' );
                delete_post_meta( $product->get_id(), 'alpus_aprs_ai_advice' );
            }

            $page ++;
        } while ( count( $products ) > 0 );

        wp_send_json_success();
        exit();
    }

    /**
     * Generate All Product Reviews
     * 
     * @since 1.0.0
     * @access public
     */
    public function generate_all() {
        if ( false === as_has_scheduled_action( 'alpus_aprs_generate_all' ) ) {
			as_schedule_recurring_action( strtotime( 'now' ), 300, 'alpus_aprs_generate_all', array(), '', true );

            // Remove running offset.
            update_option( 'alpus_aprs_generate_product_offset', 0 );

            update_option( 'alpus_aprs_generated_products', array() );

            wp_send_json_success( 'started' );
		} else {
            wp_send_json_error( 'already_exists' );
        }

        exit();
    }

    /**
     * Stop Generating All Product Review Summaries
     * 
     * @since 2.2.0
     * @access public
     */
    public function generate_stop() {
        if ( false !== as_has_scheduled_action( 'alpus_aprs_generate_all' ) ) {
            as_unschedule_all_actions( 'alpus_aprs_generate_all', array(), '' );

            wp_send_json_success( 'stopped' );
        } else {
            wp_send_json_success( 'not running' );
        }
    }

    /**
     * Is generating product reviews.
     * 
     * @since 1.0.0
     * @access public
     */
    public function is_generating() {
        // Check it has been finished.
        if ( true == get_option( 'alpus_aprs_generate_product_finished', false ) ) {
            // Remove actions.
            as_unschedule_all_actions( 'alpus_aprs_generate_all', array(), '' );
            //
            update_option( 'alpus_aprs_generate_product_finished', false );
        }

        if ( false !== as_has_scheduled_action( 'alpus_aprs_generate_all' ) ) {
            $offset = get_option( 'alpus_aprs_generate_product_offset', 0 );

            wp_send_json_success( $offset );
		} else {
            wp_send_json_error( 'false' );
        }

        exit();
    }

    /**
     * Generate 1 Product Review summary
     * 
     * @since 1.0.0
     * @access public
     */
    public function generate_step() {
        // Get current offset.
        $offset = get_option( 'alpus_aprs_generate_product_offset', 0 );

        $args = array(
            'limit'   => 1,
            'status'  => 'publish',
            'return'  => 'ids',
            'status'  => 'publish',
            'offset'  => $offset,
        );

        $product_ids = wc_get_products( $args );

        if ( empty( $product_ids ) ) {
            update_option( 'alpus_aprs_generate_product_finished', true );
        } else {
            foreach( $product_ids as $product_id ) {
                // Remove Previous Product Review Summary
                update_post_meta( $product_id, 'alpus_aprs_content', false );
    
                $this->get_summary_by_ID( $product_id );
            }

            // Increase offset.
            update_option( 'alpus_aprs_generate_product_offset', $offset + 1 );
        }
    }

    /**
     * Summarize remain reviews
     * 
     * @since 1.1.0
     * @access public
     */
    public function summarize_remain_reviews( $result, $id, $review_ids ) {
        $args = array (
            'post_type'       => 'product',       
            'status'          => 'approve',
            'type'            => 'review',
            'post_id'         => $id,
            'number'          => 10,
            'orderby'         => 'comment_date',
            'order'           => 'DESC',
            'comment__not_in' => $review_ids,
        );

        // Get Reviews from Product
        $reviews = get_comments( $args );

        // Get Summary from 10 Reviews
        $summary = $this->get_summary_from_reviews( $reviews );

        foreach ( $reviews as $review ) {
            $review_ids[] = $review->comment_ID;
        }

        // Generate final summary
        if ( false !== $summary ) {
            $prev_final_data = get_post_meta( $id, 'alpus_aprs_content_data', true );

            $prev_final_data = json_decode( base64_decode( $prev_final_data ), true );

            $summaries = array( 
                $prev_final_data,
                $summary,
            );

            $final_summary = $this->get_final_summary( $summaries );

            if ( isset( $final_summary['status'] ) && false == $final_summary['status'] ) {
                $errors        = $final_summary['content'];
                $final_summary = false;
            }

            if ( ! empty( $final_summary ) ) {
                // JSON decode from summary json data.
                if ( ! is_array( $final_summary ) ) {
                    $final_data = json_decode( $final_summary, true );
                } else {
                    $final_data = $final_summary;
                }

                if ( 1 == count( $summaries ) ) {
                    $final_data['pros'] = str_replace( ',', '<br/>- ', $final_data['pros'] );
                    $final_data['cons'] = str_replace( ',', '<br/>- ', $final_data['cons'] );

                    $result_html = '<span class="alpus-aprs-summary-label">Summary:</span>' . $final_data['summary'] . '<br/><br/><span class="alpus-aprs-pros-label">Pros:</span>- ' . $final_data['pros'] . '<br/><br/><span class="alpus-aprs-cons-label">Cons:</span>- ' . $final_data['cons'];
                } else {
                    $result_html = '<span class="alpus-aprs-summary-label">Summary:</span>' . $final_data['summary'] . '<br/><br/><span class="alpus-aprs-pros-label">Pros:</span>' . $final_data['pros'] . '<br/><br/><span class="alpus-aprs-cons-label">Cons:</span>' . $final_data['cons'];
                }
                
                update_post_meta( $id, 'alpus_aprs_content_data', base64_encode( json_encode( $final_data ) ) );

                update_post_meta( $id, 'alpus_aprs_content', $result_html );

                // Get Tags from summary.
                $tags = $this->get_product_tags( $id );

                if ( ! empty( $tags ) ) {
                    delete_post_meta( $id, 'alpus_aprs_sp_tag' );

                    foreach( $tags as $tag ) {
                        add_post_meta( $id, 'alpus_aprs_sp_tag', $tag );
                    }
                }
                
                // Get AI Advice for Product
                $advice = $this->get_product_advice( $id );

                if ( ! empty( $tags ) ) {
                    delete_post_meta( $id, 'alpus_aprs_ai_advice' );
                    update_post_meta( $id, 'alpus_aprs_ai_advice', base64_encode( $advice ) );
                }
                
                set_site_transient( 'alpus_aprs_content_' . $id, true, Alpus_Plugin_Options::get_option( 'alpus_aprs_clear_timeout' ) );

                // Save summarized reviews
                update_post_meta( $id, 'alpus_aprs_summarized_reviews', json_encode( $review_ids, true ) );

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get summary from reviews
     * 
     * @since 1.1.0
     * @access public
     */
    public function get_summary_from_reviews( $reviews ) {
        if ( empty( $reviews ) ) {
            return false;
        }

        $summaries  = array();
        $errors     = '';
        $word_count = 0;
        $comments   = '';

        $reivew_count = count( $reviews );

        for( $i = 0 ; $i < $reivew_count ; $i ++ ) {
            if ( strlen( $reviews[ $i ]->comment_content ) > 6000 ) {
                continue;
            }

            $word_count += strlen( $reviews[ $i ]->comment_content );

            if ( $word_count < 6000 ) {
                $comments .= '"' . $reviews[ $i ]->comment_content . '", ';
            } else {
                $result = $this->get_summary_from_openai( $comments );

                if ( ! empty( $result['status'] ) && true == $result['status'] ) {
                    $summaries[] = $result['content'];
                } else {
                    $errors = $result['content'];
                }                    

                $word_count  = strlen( $reviews[ $i ]->comment_content );
                $comments    = '"' . $reviews[ $i ]->comment_content . '", ';
            }
        }

        if ( ! empty( $comments ) ) {
            $result = $this->get_summary_from_openai( $comments );

            if ( true == $result['status'] ) {
                $summaries[] = $result['content'];
            } else {
                $errors = $result['content'];
            }
        }

        if ( count( $summaries ) > 1 ) {
            $final_summary = $this->get_final_summary( $summaries );

            if ( isset( $final_summary['status'] ) && false == $final_summary['status'] ) {
                $errors        = $final_summary['content'];
                $final_summary = false;
            }

        } else if ( 1 == count( $summaries ) ) {
            $final_summary = $summaries[0];
        }

        if ( $final_summary ) {
            return $final_summary;
        } else {
            return false;
        }
    }

    /**
     * Get Product Chat Query Answer
     * 
     * @since 2.0.0
     * @access public
     * 
     * @param $query Query of Chat
     */
    public function get_product_chat_query( $result, $query ) {
        $result = $this->get_summary_from_openai( $query, 'chat' );

        if ( ! empty( $result['status'] ) && true == $result['status'] ) {
            $query = $result['content'];
        } else {
            $errors = $result['content'];
        }

        if ( false !== $query ) {                
            return $query;
        }

        return false;
    }

    /**
     * Get Product AI Advice
     * 
     * @since 2.0.0
     * @access public
     * 
     * @param $id Product ID
     */
    public function get_product_advice( $id ) {
        $summary = get_post_meta( $id, 'alpus_aprs_content_data', true );

        if ( ! empty( $summary ) ) {
            $content = '';

            if ( ! empty( $summary['summary'] ) ) {
                $content .= $summary['summary'];
            }

            $content .= '\n';

            if ( ! empty( $summary['pros'] ) ) {
                $content .= $summary['pros'];
            }

            $content .= '\n';

            if ( ! empty( $summary['cons'] ) ) {
                $content .= $summary['cons'];
            }
            
            $result = $this->get_summary_from_openai( $content, 'advice' );

            if ( ! empty( $result['status'] ) && true == $result['status'] ) {
                $advice = $result['content'];
            } else {
                $errors = $result['content'];
            }

            if ( false !== $advice ) {                
                return $advice;
            }
        }

        return false;
    }

    /**
     * Get product tags
     * 
     * @since 2.0.0
     * @access public
     * 
     * @param $id Product ID
     */
    public function get_product_tags( $id ) {
        if ( 'no' == Alpus_Plugin_Options::get_option( 'alpus_aprs_tags_enable' ) ) {
            return false;
        }

        $summary = get_post_meta( $id, 'alpus_aprs_content_data', true );

        if ( ! empty( $summary ) ) {
            $content = '';

            if ( ! empty( $summary['summary'] ) ) {
                $content .= $summary['summary'];
            }

            $content .= '\n';

            if ( ! empty( $summary['pros'] ) ) {
                $content .= $summary['pros'];
            }

            $content .= '\n';

            if ( ! empty( $summary['cons'] ) ) {
                $content .= $summary['cons'];
            }

            $all_tags = Alpus_Plugin_Options::get_option( 'alpus_aprs_tags' );
            
            if ( ! empty( $all_tags ) ) {
                $all_tags = explode( ', ', $all_tags );
            }

            $tags   = false;
            $result = $this->get_summary_from_openai( $content, 'tags', $all_tags );

            if ( ! empty( $result['status'] ) && true == $result['status'] ) {
                $tags = $result['content'];
            } else {
                $errors = $result['content'];
            }

            if ( false !== $tags ) {
                $tags = json_decode( $tags, true );
                $tags_result = array();

                foreach( $tags as $key => $value ) {
                    if ( true == $value ) {
                        $tags_result[] = sanitize_title( $key );
                    }
                }
                
                return $tags_result;
            }
        }

        return false;
    }

    /**
     * Get Product Tags HTML for Product Loop.
     * 
     * @since 2.2.0
     * @access public
     */
    public function get_product_tags_html( $html, $id ) {
        if ( ! isset( $id ) ) {
            return false;
        }

        $summary = json_decode( base64_decode( get_post_meta( $id, 'alpus_aprs_content_data', true ) ), true );

        if ( 'yes' == Alpus_Plugin_Options::get_option( 'alpus_aprs_tags_enable' ) && 'yes' == Alpus_Plugin_Options::get_option( 'alpus_aprs_tags_enable_sp' ) ) {

            $tags = get_post_meta( $id, 'alpus_aprs_sp_tag' );

            $all_tags       = Alpus_Plugin_Options::get_option( 'alpus_aprs_tags' );
            $all_tags_title = false;
            $params         = false;

            if ( ! empty( $all_tags ) ) {
                $all_tags_title = explode( ', ', $all_tags );
                $temp = array();

                if ( ! empty( $all_tags_title ) && is_array( $all_tags_title ) ) {
                    foreach( $all_tags_title as $all_tag_title ) {
                        $temp[] = sanitize_title( $all_tag_title );
                    }

                    $all_tags = $temp;
                }
                
            } else {
                $all_tags = array();
            }

            
            if ( empty( $tags ) && empty( $summary ) ) {
                return false;
            }
        } else {
            if ( empty( $summary ) ) {
                return false;
            }
        }

        ob_start();
        ?>
        <div class="alpus-aprs-scroll-wrapper">
        <?php
        
        if ( 'no' != Alpus_Plugin_Options::get_option( 'alpus_aprs_tags_enable' ) && 'yes' == Alpus_Plugin_Options::get_option( 'alpus_aprs_tags_enable_sp' ) ) {
            ?>
            <div class="alpus-aprs-product-tags-wrapper">
                <ul class="alpus-aprs-product-tags">
                <?php
                if ( ! empty( $tags ) ) {
                    foreach( $tags as $tag ) {
                        $pos = array_search( $tag, $all_tags );

                        if ( false !== $pos ) {
                        ?>
                            <li class="alpus-aprs-product-tag"><?php echo esc_html( $all_tags_title[ $pos ] ); ?></li>
                        <?php
                        }
                    }
                }
                ?>
                </ul>
            </div>
            <?php
        }


        if ( ! empty( $summary ) ) {
        ?>
            <div class="alpus-aprs-product-summary-wrapper">
                <h6><?php esc_html_e( 'Summary:', 'alpus-aprs' ); ?></h6>
                <p><?php echo esc_html( $summary['summary'] ); ?></p>
            </div>
        <?php
        }

        ?>
        </div>
        <?php
  
        return ob_get_clean();
    }

    /**
     * Get Marketing Consultant
     * 
     * @since 2.0.0
     * @access public
     * 
     * @param $id Product ID
     */
    public function get_detailed_consultant( $summary, $id, $type ) {
        $summary = get_post_meta( $id, 'alpus_aprs_consultant_' . $type, true );

        if ( empty( $summary ) ) {
            $product = wc_get_product( $id );

            $terms = get_the_terms( $id, 'product_cat' );

            $category_titles = array();

            if ( $terms && ! is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) {
                    $category_titles[] = $term->name;
                }
            }

            $content = 'Product Title: ' . $product->get_title() . ', Product Category: ' . implode( ', ', $category_titles );

            $result = $this->get_summary_from_openai( $content, $type );

            if ( ! empty( $result['status'] ) && true == $result['status'] ) {
                $summary = $result['content'];
            } else {
                $errors = $result['content'];
            }

            if ( ! empty( $summary ) ) {
                update_post_meta( $id, 'alpus_aprs_consultant_' . $type, $summary );

                return $summary;
            }

            return false;
        }

        return $summary;
    }

    /**
     * Add Admin Bar Items
     * 
     * @since 1.0.0
     * @access public
     */
    public function add_admin_bar_items( $admin_bar ) {
        // Add a new top level menu item.
        $admin_bar->add_menu(
            array(
                'id'    => 'alpus-aprs-admin-menu',
                'title' => esc_html__( 'AI Product Review Summary Cache', 'alpus-aprs' ),
                'href'  => admin_url( 'admin.php?page=wp-alpus-aprs' ),
                'meta'  => array(
                    'class' => 'alpus-aprs-admin-menu',
                    'title' => esc_html__( 'Alpus APRS', 'alpus-aprs' ),
                ),
            )
        );

        // Add a submenu to the above item. add_menu is just a wrapper for add_node.
        $admin_bar->add_node(
            array(
                'parent' => 'alpus-aprs-admin-menu',
                'id'     => 'alpus-aprs-admin-clear-all',
                'title'  => esc_html__( 'Clear All', 'alpus-aprs' ),
                'href'   => admin_url( 'admin.php?page=wp-alpus-aprs' ),
                'meta'   => array(
                    'class' => 'alpus-aprs-admin-clear-all',
                    'title' => esc_html__( 'Clear All Generated Summaries', 'alpus-aprs' ),
                ),
            )
        );

        if ( current_user_can( 'manage_options' ) && is_product() ) {
            global $wp;

            $clear_url = admin_url( 'index.php?action=alpus-aprs-clear&id=' . get_the_ID() . '&path=' . $wp->request );
            
            $admin_bar->add_node(
                array(
                    'parent' => 'alpus-aprs-admin-menu',
                    'id'     => 'alpus-aprs-admin-clear-product',
                    'title'  => esc_html__( 'Clear Current Product', 'alpus-aprs' ),
                    'href'   => wp_nonce_url( $clear_url, 'alpus-aprs-clear-product' ),
                    'meta'   => array(
                        'class' => 'alpus-aprs-admin-clear-product',
                        'title' => esc_html__( 'Clear current product review summary', 'alpus-aprs' ),
                    ),
                )
            );
        }
    }

    /**
     * Clear Product Review Summary
     * 
     * @since 1.0.0
     */
    public function clear_product_review_summary() {
        // Clear Product Summary from ID
        if ( current_user_can( 'manage_options' ) && ! empty( $_REQUEST['action'] ) && 'alpus-aprs-clear' == $_REQUEST['action'] && ! empty( $_REQUEST['id'] ) ) {
            delete_post_meta( $_REQUEST['id'], 'alpus_aprs_content' );
            delete_post_meta( $_REQUEST['id'], 'alpus_aprs_content_data' );
            delete_post_meta( $_REQUEST['id'], 'alpus_aprs_sp_tag' );
            delete_post_meta( $_REQUEST['id'], 'alpus_aprs_consultant_marketing' );
            delete_post_meta( $_REQUEST['id'], 'alpus_aprs_consultant_quality' );
            delete_post_meta( $_REQUEST['id'], 'alpus_aprs_consultant_price' );
            delete_post_meta( $_REQUEST['id'], 'alpus_aprs_ai_advice' );
            
            $req_path  = isset( $_REQUEST['path'] ) ? sanitize_text_field( stripslashes( $_REQUEST['path'] ) ) : '';
            wp_safe_redirect( esc_url_raw( home_url( $req_path ) ) );
        }
        
    }
    
    /**
     * Initialize
     * 
     * @since 1.0.0
     */
    public function init() {
        if ( true == get_option( 'aprs_stop_background_running' ) && '2.1.1' == ALPUS_APRS_VERSION ) {
            as_unschedule_all_actions( 'alpus_aprs_review_bg_generate', array(), '' );

            global $wpdb;
        
            // Delete Logs
            $sql = "DELETE FROM {$wpdb->prefix}actionscheduler_logs";
            $wpdb->query( $sql );

            // Delete Actions
            $sql = "DELETE FROM {$wpdb->prefix}actionscheduler_actions";
            $wpdb->query( $sql );

            update_option( 'aprs_stop_background_running', true );
        }
    }
}

new Alpus_APRS;
