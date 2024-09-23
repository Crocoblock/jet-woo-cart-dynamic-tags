<?php
/**
 * Plugin Name: Jet WooCommerce Cart Dynamic Tags
 * Plugin URI: #
 * Description: Adds a couple of useful Elementor Dynamic Tags to work with WooCommerce Cart content, like Cart subtotal & Cart counts.
 * Version: 1.0
 * Author: Arongod
 * Author URI: #
 * License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jet_Woo_Cart_Dynamic_Tags {

	public function __construct() {
		add_action( 'init', [ $this, 'init_plugin' ], 0 );
	}

	public function init_plugin() {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		add_action( 'elementor/dynamic_tags/register', [ $this, 'register_dynamic_tags' ] );
		add_action( 'elementor/dynamic_tags/register', [ $this, 'register_dynamic_tag_group' ] );

		add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'update_woo_cart_fragments' ] );
	}

	public function register_dynamic_tags( $dynamic_tags_manager ) {
		require_once __DIR__ . '/includes/tags/jet-woo-cart-subtotal-tag.php';
		require_once __DIR__ . '/includes/tags/jet-woo-cart-count-tag.php';

		$dynamic_tags_manager->register( new \Jet_Woo_Card_Subtotal_Tag() );
		$dynamic_tags_manager->register( new \Jet_Woo_Card_Count_Tag() );
	}

	public function register_dynamic_tag_group( $dynamic_tags_manager ) {
		$dynamic_tags_manager->register_group(
			'jetwoo',
			[
				'title' => esc_html__( 'JetWoo', 'jetwoo' ),
			]
		);
	}
	public function update_woo_cart_fragments( $fragments ) {
		$fragment_1 = WC()->cart->get_cart_subtotal();
		$fragment_2 = WC()->cart->get_cart_contents_count();

		ob_start();
		?>
		<span class="jet_woo_cart_subtotal_tag"><?php echo wp_kses_post( $fragment_1 ); ?></span>
		<?php
		$fragments['span.jet_woo_cart_subtotal_tag'] = ob_get_clean();

		ob_start();
		?>
		<span class="jet_woo_cart_count_tag"><?php echo esc_html( $fragment_2 ); ?></span>
		<?php
		$fragments['span.jet_woo_cart_count_tag'] = ob_get_clean();

		return $fragments;
	}
}

new Jet_Woo_Cart_Dynamic_Tags();
