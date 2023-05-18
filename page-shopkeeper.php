<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Shopkeeper
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
            
            <h1 class="text-center text-capitalize my-5"><?php esc_html_e(the_title()); ?></h1>

            <div class="display-content table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="bg-dark text-white">
                        <tr class="text-center">
                            <th><?php esc_html_e("Sr#"); ?></th>
                            <th class="text-start"><?php esc_html_e("Shopkeeper"); ?></th>
                            <th><?php esc_html_e("Total"); ?></th>
                            <th><?php esc_html_e("Pay"); ?></th>
                            <th><?php esc_html_e("Remaining"); ?></th>
                            <th><?php esc_html_e("Account"); ?></th>
                        </tr>
                    </thead>
                    <tbody class="bg-light">
                        <?php
                            $result = $wpdb->get_results('SELECT * FROM fst_purchase_data');
                            $sr = 1;

                            if($result) {
                                foreach($result as $row) {
                                    $shopkeeper_id = $row->shopkeeper_id; 
                        ?>
                        <tr class="text-center">
                            <td><b><?php echo esc_html($sr++); ?></b></td>
                            <td class="text-start">
                                <?php 
                                    $shopkeeper = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `ID` = '".$shopkeeper_id."'");

                                    foreach($shopkeeper as $detail) {
                                        echo esc_html($shopkeeper_name = $detail->shopkeeper_name) . "&nbsp;&nbsp;&nbsp;&nbsp;" . "<b>" . esc_html($shop_number = $detail->shop_number) . "</b>";
                                    }
                                ?>
                            </td>
                            <td><b><?php echo esc_html(number_format_i18n($total_amount = $row->total_price)); ?></b></td>
                            <td>
                                <b>
                                    <?php
                                        $pay_amount = $wpdb->get_var("SELECT SUM(amount) FROM fst_shopkeeper_payments WHERE `shopkeeper_id` = '".$shopkeeper_id."'");
    
                                        echo esc_html(number_format_i18n($pay_amount));
                                    ?>
                                </b>
                            </td>
                            <td><b><?php echo esc_html(number_format_i18n($total_amount - $pay_amount)); ?></b></td>
                            <td>
                                <?php 
                                    echo esc_html($detail->shopkeeper_account) . '<br>'; 
                                    $meta_values = $wpdb->get_results("SELECT * FROM fst_shopkeeper_meta_data WHERE `shopkeeper_id` = '$shopkeeper_id' AND `meta_key` = 'account'");
                                    if($meta_values) {
                                        foreach($meta_values as $values) {
                                            echo esc_html($values->meta_value) . '<br>';
                                        }
                                    }
                                ?>
                            </td>
                        </tr>
                        <?php 
                                }
                            } else {
                                echo "<tr>
                                    <td colspan='8' class='text-center text-danger'>No Data Found...</td>
                                </tr>";
                            }
                        ?>
                    </tbody>
                    <tfoot class="bg-dark text-white">
                        <tr class="text-center">
                            <td colspan="2" class="fw-bolder text-end"><?php _e("Total Amount"); ?> = </td>
                            <td>
                                <b>
                                    <?php 
                                        $total_amount = $wpdb->get_var("SELECT SUM(total_price) FROM fst_purchase_data");
                                        echo esc_html(number_format_i18n($total_amount));
                                    ?>
                                </b>
                            </td>
                            <td>
                                <b>
                                    <?php 
                                        $received_amount = $wpdb->get_var("SELECT SUM(amount) FROM fst_shopkeeper_payments");
                                        echo esc_html(number_format_i18n($received_amount));
                                    ?>
                                </b>
                            </td>
                            <td><b><?php echo esc_html(number_format_i18n($total_amount - $received_amount)); ?></b></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div><!-- .display-content -->

        </div><!-- .container -->
    </div><!-- .page-main-content -->
    
<?php
get_footer();