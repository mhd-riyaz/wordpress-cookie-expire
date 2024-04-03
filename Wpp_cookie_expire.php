<?php
/**
 * Plugin Name: Wpp Set Cookie Expire
 * Plugin URI: https://mhdriyaz.com/wppcookieexpire
 * Description: Using this plugin remember me in login can be enabled for long time. This plugin gives settings page to add custom value for remember me
 * Version: 1.0
 * Author: Mohamed Riyaz
 * Author URI: http://www.mhdriyaz.in
 * License: GPL2
 * 
 * Copyright YEAR  MOHAMED RIYAZ  (email : mhdryz@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} 
class Wpp_cookie_expire{
	private static $instance = null;
	/**
	 * Creates or returns an instance of this class.
	 */
	public static function Wpp_instance() {
		// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	function __construct() {
		if ( is_admin() ) :
			add_action( 'admin_init', array( $this,'Wpp_set_cookie_expire_admin') );
		endif;
		add_filter( 'auth_cookie_expiration', array(&$this,'Wpp_set_cookie_expire_filter'), 10, 3);
	}
	/*
	* Cookie expire for admin
	*/
	public function Wpp_set_cookie_expire_admin(){
		foreach ( array( 'normal' => 'Normal', 'remember' => 'Remember' ) as $type => $label ) {
			register_setting( 'general', "{$type}_cookie_expire", 'absint' );
			add_settings_field( "{$type}_cookie_expire", $label . ' cookie expire', array(&$this,'Wpp_set_cookie_expire_option'), 'general', 'default', $type );
        }
	}
	/**
	* Filter in our user-specified expire times.
	*/
	public function Wpp_set_cookie_expire_filter( $default, $user_ID, $remember ){
        if ( !$expires = get_option( $remember ? 'remember_cookie_expire' : 'normal_cookie_expire' ) )
                $expires = 0;

        if ( $expires = ( intval( $expires ) * 86400 ) ) // get seconds
                $default = $expires;
        return $default;
	}
	
	/**
	* Setting field callback.
	*/
	public function Wpp_set_cookie_expire_option( $type ){
		if ( !$expires = get_option("{$type}_cookie_expire") )
			$expires = $type === 'normal' ? 2 : 14;
        echo '<input type="text" name="' . $type . '_cookie_expire" value="' . intval( $expires ) . '" class="medium-text" /> days';
	}
}

Wpp_cookie_expire::Wpp_instance();
?>