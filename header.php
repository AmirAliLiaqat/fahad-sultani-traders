<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fahad_Sultani_Traders
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> >
<?php wp_body_open(); ?>

    <header id="masthead" class="site-header">
        <div class="top-header d-flex justify-content-between py-3">
            <div class="dropdown user_profile action mx-2">
                <div class="dropdown-toggle btn text-white bg-dark" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php esc_html_e("English"); ?>
                </div>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item top-header-dropdown mx-0" href="">اردو</a></li>
                </ul>
            </div><!--dropdown-->
            <?php
                if(is_user_logged_in()) { 
                    $current_user = wp_get_current_user();
                    $username = $current_user->display_name;
                ?>
                <div class="dropdown user_profile action mx-2">
                    <div class="dropdown-toggle btn text-white bg-dark" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php esc_html_e("Hello, " . $username); ?>
                    </div>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item top-header-dropdown mx-0" href="<?php echo wp_logout_url( get_home_url().'/login/' ); ?>"><?php esc_html_e("Logout"); ?></a></li>
                    </ul>
                </div><!--dropdown-->
            <?php
                }
            ?>
        </div><!-- .top-header -->

		<div class="site-branding p-5">
            <h1 class="site-title text-center text-uppercase">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-decoration-none text-white" rel="home"><?php esc_html_e(bloginfo( 'name' )); ?></a>
            </h1>
		</div><!-- .site-branding -->

        <nav class="navbar navbar-expand-lg navbar-light bg-light rounded">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center p-3" id="navbarSupportedContent">
                    <ul class="menu">
                        <?php if( is_user_logged_in() ) { ?>
                        <li class="menu-item">
                            <a href="<?php echo esc_url(get_page_link(16)); ?>"><i class="fa-solid fa-house fa-2x"></i><?php esc_html_e(get_the_title(16)); ?></a>
                        </li>
                        <?php } if( current_user_can( 'today_sales' ) ) { ?>
                        <li class="menu-item">
                            <a href="<?php echo esc_url(get_page_link(8)); ?>"><i class="fa-solid fa-user-tie fa-2x"></i><?php esc_html_e(get_the_title(8)); ?></a>
                        </li>
                        <?php } if( current_user_can( 'sales_invoices' ) ) { ?>
                        <li class="menu-item">
                            <a href="<?php echo esc_url(get_page_link(30)); ?>"><i class="fa-solid fa-file-invoice fa-2x"></i><?php esc_html_e(get_the_title(30)); ?></a>
                        </li>
                        <?php } if( current_user_can( 'customer_payments' ) ) { ?>
                        <li class="menu-item">
                            <a href="<?php echo esc_url(get_page_link(14)); ?>"><i class="fa-solid fa-credit-card fa-2x"></i><?php esc_html_e(get_the_title(14)); ?></a>
                        </li>
                        <?php } if( current_user_can( 'customer_invoices' ) ) { ?>
                        <li class="menu-item">
                            <a href="<?php echo esc_url(get_page_link(12)); ?>"><i class="fa-solid fa-receipt fa-2x"></i><?php esc_html_e(get_the_title(12)); ?></a>
                        </li>
                        <?php } if( current_user_can( 'add_shopkeeper' ) ) { ?>
                        <li class="menu-item">
                            <a href="<?php echo esc_url(get_page_link(10)); ?>"><i class="fa-solid fa-shop fa-2x"></i><?php esc_html_e(get_the_title(10)); ?></a>
                        </li>
                        <?php } if( current_user_can( 'purchase_data' ) ) { ?>
                        <li class="menu-item">
                            <a href="<?php echo esc_url(get_page_link(24)); ?>"><i class="fa-solid fa-database fa-2x"></i><?php esc_html_e(get_the_title(24)); ?></a>
                        </li>
                        <?php } if( current_user_can( 'shopkeeper_payments' ) ) { ?>
                        <li class="menu-item">
                            <a href="<?php echo esc_url(get_page_link(42)); ?>"><i class="fa-solid fa-money-bill fa-2x"></i><?php esc_html_e(get_the_title(42)); ?></a>
                        </li>
                        <?php } if( current_user_can( 'shopkeeper_invoices' ) ) { ?>
                        <li class="menu-item">
                            <a href="<?php echo esc_url(get_page_link(40)); ?>"><i class="fa-solid fa-receipt fa-2x"></i><?php esc_html_e(get_the_title(40)); ?></a>
                        </li>
                        <?php } if( current_user_can( 'expense' ) ) { ?>
                        <li class="menu-item">
                            <a href="<?php echo esc_url(get_page_link(18)); ?>"><i class="fa-solid fa-wallet fa-2x"></i><?php esc_html_e(get_the_title(18)); ?></a>
                        </li>
                        <?php } if( current_user_can( 'salary' ) ) { ?>
                        <li class="menu-item">
                            <a href="<?php echo esc_url(get_page_link(26)); ?>"><i class="fa-sharp fa-solid fa-hand-holding-dollar fa-2x"></i><?php esc_html_e(get_the_title(26)); ?></a>
                        </li>
                        <?php } if( !is_user_logged_in() ) { ?>
                        <li class="menu-item">
                            <a href="<?php echo esc_url(get_page_link(20)); ?>"><i class="fa-solid fa-user fa-2x"></i><?php esc_html_e(get_the_title(20)); ?></a>
                        </li>
                    </ul><?php } ?>
                </div>
            </div>
        </nav>
	</header><!-- #masthead -->