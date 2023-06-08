<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Search
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
        error_reporting(0);
        global $wpdb;
        $date = date('Y-m-d');
    ?>  

    <div class="page-main-content">
        <div class="container-fluid p-5">

            <?php get_header(); ?>
            
            <h1 class="text-center text-capitalize my-5"><?php esc_html_e(the_title() . ' ' . 'Items'); ?></h1>

            <div class="display-content fw-bolder">
                <ul class="nav nav-pills justify-content-center mb-3 mx-0" id="pills-tab" role="tablist">
                    <?php if( current_user_can( 'search_product' ) ) { ?>
                        <li class="nav-item" role="presentation">
                            <div class="dashboard-content text-center form-content bg-light m-3 p-3" id="search-product-tab" data-bs-toggle="pill" data-bs-target="#search-product" type="button" role="tab" aria-controls="search-product" aria-selected="false"><?php esc_html_e("Search Product"); ?></div>
                        </li>
                    <?php } if( current_user_can( 'search_summary' ) ) { ?>
                        <li class="nav-item" role="presentation">
                            <div class="dashboard-content text-center form-content bg-light m-3 p-3" id="search-summary-tab" data-bs-toggle="pill" data-bs-target="#search-summary" type="button" role="tab" aria-controls="search-summary" aria-selected="false"><?php esc_html_e("Search Summary"); ?></div>
                        </li>
                    <?php } ?>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade bg-white border rounded table-responsive p-3 show active" id="search-product" role="tabpanel" aria-labelledby="search-product-tab">
                        <form class="row" method="post">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group my-1">
                                    <label for="serial_no" class="form-label"><?php esc_html_e("Serial"); ?>#:</label>
                                    <input type="text" name="serial_no" class="form-control bg-light" placeholder="<?php esc_html_e("Search by shop #"); ?>">
                                </div><!-- .form-group -->
                            </div><!-- .col-lg-4 -->
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group my-1">
                                    <label for="p_name" class="form-label"><?php esc_html_e("Product Name"); ?>:</label>
                                    <input type="text" name="p_name" class="form-control bg-light" placeholder="<?php esc_html_e("Search by product name"); ?>">
                                </div><!-- .form-group -->
                            </div><!-- .col-lg-4 -->
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group my-1">
                                    <label for="p_date" class="form-label"><?php esc_html_e("Date"); ?>:</label>
                                    <input type="date" name="p_date" class="form-control bg-light">
                                </div><!-- .form-group -->
                            </div><!-- .col-lg-4 -->
                            <div class="col-12 text-end">
                                <?php wp_nonce_field('search_product_details', 'search_product_details_nonce'); ?>
                                <button class="btn btn-primary my-2" name="p_search"><?php esc_html_e("Search"); ?></button>
                            </div><!-- .col-12 -->
                        </form>
                        <?php
                                if(isset($_POST['search_product_details_nonce']) && wp_verify_nonce( $_POST['search_product_details_nonce'], 'search_product_details' )) {
                                    if(is_user_logged_in()) {
                                        if(isset($_POST['p_search'])) {
                        ?>
                        <table class="table table-bordered text-center fw-bolder">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th><?php esc_html_e("Sr#"); ?></th>
                                    <th><?php esc_html_e("Product Name"); ?></th>
                                    <th><?php esc_html_e("Quantity"); ?></th>
                                    <th><?php esc_html_e("Per Piece"); ?></th>
                                    <th><?php esc_html_e("Date"); ?></th>
                                    <th><?php esc_html_e("Shopkeeper"); ?></th>
                                    <th><?php esc_html_e("Action"); ?></th>
                                </tr>
                            </thead>
                            <tbody class="bg-light">
                                <?php
                                    $serial_no = sanitize_text_field($_POST['serial_no']);
                                    $p_name = sanitize_text_field($_POST['p_name']);
                                    $p_date = sanitize_text_field($_POST['p_date']);

                                    $fetch_product = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `ID` = '$serial_no' OR `product_name` = '$p_name' OR `purchase_date` = '$p_date'");

                                    if($fetch_product) {
                                        foreach($fetch_product as $fetch_product) {
                                            $shopkeeper_id = $fetch_product->shopkeeper_id;

                                            $shopkeeper = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `ID` = '$shopkeeper_id'");
                                            foreach($shopkeeper as $shopkeeper): 
                                ?>
                                <tr>
                                    <td><?php echo esc_html($fetch_product->ID); ?></td>
                                    <td><?php echo esc_html($fetch_product->product_name); ?></td>
                                    <td><?php echo esc_html(number_format_i18n($fetch_product->quantity)); ?></td>
                                    <td><?php echo esc_html(number_format_i18n($fetch_product->price_per_piece)); ?></td>
                                    <td><?php echo esc_html($fetch_product->purchase_date); ?></td>
                                    <td><?php echo esc_html($shopkeeper->shopkeeper_name); ?></td>
                                    <td>
                                        <a href="<?php echo esc_url(home_url()); ?>/products-detail?query=view_detail&hash=<?php echo esc_html($fetch_product->ID); ?>&<?php echo esc_html(md5(rand(5,16))); ?>" target="_blank" class="btn btn-primary text-white"><?php esc_html_e('View'); ?></a>
                                    </td>
                                </tr>
                                <?php
                                            endforeach;
                                        }
                                    } else {
                                        echo "<tr class='text-center'><td colspan='10' class='text-danger text-center'>No product found!</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                        <?php   
                                    }
                                } else {
                                    echo "<div class='alert alert-danger' role='alert'>
                                        <strong>User is not logged in!</strong>
                                    </div>";
                                }
                            }
                        ?>
                    </div><!-- .tab-pane -->
                    <div class="tab-pane fade bg-white border rounded table-responsive p-3" id="search-summary" role="tabpanel" aria-labelledby="search-summary-tab">
                        <form class="row" method="post">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group my-1">
                                    <label for="s_date" class="form-label"><?php esc_html_e("Start Date"); ?>:</label>
                                    <input type="date" name="s_date" class="form-control bg-light">
                                </div><!-- .form-group -->
                            </div><!-- .col-lg-6 -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group my-1">
                                    <label for="e_date" class="form-label"><?php esc_html_e("End Date"); ?>:</label>
                                    <input type="date" name="e_date" class="form-control bg-light">
                                </div><!-- .form-group -->
                            </div><!-- .col-lg-6 -->
                            <div class="col-12 text-end">
                                <?php wp_nonce_field('search_summary_details', 'search_summary_details_nonce'); ?>
                                <button class="btn btn-primary my-2" name="summary_search"><?php esc_html_e("Search"); ?></button>
                            </div><!-- .col-12 -->
                        </form>
                        <?php
                            if(isset($_POST['search_summary_details_nonce']) && wp_verify_nonce( $_POST['search_summary_details_nonce'], 'search_summary_details' )) {
                                if(is_user_logged_in()) {
                                    if(isset($_POST['summary_search'])) {
                                        $s_date = sanitize_text_field($_POST['s_date']);
                                        $e_date = sanitize_text_field($_POST['e_date']);
                        ?>
                        <div class="row fw-bolder bg-white rounded p-3">
                            <!------- Sales Summary ------->
                            <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                                <ul class="p-0 m-0 list-unstyled fw-bolder">
                                    <li>
                                        <span><?php esc_html_e("Sales Amount"); ?>:</span>
                                        <span class="text-primary mx-2">
                                            <?php
                                                $monthly_sales = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice WHERE `sale_date` BETWEEN date('$s_date') AND date('$e_date')");
                                                echo esc_html(number_format_i18n($monthly_sales));
                                            ?>
                                        </span>
                                    </li>
                                    <li>
                                        <span><?php esc_html_e("Received Amount"); ?>:</span>
                                        <span class="text-success mx-2">
                                            <?php
                                                $monthly_received = 0;
                                                $monthly_discount  = 0;
                                                $received = $wpdb->get_results("SELECT * FROM fst_customer_payments WHERE `paid_date` BETWEEN date('$s_date') AND date('$e_date')");
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
                                                $remaining_amount = $monthly_sales -  $monthly_received;
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
                                        <span><?php esc_html_e("Purchase Amount"); ?>:</span>
                                        <span class="text-primary mx-2">
                                            <?php
                                                $monthly_purchase = $wpdb->get_var("SELECT SUM(price) FROM fst_purchase_data WHERE `purchase_date` BETWEEN date('$s_date') AND date('$e_date')");
                                                echo esc_html(number_format_i18n($monthly_purchase));
                                            ?>
                                        </span>
                                    </li>
                                    <li>
                                        <span><?php esc_html_e("Pay Amount"); ?>:</span>
                                        <span class="text-success mx-2">
                                            <?php
                                                $monthly_pay = $wpdb->get_var("SELECT SUM(amount) FROM fst_shopkeeper_payments WHERE `paid_date` BETWEEN date('$s_date') AND date('$e_date')");
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
                        
                            <!------- Expense Summary ------->
                            <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                                <span><?php esc_html_e("Expense"); ?>:</span>
                                <span class="text-primary mx-2">
                                    <?php 
                                        $expense_amount = $wpdb->get_var("SELECT SUM(expense_amount) FROM fst_expense_data WHERE `expense_date` BETWEEN date('$s_date') AND date('$e_date')");
                                        echo esc_html(number_format_i18n($expense_amount));
                                    ?>
                                </span>
                                <br>
                                <br>
                                <span><?php esc_html_e("Discount"); ?>:</span>
                                <span class="text-primary mx-2">
                                    <?php echo esc_html(number_format_i18n($monthly_discount)); ?>
                                </span>
                            </div><!-- .col-4 -->

                            <!------- Salary Summary ------->
                            <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                                <ul class="p-0 m-0 list-unstyled">
                                    <li>
                                        <span><?php esc_html_e("Salary Amount"); ?>:</span>
                                        <span class="text-primary mx-2">
                                            <?php
                                                $total_salary = $wpdb->get_var("SELECT SUM(salary) FROM fst_salary_data WHERE `pay_date` BETWEEN date('$s_date') AND date('$s_date')");
                                                echo esc_html(number_format_i18n($total_salary));
                                            ?>
                                        </span>
                                    </li>
                                    <li>
                                        <span><?php esc_html_e("Pay Amount"); ?>:</span>
                                        <span class="text-success mx-2">
                                            <?php
                                                $pay_salary = $wpdb->get_var("SELECT SUM(pay_amount) FROM fst_salary_data WHERE `pay_date` BETWEEN date('$s_date') AND date('$s_date')");
                                                echo esc_html(number_format_i18n($pay_salary));
                                            ?>
                                        </span>
                                    </li>
                                    <li>
                                        <span><?php esc_html_e("Remaining Amount"); ?>:</span>
                                        <span class="text-danger mx-2">
                                            <?php
                                                $remaining_salary = $total_salary -  $pay_salary;
                                                echo esc_html(number_format_i18n($remaining_salary));
                                            ?>
                                        </span>
                                    </li>
                                </ul>
                            </div><!-- .col-4 -->

                            <!------- Product Summary ------->
                            <div class="col-lg-6 col-md-6 col-sm-12 my-4">
                                <?php
                                    $fetch_sales_product = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `sale_date` BETWEEN date('$s_date') AND date('$s_date')");
                                    foreach($fetch_sales_product as $fetch) :
                                        $product_id = $fetch->product_id;

                                        $fetch_product = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `ID` = $product_id");
                                        foreach($fetch_product as $product) {
                                            $product_name = $product->product_name;
                                            $price = $product->price_with_expense;
                                        }
                                        $fetch_product_profit = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `product_id` = $product_id AND `sale_date` BETWEEN date('$s_date') AND date('$s_date')");
                                        error_reporting(0);
                                        $total_amount = 0;
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
                                            $gross_profit = $gross - $monthly_discount - $expense_amount;
                                        } else {
                                            $gross = 0;
                                            $gross_profit = $gross - $monthly_discount - $expense_amount;
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
                                        $net_profict = $remaining_amount + $monthly_sales - $monthly_received - $gross_profit;
                                        if($net_profict > 0) {
                                            echo "<span class='text-primary'> ".number_format_i18n($net_profict)."</span>";
                                        } else {
                                            echo "<span class='text-danger'> ".number_format_i18n($net_profict)."</span>";
                                        }
                                    ?>
                                </div><!-- .border -->
                            </div><!-- .col-4 -->
                        </div><!-- .row -->
                        <?php   
                                    }
                                } else {
                                    echo "<div class='alert alert-danger' role='alert'>
                                        <strong>User is not logged in!</strong>
                                    </div>";
                                }
                            }
                        ?>
                    </div><!-- .tab-pane -->
                </div><!-- .tab-content -->
            </div><!-- .display-content -->

        </div><!-- .container -->
    </div><!-- .page-main-content -->
    
<?php
get_footer();