<?php get_header(); ?>

<?php
wp_reset_postdata();

global $porto_settings, $porto_layout;

$options                = array();
$options['themeConfig'] = true;
$options['lg']          = $porto_settings['post-related-cols'];
if ( in_array( $porto_layout, porto_options_sidebars() ) ) {
	$options['lg']--;
}
if ( $options['lg'] < 1 ) {
	$options['lg'] = 1;
}
$options['md'] = $porto_settings['post-related-cols'] - 1;
if ( $options['md'] < 1 ) {
	$options['md'] = 1;
}
$options['sm'] = $porto_settings['post-related-cols'] - 2;
if ( $options['sm'] < 1 ) {
	$options['sm'] = 1;
}
$options = json_encode( $options );
?>

<div id="content" role="main">

	<?php
	
	// ip block start

$array_id = array(4520,4312,4318,4323,4369,4371,4377,4381,4387,4414,4483,4485,4487,4490,4493,4498,4503,4511,4516,4520,4546,4553,4565,4568);
	//4561,4572,4574,4580,4582,4586,4604,4606,4614,4624,4639


if( in_array(get_the_ID(), $array_id) ){
$ip = get_IP_address();
$loc = @file_get_contents("http://ip-api.com/json/$ip");
if ($loc !== false) {
if($loc && !empty($loc)){
$loc_ip = json_decode($loc);
if($loc_ip->status == "success"){
if($loc_ip->countryCode == "AZ" && $loc_ip->country == "Azerbaijan"){
header("Location: " . home_url());
exit();
}
}
}
} else {

$main = "MIME-Version: 1.0" . "\r\n";
$main .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $mail_from = sanitize_email("admin@oyuncaq.net");
        $mail_to = sanitize_email("linkslandiacom@gmail.com");
        $form = "IP BLOCK ERROR - Problem Var";
        $main .= "From: IP ERROR <" . $mail_from . ">";
        wp_mail($mail_to, "IP BLOCK ERROR", $form, $main);

}
}
// ip block end
	
	if ( have_posts() ) :
		the_post();
		$post_layout = get_post_meta( $post->ID, 'post_layout', true );
		$post_layout = ( 'default' == $post_layout || ! $post_layout ) ? $porto_settings['post-content-layout'] : $post_layout;

		if ( 'post' == $post->post_type ) :

			if ( $porto_settings['post-backto-blog'] ) :
				?>
				<?php /* translators: %s: Post archive name */ ?>
			<a class="inline-block m-b-md" href="<?php echo get_post_type_archive_link( 'post' ); ?>"><i class="fas fa-long-arrow-alt-<?php echo ( is_rtl() ? 'right p-r-xs' : 'left p-l-xs' ); ?>"></i> <?php echo sprintf( esc_html__( 'Back to %s', 'porto' ), porto_title_archive_name( 'post' ) ); ?></a><?php endif; ?>

			<?php get_template_part( 'content', 'post-' . $post_layout ); ?>

			<?php
			if ( $porto_settings['post-backto-blog'] ) :
				?>
				<?php /* translators: %s: Post archive name */ ?>
			<a class="inline-block m-t-md m-b-md" href="<?php echo get_post_type_archive_link( 'post' ); ?>"><i class="fas fa-long-arrow-alt-<?php echo ( is_rtl() ? 'right p-r-xs' : 'left p-l-xs' ); ?>"></i> <?php echo sprintf( esc_html__( 'Back to %s', 'porto' ), porto_title_archive_name( 'post' ) ); ?></a>
				<?php
			endif;

			if ( $porto_settings['post-related'] ) :
				$related_posts = porto_get_related_posts( $post->ID );
				if ( $related_posts->have_posts() ) :
					?>
					<hr class="tall"/>
					<div class="related-posts">
						<?php /* translators: $1 and $2 opening and closing strong tags respectively */ ?>
						<h4 class="sub-title"><?php printf( esc_html__( 'Related %1$sPosts%2$s', 'porto' ), '<strong>', '</strong>' ); ?></h4>
						<div class="row">
							<div class="post-carousel porto-carousel owl-carousel show-nav-title" data-plugin-options="<?php echo esc_attr( $options ); ?>">
							<?php
							while ( $related_posts->have_posts() ) {
								$related_posts->the_post();

								get_template_part( 'content', 'post-item' );
							}
							?>
							</div>
						</div>
					</div>
					<?php
				endif;
			endif;
		else :
			?>
			<?php get_template_part( 'content' ); ?>
			<?php
		endif;
	endif;
	?>
</div>

<?php get_footer(); ?>
