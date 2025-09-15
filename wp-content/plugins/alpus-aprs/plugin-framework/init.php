<?php
/**
 * Name: Alpus Plugin Framework
 * Version: 1.3.0
 * Author: AlpusTheme
 * Domain: alpus-plugin-framework
 *
 * @author AlpusTheme
 * @package Alpus Plugin Framework
 * @version 1.3.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'alpus_plugin_framework_loader' ) ) {
	function alpus_plugin_framework_loader( $plugin_path ) {
		global $alpus_plugin_fw_data;

		$format = array(
			'name'    => 'Name',
			'version' => 'Version',
		);

		$framework_data = get_file_data( $plugin_path . 'plugin-framework/init.php', $format );

		if ( ! empty( $alpus_plugin_fw_data ) && ! empty( $framework_data['version'] ) ) {
			foreach ( $alpus_plugin_fw_data as $version => $path ) {
				if ( version_compare( $version, $framework_data['version'], '<' ) ) {
					$alpus_plugin_fw_data = array(
						$framework_data['version'] => $plugin_path . 'plugin-framework/class-plugin-framework.php',
					);
				}
			}
		} else {
			$alpus_plugin_fw_data = array(
				$framework_data['version'] => $plugin_path . 'plugin-framework/class-plugin-framework.php',
			);
		}
	}
}
