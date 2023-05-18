<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Today Summary
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
        $yesterday = date("Y-m-d", strtotime("-1 day"));
    ?>  

    <div class="page-main-content">
        <div class="container-fluid p-5">

            <?php get_header(); ?>
            
            <h1 class="text-center text-capitalize my-5"><?php esc_html_e(the_title()); ?></h1>

            <div class="display-content">
                <div class="row fw-bolder bg-white rounded p-3">
                    <!------- Sales Summary ------->
                    <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                        <ul class="p-0 m-0 list-unstyled">
                            <li>
                                <span><?php esc_html_e("Sales Amount"); ?>:</span>
                                <span class="text-primary mx-2">
                                    <?php
                                        $yesterday_sales = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice WHERE `sale_date` = '$yesterday'");
                                        
                                        $today_sales = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice WHERE `sale_date` = '$date'");
                                        echo esc_html(number_format_i18n($today_sales));
                                    ?>
                                </span>
                            </li>
                            <li>
                                <span><?php esc_html_e("Received Amount"); ?>:</span>
                                <span class="text-success mx-2">
                                    <?php
                                        $yesterday_received = 0;
                                        $y_received = $wpdb->get_results("SELECT * FROM fst_customer_payments WHERE `paid_date` = '$yesterday'");
                                        foreach($y_received as $y_received) {
                                            $yesterday_received += $y_received->amount;
                                        }

                                        $today_received = 0;
                                        $today_discount = 0;
                                        $t_received = $wpdb->get_results("SELECT * FROM fst_customer_payments WHERE `paid_date` = '$date'");
                                        foreach($t_received as $t_received) {
                                            $today_received += $t_received->amount;
                                            $today_discount += $t_received->discount;
                                        }
                                        echo esc_html(number_format_i18n($today_received));
                                    ?>
                                </span>
                            </li>
                            <li>
                                <span><?php esc_html_e("Remaining Amount"); ?>:</span>
                                <span class="text-danger mx-2">
                                    <?php
                                        $yesterday_remain = $yesterday_sales -  $yesterday_received;
                                        $today_remain = $today_sales -  $today_received;
                                        echo esc_html(number_format_i18n($today_remain));
                                    ?>
                                </span>
                            </li>
                            <br>
                            <li>
                                <span><?php esc_html_e("Credit I/D"); ?>:</span>
                                <span class="text-info mx-2">
                                    <?php
                                        $credit_i_d = $yesterday_remain + $today_sales - $today_received;
                                        echo esc_html(number_format_i18n($credit_i_d));
                                    ?>
                                </span>
                            </li>
                        </ul>
                    </div><!-- .col-4 -->

                    <!------- Expense Summary ------->
                    <div class="col-lg-3 col-md-3 col-sm-12 my-4">
                        <span><?php esc_html_e("Expense"); ?>:</span>
                        <span class="text-primary mx-2">
                            <?php
                                $expense_amount = $wpdb->get_var("SELECT SUM(expense_amount) FROM fst_expense_data WHERE `expense_date` = '$date'");
                                echo esc_html(number_format_i18n($expense_amount));
                            ?>
                        </span>
                        <br>
                        <br>
                        <span><?php esc_html_e("Discount"); ?>:</span>
                        <span class="text-primary mx-2">
                            <?php echo esc_html(number_format_i18n($today_discount)); ?>
                        </span>
                    </div><!-- .col-3 -->

                    <!------- Product Summary ------->
                    <div class="col-lg-5 col-md-5 col-sm-12 my-4">
                        <ul class="p-0 m-0 list-unstyled">
                            <?php
                                $fetch_sales_product = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `sale_date` = '$date'");
                                foreach($fetch_sales_product as $fetch) :
                                    $product_id = $fetch->product_id;
                            ?>
                            <li class="mb-2">
                                <?php
                                    $fetch_product = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `ID` = '$product_id'");
                                    foreach($fetch_product as $product) {
                                        $product_name = $product->product_name;
                                        $price = $product->price_with_expense;
                                ?>
                                <span><?php esc_html_e("$product_name Sales"); ?>:</span>
                                <?php } ?>
                                <?php
                                    $fetch_product_profit = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `product_id` = $product_id AND `sale_date` = '$date'");
                                    error_reporting(0); 
                                    $total_amount = 0;
                                    // $gross = 0;
                                    foreach($fetch_product_profit as $profit) {
                                        $total_amount += $profit->total_amount;
                                        $quantity = $profit->quantity;
                                        $total_price = $price * $quantity;
                                        $per_product_profit = $total_amount - $total_price;
                                    }
                                    $gross += $per_product_profit;
                                    
                                ?>
                                <span class="text-primary mx-2"><?php echo esc_html(number_format_i18n($total_amount)); ?></span>
                                <span class="text-info mx-2"><?php echo esc_html(number_format_i18n($quantity)); ?></span>
                                <span class="text-primary mx-2"><?php echo esc_html(number_format_i18n($average = $total_amount / $quantity)); ?></span>
                                <?php
                                    if($per_product_profit > 0) {
                                        echo "<span class='text-success mx-2 p-1'>".esc_html(number_format_i18n($per_product_profit))."</span>";
                                    } else {
                                        echo "<span class='text-danger mx-2 p-1'>".esc_html(number_format_i18n($per_product_profit))."</span>";
                                    }
                                ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div><!-- .col-5 -->

                    <!------- Profit & Lose ------->
                    <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                        <div class='border border-dark p-2 my-2'>
                            <?php
                                if(!empty($gross)) {
                                    $gross_profit = $gross - $today_discount;
                                } else {
                                    $gross = 0;
                                    $gross_profit = $gross - $today_discount;
                                }

                                esc_html_e("Gross Profit & Lose") . ':';
                                if($gross_profit > 0) {
                                    echo "<span class='text-primary mx-2'>".esc_html(number_format_i18n($gross_profit))."</span>";
                                } else {
                                    echo "<span class='text-danger mx-2'>".esc_html(number_format_i18n($gross_profit))."</span>";
                                }
                            ?>
                        </div><!-- .border -->
                        <div class='border border-dark p-2 my-2'>
                            <?php
                                esc_html_e("Net Profit & Lose") . ':';
                                $net_profit = $gross_profit - $credit_i_d;
                                if($net_profit > 0) {
                                    echo "<span class='text-primary'> ".number_format_i18n($net_profit)."</span>";
                                } else {
                                    echo "<span class='text-danger'> ".number_format_i18n($net_profit)."</span>";
                                }
                            ?>
                        </div><!-- .border -->
                    </div><!-- .col-4 -->
                </div><!-- .row -->
            </div><!-- .display-content -->

        </div><!-- .container -->
    </div><!-- .page-main-content -->
    
<?php
get_footer();