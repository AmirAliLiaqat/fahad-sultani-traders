<?php
//redirect_not_logged_in_users();
/**
 * Template Name: Bill
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
        // $yesterday = date("Y-m-d", strtotime("-1 day"));
    ?>  

    <div class="page-main-content">
        <div class="container-fluid p-5">

            <?php get_header(); ?>
            
            <h1 class="text-center text-capitalize my-5"><?php esc_html_e(the_title()); ?></h1>

            <div class="bill bg-white rounded mx-auto" style="width: 60%">
                <form action="" method="post">
                    <div class="bill_header_section_1 p-4">
                        <div class="row fw-bolder  mb-3">
                            <div class="col-lg-6 col-md-4 col-sm-12 ">
                                <input type="date" name="tareek" value="<?php echo esc_html($date); ?>" class="border-0 border-dark border-bottom bg-transparent text-center rounded-0" style="outline: none;">
                                <h1 class="d-inline-block text-dark">تاریخ</h1>
                            </div><!-- .col-lg-4 -->
                            <div class="col-lg-6 col-md-8 col-sm-12">
                                <h1 class="text-dark text-end">فہد سلطانی ٹریڈرز</h1>
                            </div><!-- .col-lg-8 -->
                        </div><!-- .row -->
                    </div><!-- .bill_header_section_1 -->

                    <div class="row bill_header_section_2 pt-4 px-4">
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="text-center text-danger border border-dark rounded py-2">
                                حاجی محمد یونس  <br>
                                <span><i class="fa-brands fa-whatsapp"></i> 0321-4352163</span>
                                <span>0302-1494635</span>
                            </div>
                        </div><!-- .col-lg-3 -->
                        <div class="col-lg-9 col-md-9 col-sm-12 ">
                            <h3 class="bg-primary text-white text-center rounded w-100 py-2">اسپیشلسٹ ادرک لہسن اور کمیشن ایجنٹ</h3>
                            <div class="text-center py-2">
                                محمد فہد سلطانی <br>
                                <span class="fw-bolder"><i class="fa-brands fa-whatsapp"></i> 0309-6206534 / 0355-4980153</span>
                            </div><!-- .text-center -->
                        </div><!-- .col-lg-9 -->
                    </div><!-- .bill_header_section_2 -->

                    <div class="bill_header_section_3 d-flex justify-content-between my-2 px-4">
                        <div class="bill_field">
                            <input type="text" name="saler_name" class="border-0 border-dark border-bottom text-center rounded-0" style="outline: none;">
                            <h4 class="d-inline-block">نام بیوپاری</h4>
                        </div><!-- . bill_field -->
                        <div class="bill_field">
                            <input type="text" name="serial_no" class="border-0 border-dark border-bottom text-center rounded-0" style="outline: none;">
                            <h4 class="d-inline-block">سیریل نمبر</h4>
                        </div><!-- . bill_field -->
                    </div><!-- .bill_header_section_3 -->

                    <div class="bill_detail px-4">
                        <table class="table table-bordered border-dark text-center mb-0">
                            <thead>
                                <tr>
                                    <th width="100">روپے</th>
                                    <th width="100">نرخ</th>
                                    <th>تفصیل</th>
                                    <th width="30">مقدار</th>
                                    <th width="70">نمبر شمار</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($i = 1; $i <=7; $i++): ?>
                                <tr>
                                    <td class="p-1" width="100"></td>
                                    <td class="p-1" width="100">
                                        <input type="text" name="qty" class="border-0 text-center w-50" style="outline: none;">
                                    </td>
                                    <td class="p-1"></td>
                                    <td class="p-1" width="30">
                                        <input type="text" name="amount" class="border-0 text-center w-50" style="outline: none;">
                                    </td>
                                    <td class="p-1" width="70"><?php echo esc_html($i); ?></td>
                                </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                        <table class="table table-bordered border-dark text-end">
                            <tbody>
                                <tr>
                                    <td class="p-0" width="100">
                                        <h6 class="border-dark border-bottom text-center p-3">
                                            <input type="text" name="qachi_bakri" class="border-0 w-50" style="outline: none;">
                                        </h6>
                                        <h6 class="border-dark border-bottom text-center p-3">
                                            <input type="text" name="paki_bakri" class="border-0 w-50" style="outline: none;">
                                        </h6>
                                        <h5 class="border-dark border-bottom text-center p-3">
                                            <input type="text" name="total_amount" class="border-0 w-50" style="outline: none;">
                                        </h5>
                                        <h5 class="border-dark border-bottom text-center p-3">
                                            <input type="text" name="spend" class="border-0 w-50" style="outline: none;">
                                        </h5>
                                        <h6 class="text-center p-3">
                                            <input type="text" name="safi_bakri" class="border-0 w-50" style="outline: none;">
                                        </h6>
                                    </td>
                                    <td class="p-0" width="100">
                                        <h6 class="border-dark border-bottom fw-bolder p-3">کچی بکری</h6>
                                        <h6 class="border-dark border-bottom fw-bolder p-3">پکی بکری</h6>
                                        <h5 class="border-dark border-bottom p-3">کل رقم</h5>
                                        <h5 class="border-dark border-bottom p-3">کھرچہ</h5>
                                        <h6 class="fw-bolder p-3">صافی بکری</h6>
                                    </td>
                                    <td class="p-3">
                                        <div class="bill_field mb-2">
                                            <input type="text" name="commission" class="border-0 border-dark border-bottom text-center rounded-0" style="outline: none;">
                                            <h4 class="d-inline-block">کمیشن</h4>
                                        </div><!-- . bill_field -->
                                        <div class="bill_field mb-2">
                                            <input type="text" name="mazdori" class="border-0 border-dark border-bottom text-center rounded-0" style="outline: none;">
                                            <h4 class="d-inline-block">مزدوری</h4>
                                        </div><!-- . bill_field -->
                                        <div class="bill_field mb-2">
                                            <input type="text" name="market_fee" class="border-0 border-dark border-bottom text-center rounded-0" style="outline: none;">
                                            <h4 class="d-inline-block">مارکیٹ فیس</h4>
                                        </div><!-- . bill_field -->
                                        <div class="bill_field mb-2">
                                            <input type="text" name="rent" class="border-0 border-dark border-bottom text-center rounded-0" style="outline: none;">
                                            <h4 class="d-inline-block">کرایہ</h4>
                                        </div><!-- . bill_field -->
                                        <div class="bill_field mb-2">
                                            <input type="text" name="total_amount" class="border-0 border-dark border-bottom text-center rounded-0" style="outline: none;">
                                            <h2 class="d-inline-block">کل رقم</h4>
                                        </div><!-- . bill_field -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- .bill_detail -->

                    <div class="bill_submit py-2 px-4">
                        <?php wp_nonce_field( 'submitting_bill', 'submitting_bill_nonce' ); ?>
                        <button class="btn btn-primary"><?php esc_html_e('Submit'); ?></button>
                    </div><!-- .bill_submit -->

                    <div class="bill_footer p-3">
                        <h3 class="text-dark text-center fw-bolder w-100 py-2">دکان نمبر 46 نئی سبزی منڈی کاہنہ کچا فیروز پور روڈ لاہور</h3>
                    </div><!-- .bill_footer -->
                </form>
            </div><!-- .bill -->
        </div><!-- .container -->
    </div><!-- .page-main-content -->
    
<?php
get_footer();