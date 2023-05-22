<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Add & Edit Expense
 * The template for displaying expenses
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
                <div class="col-lg-6 col-sm-12 mx-auto">
                    <?php
                        /************ code for adding expense ***************/
                        if(isset($_POST['adding_expense_nonce']) && wp_verify_nonce( $_POST['adding_expense_nonce'], 'adding_expense' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['add_expense'])) {
                                    $expense_name = sanitize_text_field($_POST['expense_name']);
                                    $expense_date = sanitize_text_field($_POST['expense_date']);
                                    $expense_amount = sanitize_text_field($_POST['expense_amount']);

                                    $lastid = $wpdb->get_var('SELECT MAX(ID) FROM fst_expense_data');
                                    $serial_number = $lastid + 1;

                                    $table = $wpdb->prefix.'expense_data';
                                    $add_expense = $wpdb->insert($table, array(
                                        'serial_number' => $serial_number,
                                        'expense_name' => $expense_name,
                                        'expense_date' => $expense_date, 
                                        'expense_amount' => $expense_amount
                                    ));
                                    
                                    if($add_expense) {
                                        echo "<div class='alert alert-success' role='alert'>
                                        <strong>Expense added successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Error to adding the expense!</strong>
                                        </div>";
                                    }
                                }  
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>
                                  <strong>User is not logged in!</strong>
                                </div>";
                            }
                        }

                        /************ code for updating expense ***************/
                        if(isset($_POST['updating_expense_nonce']) && wp_verify_nonce( $_POST['updating_expense_nonce'], 'updating_expense' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['update_expense'])) {
                                    $expense_id = sanitize_text_field($_GET['hash']);
                                    $expense_name = sanitize_text_field($_POST['expense_name']);
                                    $expense_date = sanitize_text_field($_POST['expense_date']);
                                    $expense_amount = sanitize_text_field($_POST['expense_amount']);

                                    $table = $wpdb->prefix.'expense_data';
                                    $data = array(
                                        'expense_name' => $expense_name,
                                        'expense_date' => $expense_date, 
                                        'expense_amount' => $expense_amount
                                    );
                                    $where = array( 'ID' => $expense_id );
                                    $update_expense = $wpdb->update($table, $data, $where);
                                    
                                    if($update_expense) {
                                        echo "<div class='alert alert-success' role='alert'>
                                        <strong>Expense updated successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Error to updating the expense!</strong>
                                        </div>";
                                    }
                                }    
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>
                                  <strong>User is not logged in!</strong>
                                </div>";
                            }
                        }

                        /************ code for deleting expense ***************/
                        if(isset($_POST['deleting_expense_nonce']) && wp_verify_nonce( $_POST['deleting_expense_nonce'], 'deleting_expense' )) {
                            if(is_user_logged_in()) {
                                if(isset($_POST['id'])) {
                                    $expense_id = sanitize_text_field($_POST['id']);

                                    $table = $wpdb->prefix.'expense_data';
                                    $where = array( 'ID' => $expense_id );
                                    $delete_expense = $wpdb->delete($table, $where);
                                    
                                    if($delete_expense) {
                                        echo "<div class='alert alert-success' role='alert'>
                                        <strong>Expense deleted successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Error to deleting the expense!</strong>
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
                                    $fetch_expense_by_id = $wpdb->get_results("SELECT * FROM fst_expense_data WHERE `ID` = '".$ID."'");
                                    
                                    foreach($fetch_expense_by_id as $expense) :
                                        
                        ?>
                        <!-- form for updating expense -->
                        <form action="" method="post">
                            <!-- Name -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="expense_name" class="form-label fw-bolder"><?php esc_html_e("Name"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="expense_name" class="form-control" value="<?php echo esc_html($expense->expense_name); ?>" placeholder="<?php esc_html_e("Name"); ?>" required>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Date -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="expense_date" class="form-label fw-bolder"><?php esc_html_e("Date"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="date" name="expense_date" class="form-control" value="<?php echo esc_html($expense->expense_date); ?>" placeholder="<?php esc_html_e("Date"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Amount -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="expense_amount" class="form-label fw-bolder"><?php esc_html_e("Amount"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="number" name="expense_amount" class="form-control" value="<?php echo esc_html($expense->expense_amount); ?>" placeholder="<?php esc_html_e("Amount"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Update Expense Button -->
                            <div class="row">
                                <div class="col-12 p-3">
                                    <?php wp_nonce_field( 'updating_expense', 'updating_expense_nonce' ); ?>
                                    <button class="btn btn-primary" name="update_expense"><?php esc_html_e("Update Expense"); ?></button>
                                </div><!-- .col-12 -->
                            </div><!-- .row -->
                        </form>
                        <?php 
                                    endforeach;
                                }
                            } else { 
                        ?>
                        <!-- form for adding expense -->
                        <form action="" method="post">
                            <!-- Name -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="expense_name" class="form-label fw-bolder"><?php esc_html_e("Name"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" name="expense_name" class="form-control" placeholder="<?php esc_html_e("Name"); ?>" required>
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Date -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="expense_date" class="form-label fw-bolder"><?php esc_html_e("Date"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="date" name="expense_date" class="form-control" value="<?php echo $date; ?>" placeholder="<?php esc_html_e("Date"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Amount -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="expense_amount" class="form-label fw-bolder"><?php esc_html_e("Amount"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="number" name="expense_amount" class="form-control" placeholder="<?php esc_html_e("Amount"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Add Expense Button -->
                            <div class="row">
                                <div class="col-12 p-3">
                                    <?php wp_nonce_field( 'adding_expense', 'adding_expense_nonce' ); ?>
                                    <button class="btn btn-primary" name="add_expense"><?php esc_html_e("Add Expense"); ?></button>
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
                            <th><?php esc_html_e("Expense Name"); ?></th>
                            <th><?php esc_html_e("Expense Date"); ?></th>
                            <th><?php esc_html_e("Expense Amount"); ?></th>
                            <th><?php esc_html_e("Action"); ?></th>
                        </tr>
                    </thead>
                    <tbody class='bg-light'>
                        <?php
                            $date = date('Y-m-d');
                             
                            $fetch_expense = $wpdb->get_results("SELECT * FROM fst_expense_data WHERE `expense_date` = '".$date."'");

                            if($fetch_expense) {
                                foreach($fetch_expense as $expense) {
                        ?>
                        <tr class="text-center">
                            <td><?php echo esc_html($expense->serial_number); ?></td>
                            <td><?php echo esc_html($expense->expense_name); ?></td>
                            <td><?php echo esc_html($expense->expense_date); ?></td>
                            <td><?php echo esc_html(number_format_i18n($expense->expense_amount)); ?> Rs/-</td>
                            <td>
                                <div class="dropdown action mx-2">
                                    <div class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        ...
                                    </div>
                                    <div class="dropdown-menu text-center">
                                        <a href="?query=update&hash=<?php echo esc_html($expense->ID); ?>&<?php echo esc_html(md5($expense->ID)); ?>" class="btn btn-primary text-white"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <form class="d-inline" method="post">
                                            <?php wp_nonce_field( 'deleting_expense', 'deleting_expense_nonce' ); ?>
                                            <button class="btn btn-danger text-white" value="<?php echo esc_html($expense->ID); ?>" name="id"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div><!--dropdown-->
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

        </div><!-- .container -->
    </div><!-- .page-main-content -->
    
<?php
get_footer();