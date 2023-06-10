<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Products Detail
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

    <?php
        if(!is_user_logged_in()) {
            $url = get_site_url() . "/login";
            wp_redirect( $url );
        }
        // error_reporting(0);
        global $wpdb;
        
        $date = date('Y-m-d');
    ?>

    <div class="page-main-content">
        <div class="container-fluid p-5">

            <?php get_header(); ?>

            <h1 class="text-center text-capitalize my-5"><?php esc_html_e(the_title()); ?></h1>
            
            <div class="product_total_summary bg-white border rounded table-responsive p-3">
                <div class="detail">
                    <?php
                        if(is_user_logged_in()) {
                            if(isset($_GET['query'])) {
                                $product_id = $_GET['hash'];
                    ?>
                    <table class="table table-bordered text-center fw-bolder">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th><?php esc_html_e("Sr#"); ?></th>
                                <th><?php esc_html_e("Product Name"); ?></th>
                                <th><?php esc_html_e("Shopkeeper"); ?></th>
                                <th><?php esc_html_e("Date"); ?></th>
                                <th><?php esc_html_e("Quantity"); ?></th>
                                <th><?php esc_html_e("Per Piece"); ?></th>
                                <th><?php esc_html_e("Piece"); ?></th>
                                <th><?php esc_html_e("Expense"); ?></th>
                                <th><?php esc_html_e("With Expense"); ?></th>
                                <th><?php esc_html_e("Total Price"); ?></th>
                            </tr>
                        </thead>
                        <tbody class="bg-light">
                            <?php

                                // $fetch_product = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `ID` = '$serial_no' OR `product_name` = '$p_name' OR `purchase_date` = '$p_date'");
                                $fetch_product = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `ID` = '$product_id'");

                                if($fetch_product) {
                                    foreach($fetch_product as $fetch_product) {
                                        $shopkeeper_id = $fetch_product->shopkeeper_id;

                                        $shopkeeper = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `ID` = '$shopkeeper_id'");
                                        foreach($shopkeeper as $shopkeeper): 
                                            $total = $fetch_product->total_price;
                            ?>
                            <tr>
                                <td><?php echo esc_html($fetch_product->ID); ?></td>
                                <td><?php echo esc_html($fetch_product->product_name); ?></td>
                                <td><?php echo esc_html($shopkeeper->shopkeeper_name); ?></td>
                                <td><?php echo esc_html($fetch_product->purchase_date); ?></td>
                                <td><?php echo esc_html(number_format_i18n($fetch_product->quantity)); ?></td>
                                <td><?php echo esc_html(number_format_i18n($fetch_product->price_per_piece)); ?></td>
                                <td><?php echo esc_html(number_format_i18n($fetch_product->price)); ?></td>
                                <td><?php echo esc_html(number_format_i18n($fetch_product->expenses)); ?></td>
                                <td><?php echo esc_html(number_format_i18n($fetch_product->price_with_expense)); ?></td>
                                <td><?php echo esc_html(number_format_i18n($fetch_product->total_price)); ?></td>
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
                                <strong>Please first logged in with your accoutn!</strong>
                            </div>";
                        }
                    ?>
                </div><!-- .detail -->

                <div class="row mt-5">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <h2 class="mb-4"><?php esc_html_e('Product Sales'); ?>:</h2>
                        <table class="table table-bordered text-center fw-bolder">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th><?php esc_html_e('Date'); ?></th>
                                    <th><?php esc_html_e('Customer'); ?></th>
                                    <th><?php esc_html_e('Qty'); ?></th>
                                    <th><?php esc_html_e('Price'); ?></th>
                                    <th><?php esc_html_e('Total'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="bg-light">
                                <?php
                                    $fetch_product = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `product_id` = '$product_id'");

                                    $total_qty = 0;
                                    $grand_total = 0;
                                    if($fetch_product) :
                                        foreach($fetch_product as $fetch_product) :
                                            $customer_id = $fetch_product->customer_id;
                                            $sub_total = $fetch_product->total_amount;
                                            $grand_total += $fetch_product->total_amount;
                                            $total_qty += $fetch_product->quantity;
                                ?>
                                <tr>
                                    <td><?php echo esc_html($fetch_product->sale_date); ?></td>
                                    <td>
                                        <?php 
                                            $customer = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `ID` = '$customer_id'");
                                            foreach($customer as $customer) {
                                                echo esc_html($customer->name);
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo esc_html(number_format_i18n($fetch_product->quantity)); ?></td>
                                    <td><?php echo esc_html(number_format_i18n($fetch_product->price_per_quantity)); ?></td>
                                    <td><?php echo esc_html(number_format_i18n($sub_total)); ?></td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                            <tfoot class="bg-dark text-white">
                                <tr>
                                    <td colspan='4' class="text-end"><?php esc_html_e("Total Amount"); ?> =</td>
                                    <td><?php echo esc_html(number_format_i18n($grand_total)); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div><!-- .col-lg-6 -->
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <h2 class="mb-4"><?php esc_html_e('Product Summary'); ?>:</h2>
                        <table class="table table-bordered text-center fw-bolder">
                            <tbody>
                                <tr>
                                    <td class="bg-dark text-white text-start"><?php esc_html_e("Average"); ?></td>
                                    <td><?php echo esc_html(number_format_i18n($grand_total/$total_qty)); ?></td>
                                </tr>
                                <tr>
                                    <td class="bg-dark text-white text-start"><?php esc_html_e("Comission"); ?></td>
                                    <td><?php echo esc_html(number_format_i18n($grand_total*5/100)); ?></td>
                                </tr>
                                <tr>
                                    <td class="bg-dark text-white text-start"><?php esc_html_e("Profit / Lose"); ?></td>
                                    <?php
                                        $status = $grand_total - $total;

                                        if($status > 0) {
                                            echo '<td class="text-success">'.$status.'</td>';
                                        } else {
                                            echo '<td class="text-danger display-6">'.$status.'</td>';
                                        }
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- .col-lg-6 -->
                </div><!-- .row -->
            </div><!-- .product_total_summary -->
            
        </div><!-- .container-fluid -->
    </div><!-- .page-main-content -->
        
<?php
get_footer();