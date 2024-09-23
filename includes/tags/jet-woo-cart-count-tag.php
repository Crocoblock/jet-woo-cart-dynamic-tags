<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Jet_Woo_Card_Count_Tag extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'jet-woo-cart-count-tag';
	}

	public function get_title() {
		return __( 'WooCommerce Cart Count', 'jet-woo-cart' );
	}

	public function get_group() {
		return [ 'jetwoo' ];
	}

	public function get_categories() {
		return array(
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
		);
	}

	public function is_settings_required() {
		return true;
	}

	public function register_controls() {
		$this->add_control(
			'output_format',
			array(
				'label'   => __( 'Output Format', 'jet-woo-cart' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'plain',
				'options' => array(
					'html'  => __( 'Formatted HTML', 'jet-woo-cart' ),
					'plain' => __( 'Plain Data', 'jet-woo-cart' ),
				),
				'description' => __( 'If you select <b>Formatted HTML</b>, the value updates automatically via AJAX when a product is added to or removed from the cart. If you select <b>Plain Data</b>, the value updates only after the page reloads.', 'jet-woo-cart' ),
			)
		);
	}

	public function get_output_format() {
		return $this->get_settings( 'output_format' );
	}

	public function render() {
		if ( WC()->cart && ! is_null( WC()->cart ) ) {
			$format = $this->get_output_format();

			switch ( $format ) {
				case 'html':
					?>
					<span class="jet_woo_cart_count_tag"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
					<?php
					break;

				case 'plain':
				default:
					echo esc_html( WC()->cart->get_cart_contents_count() );
					break;
			}
		} else {
			echo esc_html__( 'Cart is empty or unavailable', 'jet-woo-cart-dynamic-tags' );
		}
	}
}
