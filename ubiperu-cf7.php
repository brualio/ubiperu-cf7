<?php
/**
 * Plugin Name: Ubiperu para Contact Form 7
 * Description: Adds a custom [ubiperu] shortcode to Contact Form 7, displaying a dropdown and handling selected data.
 * Version: 1.0
 * Author: BraulioAM
 * Text Domain: ubiperu-cf7
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CF7_Custom_Field_ubiperu {

	public $version = '0.0.1';

	public function __construct() {
		add_action( 'wpcf7_init', array( $this, 'add_ubiperu_tag' ) );
		add_filter( 'wpcf7_posted_data', array( $this, 'capture_ubigeo_field' ) );

		wp_enqueue_script( 'ubiperu_cf7', plugin_dir_url( __FILE__ ) . '/assets/js/main.js', array( 'jquery' ), $this->version, true );

		$ubigeo_json = plugin_dir_path( __FILE__ ) . 'assets/js/ubigeo-peru.min.json';
		if ( file_exists( $ubigeo_json ) ) {
			$json_data  = file_get_contents( $ubigeo_json );
			$data_array = json_decode( $json_data, true );

			wp_localize_script( 'ubiperu_cf7', 'ubiperu', array( 'ubigeos' => $data_array ) );
		} else {
			error_log( 'JSON file not found: ' . $ubigeo_json );
		}
	}

	public function add_ubiperu_tag() {
		wpcf7_add_form_tag( 'ubiperu', array( $this, 'render_ubiperu_dropdown' ) );

		wpcf7_add_form_tag( 'ubiperu1', array( $this, 'render_ubiperu_dropdown_depa' ) );
		wpcf7_add_form_tag( 'ubiperu2', array( $this, 'render_ubiperu_dropdown_prov' ) );
		wpcf7_add_form_tag( 'ubiperu3', array( $this, 'render_ubiperu_dropdown_dist' ) );
	}

	public function render_ubiperu_dropdown( $tag ) {
		$atts = array(
			'name'  => $tag->name,
			'class' => $tag->get_class_option(),
			'id'    => $tag->get_id_option(),
		);

		$atts = wpcf7_format_atts( $atts );

		$output  = render_ubiperu_dropdown_depa();
		$output .= render_ubiperu_dropdown_prov();
		$output .= render_ubiperu_dropdown_dist();

		return $output;
	}

	public function render_ubiperu_dropdown_depa() {
		ob_start();
		?>
		<select name="up_departamento" id="up_departamento">
			<option disabled selected><?php _e( 'Seleccione departamento', 'ubiperu-cf7' ); ?></option>
		</select>
		<?php
		return ob_get_clean();
	}

	public function render_ubiperu_dropdown_prov() {
		ob_start();
		?>
		<select name="up_provincia" id="up_provincia">
			<option disabled selected><?php _e( 'Seleccione provincia', 'ubiperu-cf7' ); ?></option>
		</select>
		<?php
		return ob_get_clean();
	}

	public function render_ubiperu_dropdown_dist() {
		ob_start();
		?>
		<select name="up_distrito" id="up_distrito">
			<option disabled selected><?php _e( 'Seleccione distrito', 'ubiperu-cf7' ); ?></option>
		</select>
		<?php
		return ob_get_clean();
	}

	public function capture_ubigeo_field( $posted_data ) {
		$ubiperu = array();

		if ( isset( $posted_data['up_departamento'] ) ) {
			$ubiperu[] = 'Departamento: ' . $posted_data['up_departamento'];
		}

		if ( isset( $posted_data['up_provincia'] ) ) {
			$ubiperu[] = 'Provincia: ' . $posted_data['up_provincia'];
		}

		if ( isset( $posted_data['up_distrito'] ) ) {
			$ubiperu[] = 'Distrito: ' . $posted_data['up_distrito'];
		}

		if ( ! empty( $ubiperu ) ) {
			$posted_data['ubiperu'] = implode( ', ', $ubiperu );
		}

		return $posted_data;
	}
}

function cf7_custom_field_ubiperu_init() {
	new CF7_Custom_Field_ubiperu();
}

add_action( 'plugins_loaded', 'cf7_custom_field_ubiperu_init' );