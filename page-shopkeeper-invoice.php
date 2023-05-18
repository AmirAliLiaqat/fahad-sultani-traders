<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Shopkeeper Invoice
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
            
            <h1 class="text-center text-capitalize my-5"><?php _e(the_title()); ?></h1>

            <div class="inner-content">
                <form action="" method="get">
                    <div class="form-group">
                        <?php wp_nonce_field( 'search_shopkeeper', 'search_shopkeeper_nonce' ); ?>
                        <input type="text" id="search" class="search_invoice" name="search_invoice" class="form-control d-inline" placeholder="<?php _e(" Search"); ?>" autocomplete="off" required/>
                        <button class="btn btn-primary my-2" name="search"><?php _e("View"); ?></button>
                        <div class="mt-2" id="result"></div>
                    </div><!-- .form-group -->
                </form>
                <?php
                    if(isset($_GET['search_shopkeeper_nonce']) && wp_verify_nonce( $_GET['search_shopkeeper_nonce'], 'search_shopkeeper' )) {
                        if(is_user_logged_in()) {
                            if(isset($_GET['search'])) {

                                if(isset($_GET['shopkeeper_id'])) {
                                    $shopkeeper_id = sanitize_text_field($_GET['shopkeeper_id']);
                                } else {
                                    $shopkeeper_id = '';
                                }

                                if($shopkeeper_id == '') {
                                    echo "<div class='alert alert-danger' role='alert'>
                                    <strong>No shopkeeper is selected!</strong>
                                    </div>";
                                } else {
                                    $result = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `ID` = '$shopkeeper_id' ");

                                    if($result) {
                                        foreach($result as $row) {
                                    ?>
                                        <div class="shopkeeper_details">
                                            <div class="shopkeeper_img text-center fw-bolder">
                                                <?php
                                                    if($row->picture !="") { 
                                                        $upload_dir = wp_upload_dir();
                                                
                                                        // Checking whether file exists or not
                                                        $url = $upload_dir['baseurl'].DIRECTORY_SEPARATOR.'shopkeeper_img';
                                                        ?>
                                                            <img src="<?php echo $url .DIRECTORY_SEPARATOR. $row->picture; ?>" width="250" style="border-radius: 50%;">
                                                        <?php
                                                    } else {
                                                        echo "<span class='text-white'>NO Picture...</span>";
                                                    }
                                                ?>
                                            </div><!-- .shopkeeper_img -->
                                            <div class="row mt-5">
                                                <h1 class="mb-3"><?php _e("Shopkeeper Details:"); ?></h1>
                                                <div class="col-lg-6 col-md-6 col-sm-12 table-responsive">
                                                    <table class="table table-bordered border-dark bg-light">
                                                        <tbody>
                                                            <tr>
                                                                <td class="p-3"><strong><?php _e("Shop Number"); ?></strong></td>
                                                                <td class="p-3"><?php echo esc_html($row->shop_number); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php _e("Name"); ?></strong></td>
                                                                <td class="p-3"><?php echo esc_html($row->shopkeeper_name); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php _e("Phone"); ?></strong></td>
                                                                <td class="p-3">
                                                                    <?php 
                                                                        echo esc_html($row->shopkeeper_phone) . '<br>'; 
                                                                        $meta_values = $wpdb->get_results("SELECT * FROM fst_shopkeeper_meta_data WHERE `shopkeeper_id` = '$shopkeeper_id' AND `meta_key` = 'phone'");
                                                                        if($meta_values) {
                                                                            foreach($meta_values as $values) {
                                                                                echo esc_html($values->meta_value) . '<br>';
                                                                            }
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php _e("Account"); ?></strong></td>
                                                                <td class="p-3">
                                                                    <?php 
                                                                        echo esc_html($row->shopkeeper_account) . '<br>'; 
                                                                        $meta_values = $wpdb->get_results("SELECT * FROM fst_shopkeeper_meta_data WHERE `shopkeeper_id` = '$shopkeeper_id' AND `meta_key` = 'account'");
                                                                        if($meta_values) {
                                                                            foreach($meta_values as $values) {
                                                                                echo esc_html($values->meta_value) . '<br>';
                                                                            }
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div><!-- .col-lg-6 -->
                                                <div class="col-lg-6 col-md-6 col-sm-12 table-responsive">
                                                    <table class="table table-bordered border-dark bg-light">
                                                        <tbody>
                                                            <tr>
                                                                <td class="p-3"><strong><?php _e("Total Purchase"); ?></strong></td>
                                                                <td class="p-3">
                                                                    <?php
                                                                        $total_amount = $wpdb->get_var("SELECT SUM(price) FROM fst_purchase_data WHERE `shopkeeper_id` = '$shopkeeper_id'");
                                                                        echo esc_html(number_format_i18n($total_amount));
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php _e("Total Payment"); ?></strong></td>
                                                                <td class="p-3">
                                                                    <?php
                                                                        $received_amount = $wpdb->get_var("SELECT SUM(amount) FROM fst_shopkeeper_payments WHERE `shopkeeper_id` = '".$shopkeeper_id."'");
                                                                        echo esc_html(number_format_i18n($received_amount));
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php _e("Remaining"); ?></strong></td>
                                                                <td class="p-3"><?php echo esc_html(number_format_i18n($total_amount - $received_amount)); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-3"><strong><?php _e("Net Cash"); ?></strong></td>
                                                                <td class="p-3"><?php echo esc_html(number_format_i18n($row->net_cash)); ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div><!-- .col-lg-6 -->
                                            </div><!-- .row -->
                                        </div><!-- .shopkeeper_details -->

                                        <!-- Showing shopkeeper invoices -->
                                        <div class="shopkeeper_invoices table-responsive my-5">
                                            <table class='table table-bordered table-hover text-center'>
                                                <thead class='bg-dark text-white'>
                                                    <tr>
                                                        <th><?php _e("Date"); ?></th>
                                                        <th><?php _e("Detail"); ?></th>
                                                        <th><?php _e("Quantity"); ?></th>
                                                        <th><?php _e("Per Piece"); ?></th>
                                                        <th><?php _e("Total Price"); ?></th>
                                                        <th><?php _e("Pay"); ?></th>
                                                        <th><?php _e("Remain"); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody class='bg-light'>
                                                    <?php
                                                        if(isset($_GET['shopkeeper_id'])) {
                                                            $shopkeeper_id = $_GET['shopkeeper_id'];
                                                        } else {
                                                            $shopkeeper_id = 0;
                                                        }

                                                        $result = $wpdb->get_results("SELECT * FROM fst_purchase_data WHERE `shopkeeper_id` = '$shopkeeper_id'");
                                                        
                                                        if($result) {
                                                            foreach($result as $row) {
                                                                $purchase_date = $row->purchase_date;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo esc_html($purchase_date); ?></td>
                                                        <td><?php echo esc_html($row->product_name); ?></td>
                                                        <td><?php echo esc_html(number_format_i18n($qty = $row->quantity)); ?></td>
                                                        <td><?php echo esc_html(number_format_i18n($per_piece = $row->price_per_piece)); ?></td>
                                                        <td><?php echo esc_html(number_format_i18n($total_amount = $qty * $per_piece)); ?></td>
                                                        <td>
                                                            <?php 
                                                                // $pay_amount = $wpdb->get_var("SELECT SUM(amount) FROM fst_shopkeeper_payments WHERE `shopkeeper_id` = '$shopkeeper_id' AND `paid_date` = '$purchase_date'");
                                                                // echo esc_html(number_format_i18n($pay_amount));
                                                                echo esc_html(number_format_i18n($pay_amount = 0));
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                                echo esc_html(number_format_i18n($remain_amount = $total_amount - $pay_amount));
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php 
                                                            }
                                                        } else {
                                                            echo "<tr>
                                                                <td colspan='10' class='text-center text-danger'>No Data Found...</td>
                                                            </tr>";
                                                        } 
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div><!-- .shopkeeper_invoices -->
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