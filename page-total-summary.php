<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Total Summary
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
            
            <h1 class="text-center text-capitalize my-5"><?php _e(the_title()); ?></h1>

            <div class="display-content">
                <div class="row fw-bolder bg-white rounded p-3">
                    <!------- Sales Summary ------->
                    <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                        <ul class="p-0 m-0 list-unstyled fw-bolder">
                            <li>
                                <span><?php _e("Sales Amount"); ?>:</span>
                                <span class="text-primary mx-2">
                                    <?php
                                        $total_sales = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice");
                                        echo esc_html(number_format_i18n($total_sales));
                                    ?>
                                </span>
                            </li>
                            <li>
                                <span><?php _e("Received Amount"); ?>:</span>
                                <span class="text-success mx-2">
                                    <?php
                                        $total_received = 0;
                                        $total_discount  = 0;
                                        $received = $wpdb->get_results("SELECT * FROM fst_customer_payments");
                                        foreach($received as $received) {
                                            $total_received += $received->amount;
                                            $total_discount += $received->discount;
                                        }
                                        echo esc_html(number_format_i18n($total_received));
                                    ?>
                                </span>
                            </li>
                            <li>
                                <span><?php _e("Remaining Amount"); ?>:</span>
                                <span class="text-danger mx-2">
                                    <?php
                                        $remaining_amount = $total_sales -  $total_received;
                                        echo esc_html(number_format_i18n($remaining_amount));
                                    ?>
                                </span>
                            </li>
                        </ul>
                    </div><!-- .col-4 -->

                    <!------- Purchase Summary ------->
                    <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                        <ul class="p-0 m-0 list-unstyled fw-bolder">
                            <li>
                                <span><?php _e("Purchase Amount"); ?>:</span>
                                <span class="text-primary mx-2">
                                    <?php
                                        $total_purchase = $wpdb->get_var("SELECT SUM(price) FROM fst_purchase_data");
                                        echo esc_html(number_format_i18n($total_purchase));
                                    ?>
                                </span>
                            </li>
                            <li>
                                <span><?php _e("Pay Amount"); ?>:</span>
                                <span class="text-success mx-2">
                                    <?php
                                        $total_pay = $wpdb->get_var("SELECT SUM(amount) FROM fst_shopkeeper_payments");
                                        echo esc_html(number_format_i18n($total_pay));
                                    ?>
                                </span>
                            </li>
                            <li>
                                <span><?php _e("Remaining Amount"); ?>:</span>
                                <span class="text-danger mx-2">
                                    <?php
                                        $remaining_amount = $total_purchase -  $total_pay;
                                        echo esc_html(number_format_i18n($remaining_amount));
                                    ?>
                                </span>
                            </li>
                        </ul>
                    </div><!-- .col-4 -->

                    <!------- Others Summary ------->
                    <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                        <!------- Expense Summary ------->
                        <span><?php _e("Expense"); ?>:</span>
                        <span class="text-primary mx-2">
                            <?php 
                                $expense_amount = $wpdb->get_var("SELECT SUM(expense_amount) FROM fst_expense_data");
                                echo esc_html(number_format_i18n($expense_amount));
                            ?>
                        </span>
                        <br>
                        <br>
                        <!------- Discount Summary ------->
                        <span><?php _e("Discount"); ?>:</span>
                        <span class="text-primary mx-2">
                            <?php echo esc_html(number_format_i18n($total_discount)); ?>
                        </span>
                        <br>
                        <br>
                        <!------- Salary Summary ------->
                        <span><?php _e("Salary Amount"); ?>:</span>
                        <span class="text-primary mx-2">
                            <?php
                                $total_salary = $wpdb->get_var("SELECT SUM(salary) FROM fst_salary_data");
                                echo esc_html(number_format_i18n($total_salary));
                            ?>
                        </span>
                    </div><!-- .col-4 -->

                    <!------- Product Summary ------->
                    <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                        <?php
                            $fetch_sales_product = $wpdb->get_results("SELECT * FROM fst_customer_invoice");
                            foreach($fetch_sales_product as $fetch) :
                                $product_id = $fetch->product_id;
                        
                                $fetch_product = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `ID` = $product_id");
                                foreach($fetch_product as $product) {
                                    $product_name = $product->product_name;
                                    $price = $product->price_with_expense;
                                }
                                $fetch_product_profit = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `product_id` = $product_id");
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
                            endforeach;
                        ?>
                    </div><!-- .col-4 -->

                    <!------- Profit & Lose ------->
                    <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                        <div class='border border-dark p-2 my-2'>
                            <?php
                                if(!empty($gross)) {
                                    $gross_profit = $gross - $total_discount - $expense_amount - $total_salary;
                                } else {
                                    $gross = 0;
                                    $gross_profit = $gross - $total_discount - $expense_amount - $total_salary;
                                }

                                _e("Gross Profit & Lose") . ':';
                                if($gross_profit > 0) {
                                    echo "<span class='text-primary mx-2'>".esc_html(number_format_i18n($gross_profit))."</span>";
                                } else {
                                    echo "<span class='text-danger mx-2'>".esc_html(number_format_i18n($gross_profit))."</span>";
                                }
                            ?>
                        </div><!-- .border -->
                        <div class='border border-dark p-2 my-2'>
                            <?php
                                _e("Net Profit & Lose") . ':';
                                $net_profict = $remaining_amount + $total_sales - $total_received - $gross_profit;
                                if($net_profict > 0) {
                                    echo "<span class='text-primary'> ".number_format_i18n($net_profict)."</span>";
                                } else {
                                    echo "<span class='text-danger'> ".number_format_i18n($net_profict)."</span>";
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