<?php

global $wpdb;
$wpdb->shopkeepers = $wpdb->prefix . 'shopkeepers';
$wpdb->shopkeeper_meta_data = $wpdb->prefix . 'shopkeeper_meta_data';
$wpdb->purchase_data = $wpdb->prefix . 'purchase_data';
$wpdb->purchase_meta_data = $wpdb->prefix . 'purchase_meta_data';
$wpdb->shopkeepr_payments = $wpdb->prefix . 'shopkeepr_payments';
$wpdb->shopkeepr_payments_meta_data = $wpdb->prefix . 'shopkeepr_payments_meta_data';
$wpdb->customer_data = $wpdb->prefix . 'customer_data';
$wpdb->customer_meta_data = $wpdb->prefix . 'customer_meta_data';
$wpdb->customer_invoice = $wpdb->prefix . 'customer_invoice';
$wpdb->customer_payments = $wpdb->prefix . 'customer_payments';

$fst_theme_version = 1;
// $fst_theme_version = generate_random_int(2);
// update_option('fst_theme_version', '');
$installed_ver = get_option('fst_theme_version');

if ( !$installed_ver && $fst_theme_version == 1) {
	update_option( 'fst_theme_version', $fst_theme_version );
	$sql = "DROP TABLE IF EXISTS {$wpdb->shopkeepers}, {$wpdb->shopkeeper_meta_data}, {$wpdb->purchase_data}, {$wpdb->shopkeepr_payments}, {$wpdb->shopkeepr_payments_meta_data}, {$wpdb->customer_data}, {$wpdb->customer_meta_data}, {$wpdb->customer_invoice}, {$wpdb->customer_payments}";
	$wpdb->query ($sql);

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE ".$wpdb->shopkeepers." (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		serial_number bigint(20) unsigned NULL,
		shop_number bigint(20) unsigned NULL,
		shopkeeper_name varchar(255) NULL,
		shopkeeper_phone varchar(25) NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
		INDEX (serial_number),
		INDEX (shop_number),
		INDEX (shopkeeper_name),
		INDEX (shopkeeper_phone),
		INDEX (created_at),
		INDEX (updated_at),
		PRIMARY KEY  (ID)
	) $charset_collate;";

	$sql .= "CREATE TABLE ".$wpdb->shopkeeper_meta_data." (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		shopkeeper_id bigint(20) unsigned NULL,
		meta_key varchar(255) NULL,
		meta_value text NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
		INDEX (created_at),
		INDEX (updated_at),
		PRIMARY KEY  (ID),
		FOREIGN KEY (shopkeeper_id) REFERENCES ".$wpdb->shopkeepers."(ID) ON DELETE CASCADE
	) $charset_collate;";

	$sql .= "CREATE TABLE ".$wpdb->purchase_data." (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		serial_number bigint(20) unsigned NULL,
		shop_number bigint(20) unsigned NULL,
		purchase_date date NULL,
		product_name varchar(255) NULL,
		quantity bigint(20) unsigned NULL,
		payable_price_per_piece varchar(20) NULL,
		price bigint(20) unsigned NULL,
		expenses bigint(20) unsigned NULL,
		price_with_expense bigint(20) unsigned NULL,
		total_price bigint(20) unsigned NULL,
		shopkeeper_name varchar(255) NULL,
		shopkeeper_phone varchar(25) NULL,
		commision_percentage varchar(20) NULL,
		total_sale_amount bigint(20) unsigned NULL,
		profit_without_commission bigint(20) unsigned NULL,
		profit bigint(20) unsigned NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
		INDEX (serial_number),
		INDEX (shop_number),
		INDEX (shopkeeper_name),
		INDEX (shopkeeper_phone),
		INDEX (created_at),
		INDEX (updated_at),
		PRIMARY KEY  (ID)
	) $charset_collate;";

	$sql .= "CREATE TABLE ".$wpdb->purchase_meta_data." (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		purchase_data_id bigint(20) unsigned NULL,
		meta_key varchar(255) NULL,
		meta_value text NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
		INDEX (created_at),
		INDEX (updated_at),
		PRIMARY KEY  (ID),
		FOREIGN KEY (purchase_data_id) REFERENCES ".$wpdb->purchase_data."(ID) ON DELETE CASCADE
	) $charset_collate;";


	$sql .= "CREATE TABLE ".$wpdb->shopkeepr_payments." (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		shopkeeper_id bigint(20) unsigned NULL,
		invoice_number varchar(255) NULL,
		amount bigint(20) NULL,
		paid_date date NULL,
		payment_type varchar(50) NULL,
		invoice_desc text NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
		INDEX (paid_date),
		INDEX (payment_type),
		INDEX (created_at),
		INDEX (updated_at),
		PRIMARY KEY  (ID),
		FOREIGN KEY (shopkeeper_id) REFERENCES ".$wpdb->purchase_data."(ID) ON DELETE CASCADE
	) $charset_collate;";

	$sql .= "CREATE TABLE ".$wpdb->shopkeepr_payments_meta_data." (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		invoice_id bigint(20) unsigned NULL,
		meta_key varchar(255) NULL,
		meta_value text NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
		INDEX (created_at),
		INDEX (updated_at),
		PRIMARY KEY  (ID),
		FOREIGN KEY (invoice_id) REFERENCES ".$wpdb->shopkeepr_payments."(ID) ON DELETE CASCADE
	) $charset_collate;";


	$sql .= "CREATE TABLE ".$wpdb->customer_data." (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		serial_number bigint(20) unsigned NULL,
		shop_number bigint(20) unsigned NULL,
		name varchar(255) NULL,
		phone varchar(255) NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
		INDEX (serial_number),
		INDEX (shop_number),
		INDEX (created_at),
		INDEX (updated_at),
		PRIMARY KEY  (ID)
	) $charset_collate;";

	$sql .= "CREATE TABLE ".$wpdb->customer_meta_data." (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		customer_id bigint(20) unsigned NULL,
		meta_key varchar(255) NULL,
		meta_value text NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
		INDEX (created_at),
		INDEX (updated_at),
		PRIMARY KEY  (ID),
		FOREIGN KEY (customer_id) REFERENCES ".$wpdb->customer_data."(ID) ON DELETE CASCADE
	) $charset_collate;";


	$sql .= "CREATE TABLE ".$wpdb->customer_invoice." (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		customer_id bigint(20) unsigned NULL,
		product_id bigint(20) unsigned NULL,
		sale_date date NULL,
		quantity bigint(20) unsigned NULL,
		price_per_quantity bigint(20) unsigned NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
		INDEX (customer_id),
		INDEX (sale_date),
		INDEX (created_at),
		INDEX (updated_at),
		PRIMARY KEY  (ID),
		FOREIGN KEY (customer_id) REFERENCES ".$wpdb->customer_data."(ID) ON DELETE CASCADE
	) $charset_collate;";

	$sql .= "CREATE TABLE ".$wpdb->customer_payments." (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		customer_id bigint(20) unsigned NULL,
		date date NULL,
		payment bigint(20) unsigned NULL,
		discount bigint(20) unsigned NULL,
		payment_type varchar(50) NULL,
		invoice_desc text NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
		INDEX (customer_id),
		INDEX (date),
		INDEX (created_at),
		INDEX (updated_at),
		PRIMARY KEY  (ID),
		FOREIGN KEY (customer_id) REFERENCES ".$wpdb->customer_data."(ID) ON DELETE CASCADE
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
}