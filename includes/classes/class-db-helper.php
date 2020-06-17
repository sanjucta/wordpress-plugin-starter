<?php

class DBHelper
{
    public function __construct()
    {
    	global $lisahub_db_version;
		$lisahub_db_version = '1.0';
    }

      
    public function addCustomTable()
    {
        global $wpdb;
        global $lisahub_db_version;

        $table_name = $wpdb->prefix . 'my_new_table';
    
        $charset_collate = $wpdb->get_charset_collate();

        if( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name )
        {
            $sql = "CREATE TABLE $table_name (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `col1` bigint(20) NOT NULL,
                
                PRIMARY KEY (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
            add_option( 'lisahub_db_version', $lisahub_db_version );
        }

	}

	public function removeCustomTable()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'my_new_table';

        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query( $sql );

        delete_option( 'lisahub_db_version' );
    
    }

	
  
}

