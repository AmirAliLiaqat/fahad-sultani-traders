<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Add & Edit Shopkeeper Payments
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
            
            <h1 class="text-center text-capitalize my-5"><?php echo isset($_GET['query']) ? _e("Edit ") : _e("Add "); _e(the_title()); ?></h1>
            
            <div class="row my-2">
                <div class="col-lg-6 col-sm-12 mx-auto">
                    <?php
                        /************ code for adding shopkeeper payment ***************/
                        if(isset($_POST['adding_shopkeeper_payments_nonce']) && wp_verify_nonce( $_POST['adding_shopkeeper_payments_nonce'], 'adding_shopkeeper_payments' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['add_shopkeeper_payment'])) {
                                    $amount = sanitize_text_field($_POST['amount']);
                                    $paid_date = sanitize_text_field($_POST['paid_date']);
                                    $payment_type = sanitize_text_field($_POST['payment_type']);
                                    $invoice_desc = sanitize_text_field($_POST['invoice_desc']);

                                    if($invoice_desc = " ") {
                                        $invoice_desc = " ";
                                    }

                                    if(isset($_POST['shopkeeper_id'])) {
                                        $shopkeeper_id = sanitize_text_field($_POST['shopkeeper_id']);
                                    } else {
                                        $shopkeeper_id = '';
                                    }

                                    if($shopkeeper_id == '') {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Shopkeeper is not selected!</strong>
                                        </div>";
                                    } else {
                                        $table = $wpdb->prefix.'shopkeeper_payments';
                                        $add_shopkeeper_payment = $wpdb->insert($table, array(
                                            'shopkeeper_id' => $shopkeeper_id, 
                                            'amount' => $amount,
                                            'paid_date' => $paid_date,
                                            'payment_type' => $payment_type,
                                            'invoice_desc' => $invoice_desc
                                        ));

                                        if($add_shopkeeper_payment) {
                                            echo "<div class='alert alert-success' role='alert'>
                                                <strong>Shopkeeper Payment added successfully...</strong>
                                            </div>";
                                        } else {
                                            echo "<div class='alert alert-danger' role='alert'>
                                                <strong>Error to adding the shopkeeper's payment!</strong>
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

                        /************ code for adding shopkeeper payment ***************/
                        if(isset($_POST['updating_shopkeeper_payments_nonce']) && wp_verify_nonce( $_POST['updating_shopkeeper_payments_nonce'], 'updating_shopkeeper_payments' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['update_shopkeeper_payment'])) {
                                    $invoice_id = sanitize_text_field($_GET['hash']);
                                    $search = sanitize_text_field($_POST['search']);
                                    $amount = sanitize_text_field($_POST['amount']);
                                    $paid_date = sanitize_text_field($_POST['paid_date']);
                                    $payment_type = sanitize_text_field($_POST['payment_type']);
                                    $invoice_desc = sanitize_text_field($_POST['invoice_desc']);
                                    
                                    $fetch_shopkeeper = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `shop_number` = '$search' OR `shopkeeper_name` = '$search' OR `shopkeeper_phone` = '$search'");

                                    if(!$fetch_shopkeeper) {
                                        echo "<div class='alert alert-danger' role='alert'>
                                            <strong>This shop Shopkeeper can not exist!</strong>
                                            </div>";
                                    } else {
                                        foreach($fetch_shopkeeper as $shopkeeper) {
                                            $shopkeeper_id = $shopkeeper->ID;
                                        }
                                        $table = $wpdb->prefix.'shopkeeper_payments';
                                        $data = array(
                                            'shopkeeper_id' => $shopkeeper_id, 
                                            'amount' => $amount,
                                            'paid_date' => $paid_date,
                                            'payment_type' => $payment_type,
                                            'invoice_desc' => $invoice_desc
                                        );
                                        $where = array( 'ID' => $invoice_id );
                                        $update_shopkeeper_payment = $wpdb->update($table, $data, $where);

                                        if($update_shopkeeper_payment) {
                                            echo "<div class='alert alert-success' role='alert'>
                                            <strong>Shopkeeper Payment updated successfully...</strong>
                                            </div>";
                                        } else {
                                            echo "<div class='alert alert-danger' role='alert'>
                                            <strong>Error to updating the shopkeeper's payment!</strong>
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

                        /************ code for deleting shopkeeper payment ***************/
                        if(isset($_POST['deleting_shopkeeper_payments_nonce']) && wp_verify_nonce( $_POST['deleting_shopkeeper_payments_nonce'], 'deleting_shopkeeper_payments' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['id'])) {
                                    $shopkeeper_payment_id = sanitize_text_field($_POST['id']);

                                    $table = $wpdb->prefix.'shopkeeper_payments';
                                    $where = array( 'ID' => $shopkeeper_payment_id );
                                    $delete_payment = $wpdb->delete($table, $where);
                                    
                                    if($delete_payment) {
                                        echo "<div class='alert alert-success' role='alert'>
                                        <strong>Payment deleted successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Error to deleting the payment!</strong>
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
                                    $fetch_shopkeeper_payment = $wpdb->get_results("SELECT * FROM fst_shopkeeper_payments WHERE `ID` = '".$ID."'");
                                    
                                    foreach($fetch_shopkeeper_payment as $shopkeeper_payment) :
                                        
                        ?>
                        <!-- form for updating shopkeeper payment -->
                        <form action="" method="post">
                            <!-- Search -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="search" class="form-label fw-bolder"><?php _e("Shopkeeper"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <?php
                                        $shopkeeper_id = $shopkeeper_payment->shopkeeper_id; 
                                        $shopkeeper = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `ID` = '".$shopkeeper_id."'");

                                        foreach($shopkeeper as $detail) {
                                            $shopkeeper_name = $detail->shopkeeper_name;
                                    ?>
                                    <input type="text" id="search" name="search" class="form-control" value="<?php echo esc_html($shopkeeper_name); ?>" placeholder="<?php _e(" Search"); ?>" autocomplete="off" required/>
                                    <?php } ?>
                                    <div class="mt-2" id="result"></div>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Amount -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="amount" class="form-label fw-bolder"><?php _e("Amount"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="number" name="amount" class="form-control" value="<?php echo esc_html($shopkeeper_payment->amount); ?>" placeholder="<?php _e(" Amount"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Paid Date -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="paid_date" class="form-label fw-bolder"><?php _e("Paid Date"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="date" name="paid_date" class="form-control" value="<?php echo esc_html($shopkeeper_payment->paid_date); ?>" required>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Payment Type -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="payment_type" class="form-label fw-bolder"><?php _e("Payment Type"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <select name="payment_type" class="form-control" required>
                                        <option <?php if($shopkeeper_payment->payment_type=='cash') {echo "selected";} ?> value="cash">Cash</option>
                                        <option <?php if($shopkeeper_payment->payment_type=='bank') {echo "selected";} ?> value="bank">Bank</option>
                                        <option <?php if($shopkeeper_payment->payment_type=='jazzcash') {echo "selected";} ?> value="jazzcash">Jazz Cash</option>
                                    </select>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Description -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="invoice_desc" class="form-label fw-bolder"><?php _e("Description"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <textarea name="invoice_desc" class="form-control" ><?php echo esc_html($shopkeeper_payment->invoice_desc); ?></textarea>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Update Shopkeeper Button -->
                            <div class="row">
                                <div class="col-12 p-3">
                                    <?php wp_nonce_field( 'updating_shopkeeper_payments', 'updating_shopkeeper_payments_nonce' ); ?>
                                    <button class="btn btn-primary" name="update_shopkeeper_payment"><?php _e("Update Shopkeeper Payment"); ?></button>
                                </div><!-- .col-12 -->
                            </div><!-- .row -->
                        </form>
                        <?php 
                                    endforeach;
                                }
                            } else { 
                        ?>
                        <!-- form for adding shopkeeper payment -->
                        <form action="" method="post">
                            <!-- Search -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="search" class="form-label fw-bolder"><?php _e("Shopkeeper"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" id="search" name="search" class="form-control" placeholder="<?php _e(" Search"); ?>" autocomplete="off" required/>
                                    <div class="mt-2" id="result"></div>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Amount -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="amount" class="form-label fw-bolder"><?php _e("Amount"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="number" name="amount" class="form-control" placeholder="<?php _e(" Amount"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Paid Date -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="paid_date" class="form-label fw-bolder"><?php _e("Paid Date"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="date" name="paid_date" class="form-control" value="<?php echo $date; ?>" required>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Payment Type -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="payment_type" class="form-label fw-bolder"><?php _e("Payment Type"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <select name="payment_type" class="form-control" required>
                                        <option value="bank" selected>Bank</option>
                                        <option value="cash">Cash</option>
                                        <option value="jazzcash">Jazz Cash</option>
                                    </select>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Description -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="invoice_desc" class="form-label fw-bolder"><?php _e("Description"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <textarea name="invoice_desc" class="form-control" ></textarea>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Add Shopkeeper Button -->
                            <div class="row">
                                <div class="col-12 p-3">
                                    <?php wp_nonce_field( 'adding_shopkeeper_payments', 'adding_shopkeeper_payments_nonce' ); ?>
                                    <button class="btn btn-primary" name="add_shopkeeper_payment"><?php _e("Add Shopkeeper Payment"); ?></button>
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
                            <th><?php _e("Sr#"); ?></th>
                            <th><?php _e("Shopkeeper Name"); ?></th>
                            <th><?php _e("Amount"); ?></th>
                            <th><?php _e("Paid Date"); ?></th>
                            <th><?php _e("Payment Type"); ?></th>
                            <th><?php _e("Description"); ?></th>
                            <th><?php _e("Action"); ?></th>
                        </tr>
                    </thead>
                    <tbody class='bg-light'>
                        <?php
                            $result = $wpdb->get_results("SELECT * FROM fst_shopkeeper_payments WHERE `paid_date` = '$date'");
                            $sr = 1;

                            if($result) {
                                foreach($result as $row) {
                        ?>
                        <tr class="text-center">
                            <td><?php echo esc_html($sr++); ?></td>
                            <td>
                                <?php 
                                    $shopkeeper_id = $row->shopkeeper_id; 
                                    $shopkeeper = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `ID` = '".$shopkeeper_id."'");

                                    foreach($shopkeeper as $detail) {
                                        echo esc_html($shopkeeper_name = $detail->shopkeeper_name);
                                    }
                                ?>
                            </td>
                            <td><?php echo esc_html(number_format_i18n($row->amount)); ?> Rs/-</td>
                            <td><?php echo esc_html($row->paid_date); ?></td>
                            <td class="text-capitalize"><?php echo esc_html($row->payment_type); ?></td>
                            <td><?php echo esc_html(substr_replace($row->invoice_desc, "...", 30)); ?></td>
                            <td>
                                <div class="dropdown action mx-2">
                                    <div class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        ...
                                    </div>
                                    <div class="dropdown-menu text-center">
                                        <a href="?query=update&hash=<?php echo esc_html($row->ID); ?>&<?php echo esc_html(md5($row->ID)); ?>" class="btn btn-primary text-white"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <form class="d-inline" method="post">
                                            <?php wp_nonce_field( 'deleting_shopkeeper_payments', 'deleting_shopkeeper_payments_nonce' ); ?>
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
                                    <td colspan='8' class='text-center text-danger'>No Data Found...</td>
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