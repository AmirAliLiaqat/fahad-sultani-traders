<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Fahad_Sultani_Traders
 */
?>

	<main id="primary" class="site-main page-main-content">
		<section class="error-404 not-found">
            <div class="container-fluid text-center p-5">

                <?php get_header(); ?>

                <header class="page-header mt-5">
                    <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'fahad-sultani-traders' ); ?></h1>
                </header><!-- .page-header -->

                <div class="page-content fw-bolder">
                    <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'fahad-sultani-traders' ); ?></p>
                    <?php get_search_form(); ?>
                </div><!-- .page-content -->
            </div><!-- .container-fluid -->
		</section><!-- .error-404 -->
	</main><!-- #main -->

<?php
get_footer();