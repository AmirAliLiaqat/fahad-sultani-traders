<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Add & Edit Customers
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
        $path = $upload_dir['basedir'].DIRECTORY_SEPARATOR.'customer_img';
    ?>

    <div class="page-main-content">
        <div class="container-fluid p-5">

            <?php get_header(); ?>
            
            <h1 class="text-center text-capitalize my-5"><?php echo isset($_GET['query']) ? esc_html_e("Edit") : esc_html_e("Add"); esc_html_e( " Customers" ); ?></h1>
            
            <div class="row my-2">
                <div class="col-lg-6 col-sm-12 mx-auto">
                    <?php
                        /************ code for adding customer ***************/
                        if(isset($_POST['add_customer_nonce']) && wp_verify_nonce( $_POST['add_customer_nonce'], 'add_customer' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['add_customer'])) {
                                    $shop_number = sanitize_text_field($_POST['shop_number']);
                                    $customer_name = sanitize_text_field($_POST['customer_name']);
                                    $customer_phone = sanitize_text_field($_POST['customer_phone']);

                                    $lastid = $wpdb->get_var('SELECT MAX(ID) FROM fst_customer_data');
                                    $serial_number = $lastid + 1;

                                    if(isset($_POST['c_phone'])) {
                                        $phone_no = $_POST['c_phone'];
                                        $sr = 1;

                                        foreach($phone_no as $phone) {
                                            $table_name = $wpdb->prefix.'customer_meta_data';
                                            $meta_data = $wpdb->insert($table_name, array(
                                                'customer_id' => $serial_number,
                                                'meta_key' => 'phone_'.$sr++,
                                                'meta_value' => $phone
                                            ));
                                        }
                                    }

                                    if($_FILES['customer_image']['name'] != '') {
                                        $customer_picture = $_FILES['customer_image']['name'];
                                        $picture_path = $_FILES['customer_image']['tmp_name'];
    
                                        // Auto rename image
                                        $ext = end(explode('.',$customer_picture));
                                        // Rename the image
                                        $customer_picture = "customer_".rand(00,99).'.'.$ext;
    
                                        $image = wp_get_image_editor($picture_path);
    
                                        if ( ! is_wp_error( $image ) ) { 
                                            $image->set_quality(80);
                                            if($image) {
                                                if (file_exists($path)) {
                                                    $image->save($path.DIRECTORY_SEPARATOR.$customer_picture);
                                                }
                                                else {
                                                    mkdir($path);
                                                    $image->save($path.DIRECTORY_SEPARATOR.$customer_picture);
                                                }
                                            }
                                        } else {
                                            echo "<div class='alert alert-danger' role='alert'>
                                                <strong>Error found for adding customer image!</strong>
                                            </div>";
                                        }

                                    } else {
                                        $customer_picture = '';
                                    }

                                    $table = $wpdb->prefix.'customer_data';
                                    $add_customer = $wpdb->insert($table, array(
                                        'serial_number' => $serial_number,
                                        'picture' => $customer_picture,
                                        'shop_number' => $shop_number, 
                                        'name' => $customer_name, 
                                        'phone' => $customer_phone
                                    ));
                                    
                                    if($add_customer) {
                                        echo "<div class='alert alert-success' role='alert'>
                                        <strong>Customer added successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Error to adding the customer!</strong>
                                        </div>";
                                    }
                                }
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>
                                  <strong>User is not logged in!</strong>
                                </div>";
                            }
                        }

                        /************ code for updating customer ***************/
                        if(isset($_POST['update_customer_nonce']) && wp_verify_nonce( $_POST['update_customer_nonce'], 'update_customer' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['update_customer'])) {
                                    $customer_id = sanitize_text_field($_POST['customer_id']);
                                    $shop_number = sanitize_text_field($_POST['shop_number']);
                                    $customer_name = sanitize_text_field($_POST['customer_name']);
                                    $customer_phone = sanitize_text_field($_POST['customer_phone']);
                                    $current_image = sanitize_text_field($_POST['current_image']);

                                    if($_FILES['customer_image']['name'] != '') {
                                        $customer_picture = $_FILES['customer_image']['name'];
                                        $picture_path = $_FILES['customer_image']['tmp_name'];
                                        // Auto rename image
                                        $ext = end(explode('.',$customer_picture));
                                        // Rename the image
                                        $customer_picture = "customer_".rand(00,99).'.'.$ext;

                                        $image = wp_get_image_editor($picture_path);

                                        if ( ! is_wp_error( $image ) ) { 
                                            $image->set_quality(80);
                                            if($image) {
                                                if (file_exists($path)) {
                                                    $image->save($path.DIRECTORY_SEPARATOR.$customer_picture);
                                                }
                                                else {
                                                    mkdir($path);
                                                    $image->save($path.DIRECTORY_SEPARATOR.$customer_picture);
                                                }
                                            }

                                            if($current_image != "") {
                                                // Remove the current image
                                                $remove_path = $path.DIRECTORY_SEPARATOR.$current_image;
                                                $remove_image = unlink($remove_path);
                                            }
                                        }
                                    } else {
                                        $customer_picture = $current_image;
                                    }

                                    if(isset($_POST['c_phone'])) {
                                        $phone_no = $_POST['c_phone'];
                                        // $sr = 1;

                                        foreach($phone_no as $phone) {
                                            $table = $wpdb->prefix.'customer_meta_data';
                                            $data = array(
                                                // 'meta_key' => 'phone_'.$sr++,
                                                'meta_key' => 'phone',
                                                'meta_value' => $phone
                                            );
                                            $where = array( 'customer_id' => $customer_id );
                                            $update_meta_data = $wpdb->update($table, $data, $where);
                                    
                                            if($update_meta_data) {
                                                echo "<div class='alert alert-success' role='alert'>
                                                    <strong>Phone number updated successfully...</strong>
                                                </div>";
                                            }
                                        }
                                    }
                                    
                                    $table = $wpdb->prefix.'customer_data';
                                    $data = array(
                                        'picture' => $customer_picture, 
                                        'shop_number' => $shop_number, 
                                        'name' => $customer_name, 
                                        'phone' => $customer_phone
                                    );
                                    $where = array( 'ID' => $customer_id );
                                    $update_customer = $wpdb->update($table, $data, $where);
                                    
                                    if($update_customer) {
                                        echo "<div class='alert alert-success' role='alert'>
                                            <strong>Customer updated successfully...</strong>
                                        </div>";
                                    }
                                }
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>
                                <strong>User is not logged in!</strong>
                                </div>";
                            }
                        }

                        /************ code for deleting customer ***************/
                        if(isset($_POST['delete_customer_nonce']) && wp_verify_nonce( $_POST['delete_customer_nonce'], 'delete_customer' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['id'])) {
                                    $customer_id = sanitize_text_field($_POST['id']);

                                    $fetch_img = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `ID` = '".$customer_id."'");

                                    if($fetch_img) {
                                        foreach($fetch_img as $img) {
                                            $picture = $img->picture;
                                        }
                                    }

                                    if($picture != "") {
                                        $image_path = $path.DIRECTORY_SEPARATOR.$picture;
                                        $remove_image = unlink($image_path);
                                    }

                                    $table = $wpdb->prefix.'customer_data';
                                    $where = array( 'ID' => $customer_id );
                                    $delete_customer = $wpdb->delete($table, $where);
                                    
                                    if($delete_customer) {
                                        echo "<div class='alert alert-success' role='alert'>
                                        <strong>Customer deleted successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Error to deleting the customer!</strong>
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
                                    $fetch_customer = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `ID` = '".$ID."'");
                                    
                                    foreach($fetch_customer as $customer) :
                                        $customer_id = $customer->ID;
                        ?>
                        <!-- form for updating shopkeeper -->
                        <form action="" method="post" enctype="multipart/form-data">
                            <!-- Shop Number -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="shop_number" class="form-label fw-bolder"><?php _e("Shop Number"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="shop_number" class="form-control" value="<?php echo esc_html($customer->shop_number); ?>" placeholder="<?php _e("Shop Number"); ?>" required>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Customer Name -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="customer_name" class="form-label fw-bolder"><?php _e("Customer Name"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="customer_name" class="form-control" value="<?php echo esc_html($customer->name); ?>" placeholder="<?php _e(" Customer Name"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Customer Phone -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="customer_phone" class="form-label fw-bolder"><?php _e("Customer Phone"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <div id="add_more_customer_phone_number">
                                        <input type="text" name="customer_phone" class="form-control mb-1" value="<?php echo esc_html($customer->phone); ?>" placeholder="<?php _e("Customer Phone"); ?>" required>
                                        <?php 
                                            $meta_values = $wpdb->get_results("SELECT * FROM fst_customer_meta_data WHERE `customer_id` = '$customer_id' ");
                                            if($meta_values) {
                                                foreach($meta_values as $values) {
                                                    echo "<input type='text' name='c_phone[]' class='form-control mb-1' value='$values->meta_value' placeholder='Customer Phone'>";
                                                }
                                            }
                                        ?>
                                    </div><!-- #add_more_customer_phone_number -->
                                    <a class="btn btn-primary float-end mt-2" id="add_more_customer_number"><?php _e("Add More"); ?></a>   
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Current Image -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="current_img" class="form-label fw-bolder"><?php _e("Current Picture"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <?php
                                        if($customer->picture !="") { 
                                            $upload_dir = wp_upload_dir();
                                    
                                            // Checking whether file exists or not
                                            $url = $upload_dir['baseurl'].DIRECTORY_SEPARATOR.'customer_img';
                                            ?>
                                                <img src="<?php echo $url .DIRECTORY_SEPARATOR. $customer->picture; ?>" width="80" height="5%" style="border-radius: 50%;">
                                            <?php
                                        } else {
                                            echo "No profile picture is added";
                                        }
                                    ?>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Customer Image -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="customer_image" class="form-label fw-bolder"><?php _e("Picture"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="file" name="customer_image" class="form-control">
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Update Customer Button -->
                            <div class="row">
                                <div class="col-12 p-3">
                                    <?php wp_nonce_field( 'update_customer', 'update_customer_nonce' ) ?>
                                    <input type="hidden" name="current_image" value="<?php echo $customer->picture; ?>">
                                    <input type="hidden" name="customer_id" value="<?php echo esc_html($customer->ID); ?>">
                                    <button class="btn btn-primary" name="update_customer"><?php _e("Update Customer"); ?></button>
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
                                    <label for="shop_number" class="form-label fw-bolder"><?php _e("Shop Number"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="shop_number" class="form-control" placeholder="<?php _e("Shop Number"); ?>" required>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Customer Name -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="customer_name" class="form-label fw-bolder"><?php _e("Customer Name"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="customer_name" class="form-control" placeholder="<?php _e("Customer Name"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Customer Phone -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="customer_phone" class="form-label fw-bolder"><?php _e("Customer Phone"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <div id="add_more_customer_phone_number">
                                        <input type="text" name="customer_phone" class="form-control mb-1" placeholder="<?php _e("Customer Phone"); ?>" required>    
                                    </div><!-- #add_more_customer_phone_number -->
                                    <a class="btn btn-primary float-end mt-2" id="add_more_customer_number"><?php _e("Add More"); ?></a>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Customer Image -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="customer_image" class="form-label fw-bolder"><?php _e("Picture"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="file" name="customer_image" class="form-control">
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Add Customer Button -->
                            <div class="row">
                                <div class="col-12 p-3">
                                    <?php wp_nonce_field( 'add_customer', 'add_customer_nonce' ) ?>
                                    <button class="btn btn-primary" name="add_customer"><?php _e("Add Customer"); ?></button>
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
                <div class="search_section_box bg-white rounded p-3 my-2">
                    <form class="row" method="post">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group my-1">
                                <label for="c_shop" class="form-label"><?php esc_html_e("Shop"); ?>:</label>
                                <input type="text" id="search_item" name="c_shop" class="form-control bg-light" placeholder="<?php esc_html_e("Search by shop #"); ?>">
                            </div><!-- .form-group -->
                        </div><!-- .col-lg-4 -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group my-1">
                                <label for="c_name" class="form-label"><?php esc_html_e("Name"); ?>:</label>
                                <input type="text" id="search_item" name="c_name" class="form-control bg-light" placeholder="<?php esc_html_e("Search by name"); ?>">
                            </div><!-- .form-group -->
                        </div><!-- .col-lg-4 -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group my-1">
                                <label for="c_phone" class="form-label"><?php esc_html_e("Phone"); ?>:</label>
                                <input type="text" id="search_item" name="c_phone" class="form-control bg-light" placeholder="<?php esc_html_e("Search by phone"); ?>">
                            </div><!-- .form-group -->
                        </div><!-- .col-lg-4 -->
                        <div class="col-12 text-end">
                            <?php wp_nonce_field('search_customer', 'search_customer_nonce'); ?>
                            <button class="btn btn-primary my-2" id="c_search" name="c_search"><?php esc_html_e('Search'); ?></button>
                        </div><!-- .col-12 -->
                    </form><!-- .row -->
                </div><!-- .search_section_box -->
                <div class="search_section_table table-responsive">
                    <?php
                        if(isset($_POST['search_customer_nonce']) && wp_verify_nonce( $_POST['search_customer_nonce'], 'search_customer' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['c_search'])) {
                                    $c_shop = sanitize_text_field($_POST['c_shop']);
                                    $c_name = sanitize_text_field($_POST['c_name']);
                                    $c_phone = sanitize_text_field($_POST['c_phone']);

                                    $fetch_customer = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `shop_number` = '$c_shop' OR `name` = '$c_name' OR `phone` = '$c_phone'");

                                    if($fetch_customer) {
                                        foreach($fetch_customer as $fetch_customer) {
                                            $customer_id = $fetch_customer->ID;
                    ?>
                    <table class="table table-bordered text-center fw-bolder">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th><?php esc_html_e("Picture"); ?></th>
                                <th><?php esc_html_e("Shop#"); ?></th>
                                <th><?php esc_html_e("Name"); ?></th>
                                <th><?php esc_html_e("Phone"); ?></th>
                                <th><?php esc_html_e("Action"); ?></th>
                            </tr>
                        </thead>
                        <tbody class="bg-light">
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
                                <td><?php echo esc_html($fetch_customer->shop_number); ?></td>
                                <td><?php echo esc_html($fetch_customer->name); ?></td>
                                <td>
                                    <?php 
                                        echo esc_html($fetch_customer->phone) . '<br>'; 
                                        $meta_values = $wpdb->get_results("SELECT * FROM fst_customer_meta_data WHERE `customer_id` = '$customer_id' ");
                                        if($meta_values) {
                                            foreach($meta_values as $values) {
                                                echo esc_html($values->meta_value) . '<br>';
                                            }
                                        }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?php echo esc_url(home_url('/customer-invoice?search=customer&customer_id=' . $customer_id)); ?>" class="btn btn-primary text-white"><?php esc_html_e("View"); ?></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php   
                                        }
                                    } else {
                                        echo "<div class='text-white text-center fw-bolder'>No customer found!</div>";
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
                                <th><?php esc_html_e("Customer Image"); ?></th>
                                <th><?php esc_html_e("Shop Number"); ?></th>
                                <th><?php esc_html_e("Customer Name"); ?></th>
                                <th><?php esc_html_e("Customer Phone"); ?></th>
                                <th><?php esc_html_e("Action"); ?></th>
                            </tr>
                        </thead>
                        <tbody class='bg-light'>
                            <?php
                                $result = $wpdb->get_results('SELECT * FROM fst_customer_data');
                                $sr = 1;

                                if($result) {
                                    foreach($result as $row) {
                                        $customer_id = $row->ID;
                            ?>
                            <tr class="text-center">
                                <td><?php echo esc_html($sr++); ?></td>
                                <td>
                                    <?php
                                        if($row->picture !="") { 
                                            $upload_dir = wp_upload_dir();
                                    
                                            // Checking whether file exists or not
                                            $url = $upload_dir['baseurl'].DIRECTORY_SEPARATOR.'customer_img';
                                            ?>
                                                <img src="<?php echo $url .DIRECTORY_SEPARATOR. $row->picture; ?>" width="50" height="5%" style="border-radius: 50%;">
                                            <?php
                                        } else {
                                            echo "<span class='text-danger'>NO Picture...</span>";
                                        }
                                    ?>
                                </td>
                                <td><?php echo esc_html($row->shop_number); ?></td>
                                <td><?php echo esc_html($row->name); ?></td>
                                <td>
                                    <?php 
                                        echo $row->phone . '<br>'; 
                                        $meta_values = $wpdb->get_results("SELECT * FROM fst_customer_meta_data WHERE `customer_id` = '$customer_id' ");
                                        if($meta_values) {
                                            foreach($meta_values as $values) {
                                                echo $values->meta_value . '<br>';
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
                                                <?php wp_nonce_field( 'delete_customer', 'delete_customer_nonce' ); ?>
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
                                        <td colspan='7' class='text-center text-danger'>No Data Found...</td>
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