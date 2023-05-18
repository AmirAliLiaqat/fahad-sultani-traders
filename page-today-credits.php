<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Today Credits
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

            <div class="filter bg-white rounded p-3 mb-4">
                <form class="row" method="post">
                    <div class="col-12">
                        <div class="form-group my-1">
                            <label for="date_filter" class="form-label"><?php esc_html_e("Date"); ?>:</label>
                            <input type="date" name="date_filter" class="form-control bg-light">
                        </div><!-- .form-group -->
                    </div><!-- .col-12 -->
                    <div class="col-12 text-end">
                        <?php wp_nonce_field('search_by_date', 'search_by_date_nonce'); ?>
                        <button class="btn btn-primary my-2" name="search_filter"><?php esc_html_e("Search"); ?></button>
                    </div><!-- .col-12 -->
                </form>
            </div><!-- .filter -->

            <?php
                /************ code for adding customer ***************/
                if(isset($_POST['search_by_date_nonce']) && wp_verify_nonce( $_POST['search_by_date_nonce'], 'search_by_date' )) {
                    if(is_user_logged_in()) {
                        if(isset($_POST['search_filter'])) {
                            $date_filter = sanitize_text_field($_POST['date_filter']);
            ?>
            <div class="display-content table-responsive">
                <table class="table table-hover table-bordered text-center fw-bolder">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th><?php esc_html_e("Current"); ?></th>
                            <th><?php esc_html_e("Customer"); ?></th>
                            <th><?php esc_html_e("Total"); ?></th>
                            <th><?php esc_html_e("Received"); ?></th>
                            <th><?php esc_html_e("Remaining"); ?></th>
                            <th><?php esc_html_e("Action"); ?></th>
                        </tr>
                    </thead>
                    <tbody class="bg-light">
                        <?php
                            $result = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `sale_date` = '$date_filter'");
                            
                            $total_amount = 0;
                            if($result) {
                                foreach($result as $row) {
                                    $customer_id = $row->customer_id;
                                    $total_amount += $row->total_amount;

                                    $customer = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `ID` = '".$customer_id."'");

                                    foreach($customer as $detail) {
                                        $current = $detail->current;
                                        $customer_detail = esc_html($customer_name = $detail->name) . "&nbsp;&nbsp;&nbsp;&nbsp;" . esc_html($shop_number = $detail->shop_number);
                                    }
    
                                    $customer_total_amount = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice WHERE `customer_id` = '$customer_id' AND `sale_date` = '$date_filter'");
                                    $customer_received_amount = $wpdb->get_var("SELECT SUM(amount) FROM fst_customer_payments WHERE `customer_id` = '$customer_id' AND `paid_date` = '$date_filter'");
    
                                    $tbody_tr_html = '<tr>';
                                    $tbody_tr_html .= '<td>'.esc_html(number_format_i18n($current)).'</td>';
                                    $tbody_tr_html .= '<td>'.esc_html($customer_detail).'</td>';
                                    $tbody_tr_html .= '<td>'.esc_html(number_format_i18n($customer_total_amount)).'</td>';
                                    $tbody_tr_html .= '<td>'.esc_html(number_format_i18n($customer_received_amount)).'</td>';
                                    $tbody_tr_html .= '<td>'.esc_html(number_format_i18n($customer_total_amount - $customer_received_amount)).'</td>';
                                    $tbody_tr_html .= '<td>';
                                    $tbody_tr_html .= '<input type="checkbox" id="action_field" name="action[]" class="action_field" value="1">&nbsp;';
                                    $tbody_tr_html .= '<input type="checkbox" id="action_field" name="action[]" class="action_field" value="2">&nbsp;';
                                    $tbody_tr_html .= '<input type="checkbox" id="action_field" name="action[]" class="action_field" value="3">';
                                    $tbody_tr_html .= '</td>';
                                    $tbody_tr_html .= '</tr>';
                                    echo $tbody_tr_html;
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
                            $tfoot_tr_html = '<tr>';
                            $tfoot_tr_html .= '<td colspan="2">'.esc_html("Total Amount").'</td>';
                            $tfoot_tr_html .= '<td>'.esc_html(number_format_i18n($total_amount)).'</td>';
                            $tfoot_tr_html .= '<td>'.esc_html(number_format_i18n($total_receive = $wpdb->get_var("SELECT SUM(amount) FROM fst_customer_payments WHERE `paid_date` = '$date'"))).'</td>';
                            $tfoot_tr_html .= '<td>'.esc_html(number_format_i18n($total_amount - $total_receive)).'</td>';
                            $tfoot_tr_html .= '<td>';
                            $tfoot_tr_html .= '<button name="save_action" class="btn btn-primary">Save Changes</button>';
                            $tfoot_tr_html .= '</td>';
                            $tfoot_tr_html .= '</tr>';
                            echo $tfoot_tr_html;
                            if(isset($_POST['save_action']) && !empty($_POST['action'])) {
                                foreach($_POST['action'] as $action) {
                                    echo $action;
                                }
                            }
                        ?>
                    </tfoot>
                </table>    
            </div><!-- .display-content -->
            <?php       
                        }
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>
                          <strong>User is not logged in!</strong>
                        </div>";
                    }
                } else {
            ?>
            <div class="display-content table-responsive">
                <table class="table table-hover table-bordered text-center fw-bolder">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th><?php esc_html_e("Current"); ?></th>
                            <th><?php esc_html_e("Customer"); ?></th>
                            <th><?php esc_html_e("Total"); ?></th>
                            <th><?php esc_html_e("Received"); ?></th>
                            <th><?php esc_html_e("Remaining"); ?></th>
                            <th><?php esc_html_e("Action"); ?></th>
                        </tr>
                    </thead>
                    <tbody class="bg-light">
                        <?php
                            $result = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `sale_date` = '$date'");
                            
                            $total_amount = 0;
                            if($result) {
                                foreach($result as $row) {
                                    $customer_id = $row->customer_id;
                                    $total_amount += $row->total_amount;

                                    $customer = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `ID` = '".$customer_id."'");

                                    foreach($customer as $detail) {
                                        $current = $detail->current;
                                        $customer_detail = esc_html($customer_name = $detail->name) . "&nbsp;&nbsp;&nbsp;&nbsp;" . esc_html($shop_number = $detail->shop_number);
                                    }
    
                                    $customer_total_amount = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice WHERE `customer_id` = '$customer_id' AND `sale_date` = '$date'");
                                    $customer_received_amount = $wpdb->get_var("SELECT SUM(amount) FROM fst_customer_payments WHERE `customer_id` = '$customer_id' AND `paid_date` = '$date'");
    
                                    $tbody_tr_html = '<tr>';
                                    $tbody_tr_html .= '<td>'.esc_html(number_format_i18n($current)).'</td>';
                                    $tbody_tr_html .= '<td>'.esc_html($customer_detail).'</td>';
                                    $tbody_tr_html .= '<td>'.esc_html(number_format_i18n($customer_total_amount)).'</td>';
                                    $tbody_tr_html .= '<td>'.esc_html(number_format_i18n($customer_received_amount)).'</td>';
                                    $tbody_tr_html .= '<td>'.esc_html(number_format_i18n($customer_total_amount - $customer_received_amount)).'</td>';
                                    $tbody_tr_html .= '<td>';
                                    $tbody_tr_html .= '<input type="checkbox" id="action_field" name="action[]" class="action_field" value="1">&nbsp;';
                                    $tbody_tr_html .= '<input type="checkbox" id="action_field" name="action[]" class="action_field" value="2">&nbsp;';
                                    $tbody_tr_html .= '<input type="checkbox" id="action_field" name="action[]" class="action_field" value="3">';
                                    $tbody_tr_html .= '</td>';
                                    $tbody_tr_html .= '</tr>';
                                    echo $tbody_tr_html;
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
                            $tfoot_tr_html = '<tr>';
                            $tfoot_tr_html .= '<td colspan="2">'.esc_html("Total Amount").'</td>';
                            $tfoot_tr_html .= '<td>'.esc_html(number_format_i18n($total_amount)).'</td>';
                            $tfoot_tr_html .= '<td>'.esc_html(number_format_i18n($total_receive = $wpdb->get_var("SELECT SUM(amount) FROM fst_customer_payments WHERE `paid_date` = '$date'"))).'</td>';
                            $tfoot_tr_html .= '<td>'.esc_html(number_format_i18n($total_amount - $total_receive)).'</td>';
                            $tfoot_tr_html .= '<td>';
                            $tfoot_tr_html .= '<button name="save_action" class="btn btn-primary">Save Changes</button>';
                            $tfoot_tr_html .= '</td>';
                            $tfoot_tr_html .= '</tr>';
                            echo $tfoot_tr_html;
                            if(isset($_POST['save_action']) && !empty($_POST['action'])) {
                                foreach($_POST['action'] as $action) {
                                    echo $action;
                                }
                            }
                        ?>
                    </tfoot>
                </table>    
            </div><!-- .display-content -->
            <?php } ?>

        </div><!-- .container -->
    </div><!-- .page-main-content -->
    
<?php
get_footer();