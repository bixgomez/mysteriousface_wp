<?php
/**
 * License Check class.
 *
 * @since 1.9.0
 *
 * @package SoliloquyWP
 * @author  SoliloquyWP Team <help@soliloquywp.com>
 */


/**
 * Undocumented class
 */
class Soliloquy_LicenseCheck {
	public $core;
	/**
	 * Class Constrtuctor
	 */
	public function __construct() {
		$this->core = Soliloquy::get_instance();
		$this->init();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function init() {

		$key    = $this->core->get_license_key();
		$option = get_option( 'soliloquy' );

		if ( defined( 'X_VERSION' ) ) {
			return;
		}

		if ( ! $key || ( isset( $option['is_expired'] ) && $option['is_expired'] ) ) {
			add_action( 'admin_init', array( $this, 'enqueue_styles' ) );

			add_filter( 'replace_editor', array( $this, 'output' ), 10, 2 );
			add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 11, 1 );

		}

	}

	/**
	 * Helper Method to load styles
	 *
	 * @since 1.9.0
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		// Load necessary metabox styles.
		wp_register_style( SOLILOQUY_SLUG . '-license-style', plugins_url( 'assets/css/license.css', SOLILOQUY_FILE ), array(), SOLILOQUY_VERSION );
		wp_enqueue_style( SOLILOQUY_SLUG . '-license-style' );

	}

	/**
	 * Undocumented function
	 *
	 * @param bool   $false false.
	 * @param object $post post object.
	 * @return bool
	 */
	public function output( $false, $post ) {

		if ( ! $this->core->is_soliloquy_page() ) {
			return false;
		}

		if ( 'auto-draft' !== $post->post_status ) {
			return false;
		}
		?>
<div id="soliloquy-license-check">
	<a class="close" href="<?php echo esc_url( admin_url( 'edit.php?post_type=soliloquy' ) ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 18 18">
			<path
				d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
			</path>
		</svg>
	</a>
	<div>
		<h1 class="soliloquy-logo" id="soliloquy-logo">
			<img src="<?php echo esc_url( plugins_url( 'assets/images/logo-color.png', SOLILOQUY_FILE ) ); ?>"
				alt="<?php esc_html_e( 'SoliloquyWP', 'soliloquy' ); ?>" width="339" height="auto" />
		</h1>
		<h3>Heads up! A SoliloquyWP License is required.</h3>
		<p>To create more sliders, please verify your SoliloquyWP license is active.</p>

		<div class="button-group">
			<a class="button button-primary"
				href="<?php echo esc_url( admin_url( 'edit.php?post_type=soliloquy&page=soliloquy-settings' ) ); ?>">Enter
				License Key</a>
			<a class="button button-outline"
				href="https://soliloquywp.com/pricing/?utm_source=proplugin&utm_medium=expired&utm_campaign=licensecheck">Renew Today</a>
		</div>
	</div>

</div>
<?php

		return true;
	}

	/**
	 * Removes Admin Footer from Soliloquy Pages.
	 *
	 * @since 1.9.0
	 *
	 * @param string $content Footer Content.
	 * @return string Footer Content.
	 */
	public function admin_footer( $content ) {

		if ( $this->core->is_soliloquy_page() ) {
			return '';
		}

		return $content;

	}
}

$licene_check = new Soliloquy_LicenseCheck;