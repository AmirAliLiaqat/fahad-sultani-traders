<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Add & Edit Purchase Data
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
                        /************ code for adding purchase data ***************/
                        if(isset($_POST['adding_purchase_data_nonce']) && wp_verify_nonce( $_POST['adding_purchase_data_nonce'], 'adding_purchase_data' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['add_purchase_data'])) {
                                    $purchase_date = sanitize_text_field($_POST['purchase_date']);
                                    $product_name = sanitize_text_field($_POST['product_name']);
                                    $quantity = sanitize_text_field($_POST['quantity']);
                                    $price_per_piece = sanitize_text_field($_POST['price_per_piece']);
                                    $net_cash = sanitize_text_field($_POST['net_cash']);
                                    $description = sanitize_text_field($_POST['description']);
                                    $expenses = sanitize_text_field($_POST['expenses']);
                                    $purchase_on = sanitize_text_field($_POST['purchase_on']);

                                    if($description = "") {
                                        $description = " ";
                                    }

                                    if(isset($_POST['shopkeeper_id'])) {
                                        $shopkeeper_id = sanitize_text_field($_POST['shopkeeper_id']);
                                    } else {
                                        $shopkeeper_id = '';
                                    }

                                    if($expenses) {
                                        $expenses = sanitize_text_field($_POST['expenses']);
                                    } else {
                                        $expenses = 0;
                                    }

                                    if(!empty($price_with_expense)) {
                                        $price_with_expense = sanitize_text_field($_POST['price_with_expense']);
                                    } else {
                                        $price_with_expense = 0;
                                    }

                                    if(!empty($total_price)) {
                                        $total_price = sanitize_text_field($_POST['total_price']);
                                    } else {
                                        $total_price = 0;
                                    }

                                    if(!empty($quantity) && !empty($price_per_piece) && !empty($price_per_piece)) {
                                        $price = ($quantity * $price_per_piece);
    
                                        $total_price = $price + $price_per_piece;
                                        $price_with_expense = ($total_price / $quantity);
                                    }

                                    $lastid = $wpdb->get_var('SELECT MAX(ID) FROM fst_purchase_data');
                                    $serial_number = $lastid + 1;

                                    if(empty($shopkeeper_id)) {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Shopkeeper is not selected!</strong>
                                        </div>";
                                    } else {
                                        if(!empty($net_cash)) {
                                            $purchase_date = '';
                                            if(empty($product_name) && empty($purchase_date) && empty($quantity) && empty($price_per_piece) && empty($expenses)) {
                                                $table = $wpdb->prefix.'shopkeepers_data';
                                                $data = array(
                                                    'net_cash' => $net_cash,
                                                    'description' => $description
                                                );
                                                $where = array( 'ID' => $shopkeeper_id );
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
                                            if($purchase_on == 'on_net') {
                                                $price_per_piece = $price_per_piece;
                                            } else {
                                                $price_per_piece = 1;
                                                $price = $quantity * $price_per_piece;
                                            }

                                            $table = $wpdb->prefix.'purchase_data';
                                            $add_purchase_data = $wpdb->insert($table, array(
                                                'serial_number' => $serial_number, 
                                                'shopkeeper_id' => $shopkeeper_id, 
                                                'purchase_date' => $purchase_date, 
                                                'product_name' => $product_name, 
                                                'quantity' => $quantity, 
                                                'price_per_piece' => $price_per_piece, 
                                                'price' => $price, 
                                                'expenses' => $expenses,
                                                'price_with_expense' => $price_with_expense, 
                                                'total_price' => $total_price
                                            ));
    
                                            if($add_purchase_data) {
                                                echo "<div class='alert alert-success' role='alert'>
                                                <strong>Purchase Data added successfully...</strong>
                                                </div>";
                                            } else {
                                                echo "<div class='alert alert-danger' role='alert'>
                                                <strong>Error to adding the purchase data!</strong>
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

                        /************ code for updating purchase data ***************/
                        if(isset($_POST['updating_purchase_data_nonce']) && wp_verify_nonce( $_POST['updating_purchase_data_nonce'], 'updating_purchase_data' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['update_purchase_data'])) {
                                    $purchase_id = sanitize_text_field($_GET['hash']);
                                    $search = sanitize_text_field($_POST['search']);
                                    $purchase_date = sanitize_text_field($_POST['purchase_date']);
                                    $product_name = sanitize_text_field($_POST['product_name']);
                                    $quantity = sanitize_text_field($_POST['quantity']);
                                    $price_per_piece = sanitize_text_field($_POST['price_per_piece']);
                                    $net_cash = sanitize_text_field($_POST['net_cash']);
                                    $description = sanitize_text_field($_POST['description']);
                                    $expenses = sanitize_text_field($_POST['expenses']);

                                    if($description = "") {
                                        $description = " ";
                                    }

                                    if($expenses) {
                                        $expenses = sanitize_text_field($_POST['expenses']);
                                    } else {
                                        $expenses = 0;
                                    }

                                    $price = ($quantity * $price_per_piece);

                                    $total_price = $price + $expenses;
                                    $price_with_expense = ($total_price / $quantity);

                                    $fetch_shopkeeper = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `shop_number` = '$search' OR `shopkeeper_name` = '$search' OR `shopkeeper_phone` = '$search'");
                                    
                                    if(!$fetch_shopkeeper) {
                                        echo "<div class='alert alert-danger' role='alert'>
                                            <strong>This shopkeeper can not exist!</strong>
                                            </div>";
                                    } else {
                                        foreach($fetch_shopkeeper as $shopkeeper) {
                                            $shopkeeper_id = $shopkeeper->ID;
                                        }

                                        /********* updating net cash *********/
                                        $table = $wpdb->prefix.'shopkeepers_data';
                                        $data = array(
                                            'net_cash' => $net_cash,
                                            'description' => $description
                                        );
                                        $where = array( 'ID' => $shopkeeper_id );
                                        $add_net_cash = $wpdb->update($table, $data, $where);

                                        /********* updating purchase invoice *********/
                                        $table = $wpdb->prefix.'purchase_data';
                                        $data = array(
                                            'shopkeeper_id' => $shopkeeper_id, 
                                            'purchase_date' => $purchase_date, 
                                            'product_name' => $product_name, 
                                            'quantity' => $quantity, 
                                            'price_per_piece' => $price_per_piece, 
                                            'price' => $price, 
                                            'expenses' => $expenses, 
                                            'price_with_expense' => $price_with_expense, 
                                            'total_price' => $total_price
                                        );
                                        $where = array( 'ID' => $purchase_id );
                                        $add_purchase_data = $wpdb->update($table, $data, $where);

                                        echo "<div class='alert alert-success' role='alert'>
                                            <strong>Purchase Data updated successfully...</strong>
                                        </div>";
                                    }
                                }  
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>
                                    <strong>User is not logged in!</strong>
                                </div>";
                            }
                        }

                        /************ code for deleting purchase data ***************/
                        if(isset($_POST['deleting_purchase_data_nonce']) && wp_verify_nonce( $_POST['deleting_purchase_data_nonce'], 'deleting_purchase_data' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['id'])) {
                                    $payment_id = sanitize_text_field($_POST['id']);
                                    $table = $wpdb->prefix.'purchase_data';
                                    $where = array( 'ID' => $payment_id );
                                    $delete_payment_data = $wpdb->delete($table, $where);

                                    if($delete_payment_data) {
                                        echo "<div class='alert alert-success' role='alert'>
                                        <strong>Payment Data deleted successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Error to deleting the payment data!</strong>
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
                                    $fetch_payment = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `ID` = '".$ID."'");
                                    
                                    foreach($fetch_payment as $payment) :
                                        
                        ?>
                        <!-- form for updating payment data -->
                        <form action="" method="post">
                            <!-- Search -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="search" class="form-label fw-bolder"><?php esc_html_e("Shopkeeper"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <?php
                                        $shopkeeper_id = $payment->shopkeeper_id; 
                                        $shopkeeper = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `ID` = '".$shopkeeper_id."'");

                                        foreach($shopkeeper as $detail) {
                                            $shopkeeper_name = $detail->shopkeeper_name;
                                    ?>
                                    <input type="text" id="search" name="search" class="form-control" value="<?php echo esc_html($shopkeeper_name); ?>" placeholder="<?php esc_html_e("Search"); ?>" autocomplete="off"/>
                                    <?php } ?>
                                    <div class="mt-2" id="result"></div>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Purchase Date -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="purchase_date" class="form-label fw-bolder"><?php esc_html_e("Purchase Date"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="date" name="purchase_date" class="form-control" value="<?php echo esc_html($payment->purchase_date); ?>" placeholder="<?php esc_html_e("Purchase Date"); ?>">    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Product Name -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="product_name" class="form-label fw-bolder"><?php esc_html_e("Product Name"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="product_name" class="form-control" value="<?php echo esc_html($payment->product_name); ?>" placeholder="<?php esc_html_e("Product Name"); ?>">    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Quantity -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="quantity" class="form-label fw-bolder"><?php esc_html_e("Quantity"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="number" name="quantity" class="form-control" value="<?php echo esc_html($payment->quantity); ?>" placeholder="<?php esc_html_e("Quantity"); ?>"> 
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Price Per Piece -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="price_per_piece" class="form-label fw-bolder"><?php esc_html_e("Price Per Piece"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="price_per_piece" class="form-control" value="<?php echo esc_html($payment->price_per_piece); ?>" placeholder="<?php esc_html_e("Price Per Piece"); ?>">    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Expense -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="expenses" class="form-label fw-bolder"><?php esc_html_e("Expenses"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="number" name="expenses" class="form-control" value="<?php echo esc_html($payment->expenses); ?>" placeholder="<?php esc_html_e("Expenses"); ?>">    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <div class="text-end mb-2">
                                <a class="btn btn-primary" id="open_more"><?php esc_html_e("Show More"); ?></a>
                            </div><!-- .text-end -->

                            <div id="show_more">
                                <?php 
                                    $shopkeeper_id = $payment->shopkeeper_id; 
                                    $shopkeeper = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `ID` = '".$shopkeeper_id."'");

                                    foreach($shopkeeper as $detail) :
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
                                        <textarea name="description"><?php esc_html_e($detail->description); ?></textarea>
                                    </div><!-- .col-lg-8 -->
                                </div><!-- .row -->
                                <?php endforeach; ?>
                            </div><!-- #show_more -->

                            <!-- Update Purchase Data Button -->
                            <div class="row">
                                <div class="col-12 p-3">
                                    <?php wp_nonce_field( 'updating_purchase_data', 'updating_purchase_data_nonce' ); ?>
                                    <button class="btn btn-primary" name="update_purchase_data"><?php esc_html_e("Update Purchase Data"); ?></button>
                                </div><!-- .col-12 -->
                            </div><!-- .row -->
                        </form>
                        <?php 
                                    endforeach;
                                }
                            } else { 
                        ?>
                        <!-- form for adding payment data -->
                        <form action="" method="post">
                            <!-- Search -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="search" class="form-label fw-bolder"><?php esc_html_e("Shopkeeper"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" id="search" name="search" class="form-control" placeholder="<?php esc_html_e("Search"); ?>" autocomplete="off"/>
                                    <div class="mt-2" id="result"></div>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Purchase Date -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="purchase_date" class="form-label fw-bolder"><?php esc_html_e("Purchase Date"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="date" name="purchase_date" class="form-control" value="<?php echo $date; ?>" placeholder="<?php esc_html_e("Purchase Date"); ?>">    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Product Name -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="product_name" class="form-label fw-bolder"><?php esc_html_e("Product Name"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="product_name" class="form-control" placeholder="<?php esc_html_e("Product Name"); ?>">    
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

                            <!-- Price Per Piece -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="price_per_piece" class="form-label fw-bolder"><?php esc_html_e("Price Per Piece"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="price_per_piece" class="form-control" placeholder="<?php esc_html_e("Price Per Piece"); ?>">    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Expense -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="expenses" class="form-label fw-bolder"><?php esc_html_e("Expenses"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="number" name="expenses" class="form-control" placeholder="<?php esc_html_e("Expenses"); ?>">    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Purchase On -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="purchase_on" class="form-label fw-bolder"><?php esc_html_e("Purchase On"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <select name="purchase_on" id="" class="form-select">
                                        <option value="on_net" selected><?php esc_html_e('On Net'); ?></option>
                                        <option value="on_bill"><?php esc_html_e('On Bill'); ?></option>
                                    </select>
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

                            <!-- Add Purchase Data Button -->
                            <div class="row">
                                <div class="col-12 p-3">
                                    <?php wp_nonce_field( 'adding_purchase_data', 'adding_purchase_data_nonce' ); ?>
                                    <button class="btn btn-primary" name="add_purchase_data"><?php esc_html_e("Add Purchase Data"); ?></button>
                                </div><!-- .col-12 -->
                            </div><!-- .row -->
                        </form>
                        <?php } ?>
                    </div><!-- .page-inner-content -->
                </div><!-- .col-lg-6 -->
            </div><!-- .row -->

            <div class="fetch_data table-responsive mt-5">
                <table class="table table-bordered table-hover table-striped table-sm">
                    <thead class="bg-dark text-white text-center">
                        <tr>
                            <th><?php esc_html_e("Sr#"); ?></th>
                            <th><?php esc_html_e("Shopkeeper Name"); ?></th>
                            <th><?php esc_html_e("Shopkeeper Phone"); ?></th>
                            <th><?php esc_html_e("Purchase Date"); ?></th>
                            <th><?php esc_html_e("Product Name"); ?></th>
                            <th><?php esc_html_e("Total Quantity"); ?></th>
                            <th><?php esc_html_e("Remain Quantity"); ?></th>
                            <th><?php esc_html_e("Price Per Piece"); ?></th>
                            <th><?php esc_html_e("Price"); ?></th>
                            <th><?php esc_html_e("Expense"); ?></th>
                            <th><?php esc_html_e("Price With Expense"); ?></th>
                            <th><?php esc_html_e("Total Price"); ?></th>
                            <th><?php esc_html_e("Action"); ?></th>
                        </tr>
                    </thead>
                    <tbody class='bg-light'>
                        <?php
                            $fetch_purchase_data = $wpdb->get_results('SELECT * FROM fst_purchase_data');
                            $sr = 1;

                            if($fetch_purchase_data) {
                                foreach($fetch_purchase_data as $data) {
                                    $product_id = $data->ID;
                                    $p_qty = $wpdb->get_var("SELECT SUM(quantity) FROM fst_purchase_data WHERE `ID` = '$product_id'");

                                    $s_qty = $wpdb->get_var("SELECT SUM(quantity) FROM fst_customer_invoice WHERE `product_id` = '$product_id'");

                                    $remain_qty = $p_qty - $s_qty;

                                    if($remain_qty > 0) {
                        ?>
                        <tr class="text-center">
                            <td><?php echo esc_html($data->serial_number); ?></td>
                            <?php 
                                $shopkeeper_id = $data->shopkeeper_id; 
                                $shopkeeper = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `ID` = '".$shopkeeper_id."'");

                                foreach($shopkeeper as $detail) {
                                    echo "<td>$detail->shopkeeper_name</td>";
                                    echo "<td>$detail->shopkeeper_phone</td>";
                                }
                            ?>
                            <td><?php echo esc_html($data->purchase_date); ?></td>
                            <td><?php echo esc_html($data->product_name); ?></td>
                            <td><?php echo esc_html(number_format_i18n($p_qty)); ?></td>
                            <td><?php echo esc_html(number_format_i18n($remain_qty)); ?></td>
                            <td><?php echo esc_html(number_format_i18n($data->price_per_piece)); ?></td>
                            <td><?php echo esc_html(number_format_i18n($data->price)); ?></td>
                            <td><?php echo esc_html(number_format_i18n($data->expenses)); ?></td>
                            <td><?php echo esc_html(number_format_i18n($data->price_with_expense)); ?></td>
                            <td><?php echo esc_html(number_format_i18n($data->total_price)); ?></td>
                            <td>
                                <div class="dropdown action mx-2">
                                    <div class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        ...
                                    </div>
                                    <div class="dropdown-menu text-center">
                                        <a href="?query=update&hash=<?php echo esc_html($data->ID); ?>&<?php echo esc_html(md5($data->ID)); ?>" class="btn btn-primary text-white"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <form class="d-inline" method="post">
                                            <?php wp_nonce_field( 'deleting_purchase_data', 'deleting_purchase_data_nonce' ); ?>
                                            <button class="btn btn-danger text-white" value="<?php echo esc_html($data->ID); ?>" name="id"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div><!--dropdown-->
                            </td>
                        </tr>
                        <?php 

                                    }
                                }
                            } else {
                                echo "<tr>
                                    <td colspan='12' class='text-center text-danger'>No Data Found...</td>
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
                url: '<?php echo esc_url( home_url( '/search-shopkeeper' ) ); ?>',
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