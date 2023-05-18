<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Dashboard Backup
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
function add_products_to_same_row($row){
    global $customer_invoices_result;
    
    if(is_array($customer_invoices_result) && isset($customer_invoices_result['customer_invoice']) && is_array($customer_invoices_result['customer_invoice'])){
        $add_new_row = true;
        foreach($customer_invoices_result['customer_invoice'] as $key=>$single_row){
           
            if(
                isset($single_row['customer_id']) && 
                $single_row['customer_id'] == $row->customer_id && 
                isset($single_row['product_ids']) && 
                is_array($single_row['product_ids']) && 
                !array_key_exists($row->product_id, $single_row['product_ids'])
            ){
                $add_new_row = $key;
            }
        }
        if($add_new_row === true){
            add_row_to_result($row);
        }else if($add_new_row){
            // db($customer_invoices_result['customer_invoice'][$add_new_row]['product_ids']);
            $customer_invoices_result['customer_invoice'][$add_new_row]['product_ids'][$row->product_id] = array(
                'per_piece_price' => $row->price_per_quantity,
                'product_id' => $row->product_id,
                'product_quantity' => $row->quantity,
            );
            // db($customer_invoices_result['customer_invoice'][$add_new_row]['product_ids']);
        }
    }else{
        add_row_to_result($row);
    }
}

function add_row_to_result($row){
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
    // db($customer_invoices_result);
}
?>

    <?php
        if(!is_user_logged_in()) {
            $url = get_site_url() . "/login";
            wp_redirect( $url );
        }
        global $wpdb;
        
        $date = date('Y-m-d');
        $date_2 = "<b> Today Date: </b>" . "<span class='text-primary'>" . date('d-F-Y') . "</span>";
        $current_month = date('m');
        $current_year = date('Y');
    ?>

    <div class="page-main-content">
        <div class="container-fluid p-5">

            <?php get_header(); ?>

            <h1 class="text-center text-capitalize my-5"><?php _e(the_title()); ?></h1>

            <div class="row">
                <?php
                    if( current_user_can( 'today_sales' ) ) {
                ?>
                <!------------- Today Sales --------------->
                <div class="col-lg-4 col-md-4 col-sm-6 my-3">
                    <!-- Button trigger modal -->
                    <div role="button" class="dashboard-content text-center form-content bg-light p-3" data-bs-toggle="modal" data-bs-target="#sales">
                        <h3><?php _e("Today Sales"); ?></h3>
                    </div><!-- .dashboard-content -->

                    <!-- Modal -->
                    <div class="modal fade" id="sales" tabindex="-1" aria-labelledby="today_sales" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="today_sales"><?php _e("Sales Invoice"); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div><!-- .modal-header -->
                                <div class="modal-body table-responsive">
                                    <?php 
                                    $table_th_html = '<tr class="text-center">
                                        <th>'. __("Sr#").'</th>
                                        <th class="text-start">'.__("Customer").'</th>';
                                        $td_ids = array();

                                        $products = $wpdb->get_results("SELECT * FROM fst_purchase_data");

                                        foreach($products as $item) {
                                            $td_ids[] = $item->ID;
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
                                        <tbody>
                                            <?php
                                                $result = $wpdb->get_results("SELECT * FROM fst_customer_invoice");
                                                $sr = 1;
                                                if($result) {
                                                    foreach($result as $row) {
                                                        add_products_to_same_row( $row);
                                                        // if(isset() && $row->product_id && $row->price_per_quantity){

                                                        // }
                                                        

                                                        $customer_tr_html = '<tr class="text-center">';
                                                        $customer_tr_html .= '<td>'.esc_html($sr++).'</td>';
                                                        $customer_tr_html .= '<td class="text-start">';
                                                        $customer_id = $row->customer_id;
                                                        $customer = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `ID` = '".$customer_id."'");

                                                        foreach($customer as $detail) {
                                                        
                                                            /*$customer_invoices_result['customer_invoice'][$row->ID]['customer_data_'.$detail->ID] = array(
                                                                'name' => $detail->name,
                                                                'shop_id' => $detail->shop_number
                                                            );*/    
                                                           $customer_tr_html .= esc_html($customer_name = $detail->name) . "&nbsp;&nbsp;&nbsp;&nbsp;" . esc_html($shop_number = $detail->shop_number);
                                                        }
                                                        
                                                        $customer_tr_html .= '</td>';
                                                        foreach($td_ids as $p_id){
                                                            if( $p_id == $row->product_id) {
                                                                $customer_tr_html .= '<td>'.esc_html(number_format_i18n($product_price = $row->price_per_quantity)).'</td>';
                                                                $customer_tr_html .= '<td>'. esc_html(number_format_i18n($product_qty = $row->quantity)).'</td>';
                                                            } else {
                                                                $customer_tr_html .= '<td>&nbsp;</td>';
                                                                $customer_tr_html .= '<td>&nbsp;</td>';
                                                            }   
                                                        }
                                                        $customer_tr_html .= '<td>'. esc_html(number_format_i18n($product_total = $product_price * $product_qty)).'</td>';
                                                        $customer_tr_html .= '</tr>';
                                                        echo $customer_tr_html;
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                        <?php 
                                        // global $customer_invoices_result; db($customer_invoices_result); 
                                        ?>
                                        <tfoot class="bg-dark text-white">
                                            <tr>
                                                <td colspan="8" class="text-end"><?php _e("Total Amount"); ?></td>
                                                <td class="text-center">
                                                    <?php 
                                                        $total_amount = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice");
                                                        echo esc_html(number_format_i18n($total_amount));
                                                    ?>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>                              
                                </div><!-- .modal-body -->
                                <div class="modal-footer">
                                    <?php echo $date_2; ?>
                                </div><!-- .modal-footer -->
                            </div><!-- .modal-content -->
                        </div><!-- .modal-dialog -->
                    </div><!-- .modal -->
                </div><!-- .col-lg-4 -->
                <?php
                    }
                ?>

                <!------------- Today Credits --------------->
                <?php
                    if( current_user_can( 'today_credits' ) ) {
                ?>
                <div class="col-lg-4 col-md-4 col-sm-6 my-3">
                    <!-- Button trigger modal -->
                    <div role="button" class="dashboard-content text-center form-content bg-light p-3" data-bs-toggle="modal" data-bs-target="#credits">
                        <h3><?php _e("Today Credits"); ?></h3>
                    </div><!-- .dashboard-content -->

                    <!-- Modal -->
                    <div class="modal fade" id="credits" tabindex="-1" aria-labelledby="today_credits" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="today_credits"><?php _e("Customer Payments"); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div><!-- .modal-header -->
                                <div class="modal-body table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead class="bg-dark text-white">
                                            <tr class="text-center">
                                                <th><?php _e("Current"); ?></th>
                                                <th class="text-start"><?php _e("Customer"); ?></th>
                                                <th><?php _e("Total"); ?></th>
                                                <th><?php _e("Received"); ?></th>
                                                <th><?php _e("Remaining"); ?></th>
                                                <th><?php _e("Action"); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $result = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `sale_date` = '$date'");
                                                $sr = 1;

                                                if($result) {
                                                    foreach($result as $row) {
                                                        $customer_id = $row->customer_id; 
                                                        $total_amount = $row->total_amount;

                                                        $received_amount = $wpdb->get_var("SELECT SUM(amount) FROM fst_customer_payments WHERE `customer_id` = '".$customer_id."'");
                                            ?>
                                            <tr class="text-center">
                                                <td>
                                                    <?php
                                                        $fetch_invoice = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `customer_id` = '$customer_id' ");
                                                        if($fetch_invoice) {
                                                            foreach($fetch_invoice as $invoice) {
                                                                $current = $invoice->current;
                                                                if(!$current) {
                                                                    $current = 0;
                                                                } else {
                                                                    $total_current = ($total_amount + $current) - $received_amount;
                                                                    echo "<b>" . esc_html(number_format_i18n($total_current)) . "</b>";
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </td>
                                                <td class="text-start">
                                                    <?php 
                                                        $customer = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `ID` = '".$customer_id."'");
                    
                                                        foreach($customer as $detail) {
                                                            echo esc_html($customer_name = $detail->name) . "&nbsp;&nbsp;&nbsp;&nbsp;" . "<b>" . esc_html($shop_number = $detail->shop_number) . "</b>";
                                                        }
                                                    ?>
                                                </td>
                                                <td><b><?php echo esc_html(number_format_i18n($total_amount)); ?></b></td>
                                                <td>
                                                    <?php
                                                        echo "<b>" . esc_html(number_format_i18n($received_amount)) . "</b>";
                                                    ?>
                                                </td>
                                                <td><?php echo "<b>" . esc_html(number_format_i18n($total_amount - $received_amount)) . "</b>"; ?></td>
                                                <td>
                                                    <form class="mb-0" method="post">
                                                        <input type="hidden" name="customer_<?php echo esc_html($sr++); ?>" value="<?php echo $customer_id; ?>">
                                                        <input type="checkbox"  name="action_<?php echo $sr++; ?>" class="action_field" value="1">
                                                        <input type="checkbox" name="action_<?php echo $sr++; ?>" class="action_field" value="2">
                                                        <input type="checkbox" name="action_<?php echo $sr++; ?>" class="action_field" value="3">
                                                    </form>
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
                                                    <?php 
                                                        $total_amount = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice WHERE `sale_date` = '$date'");
                                                        echo "<b>" . esc_html(number_format_i18n($total_amount)) . "</b>";
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        $received_amount = $wpdb->get_var("SELECT SUM(amount) FROM fst_customer_payments WHERE `paid_date` = '$date'");
                                                        echo "<b>" . esc_html(number_format_i18n($received_amount)) . "</b>";
                                                    ?>
                                                </td>
                                                <td><b><?php echo esc_html(number_format_i18n($total_amount - $received_amount)); ?></b></td>
                                                <td>
                                                    <?php
                                                        if(isset($_POST['save_action'])) {
                                                            echo $demo = print_r($_POST, true);
                                                        }
                                                    ?>
                                                    <button name="save_action" class="btn btn-primary"><?php _e("Save Changes"); ?></button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>                             
                                </div><!-- .modal-body -->
                                <div class="modal-footer">
                                    <?php echo $date_2; ?>
                                </div><!-- .modal-footer -->
                            </div><!-- .modal-content -->
                        </div><!-- .modal-dialog -->
                    </div><!-- .modal -->
                </div><!-- .col-lg-4 -->
                <?php
                    }
                ?>

                <!------------- Shopkeeper Payments --------------->
                <?php
                    if( current_user_can( 'dashboard_shopkeeper' ) ) {
                ?>
                <div class="col-lg-4 col-md-4 col-sm-6 my-3">
                    <!-- Button trigger modal -->
                    <div role="button" class="dashboard-content text-center form-content bg-light p-3" data-bs-toggle="modal" data-bs-target="#shopkeeper">
                        <h3><?php _e("Shopkeepers"); ?></h3>
                    </div><!-- .dashboard-content -->

                    <!-- Modal -->
                    <div class="modal fade" id="shopkeeper" tabindex="-1" aria-labelledby="shopkeeper_payments" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="shopkeeper_payments"><?php _e("Shopkeeper Payments"); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div><!-- .modal-header -->
                                <div class="modal-body table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead class="bg-dark text-white">
                                            <tr class="text-center">
                                                <th><?php _e("Sr#"); ?></th>
                                                <th class="text-start"><?php _e("Shopkeeper"); ?></th>
                                                <th><?php _e("Total"); ?></th>
                                                <th><?php _e("Pay"); ?></th>
                                                <th><?php _e("Remaining"); ?></th>
                                                <th><?php _e("Account"); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
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
                                                <td></td>
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
                                </div><!-- .modal-body -->
                                <div class="modal-footer">
                                    <?php echo $date_2; ?>
                                </div><!-- .modal-footer -->
                            </div><!-- .modal-content -->
                        </div><!-- .modal-dialog -->
                    </div><!-- .modal -->
                </div><!-- .col-lg-4 -->
                <?php
                    }
                ?>

                <!------------- Today Summary --------------->
                <?php
                    if( current_user_can( 'today_summary' ) ) {
                ?>
                <div class="col-lg-4 col-md-4 col-sm-6 my-3">
                    <!-- Button trigger modal -->
                    <div role="button" class="dashboard-content text-center form-content bg-light p-3" data-bs-toggle="modal" data-bs-target="#today">
                        <h3><?php _e("Today Summary"); ?></h3>
                    </div><!-- .dashboard-content -->

                    <!-- Modal -->
                    <div class="modal fade" id="today" tabindex="-1" aria-labelledby="today_summary" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="today_summary"><?php _e("Today Summary"); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div><!-- .modal-header -->
                                <div class="modal-body table-responsive">
                                    <div class="row fw-bolder">
                                        <!------- Sales Summary ------->
                                        <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                                            <ul class="p-0 m-0 list-unstyled">
                                                <li>
                                                    <span><?php _e("Sales Amount"); ?>:</span>
                                                    <span class="text-primary mx-2">
                                                        <?php
                                                            $today_sales = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice WHERE `sale_date` = '$date'");
                                                            echo esc_html(number_format_i18n($today_sales));
                                                        ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span><?php _e("Received Amount"); ?>:</span>
                                                    <span class="text-success mx-2">
                                                        <?php
                                                            $today_received = $wpdb->get_var("SELECT SUM(amount) FROM fst_customer_payments WHERE `paid_date` = '$date'");
                                                            echo esc_html(number_format_i18n($today_received));
                                                        ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span><?php _e("Remaining Amount"); ?>:</span>
                                                    <span class="text-danger mx-2">
                                                        <?php
                                                            $today_amount = $today_sales -  $today_received;
                                                            echo esc_html(number_format_i18n($today_amount));
                                                        ?>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div><!-- .col-4 -->

                                        <!------- Purchase Summary ------->
                                        <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                                            <ul class="p-0 m-0 list-unstyled">
                                                <li>
                                                    <span><?php _e("Purchase Amount"); ?>:</span>
                                                    <span class="text-primary mx-2">
                                                        <?php
                                                            $today_purchase = $wpdb->get_var("SELECT SUM(price) FROM fst_purchase_data WHERE `purchase_date` = '$date'");
                                                            echo esc_html(number_format_i18n($today_purchase));
                                                        ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span><?php _e("Pay Amount"); ?>:</span>
                                                    <span class="text-success mx-2">
                                                        <?php
                                                            $today_pay = $wpdb->get_var("SELECT SUM(amount) FROM fst_shopkeeper_payments WHERE `paid_date` = '$date'");
                                                            echo esc_html(number_format_i18n($today_pay));
                                                        ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span><?php _e("Remaining Amount"); ?>:</span>
                                                    <span class="text-danger mx-2">
                                                        <?php
                                                            $today_remaining_amount = $today_purchase -  $today_pay;
                                                            echo esc_html(number_format_i18n($today_remaining_amount));
                                                        ?>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div><!-- .col-4 -->

                                        <!------- Expense Summary ------->
                                        <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                                            <span><?php _e("Expense Amount"); ?>:</span>
                                            <span class="text-primary mx-2">
                                                <?php
                                                    $expense_amount = $wpdb->get_var("SELECT SUM(expense_amount) FROM fst_expense_data WHERE `expense_date` = '$date'");
                                                    echo esc_html(number_format_i18n($expense_amount));
                                                ?>
                                            </span>
                                        </div><!-- .col-4 -->

                                        <!------- Product Summary ------->
                                        <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                                            <ul class="p-0 m-0 list-unstyled">
                                                <?php
                                                    $fetch_sales_product = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `sale_date` = '$date'");
                                                    foreach($fetch_sales_product as $fetch) :
                                                        $product_id = $fetch->product_id;
                                                    
                                                ?>
                                                <li>
                                                    <?php
                                                        $fetch_product = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `ID` = $product_id");
                                                        foreach($fetch_product as $product) {
                                                            $product_name = $product->product_name;
                                                        
                                                    ?>
                                                    <span><?php _e("$product_name Sales"); ?>:</span>
                                                    <?php } ?>
                                                    <?php
                                                        $total_amount = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice WHERE `product_id` = $product_id AND `sale_date` = '$date'");
                                                    ?>
                                                    <span class="text-primary mx-2"><?php echo esc_html(number_format_i18n($total_amount)); ?></span>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div><!-- .col-4 -->
                                    </div><!-- .row -->
                                </div><!-- .modal-body -->
                                <div class="modal-footer">
                                    <?php echo $date_2; ?>
                                </div><!-- .modal-footer -->
                            </div><!-- .modal-content -->
                        </div><!-- .modal-dialog -->
                    </div><!-- .modal -->
                </div><!-- .col-lg-4 -->
                <?php
                    }
                ?>

                <!------------- Monthly Summary --------------->
                <?php
                    if( current_user_can( 'monthly_summary' ) ) {
                ?>
                <div class="col-lg-4 col-md-4 col-sm-6 my-3">
                    <!-- Button trigger modal -->
                    <div role="button" class="dashboard-content text-center form-content bg-light p-3" data-bs-toggle="modal" data-bs-target="#monthly">
                        <h3><?php _e("Monthly Summary"); ?></h3>
                    </div><!-- .dashboard-content -->

                    <!-- Modal -->
                    <div class="modal fade" id="monthly" tabindex="-1" aria-labelledby="monthly_summary" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="monthly_summary"><?php _e("Monthly Summary"); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div><!-- .modal-header -->
                                <div class="modal-body table-responsive">
                                    <div class="row fw-bolder">
                                        <!------- Sales Summary ------->
                                        <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                                            <ul class="p-0 m-0 list-unstyled fw-bolder">
                                                <li>
                                                    <span><?php _e("Sales Amount"); ?>:</span>
                                                    <span class="text-primary mx-2">
                                                        <?php
                                                            $monthly_sales = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice WHERE `sale_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                                            echo esc_html(number_format_i18n($monthly_sales));
                                                        ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span><?php _e("Received Amount"); ?>:</span>
                                                    <span class="text-success mx-2">
                                                        <?php
                                                            $monthly_received = $wpdb->get_var("SELECT SUM(amount) FROM fst_customer_payments WHERE `paid_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                                            echo esc_html(number_format_i18n($monthly_received));
                                                        ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span><?php _e("Remaining Amount"); ?>:</span>
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
                                                    <span><?php _e("Purchase Amount"); ?>:</span>
                                                    <span class="text-primary mx-2">
                                                        <?php
                                                            $monthly_purchase = $wpdb->get_var("SELECT SUM(price) FROM fst_purchase_data WHERE `purchase_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                                            echo esc_html(number_format_i18n($monthly_purchase));
                                                        ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span><?php _e("Pay Amount"); ?>:</span>
                                                    <span class="text-success mx-2">
                                                        <?php
                                                            $monthly_pay = $wpdb->get_var("SELECT SUM(amount) FROM fst_shopkeeper_payments WHERE `paid_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                                            echo esc_html(number_format_i18n($monthly_pay));
                                                        ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span><?php _e("Remaining Amount"); ?>:</span>
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
                                            <span><?php _e("Expense Amount"); ?>:</span>
                                            <span class="text-primary mx-2">
                                                <?php 
                                                    $expense_amount = $wpdb->get_var("SELECT SUM(expense_amount) FROM fst_expense_data WHERE `expense_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                                    echo esc_html(number_format_i18n($expense_amount));
                                                ?>
                                            </span>
                                        </div><!-- .col-4 -->

                                        <!------- Product Summary ------->
                                        <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                                            <ul class="p-0 m-0 list-unstyled">
                                                <?php
                                                    $fetch_sales_product = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `sale_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                                    foreach($fetch_sales_product as $fetch) :
                                                        $product_id = $fetch->product_id;
                                                    
                                                ?>
                                                <li>
                                                    <?php
                                                        $fetch_product = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `ID` = $product_id");
                                                        foreach($fetch_product as $product) {
                                                            $product_name = $product->product_name;
                                                        
                                                    ?>
                                                    <span><?php _e("$product_name Sales"); ?>:</span>
                                                    <?php } ?>
                                                    <?php
                                                        $total_amount = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice WHERE `product_id` = $product_id AND `sale_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                                    ?>
                                                    <span class="text-primary mx-2"><?php echo esc_html(number_format_i18n($total_amount)); ?></span>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div><!-- .col-4 -->

                                        <!------- Salary Summary ------->
                                        <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                                            <ul class="p-0 m-0 list-unstyled">
                                                <li>
                                                    <span><?php _e("Salary Amount"); ?>:</span>
                                                    <span class="text-primary mx-2">
                                                        <?php
                                                            $total_salary = $wpdb->get_var("SELECT SUM(salary) FROM fst_salary_data WHERE `pay_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                                            echo esc_html(number_format_i18n($total_salary));
                                                        ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span><?php _e("Pay Amount"); ?>:</span>
                                                    <span class="text-success mx-2">
                                                        <?php
                                                            $pay_salary = $wpdb->get_var("SELECT SUM(pay_amount) FROM fst_salary_data WHERE `pay_date` BETWEEN date('$current_year-$current_month-01') AND date('$current_year-$current_month-30')");
                                                            echo esc_html(number_format_i18n($pay_salary));
                                                        ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span><?php _e("Remaining Amount"); ?>:</span>
                                                    <span class="text-danger mx-2">
                                                        <?php
                                                            $remaining_salary = $total_salary -  $pay_salary;
                                                            echo esc_html(number_format_i18n($remaining_salary));
                                                        ?>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div><!-- .col-4 -->
                                    </div><!-- .row -->
                                </div><!-- .modal-body -->
                                <div class="modal-footer">
                                    <?php echo $date_2; ?>
                                </div><!-- .modal-footer -->
                            </div><!-- .modal-content -->
                        </div><!-- .modal-dialog -->
                    </div><!-- .modal -->
                </div><!-- .col-lg-4 -->
                <?php
                    }
                ?>

                <!------------- Total Summary --------------->
                <?php
                    if( current_user_can( 'total_summary' ) ) {
                ?>
                <div class="col-lg-4 col-md-4 col-sm-6 my-3">
                    <!-- Button trigger modal -->
                    <div role="button" class="dashboard-content text-center form-content bg-light p-3" data-bs-toggle="modal" data-bs-target="#total">
                        <h3><?php _e("Total Summary"); ?></h3>
                    </div><!-- .dashboard-content -->

                    <!-- Modal -->
                    <div class="modal fade" id="total" tabindex="-1" aria-labelledby="total_summary" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="total_summary"><?php _e("Total Summary"); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div><!-- .modal-header -->
                                <div class="modal-body table-responsive">
                                    <div class="row fw-bolder">
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
                                                            $total_received = $wpdb->get_var("SELECT SUM(amount) FROM fst_customer_payments");
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

                                        <!------- Expense Summary ------->
                                        <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                                            <span><?php _e("Expense Amount"); ?>:</span>
                                            <span class="text-primary mx-2">
                                                <?php 
                                                    $expense_amount = $wpdb->get_var("SELECT SUM(expense_amount) FROM fst_expense_data");
                                                    echo esc_html(number_format_i18n($expense_amount));
                                                ?>
                                            </span>
                                        </div><!-- .col-4 -->

                                        <!------- Product Summary ------->
                                        <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                                            <ul class="p-0 m-0 list-unstyled">
                                                <?php
                                                    $fetch_sales_product = $wpdb->get_results("SELECT * FROM fst_customer_invoice");
                                                    foreach($fetch_sales_product as $fetch) :
                                                        $product_id = $fetch->product_id;
                                                    
                                                ?>
                                                <li>
                                                    <?php
                                                        $fetch_product = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `ID` = $product_id");
                                                        foreach($fetch_product as $product) {
                                                            $product_name = $product->product_name;
                                                        
                                                    ?>
                                                    <span><?php _e("$product_name Sales"); ?>:</span>
                                                    <?php } ?>
                                                    <?php
                                                        $total_amount = $wpdb->get_var("SELECT SUM(total_amount) FROM fst_customer_invoice WHERE `product_id` = $product_id");
                                                    ?>
                                                    <span class="text-primary mx-2"><?php echo esc_html(number_format_i18n($total_amount)); ?></span>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div><!-- .col-4 -->

                                        <!------- Salary Summary ------->
                                        <div class="col-lg-4 col-md-4 col-sm-12 my-4">
                                            <ul class="p-0 m-0 list-unstyled">
                                                <li>
                                                    <span><?php _e("Salary Amount"); ?>:</span>
                                                    <span class="text-primary mx-2">
                                                        <?php
                                                            $total_salary = $wpdb->get_var("SELECT SUM(salary) FROM fst_salary_data");
                                                            echo esc_html(number_format_i18n($total_salary));
                                                        ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span><?php _e("Pay Amount"); ?>:</span>
                                                    <span class="text-success mx-2">
                                                        <?php
                                                            $pay_salary = $wpdb->get_var("SELECT SUM(pay_amount) FROM fst_salary_data");
                                                            echo esc_html(number_format_i18n($pay_salary));
                                                        ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span><?php _e("Remaining Amount"); ?>:</span>
                                                    <span class="text-danger mx-2">
                                                        <?php
                                                            $remaining_salary = $total_salary -  $pay_salary;
                                                            echo esc_html(number_format_i18n($remaining_salary));
                                                        ?>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div><!-- .col-4 -->
                                    </div><!-- .row -->
                                </div><!-- .modal-body -->
                                <div class="modal-footer">
                                    <?php echo $date_2; ?>
                                </div><!-- .modal-footer -->
                            </div><!-- .modal-content -->
                        </div><!-- .modal-dialog -->
                    </div><!-- .modal -->
                </div><!-- .col-lg-4 -->
                <?php
                    }
                ?>
            </div><!-- .row -->

            <?php
                if( current_user_can( 'search_items' ) ) {
            ?>
            <div class="row fw-bolder">
                <!------------- Search --------------->
                <div class="col-lg-4 col-md-4 col-sm-6 my-3 mx-auto">
                    <!-- Button trigger modal -->
                    <div role="button" class="dashboard-content text-center form-content bg-light p-3" data-bs-toggle="modal" data-bs-target="#search">
                        <h3><?php _e("Search"); ?></h3>
                    </div><!-- .dashboard-content -->

                    <!-- Modal -->
                    <div class="modal fade" id="search" tabindex="-1" aria-labelledby="search_summary" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="search_summary"><?php _e("Search Filters"); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div><!-- .modal-header -->
                                <div class="modal-body table-responsive">
                                    <ul class="nav nav-pills justify-content-center mb-3 mx-0" id="pills-tab" role="tablist">
                                        <?php if( current_user_can( 'search_customer' ) ) { ?>
                                            <li class="nav-item" role="presentation">
                                                <div class="dashboard-content text-center form-content bg-light m-3 p-3 active" id="search-customer-tab" data-bs-toggle="pill" data-bs-target="#search-customer" type="button" role="tab" aria-controls="search-customer" aria-selected="true"><?php _e("Search Customer"); ?></div>
                                            </li>
                                        <?php } if( current_user_can( 'search_shopkeeper' ) ) { ?>
                                            <li class="nav-item" role="presentation">
                                                <div class="dashboard-content text-center form-content bg-light m-3 p-3" id="search-shopkeeper-tab" data-bs-toggle="pill" data-bs-target="#search-shopkeeper" type="button" role="tab" aria-controls="search-shopkeeper" aria-selected="false"><?php _e("Search Shopkeeper"); ?></div>
                                            </li>
                                        <?php } if( current_user_can( 'search_product' ) ) { ?>
                                            <li class="nav-item" role="presentation">
                                                <div class="dashboard-content text-center form-content bg-light m-3 p-3" id="search-product-tab" data-bs-toggle="pill" data-bs-target="#search-product" type="button" role="tab" aria-controls="search-product" aria-selected="false"><?php _e("Search Product"); ?></div>
                                            </li>
                                        <?php } if( current_user_can( 'search_summary' ) ) { ?>
                                            <li class="nav-item" role="presentation">
                                                <div class="dashboard-content text-center form-content bg-light m-3 p-3" id="search-summary-tab" data-bs-toggle="pill" data-bs-target="#search-summary" type="button" role="tab" aria-controls="search-summary" aria-selected="false"><?php _e("Search Summary"); ?></div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade p-2 show active" id="search-customer" role="tabpanel" aria-labelledby="search-customer-tab">
                                            <form class="row border rounded p-2" method="post">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <div class="form-group my-1">
                                                        <label for="c_shop" class="form-label"><?php _e("Shop"); ?>:</label>
                                                        <input type="text" id="search_item" name="c_shop" class="form-control" placeholder="<?php _e("Search by shop #"); ?>">
                                                    </div><!-- .form-group -->
                                                </div><!-- .col-lg-4 -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <div class="form-group my-1">
                                                        <label for="c_name" class="form-label"><?php _e("Name"); ?>:</label>
                                                        <input type="text" id="search_item" name="c_name" class="form-control" placeholder="<?php _e("Search by name"); ?>">
                                                    </div><!-- .form-group -->
                                                </div><!-- .col-lg-4 -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <div class="form-group my-1">
                                                        <label for="c_phone" class="form-label"><?php _e("Phone"); ?>:</label>
                                                        <input type="text" id="search_item" name="c_phone" class="form-control" placeholder="<?php _e("Search by phone"); ?>">
                                                    </div><!-- .form-group -->
                                                </div><!-- .col-lg-4 -->
                                                <div class="text-end">
                                                    <?php wp_nonce_field('search_customer', 'search_customer_nonce'); ?>
                                                    <button class="btn btn-primary my-2" id="c_search" name="c_search">Search</button>
                                                </div>
                                                <div class="mt-2" id="result"></div>
                                            </form><!-- .row -->
                                            <?php
                                                if(isset($_POST['search_customer_nonce']) && wp_verify_nonce( $_POST['search_customer_nonce'], 'search_customer' )) {
                                                    if(is_user_logged_in()) {
                                                        if(isset($_POST['c_search'])) {
                                                            $c_shop = sanitize_text_field($_POST['c_shop']);
                                                            $c_name = sanitize_text_field($_POST['c_name']);
                                                            $c_phone = sanitize_text_field($_POST['c_phone']);

                                                            ?>
                                                            <script type="text/javascript">
                                                                $('#search').modal('show'); 
                                                                // $("#c_search").on("click", function() {
                                                                // });
                                                                // function myFunction() {
                                                                //     var search_term = $("#search-customer #search_item").val();

                                                                //     $.ajax({
                                                                //         url: '<?php //echo esc_url( home_url( '/search-customer' ) ); ?>',
                                                                //         method: "POST",
                                                                //         data: {search: search_term},
                                                                //         success: function(data) {
                                                                //             $('#result').html(data);
                                                                //         }
                                                                //     });  
                                                                // }

                                                                // $("#search-customer #search_item").on("keyup", function() {
                                                                //     myFunction();
                                                                // });
                                                            </script>
                                                            <?php

                                                            $fetch_customer = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `shop_number` = '$c_shop' OR `name` = '$c_name' OR `phone` = '$c_phone'");

                                                            if($fetch_customer) {
                                                                foreach($fetch_customer as $fetch_customer) {
                                            ?>
                                                <table class="table table-bordered text-center fw-bolder">
                                                    <thead class="bg-dark text-white">
                                                        <tr>
                                                            <th><?php _e(esc_html("Picture")); ?></th>
                                                            <th><?php _e(esc_html("Shop#")); ?></th>
                                                            <th><?php _e(esc_html("Name")); ?></th>
                                                            <th><?php _e(esc_html("Phone")); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                                    if($fetch_customer->picture !="") { 
                                                                        $upload_dir = wp_upload_dir();
                                                                
                                                                        // Checking whether file exists or not
                                                                        $url = $upload_dir['baseurl'].DIRECTORY_SEPARATOR.'customer_img';
                                                                        ?>
                                                                            <img src="<?php echo $url .DIRECTORY_SEPARATOR. $fetch_customer->picture; ?>" width="50" height="5%" style="border-radius: 50%;">
                                                                        <?php
                                                                    } else {
                                                                        echo "<span class='text-danger'>NO Picture...</span>";
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td><?php _e(esc_html($fetch_customer->shop_number)); ?></td>
                                                            <td><?php _e(esc_html($fetch_customer->name)); ?></td>
                                                            <td><?php _e(esc_html($fetch_customer->phone)); ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            <?php   
                                                                }
                                                            } else {
                                                                echo "<div class='text-danger'>No customer found!</div>";
                                                            }
                                                        }
                                                    } else {
                                                        echo "<div class='alert alert-danger' role='alert'>
                                                            <strong>User is not logged in!</strong>
                                                        </div>";
                                                    }
                                                }
                                            ?>
                                        </div><!-- .tab-pane -->
                                        <div class="tab-pane fade p-2" id="search-shopkeeper" role="tabpanel" aria-labelledby="search-shopkeeper-tab">
                                            <form class="row border rounded p-2" method="post">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <div class="form-group my-1">
                                                        <label for="s_shop" class="form-label"><?php _e("Shop"); ?>:</label>
                                                        <input type="text" name="s_shop" class="form-control" placeholder="<?php _e("Search by shop #"); ?>">
                                                    </div><!-- .form-group -->
                                                </div><!-- .col-lg-4 -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <div class="form-group my-1">
                                                        <label for="s_name" class="form-label"><?php _e("Name"); ?>:</label>
                                                        <input type="text" name="s_name" class="form-control" placeholder="<?php _e("Search by name"); ?>">
                                                    </div><!-- .form-group -->
                                                </div><!-- .col-lg-4 -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <div class="form-group my-1">
                                                        <label for="s_phone" class="form-label"><?php _e("Phone"); ?>:</label>
                                                        <input type="text" name="s_phone" class="form-control" placeholder="<?php _e("Search by phone"); ?>">
                                                    </div><!-- .form-group -->
                                                </div><!-- .col-lg-4 -->
                                                <div class="col-12 text-end">
                                                    <button class="btn btn-primary my-2" name="s_search"><?php _e("Search"); ?></button>
                                                </div><!-- .col-12 -->
                                            </form>
                                        </div><!-- .tab-pane -->
                                        <div class="tab-pane fade p-2" id="search-product" role="tabpanel" aria-labelledby="search-product-tab">
                                            <form class="row border rounded p-2" method="post">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <div class="form-group my-1">
                                                        <label for="serial_no" class="form-label"><?php _e("Serial"); ?>#:</label>
                                                        <input type="text" name="serial_no" class="form-control" placeholder="<?php _e("Search by shop #"); ?>">
                                                    </div><!-- .form-group -->
                                                </div><!-- .col-lg-4 -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <div class="form-group my-1">
                                                        <label for="p_name" class="form-label"><?php _e("Product Name"); ?>:</label>
                                                        <input type="text" name="p_name" class="form-control" placeholder="<?php _e("Search by product name"); ?>">
                                                    </div><!-- .form-group -->
                                                </div><!-- .col-lg-4 -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <div class="form-group my-1">
                                                        <label for="s_name" class="form-label"><?php _e("Shopkeeper Name"); ?>:</label>
                                                        <input type="text" name="s_name" class="form-control" placeholder="<?php _e("Search by shopkeeper name"); ?>">
                                                    </div><!-- .form-group -->
                                                </div><!-- .col-lg-4 -->
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <div class="form-group my-1">
                                                        <label for="p_date" class="form-label"><?php _e("Phone"); ?>:</label>
                                                        <input type="date" name="p_date" class="form-control">
                                                    </div><!-- .form-group -->
                                                </div><!-- .col-lg-4 -->
                                                <div class="col-12 text-end">
                                                    <button class="btn btn-primary my-2" name="p_search"><?php _e("Search"); ?></button>
                                                </div><!-- .col-12 -->
                                            </form>
                                        </div><!-- .tab-pane -->
                                        <div class="tab-pane fade p-2" id="search-summary" role="tabpanel" aria-labelledby="search-summary-tab">
                                            <form class="row border rounded p-2" method="post">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group my-1">
                                                        <label for="s_date" class="form-label"><?php _e("Start Date"); ?>:</label>
                                                        <input type="date" name="s_date" class="form-control">
                                                    </div><!-- .form-group -->
                                                </div><!-- .col-lg-6 -->
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group my-1">
                                                        <label for="e_date" class="form-label"><?php _e("End Date"); ?>:</label>
                                                        <input type="date" name="e_date" class="form-control">
                                                    </div><!-- .form-group -->
                                                </div><!-- .col-lg-6 -->
                                                <div class="col-12 text-end">
                                                    <button class="btn btn-primary my-2" name="summary_search"><?php _e("Search"); ?></button>
                                                </div><!-- .col-12 -->
                                            </form>
                                        </div><!-- .tab-pane -->
                                    </div><!-- .tab-content -->
                                </div><!-- .modal-body -->
                                <div class="modal-footer">
                                    <?php echo $date_2; ?>
                                </div><!-- .modal-footer -->
                            </div><!-- .modal-content -->
                        </div><!-- .modal-dialog -->
                    </div><!-- .modal -->
                </div><!-- .col-lg-4 -->
            </div><!-- .row -->
            <?php
                }
            ?>
            
        </div><!-- .container-fluid -->
    </div><!-- .page-main-content -->

    <script type="text/javascript">
        // function myFunction() {
        //     var search_term = $("#search-customer #search_item").val();

        //     $.ajax({
        //         url: '<?php echo esc_url( home_url( '/search-customer' ) ); ?>',
        //         method: "POST",
        //         data: {search: search_term},
        //         success: function(data) {
        //             $('#result').html(data);
        //         }
        //     });  
        // }

        // $("#search-customer #search_item").on("keyup", function() {
        //     myFunction();
        // });
    </script>
        
<?php
get_footer();