<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Add & Edit Sales Invoices
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
        
        global $wpdb;
        
        $date = date('Y-m-d');
    ?>

    <div class="page-main-content">
        <div class="container-fluid p-5">

            <?php get_header(); ?>
            
            <h1 class="text-center text-capitalize my-5"><?php isset($_GET['query']) ? esc_html_e("Edit ") : esc_html_e("Add "); esc_html_e(the_title()); ?></h1>
            
            <div class="row my-2">
                <div class="col-lg-6 col-sm-12 mx-auto">
                    <?php
                        /************ code for adding customer invoice ***************/
                        if(isset($_POST['adding_sales_invoices_nonce']) && wp_verify_nonce( $_POST['adding_sales_invoices_nonce'], 'adding_sales_invoices' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['add_invoice'])) {
                                    $product_id = sanitize_text_field($_POST['product_id']);
                                    $sale_date = sanitize_text_field($_POST['sale_date']);
                                    $quantity = sanitize_text_field($_POST['quantity']);
                                    $price_per_quantity = sanitize_text_field($_POST['price_per_quantity']);
                                    $net_cash = sanitize_text_field($_POST['net_cash']);
                                    $description = sanitize_text_field($_POST['description']);

                                    if($description = "") {
                                        $description = " ";
                                    }

                                    if(isset($_POST['customer_id'])) {
                                        $customer_id = sanitize_text_field($_POST['customer_id']);
                                    } else {
                                        $customer_id = '';
                                    }

                                    if($customer_id == '') {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Customer is not selected!</strong>
                                        </div>";
                                    } else {
                                        if(!empty($net_cash)) {
                                            $sale_date = '';
                                            if(empty($product_id) && empty($sale_date) && empty($quantity) && empty($price_per_quantity)) {
                                                $table = $wpdb->prefix.'customer_data';
                                                $data = array(
                                                    'net_cash' => $net_cash,
                                                    'description' => $description
                                                );
                                                $where = array( 'ID' => $customer_id );
                                                $add_net_cash = $wpdb->update($table, $data, $where);

                                                if($add_net_cash) {
                                                    echo "<div class='alert alert-success' role='alert'>
                                                        <strong>Net cash added successfully</strong>
                                                    </div>";
                                                } else {
                                                    echo "<div class='alert alert-danger' role='alert'>
                                                        <strong>Error to adding net cash!</strong>
                                                    </div>";
                                                }
                                            } else {
                                                echo "<div class='alert alert-danger' role='alert'>
                                                    <strong>Such field is not empty for adding net cash!</strong>
                                                </div>";
                                            }
                                        } else {
                                            $check_prodcut = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `ID` = '$product_id'");

                                            if($check_prodcut) {
                                                $p_qty = $wpdb->get_var("SELECT SUM(quantity) FROM fst_purchase_data WHERE `ID` = '$product_id'");

                                                $s_qty = $wpdb->get_var("SELECT SUM(quantity) FROM fst_customer_invoice WHERE `product_id` = '$product_id'");

                                                $remain_qty = $p_qty - $s_qty;

                                                $total_amount = ($quantity * $price_per_quantity);

                                                if($remain_qty >= $quantity) {
                                                    $table = $wpdb->prefix.'customer_invoice';
                                                    $add_customer_invoice = $wpdb->insert($table, array(
                                                        'customer_id' => $customer_id, 
                                                        'product_id' => $product_id,
                                                        'sale_date' => $sale_date,
                                                        'quantity' => $quantity,
                                                        'price_per_quantity' => $price_per_quantity,
                                                        'total_amount' => $total_amount
                                                    ));

                                                    if($add_customer_invoice) {
                                                        echo "<div class='alert alert-success' role='alert'>
                                                            <strong>Invoice added successfully...</strong>
                                                        </div>";
                                                    } else {
                                                        echo "<div class='alert alert-danger' role='alert'>
                                                            <strong>Error to adding the invoice!</strong>
                                                        </div>";
                                                    }
                                                } else {
                                                    echo "<div class='alert alert-danger' role='alert'>
                                                        <strong>Out of limit product!</strong>
                                                    </div>";
                                                }
                                            } else {
                                                echo "<div class='alert alert-danger' role='alert'>
                                                    <strong>Product not exist!</strong>
                                                </div>";
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

                        /************ code for updating customer invoice ***************/
                        if(isset($_POST['updating_sales_invoices_nonce']) && wp_verify_nonce( $_POST['updating_sales_invoices_nonce'], 'updating_sales_invoices' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['update_invoice'])) {
                                    $invoice_id = sanitize_text_field($_GET['hash']);
                                    $search = sanitize_text_field($_POST['search']);
                                    $product_id = sanitize_text_field($_POST['product_id']);
                                    $sale_date = sanitize_text_field($_POST['sale_date']);
                                    $quantity = sanitize_text_field($_POST['quantity']);
                                    $price_per_quantity = sanitize_text_field($_POST['price_per_quantity']);
                                    $net_cash = sanitize_text_field($_POST['net_cash']);
                                    $description = sanitize_text_field($_POST['description']);

                                    if($description = "") {
                                        $description = " ";
                                    }

                                    $total_amount = ($quantity * $price_per_quantity);
                                    
                                    $result = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `shop_number` = '$search' OR `name` = '$search' OR `phone` = '$search'");
                                    
                                    if(!$result) {
                                        echo "<div class='alert alert-danger' role='alert'>
                                            <strong>This customer can not exist!</strong>
                                            </div>";
                                    } else {
                                        foreach($result as $row) {
                                            $customer_id = $row->ID;
                                        }

                                        $check_prodcut = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `ID` = '$product_id'");
    
                                        if($check_prodcut) {
                                            $p_qty = $wpdb->get_var("SELECT SUM(quantity) FROM fst_purchase_data WHERE `ID` = '$product_id'");
        
                                            $s_qty = $wpdb->get_var("SELECT SUM(quantity) FROM fst_customer_invoice WHERE `product_id` = '$product_id'");
        
                                            $remain_qty = $p_qty - $s_qty;
                                            
                                            if($remain_qty >= $quantity) {
                                                /********* updating net cash *********/
                                                $table_name = $wpdb->prefix.'customer_data';
                                                $detail = array(
                                                    'net_cash' => $net_cash,
                                                    'description' => $description
                                                );
                                                $where_net_cash = array( 'ID' => $customer_id );
                                                $add_net_cash = $wpdb->update($table_name, $detail, $where_net_cash);

                                                /********* updating invoice *********/
                                                $table = $wpdb->prefix.'customer_invoice';
                                                $data = array(
                                                    'customer_id' => $customer_id, 
                                                    'product_id' => $product_id,
                                                    'sale_date' => $sale_date,
                                                    'quantity' => $quantity,
                                                    'price_per_quantity' => $price_per_quantity,
                                                    'total_amount' => $total_amount
                                                );
                                                $where = array( 'ID' => $invoice_id );
                                                $update_customer_payment = $wpdb->update($table, $data, $where);

                                                echo "<div class='alert alert-success' role='alert'>
                                                    <strong>Customer Invoice updated successfully...</strong>
                                                </div>";
                                            } else {
                                                echo "<div class='alert alert-danger' role='alert'>
                                                    <strong>Out of limit product!</strong>
                                                </div>";
                                            }
                                        } else {
                                            echo "<div class='alert alert-danger' role='alert'>
                                                <strong>Product not exist!</strong>
                                            </div>";
                                        }
                                    }
                                }  
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>
                                  <strong>User is not logged in!</strong>
                                </div>";
                            }
                        }

                        /************ code for deleting customer payment ***************/
                        if(isset($_POST['deleting_sales_invoices_nonce']) && wp_verify_nonce( $_POST['deleting_sales_invoices_nonce'], 'deleting_sales_invoices' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['id'])) {
                                    $customer_invoice_id = sanitize_text_field($_POST['id']);

                                    $table = $wpdb->prefix.'customer_invoice';
                                    $where = array( 'ID' => $customer_invoice_id );
                                    $delete_invoice = $wpdb->delete($table, $where);
                                    
                                    if($delete_invoice) {
                                        echo "<div class='alert alert-success' role='alert'>
                                        <strong>Invoice deleted successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Error to deleting the invoice!</strong>
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
                    <div class="page-inner-content bg-light form-content p-4">
                        <?php
                            if(isset($_GET['query']) && isset($_GET['hash'])) {
                                $query = sanitize_text_field($_GET['query']);
                                $ID = sanitize_text_field($_GET['hash']);
                                if($query == 'update') {
                                    $fetch_customer_invoice = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `ID` = '".$ID."'");
                                    
                                    foreach($fetch_customer_invoice as $customer_invoice) :
                                        
                        ?>
                        <!-- form for updating customer invoice -->
                        <form action="" method="post">
                            <!-- Search -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="search" class="form-label fw-bolder"><?php esc_html_e("Customer"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <?php
                                        $customer_id = $customer_invoice->customer_id; 
                                        $customer = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `ID` = '".$customer_id."'");

                                        foreach($customer as $detail) {
                                            $customer_name = $detail->name;
                                    ?>
                                    <input type="text" id="search" name="search" class="form-control" value="<?php echo esc_html($customer_name); ?>" placeholder="<?php esc_html_e("Search"); ?>" autocomplete="off" required/>
                                    <?php } ?>
                                    <div class="mt-2" id="result"></div>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Product ID -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="product_id" class="form-label fw-bolder"><?php esc_html_e("Product ID"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="product_id" class="form-control" value="<?php echo esc_html($customer_invoice->product_id); ?>" placeholder="<?php esc_html_e("Product ID"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Sale Date -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="sale_date" class="form-label fw-bolder"><?php esc_html_e("Sale Date"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="date" name="sale_date" class="form-control" value="<?php echo esc_html($customer_invoice->sale_date); ?>" placeholder="<?php esc_html_e("Sale Date"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->
                            
                            <!-- Quantity -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="quantity" class="form-label fw-bolder"><?php esc_html_e("Quantity"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="number" name="quantity" class="form-control" value="<?php echo esc_html($customer_invoice->quantity); ?>" placeholder="<?php esc_html_e("Quantity"); ?>"> 
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Price Per Quantity -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="price_per_quantity" class="form-label fw-bolder"><?php esc_html_e("Price Per Quantity"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="number" name="price_per_quantity" class="form-control" value="<?php echo esc_html($customer_invoice->price_per_quantity); ?>" placeholder="<?php esc_html_e("Price Per Quantity"); ?>">
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <div class="text-end mb-2">
                                <a class="btn btn-primary" id="open_more"><?php esc_html_e("Show More"); ?></a>
                            </div><!-- .text-end -->

                            <div id="show_more">
                                <?php 
                                    $customer_id = $customer_invoice->customer_id; 
                                    $customer = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `ID` = '".$customer_id."'");

                                    foreach($customer as $detail) :
                                ?>
                                <!-- Net Cash -->
                                <div class="row mb-3">
                                    <div class="col-lg-4 col-sm-12">
                                        <label for="net_cash" class="form-label fw-bolder"><?php esc_html_e("Net Cash"); ?></label>
                                    </div><!-- .col-lg-4 -->
                                    <div class="col-lg-8 col-sm-12">
                                        <input type="number" name="net_cash" class="form-control" value="<?php echo esc_html($detail->net_cash); ?>" placeholder="<?php esc_html_e("Net Cash"); ?>"> 
                                    </div><!-- .col-lg-8 -->
                                </div><!-- .row -->

                                <!-- Description -->
                                <div class="row mb-3">
                                    <div class="col-lg-4 col-sm-12">
                                        <label for="description" class="form-label fw-bolder"><?php esc_html_e("Description"); ?></label>
                                    </div><!-- .col-lg-4 -->
                                    <div class="col-lg-8 col-sm-12">
                                        <textarea name="description"><?php echo esc_html($detail->description); ?></textarea>
                                    </div><!-- .col-lg-8 -->
                                </div><!-- .row -->
                                <?php endforeach; ?>
                            </div><!-- #show_more -->

                            <!-- Add Invoice Button -->
                            <div class="row">
                                <div class="col-12 p-3">
                                    <?php wp_nonce_field( 'updating_sales_invoices', 'updating_sales_invoices_nonce' ); ?>
                                    <button class="btn btn-primary" name="update_invoice"><?php esc_html_e("Update Invoice"); ?></button>
                                </div><!-- .col-12 -->
                            </div><!-- .row -->
                        </form>
                        <?php 
                                    endforeach;
                                }
                            } else { 
                        ?>
                        <!-- form for adding customer invoice -->
                        <form action="" method="post">
                            <!-- Search -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="search" class="form-label fw-bolder"><?php esc_html_e("Customer"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" id="search" name="search" class="form-control" placeholder="<?php esc_html_e("Search"); ?>" autocomplete="off" required/>
                                    <div class="mt-2" id="result"></div>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Product ID -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="product_id" class="form-label fw-bolder"><?php esc_html_e("Product ID"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="product_id" class="form-control" placeholder="<?php esc_html_e("Product ID"); ?>">    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Sale Date -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="sale_date" class="form-label fw-bolder"><?php esc_html_e("Sale Date"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="date" name="sale_date" class="form-control" value="<?php echo $date; ?>" placeholder="<?php esc_html_e("Sale Date"); ?>">    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->
                            
                            <!-- Quantity -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="quantity" class="form-label fw-bolder"><?php esc_html_e("Quantity"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="number" name="quantity" class="form-control" placeholder="<?php esc_html_e("Quantity"); ?>"> 
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Price Per Quantity -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="price_per_quantity" class="form-label fw-bolder"><?php esc_html_e("Price Per Quantity"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="number" name="price_per_quantity" class="form-control" placeholder="<?php esc_html_e("Price Per Quantity"); ?>"> 
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <div class="text-end mb-2">
                                <a class="btn btn-primary" id="open_more"><?php esc_html_e("Show More"); ?></a>
                            </div><!-- .text-end -->

                            <div id="show_more">
                                <!-- Net Cash -->
                                <div class="row mb-3">
                                    <div class="col-lg-4 col-sm-12">
                                        <label for="net_cash" class="form-label fw-bolder"><?php esc_html_e("Net Cash"); ?></label>
                                    </div><!-- .col-lg-4 -->
                                    <div class="col-lg-8 col-sm-12">
                                        <input type="number" name="net_cash" class="form-control" placeholder="<?php esc_html_e("Net Cash"); ?>"> 
                                    </div><!-- .col-lg-8 -->
                                </div><!-- .row -->

                                <!-- Description -->
                                <div class="row mb-3">
                                    <div class="col-lg-4 col-sm-12">
                                        <label for="description" class="form-label fw-bolder"><?php esc_html_e("Description"); ?></label>
                                    </div><!-- .col-lg-4 -->
                                    <div class="col-lg-8 col-sm-12">
                                        <textarea name="description"></textarea>
                                    </div><!-- .col-lg-8 -->
                                </div><!-- .row -->
                            </div><!-- #show_more -->

                            <!-- Add Invoice Button -->
                            <div class="row">
                                <div class="col-12 p-3">
                                    <?php wp_nonce_field( 'adding_sales_invoices', 'adding_sales_invoices_nonce' ); ?>
                                    <button class="btn btn-primary" name="add_invoice"><?php esc_html_e("Add Invoice"); ?></button>
                                </div><!-- .col-12 -->
                            </div><!-- .row -->
                        </form>
                        <?php
                            }
                        ?>
                    </div><!-- .page-inner-content -->
                </div><!-- .col-lg-6 -->
            </div><!-- .row -->
            
            <div class="fetch_data table-responsive mt-5">
                <table class="table table-bordered table-hover table-striped table-sm">
                    <thead class="bg-dark text-white text-center">
                        <tr>
                            <th><?php esc_html_e("Sr#"); ?></th>
                            <th><?php esc_html_e("Product ID"); ?></th>
                            <th><?php esc_html_e("Customer Name"); ?></th>
                            <th><?php esc_html_e("Sale Date"); ?></th>
                            <th><?php esc_html_e("Quantity"); ?></th>
                            <th><?php esc_html_e("Price Pre Quantity"); ?></th>
                            <th><?php esc_html_e("Total Amount"); ?></th>
                            <th><?php esc_html_e("Action"); ?></th>
                        </tr>
                    </thead>
                    <tbody class='bg-light'>
                        <?php
                            $date = date('Y-m-d');
                             
                            $result = $wpdb->get_results("SELECT * FROM fst_customer_invoice WHERE `sale_date` = '".$date."'");
                            $sr = 1;

                            if($result) {
                                foreach($result as $row) {
                        ?>
                        <tr class="text-center">
                            <td><?php echo esc_html($sr++); ?></td>
                            <td><?php echo esc_html($row->product_id); ?></td>
                            <td class="text-start">
                                <?php 
                                    $customer_id = $row->customer_id; 
                                    $customer = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `ID` = '".$customer_id."'");

                                    foreach($customer as $detail) {
                                        echo esc_html($customer_name = $detail->name);
                                    }
                                ?>
                            </td>
                            <td><?php echo esc_html($row->sale_date); ?></td>
                            <td><?php echo esc_html(number_format_i18n($row->quantity)); ?></td>
                            <td><?php echo esc_html(number_format_i18n($row->price_per_quantity)); ?></td>
                            <td><?php echo esc_html(number_format_i18n($row->total_amount)); ?></td>
                            <td>
                                <div class="dropdown action mx-2">
                                    <div class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        ...
                                    </div>
                                    <div class="dropdown-menu text-center">
                                        <a href="?query=update&hash=<?php echo esc_html($row->ID); ?>&<?php echo esc_html(md5($row->ID)); ?>" class="btn btn-primary text-white"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <form class="d-inline" method="post">
                                            <?php wp_nonce_field( 'deleting_sales_invoices', 'deleting_sales_invoices_nonce' ); ?>
                                            <button class="btn btn-danger text-white" value="<?php echo esc_html($row->ID); ?>" name="id"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div><!--dropdown-->
                            </td>
                        </tr>
                        <?php 
                                }
                            } else {
                                echo "<tr>
                                    <td colspan='15' class='text-center text-danger'>No Data Found...</td>
                                </tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div><!-- .fetch_data -->
            
        </div><!-- .container-fluid -->
    </div><!-- .page-main-content -->

    <script>
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
            
<?php
get_footer();