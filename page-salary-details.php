<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Salary Detail
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

            <h1 class="text-center text-capitalize my-5"><?php _e(the_title()); ?></h1>
            
            <div class="row">
                <?php
                    /************ code for adding salary ***************/
                    if(isset($_POST['pay_salary_nonce']) && wp_verify_nonce( $_POST['pay_salary_nonce'], 'pay_salary' )) {
                        if(is_user_logged_in()) {
                            if(isset($_POST['pay_salary'])) {
                                $ID = sanitize_text_field($_POST['salary_id']);
                                $pay_date = sanitize_text_field($_POST['pay_date']);
                                $pay_amount = sanitize_text_field($_POST['pay_amount']);

                                if(!empty($pay_amount)) {
                                    $table = $wpdb->prefix.'salary_data';
                                    $data = array(
                                        'pay_amount' => $pay_amount,
                                        'pay_date' => $pay_date
                                    );
                                    $where = array( 'ID' => $ID );
                                    $add_salary = $wpdb->update($table, $data, $where);
                                    
                                    if($add_salary) {
                                        echo "<div class='alert alert-success' role='alert'>
                                        <strong>Salary pay successfully...</strong>
                                        </div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        <strong>Error to paying the salary!</strong>
                                        </div>";
                                    }
                                } else {
                                    echo "<div class='alert alert-danger' role='alert'>
                                    <strong>Enter pay amount!</strong>
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
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <table class="table table-bordered fw-bolder bg-light">
                        <?php
                            $ID = esc_html($_GET['hash']);
                            $result = $wpdb->get_results("SELECT * FROM fst_salary_data WHERE `ID` = '$ID'");
                            $sr = 1;

                            if($result) {
                                foreach($result as $row) {
                        ?>
                        <tbody>
                            <tr>
                                <td><?php _e(esc_html('Name')); ?></td>
                                <td><?php echo esc_html($row->name); ?></td>
                            </tr>
                            <tr>
                                <td><?php _e(esc_html('Salary')); ?></td>
                                <td><?php echo esc_html(number_format_i18n($total = $row->salary)); ?></td>
                            </tr>
                            <tr>
                                <td><?php _e(esc_html('Pay')); ?></td>
                                <td><?php echo esc_html(number_format_i18n($pay = $row->pay_amount)); ?></td>
                            </tr>
                            <tr>
                                <td><?php _e(esc_html('Remain')); ?></td>
                                <td><?php echo esc_html(number_format_i18n($remain = $total - $pay)); ?></td>
                            </tr>
                        </tbody>
                        <?php
                                }
                            } else {
                                echo "<tr>
                                    <td colspan='5' class='text-center text-danger'>No Data Found...</td>
                                </tr>";
                            }
                        ?>
                    </table>
                </div><!-- .col-lg-6 -->
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="salary_payment">
                        <!-- form for adding pay salary -->
                        <form class="text-start border bg-light p-2" method="post">
                            <!-- Date -->
                            <div class="row mb-3 p-2">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="pay_date" class="form-label fw-bolder"><?php _e("Date"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="date" name="pay_date" class="form-control" value="<?php echo esc_html($date); ?>" placeholder="<?php _e("Date"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Amount -->
                            <div class="row mb-3 p-2 pb-0">
                                <div class="col-lg-4 col-sm-12">
                                    <label for="pay_amount" class="form-label fw-bolder"><?php _e("Amount"); ?></label>
                                </div><!-- .col-lg-4 -->
                                <div class="col-lg-8 col-sm-12">
                                    <input type="number" name="pay_amount" class="form-control" placeholder="<?php _e("Amount"); ?>" required>    
                                </div><!-- .col-lg-8 -->
                            </div><!-- .row -->

                            <!-- Pay Salary Button -->
                            <div class="row text-end">
                                <div class="col-12 px-3">
                                    <?php wp_nonce_field( 'pay_salary', 'pay_salary_nonce' ); ?>
                                    <input type="hidden" name="salary_id" value="<?php echo esc_html($row->ID); ?>">
                                    <button class="btn btn-primary" name="pay_salary"><?php _e("Pay salary"); ?></button>
                                </div><!-- .col-12 -->
                            </div><!-- .row -->
                        </form>
                    </div><!-- .salary_payment -->
                </div><!-- .col-lg-6 -->
            </div><!-- .row -->
            
        </div><!-- .container-fluid -->
    </div><!-- .page-main-content -->
        
<?php
get_footer();