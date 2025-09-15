<?php
/**
 * Alpus Plugin Class
 *
 * To create an instance:
 *
 *    CLASS_NAME::get_instance();
 *
 * To create an instance of extended class:
 *
 *    CLASS_NAME::get_child_instance();
 *
 * @author     Alpustheme
 * @package    Alpus Plugin Framework
 * @subpackage Plugin
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

abstract class Alpus_Plugin_Base {

	/**
	 * Global Instance Objects
	 *
	 * @var array $instances
	 * @since 1.0
	 * @access private
	 */
	private static $instances = array();

	/**
	 * Create or get global instance object for each child class
	 *
	 * @since 1.0
	 * @access public
	 * @return Alpus_Plugin_Base
	 */
	static function get_instance() {
		$called_class = get_called_class();
		if ( empty( self::$instances[ $called_class ] ) ) {
			self::$instances[ $called_class ] = new $called_class();
		}
		return self::$instances[ $called_class ];
	}

	/**
	 * Create or get global instance object for each child class
	 *
	 * @since 1.0
	 * @access public
	 * @return Alpus_Plugin_Base
	 */
	static function get_child_instance() {
		$called_class = get_called_class();
		if ( empty( self::$instances[ $called_class ] ) ) {
			$parent_class                     = get_parent_class( $called_class );
			self::$instances[ $called_class ] = new $called_class();
			if ( empty( self::$instances[ $parent_class ] ) ) {
				self::$instances[ $parent_class ] = self::$instances[ $called_class ];
			}
		}
		return self::$instances[ $called_class ];
	}
}
