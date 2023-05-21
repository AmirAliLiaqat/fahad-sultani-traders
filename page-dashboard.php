<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Dashboard
 * The template for displaying dashboard pages
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

    <?php
        if(!is_user_logged_in()) {
            $url = get_site_url() . "/login";
            wp_redirect( $url );
        }
        global $wpdb;
        $date = date('Y-m-d');
    ?>

    <div class="page-main-content">
        <div class="container-fluid p-5">

            <?php get_header(); ?>

            <h1 class="text-center text-capitalize my-5"><?php esc_html_e(get_the_title()); ?></h1>

            <div class="row">
                <!----------- Today Sales --------------->
                <?php if( current_user_can( 'today_sales' ) ) { ?> 
                    <div class="col-lg-4 col-md-4 col-sm-6 my-3">
                        <h3 class="dashboard-content text-center form-content bg-light">
                            <a href="<?php echo esc_url(get_page_link(48)); ?>" class="d-block text-dark text-decoration-none p-3"><?php esc_html_e('Today Sales'); ?></a>
                        </h3>
                    </div><!-- .col-lg-4 -->
                <?php } ?>

                <!----------- Today Credits --------------->
                <?php if( current_user_can( 'today_credits' ) ) { ?> 
                    <div class="col-lg-4 col-md-4 col-sm-6 my-3">
                        <h3 class="dashboard-content text-center form-content bg-light">
                            <a href="<?php echo esc_url(get_page_link(46)); ?>" class="d-block text-dark text-decoration-none p-3"><?php esc_html_e('Today Credits'); ?></a>
                        </h3>
                    </div><!-- .col-lg-4 -->
                <?php } ?>

                <!----------- Shopkeeper Payments --------------->
                <?php if( current_user_can( 'dashboard_shopkeeper' ) ) { ?> 
                    <div class="col-lg-4 col-md-4 col-sm-6 my-3">
                        <h3 class="dashboard-content text-center form-content bg-light">
                            <a href="<?php echo esc_url(get_page_link(39)); ?>" class="d-block text-dark text-decoration-none p-3"><?php esc_html_e('Shopkeeper'); ?></a>
                        </h3>
                    </div><!-- .col-lg-4 -->
                <?php } ?>

                <!----------- Today Summary --------------->
                <?php if( current_user_can( 'today_summary' ) ) { ?> 
                    <div class="col-lg-4 col-md-4 col-sm-6 my-3">
                        <h3 class="dashboard-content text-center form-content bg-light">
                            <a href="<?php echo esc_url(get_page_link(50)); ?>" class="d-block text-dark text-decoration-none p-3"><?php esc_html_e('Today Summary'); ?></a>
                        </h3>
                    </div><!-- .col-lg-4 -->
                <?php } ?>

                <!----------- Monthly Summary --------------->
                <?php if( current_user_can( 'monthly_summary' ) ) { ?> 
                    <div class="col-lg-4 col-md-4 col-sm-6 my-3">
                        <h3 class="dashboard-content text-center form-content bg-light">
                            <a href="<?php echo esc_url(get_page_link(23)); ?>" class="d-block text-dark text-decoration-none p-3"><?php esc_html_e('Monthly Summary'); ?></a>
                        </h3>
                    </div><!-- .col-lg-4 -->
                <?php } ?>

                <!----------- Total Summary --------------->
                <?php if( current_user_can( 'total_summary' ) ) { ?> 
                    <div class="col-lg-4 col-md-4 col-sm-6 my-3">
                        <h3 class="dashboard-content text-center form-content bg-light">
                            <a href="<?php echo esc_url(get_page_link(52)); ?>" class="d-block text-dark text-decoration-none p-3"><?php esc_html_e('Total Summary'); ?></a>
                        </h3>
                    </div><!-- .col-lg-4 -->
                <?php } ?>
            </div><!-- .row -->

            <!------------- Search --------------->
            <?php if( current_user_can( 'search_items' ) ) { ?>
                <div class="row fw-bolder">
                    <div class="col-lg-4 col-md-4 col-sm-6 my-3 mx-auto">
                        <h3 class="dashboard-content text-center form-content bg-light">
                            <a href="<?php echo esc_url(get_page_link(32)); ?>" class="d-block text-dark text-decoration-none p-3"><?php esc_html_e('Search'); ?></a>
                        </h3>
                    </div><!-- .col-lg-4 -->
                </div><!-- .row -->
            <?php } ?>
        </div><!-- .container-fluid -->
    </div><!-- .page-main-content -->
        
<?php
get_footer();