<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Search Customer
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

    global $wpdb;
    
    $upload_dir = wp_upload_dir();

    // Checking whether file exists or not
    $url = $upload_dir['baseurl'].DIRECTORY_SEPARATOR.'customer_img';

    if(is_user_logged_in()) {
        if(isset($_POST['search'])) {
            $search_value = sanitize_text_field($_POST['search']);
        
            $result = $wpdb->get_results("SELECT * FROM fst_customer_data WHERE `shop_number` = '$search_value' OR `name` = '$search_value' OR `phone` = '$search_value' ");
        
            if($result) {
                foreach($result as $row) { ?>
                    <input type='radio' class='action_field' name='customer_id' value='<?php echo $row->ID; ?>'>
                    <?php
                        if($row->picture !="") { 
                            echo "<img src='$url/$row->picture' width='50' height='5%' style='border-radius: 50%;'>";
                        }
                    ?>
                    <b style='font-size: 1.5rem;'><?php echo $row->name; ?> &nbsp;&nbsp;&nbsp; <?php echo $row->shop_number; ?></b><br>
                <?php }
            } else {
                echo "<span class='text-danger'>No record found!</span>";
            }
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>
            <strong>User is not logged in!</strong>
        </div>";
    }

?>