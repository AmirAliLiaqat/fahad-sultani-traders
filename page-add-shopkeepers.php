<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Add & Edit Shopkeepers
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
        
        $upload_dir = wp_upload_dir();

        // Checking whether file exists or not
        $path = $upload_dir['basedir'].DIRECTORY_SEPARATOR.'shopkeeper_img';
    ?>

    <div class="page-main-content">
        <div class="container-fluid p-5 p-sm-3">

            <?php get_header(); ?>
            
            <h1 class="text-center text-capitalize my-5"><?php echo isset($_GET['query']) ? esc_html_e("Edit") : esc_html_e("Add"); esc_html_e( " Shopkeeper" ); ?></h1>
            
            <div class="row my-2">
                <div class="col-lg-6 col-sm-12 mx-auto">
                    <?php
                        /************ code for adding shopkeeper ***************/
                        if(isset($_POST['add_shopkeeper_nonce']) && wp_verify_nonce( $_POST['add_shopkeeper_nonce'], 'add_shopkeeper' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['add_shopkeeper'])) {
                                    $shop_number = sanitize_text_field(($_POST['shop_number']));
                                    $shopkeeper_name = sanitize_text_field($_POST['shopkeeper_name']);
                                    $shopkeeper_phone = sanitize_text_field($_POST['shopkeeper_phone']);
                                    $shopkeeper_account = sanitize_text_field($_POST['shopkeeper_account']);
                                
                                    $lastid = $wpdb->get_var('SELECT MAX(ID) FROM fst_shopkeepers_data');
                                    $serial_number = $lastid + 1;

                                    if(isset($_POST['s_phone'])) {
                                        $meta_phone = $_POST['s_phone'];

                                        foreach($meta_phone as $phone) {
                                            if($phone != '') {
                                                $table_name = $wpdb->prefix.'shopkeeper_meta_data';
                                                $meta_data = $wpdb->insert($table_name, array(
                                                    'shopkeeper_id' => $serial_number,
                                                    'meta_key' => 'phone',
                                                    'meta_value' => $phone
                                                ));
                                            }
                                        }
                                    }

                                    if(isset($_POST['account_details'])) {
                                        $meta_account = $_POST['account_details'];

                                        foreach($meta_account as $account) {
                                            if($account != '') {
                                                $table_name = $wpdb->prefix.'shopkeeper_meta_data';
                                                $meta_data = $wpdb->insert($table_name, array(
                                                    'shopkeeper_id' => $serial_number,
                                                    'meta_key' => 'account',
                                                    'meta_value' => $account
                                                ));
                                            }
                                        }
                                    }

                                    if($_FILES['picture']['name'] != '') {
                                        $shopkeeper_picture = $_FILES['picture']['name'];
                                        $picture_path = $_FILES['picture']['tmp_name'];
    
                                        // Auto rename image
                                        $ext = end(explode('.',$shopkeeper_picture));
                                        // Rename the image
                                        $shopkeeper_picture = "shopkeeper_".rand(00,99).'.'.$ext;
    
                                        $image = wp_get_image_editor($picture_path);
    
                                        if ( ! is_wp_error( $image ) ) { 
                                            $image->set_quality(80);
                                            if($image) {
                                                if (file_exists($path)) {
                                                    $image->save($path.DIRECTORY_SEPARATOR.$shopkeeper_picture);
                                                }
                                                else {
                                                    mkdir($path);
                                                    $image->save($path.DIRECTORY_SEPARATOR.$shopkeeper_picture);
                                                }
                                            }
                                        } else {
                                            echo "<div class='alert alert-danger' role='alert'>
                                                <strong>Error found for adding shopkeeper image!</strong>
                                            </div>";
                                        }

                                    } else {
                                        $shopkeeper_picture = '';
                                    }
                                
                                    $table = $wpdb->prefix.'shopkeepers_data';
                                    $add_shopkeeper = $wpdb->insert($table, array(
                                        'serial_number' => $serial_number, 
                                        'picture' => $shopkeeper_picture, 
                                        'shop_number' => $shop_number, 
                                        'shopkeeper_name' => $shopkeeper_name, 
                                        'shopkeeper_phone' => $shopkeeper_phone,
                                        'shopkeeper_account' => $shopkeeper_account
                                    ));
                                
                                    if($add_shopkeeper) {
                                        echo "<div class='alert alert-success' role='alert'>
                                            <strong>Shopkeeper added successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                            <strong>Error to adding the shopkeeper!</strong>
                                        </div>";
                                    }
                                }
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>
                                  <strong>User is not logged in!</strong>
                                </div>";
                            }
                        }
                    
                        /************ code for updating shopkeeper ***************/
                        if(isset($_POST['update_shopkeeper_nonce']) && wp_verify_nonce( $_POST['update_shopkeeper_nonce'], 'update_shopkeeper' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['update_shopkeeper'])) {
                                    $shopkeeper_id = sanitize_text_field(($_POST['shopkeeper_id']));
                                    $shop_number = sanitize_text_field(($_POST['shop_number']));
                                    $shopkeeper_name = sanitize_text_field($_POST['shopkeeper_name']);
                                    $shopkeeper_phone = sanitize_text_field($_POST['shopkeeper_phone']);
                                    $shopkeeper_account = sanitize_text_field($_POST['shopkeeper_account']);
                                    $current_image = sanitize_text_field($_POST['current_image']);

                                    if($_FILES['picture']['name'] != '') {
                                        $shopkeeper_picture = $_FILES['picture']['name'];
                                        $picture_path = $_FILES['picture']['tmp_name'];
                                        // Auto rename image
                                        $ext = end(explode('.',$shopkeeper_picture));
                                        // Rename the image
                                        $shopkeeper_picture = "shopkeeper_".rand(00,99).'.'.$ext;

                                        $image = wp_get_image_editor($picture_path);

                                        if ( ! is_wp_error( $image ) ) { 
                                            $image->set_quality(80);
                                            if($image) {
                                                if (file_exists($path)) {
                                                    $image->save($path.DIRECTORY_SEPARATOR.$shopkeeper_picture);
                                                }
                                                else {
                                                    mkdir($path);
                                                    $image->save($path.DIRECTORY_SEPARATOR.$shopkeeper_picture);
                                                }
                                            }

                                            if($current_image != "") {
                                                // Remove the current image
                                                $remove_path = $path.DIRECTORY_SEPARATOR.$current_image;
                                                $remove_image = unlink($remove_path);
                                            }
                                        }
                                    } else {
                                        $shopkeeper_picture = $current_image;
                                    }
                                    
                                    $table = $wpdb->prefix.'shopkeepers_data';
                                    $data = array(
                                        'picture' => $shopkeeper_picture, 
                                        'shop_number' => $shop_number, 
                                        'shopkeeper_name' => $shopkeeper_name, 
                                        'shopkeeper_phone' => $shopkeeper_phone,
                                        'shopkeeper_account' => $shopkeeper_account
                                    );
                                    $where = array( 'ID' => $shopkeeper_id );
                                    $update_shopkeeper = $wpdb->update($table, $data, $where);

                                    if($update_shopkeeper) {
                                        echo "<div class='alert alert-success' role='alert'>
                                        <strong>Shopkeeper updated successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Error to updating the shopkeeper!</strong>
                                        </div>";
                                    }
                                }
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>
                                <strong>User is not logged in!</strong>
                                </div>";
                            }
                        }
                    
                        /************ code for deleting shopkeeper ***************/
                        if(isset($_POST['deleting_shopkeeper_nonce']) && wp_verify_nonce( $_POST['deleting_shopkeeper_nonce'], 'deleting_shopkeeper' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['user_id'])) {
                                    $shopkeeper_id = sanitize_text_field($_POST['user_id']);

                                    $get_img = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `ID` = '".$shopkeeper_id."'");

                                    if($get_img) {
                                        foreach($get_img as $image) {
                                            $remove_picture = $image->picture;
                                        }
                                    }

                                    if($remove_picture != "") {
                                        $image_path = $path.DIRECTORY_SEPARATOR.$remove_picture;
                                        $remove_image = unlink($image_path);
                                    }

                                    $table = $wpdb->prefix.'shopkeepers_data';
                                    $where = array( 'ID' => $shopkeeper_id );
                                    $delete_shopkeeper = $wpdb->delete($table, $where);
                                    
                                    if($delete_shopkeeper) {
                                        echo "<div class='alert alert-success' role='alert'>
                                        <strong>Shopkeeper deleted successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Error to deleting the shopkeeper!</strong>
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
                                    $fetch_shopkeeper = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `ID` = '".$ID."'");
                                    
                                    foreach($fetch_shopkeeper as $shopkeeper) :
                                        $shopkeeper_id = $shopkeeper->ID;
                        ?>
                        <!-- form for updating shopkeeper -->
                        <form action="" method="post" enctype="multipart/form-data">
                            <!-- Shop Number -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="shop_number" class="form-label fw-bolder"><?php esc_html_e("Shop Number"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="shop_number" class="form-control" value="<?php echo esc_html($shopkeeper->shop_number); ?>" placeholder="<?php esc_html_e("Shop Number"); ?>" required>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Shopkeeper Name -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="shopkeeper_name" class="form-label fw-bolder"><?php esc_html_e("Shopkeeper Name"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="shopkeeper_name" class="form-control" value="<?php echo esc_html($shopkeeper->shopkeeper_name); ?>" placeholder="<?php esc_html_e("Shopkeeper Name"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Shopkeeper Phone -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="shopkeeper_phone" class="form-label fw-bolder"><?php esc_html_e("Shopkeeper Phone"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <div id="add_more_phone_number">
                                        <input type="text" name="shopkeeper_phone" class="form-control mb-1" value="<?php echo esc_html($shopkeeper->shopkeeper_phone); ?>" placeholder="<?php esc_html_e("Shopkeeper Phone"); ?>" required>    
                                        <?php 
                                            $meta_values = $wpdb->get_results("SELECT * FROM fst_shopkeeper_meta_data WHERE `shopkeeper_id` = '$shopkeeper_id' AND `meta_key` = 'phone'");
                                            if($meta_values) {
                                                foreach($meta_values as $values) {
                                                    echo "<input type='text' name='s_phone[]' class='form-control mb-1' value='$values->meta_value' placeholder='Customer Phone'>";
                                                }
                                            }
                                        ?>
                                    </div><!-- #add_more_phone_number -->
                                    <a class="btn btn-primary float-end mt-2" id="add_more_number"><?php esc_html_e("Add More"); ?></a>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Account Detail -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="shopkeeper_account" class="form-label fw-bolder"><?php esc_html_e("Account Details"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <div id="add_more_account_detail">
                                        <input type="text" name="shopkeeper_account" class="form-control mb-1" value="<?php echo esc_html($shopkeeper->shopkeeper_account); ?>" placeholder="<?php esc_html_e("Account Details"); ?>">    
                                        <?php 
                                            $meta_values = $wpdb->get_results("SELECT * FROM fst_shopkeeper_meta_data WHERE `shopkeeper_id` = '$shopkeeper_id' AND `meta_key` = 'account'");
                                            if($meta_values) {
                                                foreach($meta_values as $values) {
                                                    echo "<input type='text' name='account_details[]' class='form-control mb-1' value='$values->meta_value' placeholder='Customer Phone'>";
                                                }
                                            }
                                        ?>
                                    </div><!-- #add_more_account_detail -->
                                    <a class="btn btn-primary float-end mt-2" id="add_more_detail"><?php esc_html_e("Add More"); ?></a>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Current Image -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="current_img" class="form-label fw-bolder"><?php esc_html_e("Current Picture"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <?php
                                        if($shopkeeper->picture !="") { 
                                            $upload_dir = wp_upload_dir();
                                    
                                            // Checking whether file exists or not
                                            $url = $upload_dir['baseurl'].DIRECTORY_SEPARATOR.'shopkeeper_img';
                                            ?>
                                                <img src="<?php echo $url .DIRECTORY_SEPARATOR. $shopkeeper->picture; ?>" width="80" height="5%" style="border-radius: 50%;">
                                            <?php
                                        } else {
                                            echo "No profile picture is added";
                                        }
                                    ?>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Profile Pic -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="picture" class="form-label fw-bolder"><?php esc_html_e("Picture"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="file" name="picture" class="form-control" accept=".png,.jpg,.jpeg">    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Update Shopkeeper Button -->
                            <div class="row">
                                <div class="col-12 p-3">
                                    <?php wp_nonce_field( 'update_shopkeeper', 'update_shopkeeper_nonce' ) ?>
                                    <input type="hidden" name="current_image" value="<?php echo $shopkeeper->picture; ?>">
                                    <input type="hidden" name="shopkeeper_id" value="<?php echo esc_html($shopkeeper->ID); ?>">
                                    <button class="btn btn-primary" name="update_shopkeeper"><?php esc_html_e("Update Shopkeeper"); ?></button>
                                </div><!-- .col-12 -->
                            </div><!-- .row -->
                        </form>
                        <?php 
                                    endforeach;
                                }
                            } else { 
                        ?>
                        <!-- form for adding shopkeeper -->
                        <form action="" method="post" enctype="multipart/form-data">
                            <!-- Shop Number -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="shop_number" class="form-label fw-bolder"><?php esc_html_e("Shop Number"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="shop_number" class="form-control" placeholder="<?php esc_html_e("Shop Number"); ?>" required>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Shopkeeper Name -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="shopkeeper_name" class="form-label fw-bolder"><?php esc_html_e("Shopkeeper Name"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="shopkeeper_name" class="form-control" placeholder="<?php esc_html_e("Shopkeeper Name"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Shopkeeper Phone -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="shopkeeper_phone" class="form-label fw-bolder"><?php esc_html_e("Shopkeeper Phone"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <div id="add_more_phone_number">
                                        <input type="text" name="shopkeeper_phone" class="form-control mb-1" placeholder="<?php esc_html_e("Shopkeeper Phone"); ?>" required>    
                                    </div><!-- #add_more_phone_number -->
                                    <a class="btn btn-primary float-end mt-2" id="add_more_number"><?php esc_html_e("Add More"); ?></a>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Account Detail -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="shopkeeper_account" class="form-label fw-bolder"><?php esc_html_e("Account Details"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <div id="add_more_account_detail">
                                        <input type="text" name="shopkeeper_account" class="form-control mb-1" placeholder="<?php esc_html_e("Account Details"); ?>">    
                                    </div><!-- #add_more_account_detail -->
                                    <a class="btn btn-primary float-end mt-2" id="add_more_detail"><?php esc_html_e("Add More"); ?></a>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Profile Pic -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="picture" class="form-label fw-bolder"><?php esc_html_e("Picture"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="file" name="picture" class="form-control" accept=".png,.jpg,.jpeg">    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Add Shopkeeper Button -->
                            <div class="row">
                                <div class="col-12 p-3">
                                    <?php wp_nonce_field( 'add_shopkeeper', 'add_shopkeeper_nonce' ) ?>
                                    <button class="btn btn-primary" name="add_shopkeeper"><?php esc_html_e("Add Shopkeeper"); ?></button>
                                </div><!-- .col-12 -->
                            </div><!-- .row -->
                        </form>
                        <?php
                            }
                        ?>
                    </div><!-- .page-inner-content -->
                </div><!-- .col-lg-6 -->
            </div><!-- .row -->
            
            <div class="fetch_data mt-5">
                <div class="search_section_box bg-white rounded my-2 p-3">
                    <form class="row" method="post">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group my-1">
                                <label for="s_shop" class="form-label"><?php esc_html_e("Shop"); ?>:</label>
                                <input type="text" name="s_shop" class="form-control bg-light" placeholder="<?php esc_html_e("Search by shop #"); ?>">
                            </div><!-- .form-group -->
                        </div><!-- .col-lg-4 -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group my-1">
                                <label for="s_name" class="form-label"><?php esc_html_e("Name"); ?>:</label>
                                <input type="text" name="s_name" class="form-control bg-light" placeholder="<?php esc_html_e("Search by name"); ?>">
                            </div><!-- .form-group -->
                        </div><!-- .col-lg-4 -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group my-1">
                                <label for="s_phone" class="form-label"><?php esc_html_e("Phone"); ?>:</label>
                                <input type="text" name="s_phone" class="form-control bg-light" placeholder="<?php esc_html_e("Search by phone"); ?>">
                            </div><!-- .form-group -->
                        </div><!-- .col-lg-4 -->
                        <div class="col-12 text-end">
                            <?php wp_nonce_field('search_shopkeeper_details', 'search_shopkeeper_details_nonce'); ?>
                            <button class="btn btn-primary my-2" name="s_search"><?php esc_html_e("Search"); ?></button>
                        </div><!-- .col-12 -->
                    </form>
                </div><!-- .search_section_box -->
                <div class="search_section_table table-responsive">
                    <?php
                        if(isset($_POST['search_shopkeeper_details_nonce']) && wp_verify_nonce( $_POST['search_shopkeeper_details_nonce'], 'search_shopkeeper_details' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['s_search'])) {
                                    $s_shop = sanitize_text_field($_POST['s_shop']);
                                    $s_name = sanitize_text_field($_POST['s_name']);
                                    $s_phone = sanitize_text_field($_POST['s_phone']);

                                    $fetch_shopkeeper = $wpdb->get_results("SELECT * FROM fst_shopkeepers_data WHERE `shop_number` = '$s_shop' OR `shopkeeper_name` = '$s_name' OR `shopkeeper_phone` = '$s_phone'");

                                    if($fetch_shopkeeper) {
                                        foreach($fetch_shopkeeper as $fetch_shopkeeper) {
                                            $shopkeeper_id = $fetch_shopkeeper->ID;
                    ?>
                    <table class="table table-bordered text-center fw-bolder">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th><?php esc_html_e("Picture"); ?></th>
                                <th><?php esc_html_e("Shop#"); ?></th>
                                <th><?php esc_html_e("Name"); ?></th>
                                <th><?php esc_html_e("Phone"); ?></th>
                                <th><?php esc_html_e("Account"); ?></th>
                                <th><?php esc_html_e("Action"); ?></th>
                            </tr>
                        </thead>
                        <tbody class="bg-light">
                            <tr>
                                <td>
                                    <?php
                                        if($fetch_shopkeeper->picture !="") { 
                                            $upload_dir = wp_upload_dir();
                                    
                                            // Checking whether file exists or not
                                            $url = $upload_dir['baseurl'].DIRECTORY_SEPARATOR.'shopkeeper_img';
                                            ?>
                                                <img src="<?php echo $url .DIRECTORY_SEPARATOR. $fetch_shopkeeper->picture; ?>" width="50" height="5%" style="border-radius: 50%;">
                                            <?php
                                        } else {
                                            echo "<span class='text-danger'>NO Picture...</span>";
                                        }
                                    ?>
                                </td>
                                <td><?php esc_html_e($fetch_shopkeeper->shop_number); ?></td>
                                <td><?php esc_html_e($fetch_shopkeeper->shopkeeper_name); ?></td>
                                <td>
                                    <?php 
                                        esc_html($fetch_shopkeeper->shopkeeper_phone) . '<br>'; 
                                        $shopkeeper_meta_phone = $wpdb->get_results("SELECT * FROM fst_shopkeeper_meta_data WHERE `shopkeeper_id` = '$shopkeeper_id' AND `meta_key` = 'phone'");
                                        if($shopkeeper_meta_phone) {
                                            foreach($shopkeeper_meta_phone as $phone) {
                                                esc_html($phone->meta_value) . '<br>';
                                            }
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        esc_html($fetch_shopkeeper->shopkeeper_account) . '<br>'; 
                                        $shopkeeper_meta_account = $wpdb->get_results("SELECT * FROM fst_shopkeeper_meta_data WHERE `shopkeeper_id` = '$shopkeeper_id' AND `meta_key` = 'account'");
                                        if($shopkeeper_meta_account) {
                                            foreach($shopkeeper_meta_account as $account) {
                                                esc_html($account->meta_value) . '<br>';
                                            }
                                        }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?php esc_url(home_url('/shopkeeper-invoice')); ?>?search=shopkeeper&shopkeeper_id=<?php _e(esc_html($shopkeeper_id)); ?>" class="btn btn-primary text-white"><?php _e("View"); ?></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php   
                                        }
                                    } else {
                                        echo "<div class='text-white text-center fw-bolder'>No shopkeeper found!</div>";
                                    }
                                }
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>
                                    <strong>User is not logged in!</strong>
                                </div>";
                            }
                        } else {
                    ?>
                    <table class="table table-bordered table-hover table-striped table-sm">
                        <thead class="bg-dark text-white text-center">
                            <tr>
                                <th><?php esc_html_e("Sr#"); ?></th>
                                <th><?php esc_html_e("Image"); ?></th>
                                <th><?php esc_html_e("Shop#"); ?></th>
                                <th><?php esc_html_e("Name"); ?></th>
                                <th><?php esc_html_e("Phone"); ?></th>
                                <th><?php esc_html_e("Account"); ?></th>
                                <th><?php esc_html_e("Action"); ?></th>
                            </tr>
                        </thead>
                        <tbody class='bg-light'>
                            <?php
                                $result = $wpdb->get_results('SELECT * FROM fst_shopkeepers_data');
                                $sr = 1;

                                if($result) {
                                    foreach($result as $row) {
                                        $shopkeeper_id = $row->ID;
                            ?>
                            <tr class="text-center">
                                <td><?php echo esc_html($sr++); ?></td>
                                <td>
                                    <?php
                                        if($row->picture !="") { 
                                            $upload_dir = wp_upload_dir();
                                    
                                            // Checking whether file exists or not
                                            $url = $upload_dir['baseurl'].DIRECTORY_SEPARATOR.'shopkeeper_img';
                                            ?>
                                                <img src="<?php echo $url .DIRECTORY_SEPARATOR. $row->picture; ?>" width="50" height="5%" style="border-radius: 50%;">
                                            <?php
                                        } else {
                                            echo "<span class='text-danger'>NO Picture...</span>";
                                        }
                                    ?>
                                </td>
                                <td><?php echo esc_html($row->shop_number); ?></td>
                                <td><?php echo esc_html($row->shopkeeper_name); ?></td>
                                <td>
                                    <?php 
                                        echo esc_html($row->shopkeeper_phone) . '<br>'; 
                                        $fetch_meta_phone = $wpdb->get_results("SELECT * FROM fst_shopkeeper_meta_data WHERE `shopkeeper_id` = '$shopkeeper_id' AND `meta_key` = 'phone'");
                                        if($fetch_meta_phone) {
                                            foreach($fetch_meta_phone as $phone) {
                                                echo esc_html($phone->meta_value) . '<br>';
                                            }
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        echo esc_html($row->shopkeeper_account) . '<br>'; 
                                        $fetch_meta_account = $wpdb->get_results("SELECT * FROM fst_shopkeeper_meta_data WHERE `shopkeeper_id` = '$shopkeeper_id' AND `meta_key` = 'account'");
                                        if($fetch_meta_account) {
                                            foreach($fetch_meta_account as $account) {
                                                echo esc_html($account->meta_value) . '<br>';
                                            }
                                        }
                                    ?>
                                </td>
                                <td>
                                    <div class="dropdown action mx-2">
                                        <div class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            ...
                                        </div>
                                        <div class="dropdown-menu text-center">
                                            <a href="?query=update&hash=<?php echo esc_html($row->ID); ?>&<?php echo esc_html(md5($row->ID)); ?>" class="btn btn-primary text-white"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <form class="d-inline" method="post">
                                                <?php wp_nonce_field( 'deleting_shopkeeper', 'deleting_shopkeeper_nonce' ); ?>
                                                <button class="btn btn-danger text-white" value="<?php echo esc_html($row->ID); ?>" name="user_id"><i class="fa-solid fa-trash"></i></button>
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
                    <?php } ?>
                </div><!-- .search_section_table -->
            </div><!-- .fetch_data -->
            
        </div><!-- .container -->
    </div><!-- .page-main-content -->
        
<?php
get_footer();