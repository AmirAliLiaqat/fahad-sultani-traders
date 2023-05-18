<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Login
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fahad_Sultani_Traders
 */
?>

    <div class="page-main-content">
        <div class="container-fluid p-5">

            <?php get_header(); ?>

            <h1 class="text-center text-capitalize my-5"><?php _e(the_title()); ?></h1>

            <div class="row my-2">
                <div class="col-lg-6 col-sm-12 bg-light form-content p-4 mx-auto">
                    <?php
                        $args = array(
                            'echo'            => true,
                            'redirect'        => get_site_url(),
                            'remember'        => true,
                            'value_remember'  => true,
                        );

                        wp_login_form( $args );
                    ?>
                </div><!-- .col-lg-6 -->
            </div><!-- .row -->
            
        </div><!-- .container-fluid -->
    </div><!-- .page-main-content -->
        
<?php
get_footer();