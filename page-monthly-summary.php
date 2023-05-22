<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Monthly Summary
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
        $current_month = date('m');
        $previous_month = date("m", strtotime("-1 months"));
        $current_year = date('Y');
    ?>  

    <div class="page-main-content">
        <div class="container-fluid p-5">

            <?php get_header(); ?>
            
            <h1 class="text-center text-capitalize my-5"><?php esc_html_e(the_title()); ?></h1>

            <div class="display-content">
                <div class="row fw-bolder bg-white rounded p-3">
                    <!------- Sales Summary ------->
                    <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                        <ul class="p-0 m-0 list-unstyled fw-bolder">
                            <li>
                                <span><?php esc_html_e("Sales Amount"); ?>:</span>
                                <span class="text-primary mx-2">
                                    <?php
                                        $pre_sales = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice WHERE `sale_date` BETWEEN date('$current_year-$previous_month-01') AND date('$current_year-$previous_month-30')");

                                        $monthly_sales = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice WHERE `sale_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                        echo esc_html(number_format_i18n($monthly_sales));
                                    ?>
                                </span>
                            </li>
                            <li>
                                <span><?php esc_html_e("Received Amount"); ?>:</span>
                                <span class="text-success mx-2">
                                    <?php
                                        $pre_received = 0;
                                        $pre_monthly_received = $wpdb->get_results("SELECT * FROM fst_customer_payments WHERE `paid_date` BETWEEN date('$current_year-$previous_month-01') AND date('$current_year-$previous_month-30')");
                                        foreach($pre_monthly_received as $pre_monthly_received) {
                                            $pre_received += $pre_monthly_received->amount;
                                        }

                                        $monthly_received = 0;
                                        $monthly_discount  = 0;
                                        $received = $wpdb->get_results("SELECT * FROM fst_customer_payments WHERE `paid_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                        foreach($received as $received) {
                                            $monthly_received += $received->amount;
                                            $monthly_discount += $received->discount;
                                        }
                                        echo esc_html(number_format_i18n($monthly_received));
                                    ?>
                                </span>
                            </li>
                            <li>
                                <span><?php esc_html_e("Remaining Amount"); ?>:</span>
                                <span class="text-danger mx-2">
                                    <?php
                                        $pre_remaining = $pre_sales -  $pre_received;

                                        $remaining_amount = $monthly_sales -  $monthly_received;
                                        echo esc_html(number_format_i18n($remaining_amount));
                                    ?>
                                </span>
                            </li>
                            <br>
                            <li>
                                <span><?php esc_html_e("Credit I/D"); ?>:</span>
                                <span class="text-info mx-2">
                                    <?php
                                        $credit_i_d = $pre_remaining + $monthly_sales - $monthly_received;
                                        echo esc_html(number_format_i18n($credit_i_d));
                                    ?>
                                </span>
                            </li>
                        </ul>                  
                    </div><!-- .col-4 -->

                    <!------- Purchase Summary ------->
                    <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                        <ul class="p-0 m-0 list-unstyled fw-bolder">
                            <li>
                                <span><?php esc_html_e("Purchase Amount"); ?>:</span>
                                <span class="text-primary mx-2">
                                    <?php
                                        $monthly_purchase = $wpdb->get_var("SELECT SUM(price) FROM fst_purchase_data WHERE `purchase_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                        echo esc_html(number_format_i18n($monthly_purchase));
                                    ?>
                                </span>
                            </li>
                            <li>
                                <span><?php esc_html_e("Pay Amount"); ?>:</span>
                                <span class="text-success mx-2">
                                    <?php
                                        $monthly_pay = $wpdb->get_var("SELECT SUM(amount) FROM fst_shopkeeper_payments WHERE `paid_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                        echo esc_html(number_format_i18n($monthly_pay));
                                    ?>
                                </span>
                            </li>
                            <li>
                                <span><?php esc_html_e("Remaining Amount"); ?>:</span>
                                <span class="text-danger mx-2">
                                    <?php
                                        $monthly_remaining_amount = $monthly_purchase -  $monthly_pay;
                                        echo esc_html(number_format_i18n($monthly_remaining_amount));
                                    ?>
                                </span>
                            </li>
                        </ul>                  
                    </div><!-- .col-4 -->

                    <!------- Others Summary ------->
                    <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                        <!------- Expense Summary ------->
                        <span><?php esc_html_e("Expense"); ?>:</span>
                        <span class="text-primary mx-2">
                            <?php 
                                $expense_amount = $wpdb->get_var("SELECT SUM(expense_amount) FROM fst_expense_data WHERE `expense_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                echo esc_html(number_format_i18n($expense_amount));
                            ?>
                        </span>
                        <br>
                        <br>
                        <!------- Discount Summary ------->
                        <span><?php esc_html_e("Discount"); ?>:</span>
                        <span class="text-primary mx-2">
                            <?php echo esc_html(number_format_i18n($monthly_discount)); ?>
                        </span>
                        <br>
                        <br>
                        <!------- Salary Summary ------->
                        <span><?php esc_html_e("Salary Amount"); ?>:</span>
                        <span class="text-primary mx-2">
                            <?php
                                $total_salary = $wpdb->get_var("SELECT SUM(salary) FROM fst_salary_data WHERE `pay_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                echo esc_html(number_format_i18n($total_salary));
                            ?>
                        </span>
                    </div><!-- .col-4 -->

                    <!------- Product Summary ------->
                    <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                        <?php
                            $fetch_sales_product = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `sale_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                            foreach($fetch_sales_product as $fetch) :
                                $product_id = $fetch->product_id;

                                $fetch_product = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `ID` = $product_id");
                                foreach($fetch_product as $product) {
                                    $product_name = $product->product_name;
                                    $price = $product->price_with_expense;
                                }
                                $fetch_product_profit = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `product_id` = $product_id AND `sale_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
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
                                    $gross_profit = $gross - $monthly_discount - $expense_amount - $total_salary;
                                } else {
                                    $gross = 0;
                                    $gross_profit = $gross - $monthly_discount - $expense_amount - $total_salary;
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
                                $net_profict = $gross_profit - $credit_i_d;
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