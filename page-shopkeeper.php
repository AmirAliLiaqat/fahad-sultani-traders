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
        // error_reporting(0);
        global $wpdb;
        $date = date('Y-m-d');
    ?>  

    <div class="page-main-content">
        <div class="container-fluid p-5">

            <?php get_header(); ?>
            
            <h1 class="text-center text-capitalize my-5"><?php esc_html_e(the_title()); ?></h1>

            <div class="display-content table-responsive">
                <table class="table table-hover table-bordered fw-bolder">
                    <thead class="bg-dark text-white">
                        <?php
                            $thead_tr_html = '<tr class="text-center">';
                            $thead_tr_html .= '<th>'.esc_html__("Sr#").'</th>';
                            $thead_tr_html .= '<th class="text-start">'.esc_html__("Shopkeeper").'</th>';
                            $thead_tr_html .= '<th>'.esc_html__("Total").'</th>';
                            $thead_tr_html .= '<th>'.esc_html__("Account").'</th>';
                            $thead_tr_html .= '</tr>';
                            echo $thead_tr_html;
                        ?>
                    </thead>
                    <tbody class="bg-light">
                        <?php
                            $result = $wpdb->get_results('SELECT * FROM fst_purchase_data');
                            $sr = 1;

                            $grand_amount = 0;
                            $shopkeeper_invoices = array();
                            if($result) {
                                foreach($result as $row) {
                                    $shopkeeper_id = $row->shopkeeper_id; 
                                    $grand_amount += $row->price;
                                    
                                    $total_amount = $wpdb->get_var("SELECT SUM(price) FROM fst_purchase_data WHERE `shopkeeper_id` = '".$shopkeeper_id."'");
                                    
                                    if(isset($shopkeeper_invoices[$shopkeeper_id])) {
                                        $shopkeeper_invoices[$shopkeeper_id]['price'] += $row->price;
                                    } else {
                                        $shopkeeper = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `ID` = '$shopkeeper_id'");
                                        foreach($shopkeeper as $detail) {
                                            $shopkeeper_detail = esc_html($name = $detail->shopkeeper_name) . "&nbsp;&nbsp;&nbsp;&nbsp;" . esc_html($shop_number = $detail->shop_number);
                                            $account = $detail->shopkeeper_account;
                                        }

                                        $receive_amount = $wpdb->get_var("SELECT SUM(amount) FROM fst_shopkeeper_payments WHERE `shopkeeper_id` = '$shopkeeper_id'");
                                        $remain_amount = $total_amount - $receive_amount;
                                        $shopkeeper_invoices[$shopkeeper_id]['price'] = $total_amount;

                                        $tbody_tr_html = '<tr class="text-center">';
                                        $tbody_tr_html .= '<td>'.esc_html($sr++).'</td>';
                                        $tbody_tr_html .= '<td class="text-start">'.esc_html($shopkeeper_invoices[$shopkeeper_id]['shopkeeper_detail'] = $shopkeeper_detail).'</td>';
                                        $tbody_tr_html .= '<td>'.esc_html(number_format_i18n($shopkeeper_invoices[$shopkeeper_id]['remain_amount'] = $remain_amount)).'</td>';
                                        $tbody_tr_html .= '<td>'
                                                                .esc_html($account) . '<br>';
                                                                $meta_values = $wpdb->get_results("SELECT * FROM fst_shopkeeper_meta_data WHERE `shopkeeper_id` = '$shopkeeper_id' AND `meta_key` = 'account'");
                                                                if($meta_values) {
                                                                    foreach($meta_values as $values) {
                                                                        esc_html($values->meta_value) . '<br>';
                                                                    }
                                                                }
                                                          '</td>';
                                        $tbody_tr_html .= '</tr>';
                                        echo $tbody_tr_html;
                                    }
                                }
                            } else {
                                echo "<tr>
                                    <td colspan='8' class='text-center text-danger'>No Data Found...</td>
                                </tr>";
                            }
                        ?>
                    </tbody>
                    <tfoot class="bg-dark text-white">
                        <?php
                            $receive_amount = $wpdb->get_var("SELECT SUM(amount) FROM fst_shopkeeper_payments");
                            $remiain_amount = $grand_amount - $receive_amount;

                            $tfoot_tr_html = '<tr class="text-center">';
                            $tfoot_tr_html .= '<th colspan="2" class="text-end">'.esc_html__("Total Amount").'</th>';
                            $tfoot_tr_html .= '<th>'.esc_html(number_format_i18n($remiain_amount)).'</th>';
                            $tfoot_tr_html .= '<th></th>';
                            $tfoot_tr_html .= '</tr>';
                            echo $tfoot_tr_html;
                        ?>
                    </tfoot>
                </table>
            </div><!-- .display-content -->

        </div><!-- .container -->
    </div><!-- .page-main-content -->
    
<?php
get_footer();