<?php

/**

 * The template for displaying the footer.

 *

 * Contains the closing of the id=main div and all content

 * after.  Calls sidebar-footer.php for bottom widgets.

 *

 * @package WordPress

 * @subpackage Twenty_Ten

 * @since Twenty Ten 1.0

 */

?>

	</div><!-- #main -->



<?php /*?>	<div id="footer" role="contentinfo">

		<div id="colophon">



<?php

	/* A sidebar in the footer? Yep. You can can customize

	 * your footer with four columns of widgets.

	 */

	//get_sidebar( 'footer' );

?>



			<div id="site-info">

				<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">

					<?php bloginfo( 'name' ); ?>

				</a>

			</div><!-- #site-info -->



			<div id="site-generator">

				<?php do_action( 'twentyten_credits' ); ?>

				<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'twentyten' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'twentyten' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s.', 'twentyten' ), 'WordPress' ); ?></a>

			</div><!-- #site-generator -->



		</div><!-- #colophon -->

	</div><!-- #footer --><?php */?>







<?php

	/* Always have wp_footer() just before the closing </body>

	 * tag of your theme, or you will break many plugins, which

	 * generally use this hook to reference JavaScript files.

	 */



	//wp_footer();

?>
<script type="text/javascript">

 var _gaq = _gaq || [];
 _gaq.push(['_setAccount', 'UA-703844-1']);
 _gaq.push(['_setDomainName', '.rogerwilliamsart.com']);
 _gaq.push(['_trackPageview']);

 (function() {
   var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
   ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
   var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
 })();

</script>


</body>

</html>

