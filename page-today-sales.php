<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Today Sales
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

global $customer_invoices_result;
$customer_invoices_result = array();

function add_products_to_same_row($row) {
    global $customer_invoices_result;
    
    if(is_array($customer_invoices_result) && isset($customer_invoices_result['customer_invoice']) && is_array($customer_invoices_result['customer_invoice'])) {
        $add_new_row = true;
        foreach($customer_invoices_result['customer_invoice'] as $key=>$single_row) {
            if(
                isset($single_row['customer_id']) && 
                $single_row['customer_id'] == $row->customer_id && 
                isset($single_row['product_ids']) && 
                is_array($single_row['product_ids']) && 
                !array_key_exists($row->product_id, $single_row['product_ids'])
            ) {
                $add_new_row = $key;
            }
        }

        if($add_new_row === true) {
            add_row_to_result($row);
        } else if($add_new_row) {
            $customer_invoices_result['customer_invoice'][$add_new_row]['product_ids'][$row->product_id] = array(
                'per_piece_price' => $row->price_per_quantity,
                'product_id' => $row->product_id,
                'product_quantity' => $row->quantity,
            );
        }
    } else {
        add_row_to_result($row);
    }
}

function add_row_to_result($row) {
    global $customer_invoices_result;
    
    $product_ids = array($row->product_id => array(
        'per_piece_price' => $row->price_per_quantity,
        'product_id' => $row->product_id,
        'product_quantity' => $row->quantity,
    ));

    $customer_invoices_result['customer_invoice'][] = array(
        'customer_id' => $row->customer_id,
        'product_ids' => $product_ids,
    );
}
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
                <?php 
                    $table_th_html = '<tr class="text-center">
                        <th>'. __("Sr#").'</th>
                        <th class="text-start">'.__("Customer").'</th>';
                        $td_ids = array();
                        $customer_invoices_total_data = array();
                        $products = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `purchase_date` = '$date_filter'");

                        foreach($products as $item) {
                            if(isset($td_ids[$item->ID]) && isset($td_ids[$item->ID]['total_quantity'])) {
                                $td_ids[$item->ID]['total_quantity'] += (int)$item->quantity; 
                            } else {
                                $td_ids[$item->ID] = array('total_quantity' => (int)$item->quantity);
                            }

                            $table_th_html .= "<th>$item->product_name</th>";
                            $table_th_html .= "<th>$item->quantity</th>";
                        }
                    $table_th_html .= '<th> '.__("Total").'</th>
                        </tr>';
                ?>
                <table class="table table-hover table-bordered fw-bolder">
                    <thead class="bg-dark text-white">
                        <?php echo $table_th_html; ?>
                    </thead>
                    <tbody class="bg-light">
                        <?php
                            $result = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `sale_date` = '$date_filter'");
                            $sr = 1;
                            $whole_total = 0;
                            if($result) {
                                foreach($result as $row) {
                                    add_products_to_same_row( $row);

                                    if(isset($customer_invoices_total_data[$row->product_id]) && isset($customer_invoices_total_data[$row->product_id]['total_quantity'])) {
                                        $customer_invoices_total_data[$row->product_id]['total_quantity'] += (int)$row->quantity;
                                        $customer_invoices_total_data[$row->product_id]['total_price'] += (int)$row->price_per_quantity;
                                    } else {
                                        $customer_invoices_total_data[$row->product_id]['total_quantity'] = (int)$row->quantity;
                                        $customer_invoices_total_data[$row->product_id]['total_price'] = (int)$row->price_per_quantity;
                                    }
                                }
                                    
                                foreach($customer_invoices_result['customer_invoice'] as $row) {
                                    $customer_tr_html = '<tr class="text-center">';
                                    $customer_tr_html .= '<td>'.esc_html($sr++).'</td>';
                                    $customer_tr_html .= '<td class="text-start">';
                                    $customer_id = $row['customer_id'];
                                    $customer = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `ID` = '".$customer_id."'");

                                    foreach($customer as $detail) {
                                        $customer_tr_html .= esc_html($customer_name = $detail->name) . "&nbsp;&nbsp;&nbsp;&nbsp;" . esc_html($shop_number = $detail->shop_number);
                                    }
                                    
                                    $customer_tr_html .= '</td>';
                                    $sub_total = 0;
                                    foreach($td_ids as $key=>$p_id) {
                                        if(isset($row['product_ids']) && isset($row['product_ids'][$key])) {
                                            $customer_tr_html .= '<td>'.esc_html(number_format_i18n($product_price = $row['product_ids'][$key]['per_piece_price'])).'</td>';
                                            $customer_tr_html .= '<td>'. esc_html(number_format_i18n($product_qty = $row['product_ids'][$key]['product_quantity'])).'</td>';
                                            $sub_total += ((int)$product_price) * ((int)$product_qty);
                                        } else {
                                            $customer_tr_html .= '<td>&nbsp;</td>';
                                            $customer_tr_html .= '<td>&nbsp;</td>';
                                        }   
                                    }
                                    $customer_tr_html .= '<td>'. esc_html(number_format_i18n($sub_total)).'</td>';
                                    $customer_tr_html .= '</tr>';
                                    $whole_total += $sub_total;
                                    echo $customer_tr_html;
                                }
                            } else {
                                echo "<tr><td colspan='15' class='text-center text-danger'>No data found!</td></tr>";
                            }
                        ?>
                    </tbody>
                    <tfoot class="bg-dark text-white text-center">
                        <tr>
                            <td>&nbsp;</td>
                            <td><?php _e("Total Amount"); ?></td>
                            <?php 
                                foreach($td_ids as $key=>$p_id){
                                    $total_qty = $wpdb->get_var("SELECT SUM(quantity) FROM fst_purchase_data WHERE `ID` = $key");
                                    $sales_qty = $customer_invoices_total_data[$key]['total_quantity'];
                                    $remain_qty = $total_qty - $sales_qty;

                                    echo '<td>'. esc_html(number_format_i18n(($remain_qty))).'</td>';
                                    echo '<td>'. esc_html(number_format_i18n(($sales_qty))).'</td>';
                                }
                            ?>
                            <td><?php echo esc_html(number_format_i18n($whole_total)); ?></td>
                        </tr>
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
                <?php 
                    $table_th_html = '<tr class="text-center">
                        <th>'. __("Sr#").'</th>
                        <th class="text-start">'.__("Customer").'</th>';
                        $td_ids = array();
                        $customer_invoices_total_data = array();
                        $products = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `purchase_date` = '$date'");

                        foreach($products as $item) {
                            if(isset($td_ids[$item->ID]) && isset($td_ids[$item->ID]['total_quantity'])) {
                                $td_ids[$item->ID]['total_quantity'] += (int)$item->quantity; 
                            } else {
                                $td_ids[$item->ID] = array('total_quantity' => (int)$item->quantity);
                            }

                            $table_th_html .= "<th>$item->product_name</th>";
                            $table_th_html .= "<th>$item->quantity</th>";
                        }
                    $table_th_html .= '<th> '.__("Total").'</th>
                        </tr>';
                ?>
                <table class="table table-hover table-bordered fw-bolder">
                    <thead class="bg-dark text-white">
                        <?php echo $table_th_html; ?>
                    </thead>
                    <tbody class="bg-light">
                        <?php
                            $result = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `sale_date` = '$date'");
                            $sr = 1;
                            $whole_total = 0;
                            if($result) {
                                foreach($result as $row) {
                                    add_products_to_same_row( $row);

                                    if(isset($customer_invoices_total_data[$row->product_id]) && isset($customer_invoices_total_data[$row->product_id]['total_quantity'])) {
                                        $customer_invoices_total_data[$row->product_id]['total_quantity'] += (int)$row->quantity;
                                        $customer_invoices_total_data[$row->product_id]['total_price'] += (int)$row->price_per_quantity;
                                    } else {
                                        $customer_invoices_total_data[$row->product_id]['total_quantity'] = (int)$row->quantity;
                                        $customer_invoices_total_data[$row->product_id]['total_price'] = (int)$row->price_per_quantity;
                                    }
                                }
                                    
                                foreach($customer_invoices_result['customer_invoice'] as $row) {
                                    $customer_tr_html = '<tr class="text-center">';
                                    $customer_tr_html .= '<td>'.esc_html($sr++).'</td>';
                                    $customer_tr_html .= '<td class="text-start">';
                                    $customer_id = $row['customer_id'];
                                    $customer = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `ID` = '".$customer_id."'");

                                    foreach($customer as $detail) {
                                        $customer_tr_html .= esc_html($customer_name = $detail->name) . "&nbsp;&nbsp;&nbsp;&nbsp;" . esc_html($shop_number = $detail->shop_number);
                                    }
                                    
                                    $customer_tr_html .= '</td>';
                                    $sub_total = 0;
                                    foreach($td_ids as $key=>$p_id) {
                                        if(isset($row['product_ids']) && isset($row['product_ids'][$key])) {
                                            $customer_tr_html .= '<td>'.esc_html(number_format_i18n($product_price = $row['product_ids'][$key]['per_piece_price'])).'</td>';
                                            $customer_tr_html .= '<td>'. esc_html(number_format_i18n($product_qty = $row['product_ids'][$key]['product_quantity'])).'</td>';
                                            $sub_total += ((int)$product_price) * ((int)$product_qty);
                                        } else {
                                            $customer_tr_html .= '<td>&nbsp;</td>';
                                            $customer_tr_html .= '<td>&nbsp;</td>';
                                        }   
                                    }
                                    $customer_tr_html .= '<td>'. esc_html(number_format_i18n($sub_total)).'</td>';
                                    $customer_tr_html .= '</tr>';
                                    $whole_total += $sub_total;
                                    echo $customer_tr_html;
                                }
                            } else {
                                echo "<tr><td colspan='15' class='text-center text-danger'>No data found!</td></tr>";
                            }
                        ?>
                    </tbody>
                    <tfoot class="bg-dark text-white text-center">
                        <tr>
                            <td>&nbsp;</td>
                            <td><?php _e("Total Amount"); ?></td>
                            <?php 
                                foreach($td_ids as $key=>$p_id){
                                    $total_qty = $wpdb->get_var("SELECT SUM(quantity) FROM fst_purchase_data WHERE `ID` = $key");
                                    $sales_qty = $customer_invoices_total_data[$key]['total_quantity'];
                                    $remain_qty = $total_qty - $sales_qty;

                                    echo '<td>'. esc_html(number_format_i18n(($remain_qty))).'</td>';
                                    echo '<td>'. esc_html(number_format_i18n(($sales_qty))).'</td>';
                                }
                            ?>
                            <td><?php echo esc_html(number_format_i18n($whole_total)); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div><!-- .display-content -->
            <?php } ?>

        </div><!-- .container -->
    </div><!-- .page-main-content -->
    
<?php
get_footer();