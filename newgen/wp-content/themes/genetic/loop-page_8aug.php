<?php

/**

 * The loop that displays a page.

 *

 * The loop displays the posts and the post content.  See

 * http://codex.wordpress.org/The_Loop to understand it and

 * http://codex.wordpress.org/Template_Tags to understand

 * the tags used in it.

 *

 * This can be overridden in child themes with loop-page.php.

 *

 * @package WordPress

 * @subpackage Twenty_Ten

 * @since Twenty Ten 1.2

 */

?>



<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php /*?><?php if ( is_front_page() ) { ?>

						<h2 class="entry-title"><?php the_title(); ?></h2>

					<?php } else { ?>

						<h1 class="entry-title"><?php the_title(); ?></h1>

					<?php } ?><?php */?>



					<div class="featured_content">

						<?php the_content(); ?>

						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>

						<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>

					</div><!-- .entry-content -->

				</div><!-- #post-## -->
					<?php
					$abs_path = dirname(dirname(dirname(dirname(__FILE__))));
					//echo $_SERVER['SERVER_NAME'];
							if($_SERVER['SERVER_NAME']=="localhost")
							{
								//$ajaxpath = "../../jxCommon.php";
								$ajaxpath = "jxCommon.php";
							}
							
							else
							{
								$ajaxpath = "jxCommon.php";
							}
							//$ajaxpath = $abs_path."/jxCommon.php";
							$ajaxpath = get_bloginfo("siteurl")."/jxCommon.php";
					?>
                    <script language="javascript">
						function submitContact(obj)
						{
							var serializedata = jQuery("#contact_us").serialize();
							$.post("<?php echo $ajaxpath;?>", serializedata, function(data){
								data = jQuery.trim(data);
								if(data=="EMAIL_EMPTY")
								{
									jQuery("#email_msg_contact").html("Please enter an email id.");
								}
								else if(data=="EMAIL_INVALID")
								{
									jQuery("#email_msg_contact").html("Please enter a valid email id.");
								}
								else
								{
									jQuery("#email_msg_contact").html("");
									jQuery("#success_msg").html(data);
									document.getElementById("contact_us").reset();
								}

								
								
							});
						}
						
					function artSalesSubmit(obj)
					{
						var serializedata = jQuery(obj).serialize();

							$.post("<?php echo $ajaxpath;?>", serializedata, function(data){
								data = jQuery.trim(data);
								
								if(data=="EMAIL_EMPTY")
								{
									jQuery("#org_artwork_form #email_msg").html("Please enter an email id.");
								}
								else if(data=="EMAIL_INVALID")
								{
									jQuery("#org_artwork_form #email_msg").html("Please enter a valid email id.");
								}
								else
								{
									jQuery("#org_artwork_form #email_msg").html("");
									jQuery("#org_artwork_form #art_sales_msg").html(data);
									document.getElementById("org_artwork_form").reset();
								}
								
								
							});
							return false;
					}	
					</script>
                    


				<?php //comments_template( '', true ); ?>



<?php endwhile; // end of the loop. ?>