<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Customer Invoice
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

            <div class="inner-content">
                <form action="" method="get">
                    <div class="form-group">
                        <?php wp_nonce_field( 'search_customer_invoice', 'search_invoice_nonce' ); ?>
                        <input type="text" id="search" class="search_invoice form-control d-inline" name="search_invoice" placeholder="<?php esc_html_e(" Search"); ?>" autocomplete="off" required/>
                        <button class="btn btn-primary my-2" name="search"><?php esc_html_e("View"); ?></button>
                        <div class="mt-2" id="result"></div>
                    </div><!-- .form-group -->
                </form>
                <?php
                    if(isset($_GET['search_invoice_nonce']) && wp_verify_nonce( $_GET['search_invoice_nonce'], 'search_customer_invoice' )) {
                        if(is_user_logged_in()) {
                            if(isset($_GET['search'])) {

                                if(isset($_GET['customer_id'])) {
                                    $customer_id = sanitize_text_field($_GET['customer_id']);
                                } else {
                                    $customer_id = '';
                                }

                                if($customer_id == '') {
                                    echo "<div class='alert alert-danger' role='alert'>
                                    <strong>Customer is not selected!</strong>
                                    </div>";
                                } else {
                                    $result = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `ID` = '$customer_id' ");
                                    if($result) {
                                        foreach($result as $row) {
                                    ?>
                                        <div class="customer_details">
                                            <div class="customer_img text-center fw-bolder">
                                                <?php
                                                    if($row->picture !="") { 
                                                        $upload_dir = wp_upload_dir();
                                                
                                                        // Checking whether file exists or not
                                                        $url = $upload_dir['baseurl'].DIRECTORY_SEPARATOR.'customer_img';
                                                        ?>
                                                            <img src="<?php echo $url .DIRECTORY_SEPARATOR. $row->picture; ?>" width="250" style="border-radius: 50%;">
                                                        <?php
                                                    } else {
                                                        echo "<span class='text-white'>NO Picture...</span>";
                                                    }
                                                ?>
                                            </div><!-- .customer_img -->
                                            <div class="row mt-5">
                                                <div class="col-lg-6 col-md-6 col-sm-12 table-responsive">
                                                    <h1 class="mb-3"><?php esc_html_e("Customer Details"); ?>:</h1>
                                                    <table class="table table-bordered border-dark bg-light">
                                                        <tbody>
                                                            <tr>
                                                                <td class="p-3"><strong><?php esc_html_e("Shop Number"); ?></strong></td>
                                                                <td class="p-3"><?php echo esc_html($row->shop_number); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php esc_html_e("Name"); ?></strong></td>
                                                                <td class="p-3"><?php echo (esc_html($row->name)); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php esc_html_e("Phone"); ?></strong></td>
                                                                <td class="p-3">
                                                                    <?php 
                                                                        echo esc_html($row->phone) . '<br>'; 
                                                                        $meta_values = $wpdb->get_results("SELECT * FROM fst_customer_meta_data WHERE `customer_id` = '$customer_id' ");
                                                                        if($meta_values) {
                                                                            foreach($meta_values as $values) {
                                                                                echo $values->meta_value . '<br>';
                                                                            }
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php esc_html_e("ID Card"); ?></strong></td>
                                                                <td class="p-3 d-flex justify-content-between">
                                                                    <?php echo esc_html($row->id_card); ?>
                                                                    <?php
                                                                        if($row->id_card_picture !="") { 
                                                                            $upload_dir = wp_upload_dir();
                                                                    
                                                                            // Checking whether file exists or not
                                                                            $url = $upload_dir['baseurl'].DIRECTORY_SEPARATOR.'customer_img';
                                                                    ?>
                                                                    <!-- Button trigger modal -->
                                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><?php esc_html_e("View Pic"); ?></button>

                                                                    <!-- Modal -->
                                                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="btn btn-primary" onclick="printDiv()"><?php esc_html_e("Print"); ?></button>
                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                </div><!-- .modal-header -->
                                                                                <div class="modal-body text-center" id="print_id_card">
                                                                                    <img src="<?php echo $url .DIRECTORY_SEPARATOR. $row->id_card_picture; ?>" width="800">
                                                                                </div><!-- .modal-body -->
                                                                            </div><!-- .modal-content -->
                                                                        </div><!-- .modal-dialog -->
                                                                    </div><!-- .modal -->
                                                                            <?php
                                                                        } else {
                                                                            echo "<span class='text-danger'>NO Picture...</span>";
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div><!-- .col-lg-6 -->
                                                <div class="col-lg-6 col-md-6 col-sm-12 table-responsive">
                                                    <h1 class="mb-3"><?php esc_html_e("Total Summary"); ?>:</h1>
                                                    <table class="table table-bordered border-dark bg-light">
                                                        <tbody>
                                                            <tr>
                                                                <td class="p-3"><strong><?php esc_html_e("Total Sale"); ?></strong></td>
                                                                <td class="p-3">
                                                                    <?php
                                                                        $total_amount = 0;
                                                                        $get_invoice = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `customer_id` = '$customer_id'");
                                                                        foreach($get_invoice as $invoice) {
                                                                            $total_amount += $invoice->total_amount;
                                                                        }

                                                                        $received_amount = 0;
                                                                        $total_discount = 0;
                                                                        $received = $wpdb->get_results("SELECT * FROM fst_customer_payments WHERE `customer_id` = '$customer_id'");
                                                                        foreach($received as $amount) {
                                                                            $received_amount += $amount->amount;
                                                                            $total_discount += $amount->discount;
                                                                        }
                                                                        $total = $total_amount - $total_discount;
                                                                        echo esc_html(number_format_i18n($total));
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php esc_html_e("Total Payment"); ?></strong></td>
                                                                <td class="p-3"><?php echo esc_html(number_format_i18n($received_amount)); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php esc_html_e("Remaining"); ?></strong></td>
                                                                <td class="p-3"><?php echo esc_html(number_format_i18n($total - $received_amount)); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php esc_html_e("Discount"); ?></strong></td>
                                                                <td class="p-3"><?php echo esc_html(number_format_i18n($total_discount)); ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div><!-- .col-lg-6 -->
                                            </div><!-- .row -->
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12 table-responsive">
                                                    <h1 class="mb-3"><?php esc_html_e("Today Summary"); ?>:</h1>
                                                    <table class="table table-bordered border-dark bg-light">
                                                        <tbody>
                                                            <tr>
                                                                <td class="p-3"><strong><?php esc_html_e("Total Sale"); ?></strong></td>
                                                                <td class="p-3">
                                                                    <?php
                                                                        $total_amount = 0;
                                                                        $get_invoice = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `customer_id` = '$customer_id' AND `sale_date` = '$date'");
                                                                        foreach($get_invoice as $invoice) {
                                                                            $total_amount += $invoice->total_amount;
                                                                        }
                                                                        $current = $row->current;

                                                                        $received_amount = 0;
                                                                        $total_discount = 0;
                                                                        $received = $wpdb->get_results("SELECT * FROM fst_customer_payments WHERE `customer_id` = '$customer_id' AND `purchase_date` = '$date'");
                                                                        foreach($received as $amount) {
                                                                            $received_amount += $amount->amount;
                                                                            $total_discount += $amount->discount;
                                                                        }
                                                                        $total = $total_amount - $total_discount;
                                                                        echo esc_html(number_format_i18n($total));
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php esc_html_e("Total Payment"); ?></strong></td>
                                                                <td class="p-3"><?php echo esc_html(number_format_i18n($received_amount)); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php esc_html_e("Remaining"); ?></strong></td>
                                                                <td class="p-3"><?php echo esc_html(number_format_i18n($total - $received_amount)); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php esc_html_e("Discount"); ?></strong></td>
                                                                <td class="p-3"><?php echo esc_html(number_format_i18n($total_discount)); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php esc_html_e("Current"); ?></strong></td>
                                                                <td class="p-3">
                                                                    <?php
                                                                        if($current == 0) {
                                                                            $total_current = 0;
                                                                        } else {
                                                                            $total_current = number_format_i18n($total + $current - $received_amount);
                                                                        }
                                                                        echo esc_html($total_current);
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <!-- Adding Current Amount -->
                                                    <?php
                                                        if(isset($_POST['adding_current_nonce']) && wp_verify_nonce( $_POST['adding_current_nonce'], 'adding_current' )) {
                                                            if(is_user_logged_in()) {
                                                                if(isset($_POST['add_current'])) {
                                                                    $customer_id = sanitize_text_field($_GET['customer_id']);
                                                                    $current = sanitize_text_field($_POST['current']);

                                                                    $table = $wpdb->prefix.'customer_data';
                                                                    $date = array(
                                                                        'current' => $current
                                                                    );
                                                                    $where = array( 'ID' => $customer_id );
                                                                    $add_current_amount = $wpdb->update($table, $date, $where);
                                                                        
                                                                    if($add_current_amount) {
                                                                        echo "<div class='alert alert-success' role='alert'>
                                                                        <strong>Current added successfully...</strong>
                                                                        </div>";
                                                                    } else {
                                                                        echo "<div class='alert alert-danger' role='alert'>
                                                                        <strong>Error to adding the current!</strong>
                                                                        </div>";
                                                                    }
                                                                }
                                                            } else {
                                                                echo "<div class='alert alert-danger' role='alert'>
                                                                <strong>User is not logged in!</strong>
                                                                </div>";
                                                            }
                                                        }
                                                    ?>
                                                    <form class="" method="post">
                                                        <?php wp_nonce_field( 'adding_current', 'adding_current_nonce' ); ?>
                                                        <input type="text" name="current" class="current form-control d-inline">
                                                        <button class="btn btn-primary my-2" name="add_current"><?php esc_html_e('Add'); ?></button>
                                                    </form>
                                                </div><!-- .col-lg-6 -->
                                                <div class="col-lg-6 col-md-6 col-sm-12 table-responsive"></div><!-- .col-lg-6 -->
                                            </div><!-- .row -->
                                        </div><!-- .customer_details -->

                                        <!-- Showing customer invoices -->
                                        <div class="customer_invoices table-responsive my-5">
                                            <table class='table table-bordered table-hover text-center'>
                                                <thead class='bg-dark text-white'>
                                                    <tr>
                                                        <th><?php esc_html_e("Date"); ?></th>
                                                        <th><?php esc_html_e("Product"); ?></th>
                                                        <th><?php esc_html_e("Quantity"); ?></th>
                                                        <th><?php esc_html_e("Per Piece"); ?></th>
                                                        <th><?php esc_html_e("Total Price"); ?></th>
                                                        <th><?php esc_html_e("Received"); ?></th>
                                                        <th><?php esc_html_e("Remaining"); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody class='bg-light'>
                                                    <?php
                                                        $result = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `customer_id` = '$customer_id'");
                                                        
                                                        if($result) {
                                                            foreach($result as $row) {
                                                                $product_id = $row->product_id;

                                                                $product = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `ID` = '$product_id'");
                                                                foreach($product as $product) {
                                                                    $product_name = $product->product_name;
                                                                }

                                                                $tbody_tr_html = '<tr>';
                                                                $tbody_tr_html .= '<td>'.esc_html($row->sale_date).'</td>';
                                                                $tbody_tr_html .= '<td>'.esc_html($product_name).'</td>';
                                                                $tbody_tr_html .= '<td>'.esc_html(number_format_i18n($qty = $row->quantity)).'</td>';
                                                                $tbody_tr_html .= '<td>'.esc_html(number_format_i18n($per_piece = $row->price_per_quantity)).'</td>';
                                                                $tbody_tr_html .= '<td>'.esc_html(number_format_i18n($total_amount = $qty * $per_piece)).'</td>';
                                                                $tbody_tr_html .= '<td>'.esc_html(number_format_i18n($received_amount = 0)).'</td>';
                                                                $tbody_tr_html .= '<td>'.esc_html(number_format_i18n($remain_amount = $total_amount - $received_amount)).'</td>';
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
                                            </table>
                                        </div><!-- .customer_invoices -->
                                    <?php
                                        }
                                    }
                                }
                            }  
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>
                              <strong>User is not logged in!</strong>
                            </div>";
                        }
                    }
                ?>
            </div><!-- .inner-content -->

        </div><!-- .container -->
    </div><!-- .page-main-content -->

    <script type="text/javascript">
        $("#search").on("keyup", function() {
            var search_term = $(this).val();

            $.ajax({
                url: '<?php echo esc_url( home_url( '/search-customer' ) ); ?>',
                method: "POST",
                data: {search: search_term},
                success: function(data) {
                    $('#result').html(data);
                }
            });  
        });
    </script>

    <script>
        function printDiv() {
            var divContents = document.getElementById("print_id_card").innerHTML;
            var a = window.open('', '', 'height=500, width=500');
            a.document.write(divContents);
            a.print();
        }
    </script>
        
<?php
get_footer();