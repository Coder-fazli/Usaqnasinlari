<?php
/**
 * Product Review Summary Template
 *
 * @author AlpusTheme
 * @package Alpus APRS (AI Product Review Summary)
 * @version 1.0.0
 */

ob_start();
global $post;


?>
<div class="alpus-aprs-wrapper hide loading" data-post-id="<?php echo esc_attr( $post->ID ); ?>">
    <?php if ( ! empty( $title ) ) : ?>
        <h3 class="alpus-aprs-title"><?php echo esc_html( $title ); ?></h3>
    <?php endif; ?>
    <div class="loading-overlay"><div class="loader"></div></div>
    <div class="alpus-aprs-content"></div>
    <div class="alpus-aprs-error-msg"></div>
</div>
<?php
$template = ob_get_clean();

echo apply_filters( 'alpus_aprs_summary_template', $template );