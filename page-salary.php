<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Salary
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

            <h1 class="text-center text-capitalize my-5"><?php echo isset($_GET['query']) ? esc_html_e("Edit ") : esc_html_e("Add "); esc_html_e(the_title()); ?></h1>

            <div class="row my-2">
                <div class="col-lg-6 col-md-6 col-sm-12 mx-auto">
                    <?php
                        /************ code for adding salary ***************/
                        if(isset($_POST['adding_salary_nonce']) && wp_verify_nonce( $_POST['adding_salary_nonce'], 'adding_salary' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['add_salary'])) {
                                    $salary_person = sanitize_text_field($_POST['salary_person']);
                                    $salary_amount = sanitize_text_field($_POST['salary_amount']);

                                    $lastid = $wpdb->get_var('SELECT MAX(ID) FROM fst_salary_data');
                                    $serial_number = $lastid + 1;

                                    $table = $wpdb->prefix.'salary_data';
                                    $add_salary = $wpdb->insert($table, array(
                                        'serial_number' => $serial_number,
                                        'name' => $salary_person,
                                        'salary' => $salary_amount
                                    ));
                                    
                                    if($add_salary) {
                                        echo "<div class='alert alert-success' role='alert'>
                                        <strong>Salary added successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Error to adding the salary!</strong>
                                        </div>";
                                    }
                                }  
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>
                                  <strong>User is not logged in!</strong>
                                </div>";
                            }
                        }

                        /************ code for updating salary ***************/
                        if(isset($_POST['updating_salary_nonce']) && wp_verify_nonce( $_POST['updating_salary_nonce'], 'updating_salary' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['update_salary'])) {
                                    $salary_id = sanitize_text_field($_GET['hash']);
                                    $salary_person = sanitize_text_field($_POST['salary_person']);
                                    $salary_amount = sanitize_text_field($_POST['salary_amount']);

                                    $table = $wpdb->prefix.'salary_data';
                                    $data = array(
                                        'name' => $salary_person,
                                        'salary' => $salary_amount
                                    );
                                    $where = array( 'ID' => $salary_id );
                                    $update_salary = $wpdb->update($table, $data, $where);
                                    
                                    if($update_salary) {
                                        echo "<div class='alert alert-success' role='alert'>
                                        <strong>Salary updated successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Error to updating the salary!</strong>
                                        </div>";
                                    }
                                }    
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>
                                  <strong>User is not logged in!</strong>
                                </div>";
                            }
                        }

                        /************ code for deleting salary ***************/
                        if(isset($_POST['deleting_salary_nonce']) && wp_verify_nonce( $_POST['deleting_salary_nonce'], 'deleting_salary' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['id'])) {
                                    $salary_id = sanitize_text_field($_POST['id']);

                                    $table = $wpdb->prefix.'salary_data';
                                    $where = array( 'ID' => $salary_id );
                                    $delete_salary = $wpdb->delete($table, $where);
                                    
                                    if($delete_salary) {
                                        echo "<div class='alert alert-success' role='alert'>
                                        <strong>Salary deleted successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Error to deleting the salary!</strong>
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
                                    $fetch_salary_by_id = $wpdb->get_results("SELECT * FROM fst_salary_data WHERE `ID` = '".$ID."'");
                                    
                                    foreach($fetch_salary_by_id as $salary) :
                                        
                        ?>
                        <!-- form for adding salary -->
                        <form action="" method="post">
                            <!-- Name -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="salary_person" class="form-label fw-bolder"><?php esc_html_e("Name"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="salary_person" class="form-control" value="<?php echo $salary->name; ?>" placeholder="<?php esc_html_e("Name"); ?>" required>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Amount -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="salary_amount" class="form-label fw-bolder"><?php esc_html_e("Salary"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="number" name="salary_amount" class="form-control" value="<?php echo $salary->salary; ?>" placeholder="<?php esc_html_e("Salary"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Update Salary Button -->
                            <div class="row">
                                <div class="col-12 p-3">
                                    <?php wp_nonce_field( 'updating_salary', 'updating_salary_nonce' ); ?>
                                    <button class="btn btn-primary" name="update_salary"><?php esc_html_e("Update Salary"); ?></button>
                                </div><!-- .col-12 -->
                            </div><!-- .row -->
                        </form>
                        <?php 
                                    endforeach;
                                }
                            } else { 
                        ?>
                        <!-- form for adding salary -->
                        <form action="" method="post">
                            <!-- Name -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="salary_person" class="form-label fw-bolder"><?php esc_html_e("Name"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="salary_person" class="form-control" placeholder="<?php esc_html_e("Name"); ?>" required>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Amount -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="salary_amount" class="form-label fw-bolder"><?php esc_html_e("Salary"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="number" name="salary_amount" class="form-control" placeholder="<?php esc_html_e("Salary"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Add Salary Button -->
                            <div class="row">
                                <div class="col-12 p-3">
                                    <?php wp_nonce_field( 'adding_salary', 'adding_salary_nonce' ); ?>
                                    <button class="btn btn-primary" name="add_salary"><?php esc_html_e("Add Salary"); ?></button>
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
                            <th><?php esc_html_e("Name"); ?></th>
                            <th><?php esc_html_e("View"); ?></th>
                        </tr>
                    </thead>
                    <tbody class='bg-light'>
                        <?php
                            $result = $wpdb->get_results('SELECT * FROM fst_salary_data');
                            $sr = 1;

                            if($result) {
                                foreach($result as $row) {
                        ?>
                        <tr class="text-center">
                            <td><?php echo esc_html($sr++); ?></td>
                            <td><?php echo esc_html($row->name); ?></td>
                            <td>
                                <a href="<?php echo get_site_url(); ?>/salary-detail?query=view&hash=<?php echo esc_html($row->ID); ?>&<?php echo esc_html(md5($row->ID)); ?>" class="btn btn-primary text-white"><?php _e('View'); ?></a>
                                <a href="?query=update&hash=<?php echo esc_html($row->ID); ?>&<?php echo esc_html(md5($row->ID)); ?>" class="btn btn-primary text-white"><?php _e('Edit'); ?></a>
                                <?php if(!isset($_GET['query'])) : ?>
                                <form class="d-inline" method="post">
                                    <?php wp_nonce_field( 'deleting_salary', 'deleting_salary_nonce' ); ?>
                                    <button class="btn btn-danger text-white" value="<?php echo esc_html($row->ID); ?>" name="id"><?php _e('Delete'); ?></button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                                }
                            } else {
                                echo "<tr>
                                    <td colspan='5' class='text-center text-danger'>No Data Found...</td>
                                </tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div><!-- .fetch_data -->
            
        </div><!-- .container-fluid -->
    </div><!-- .page-main-content -->
        
<?php
get_footer();