<?php
/**
* Plugin Name:	Light and Smart
* Plugin URI:	https://extremo.dev
* Description:	Lighten your Wordpress website by optimizing source code
* Text Domain:	light-and-smart
* Domain Path:  /languages
* Version:		0.1.0
* Author:		Emil Emilov
* Author URI:	https://extremo.dev/aic
* License:		GPL2
*
Light & Smart is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Light & Smart is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Light & Smart. If not, see https://www.gnu.org/licenses/gpl-2.0.html.

* @package     light-and-smart
*/

/* ====================== */

function light_and_smart_admin_style() {
    wp_enqueue_style('light_and_smart-admin-style', plugins_url('assets/css/admin-style.css', __FILE__));
    //    wp_enqueue_script('ls-admin-script', plugins_url('assets/js/script.js', __FILE__));
}
add_action('admin_enqueue_scripts', 'light_and_smart_admin_style');
add_action('login_enqueue_scripts', 'light_and_smart_admin_style');


class LightAndSmartSettings {
	private $lasm_settings_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'light_and_smart_settings_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'light_and_smart_settings_page_init' ) );
	}

	public function light_and_smart_settings_add_plugin_page() {
		add_options_page(
			'Light & Smart Settings', // page_title
			'Light & Smart Settings', // menu_title
			'manage_options', // capability
			'light-smart-settings', // menu_slug
			array( $this, 'light_and_smart_settings_create_admin_page' ) // function
		);
	}

	public function light_and_smart_settings_create_admin_page() {
		$this->lasm_settings_options = get_option( 'lasm_settings_option_name' ); ?>

		<div class="wrap">
			<h2>Light & Smart Settings</h2>
			<p>Please, select your options.</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'lasm_settings_option_group' );
					do_settings_sections( 'lasm-settings-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function light_and_smart_settings_page_init() {
		register_setting(
			'lasm_settings_option_group', // option_group
			'lasm_settings_option_name', // option_name
			array( $this, 'light_and_smart_settings_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'lasm_settings_setting_section', // id
			'Make your Wordpress website lighter', // title
			array( $this, 'light_and_smart_settings_section_info' ), // callback
			'lasm-settings-admin' // page
		);

		add_settings_field(
			'remove_wp_emojies_0', 
			'Remove WP emojies?', 
			array( $this, 'light_and_smart_remove_wp_emojies_0_callback' ),
			'lasm-settings-admin',
			'lasm_settings_setting_section'
		);

		add_settings_field(
			'remove_generator_meta_tag_1',
			'Remove "generator" meta tag',
			array( $this, 'light_and_smart_remove_generator_meta_tag_1_callback' ),
			'lasm-settings-admin',
			'lasm_settings_setting_section'
		);

		add_settings_section(
			'lasm_settings_enhance_section',
			'Enhance your Wordpress website by adding some analytics',
			array( $this, 'light_and_smart_settings_enhance_info' ),
			'lasm-settings-admin'
		);

		add_settings_field(
			'google_analytics_id_2',
			'Google Analytics ID, i.e. UA-123456789-1',
			array( $this, 'light_and_smart_google_analytics_id_2_callback' ),
			'lasm-settings-admin',
			'lasm_settings_enhance_section'
		);

		add_settings_field(
			'google_tag_manager_id_3',
			'Google Tag Manager ID, i.e. GTM-1234567',
			array( $this, 'light_and_smart_google_tag_manager_id_3_callback' ),
			'lasm-settings-admin',
			'lasm_settings_enhance_section'
		);

		add_settings_field(
			'facebook_pixel_id_4',
			'Facebook Pixel ID',
			array( $this, 'light_and_smart_facebook_pixel_id_4_callback' ),
			'lasm-settings-admin',
			'lasm_settings_enhance_section'
		);
	}

	public function light_and_smart_settings_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['remove_wp_emojies_0'] ) ) {
			$sanitary_values['remove_wp_emojies_0'] = $input['remove_wp_emojies_0'];
		}

		if ( isset( $input['remove_generator_meta_tag_1'] ) ) {
			$sanitary_values['remove_generator_meta_tag_1'] = $input['remove_generator_meta_tag_1'];
		}

		if ( isset( $input['google_analytics_id_2'] ) ) {
			$sanitary_values['google_analytics_id_2'] = sanitize_text_field( $input['google_analytics_id_2'] );
		}

		if ( isset( $input['google_tag_manager_id_3'] ) ) {
			$sanitary_values['google_tag_manager_id_3'] = sanitize_text_field( $input['google_tag_manager_id_3'] );
		}

		if ( isset( $input['facebook_pixel_id_4'] ) ) {
			$sanitary_values['facebook_pixel_id_4'] = sanitize_text_field( $input['facebook_pixel_id_4'] );
		}

		return $sanitary_values;
	}

	public function light_and_smart_settings_section_info() {
		
	}

	public function light_and_smart_settings_enhance_info() {
		?>
			<div class="alert alert-warning" role="alert">Please note:
				<ul class="ls">
					<li>If your theme does not support wp_body_open function, the 'noscript' part of Google Tag Manager code will not be added. Still, the added code should be working in most conditions;</li>
					<li>For a more grained control over what your Facebook pixel does you may want to use the official Wordpress integration plugin from Facebook</li>
				</ul>
			</div>
		<?php
	}

	public function light_and_smart_remove_wp_emojies_0_callback() {
		printf(
			'<input type="checkbox" name="lasm_settings_option_name[remove_wp_emojies_0]" id="remove_wp_emojies_0" value="remove_wp_emojies_0" %s> <label for="remove_wp_emojies_0">Check to remove code for WP emojies</label>',
			( isset( $this->lasm_settings_options['remove_wp_emojies_0'] ) && $this->lasm_settings_options['remove_wp_emojies_0'] === 'remove_wp_emojies_0' ) ? 'checked' : ''
		);
	}

	public function light_and_smart_remove_generator_meta_tag_1_callback() {
		printf(
			'<input type="checkbox" name="lasm_settings_option_name[remove_generator_meta_tag_1]" id="remove_generator_meta_tag_1" value="remove_generator_meta_tag_1" %s> <label for="remove_generator_meta_tag_1">Check to remove the "generator" meta tag</label>',
			( isset( $this->lasm_settings_options['remove_generator_meta_tag_1'] ) && $this->lasm_settings_options['remove_generator_meta_tag_1'] === 'remove_generator_meta_tag_1' ) ? 'checked' : ''
		);
	}

	public function light_and_smart_google_analytics_id_2_callback() {
		printf(
			'<input class="regular-text" type="text" name="lasm_settings_option_name[google_analytics_id_2]" id="google_analytics_id_2" value="%s">',
			isset( $this->lasm_settings_options['google_analytics_id_2'] ) ? esc_attr( $this->lasm_settings_options['google_analytics_id_2']) : ''
		);
	}

	public function light_and_smart_google_tag_manager_id_3_callback() {
		printf(
			'<input class="regular-text" type="text" name="lasm_settings_option_name[google_tag_manager_id_3]" id="google_tag_manager_id_3" value="%s">',
			isset( $this->lasm_settings_options['google_tag_manager_id_3'] ) ? esc_attr( $this->lasm_settings_options['google_tag_manager_id_3']) : ''
		);
	}

	public function light_and_smart_facebook_pixel_id_4_callback() {
		printf(
			'<input class="regular-text" type="text" name="lasm_settings_option_name[facebook_pixel_id_4]" id="facebook_pixel_id_4" value="%s">',
			isset( $this->lasm_settings_options['facebook_pixel_id_4'] ) ? esc_attr( $this->lasm_settings_options['facebook_pixel_id_4']) : ''
		);
	}

}
if ( is_admin() )
	$lasm_settings = new LightAndSmartSettings();

/* Adding FB OG, Google Analytics & FB Pixel tags to posts 
============================*/
function light_and_smart_doctype_opengraph($output) {
    return $output . '
    xmlns:og="http://opengraphprotocol.org/schema/"
    xmlns:fb="http://www.facebook.com/2008/fbml"';
}
add_filter('language_attributes', 'light_and_smart_doctype_opengraph');

// Array of All Options
$light_and_smart_options = get_option( 'lasm_settings_option_name' );

// If Remove WP emojies set to TRUE
if ( $remove_wp_emojies_0 = $light_and_smart_options['remove_wp_emojies_0'] ) { 
	/**
	* Disable the emoji's
	*/

	function light_and_smart_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'light_and_smart_disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'light_and_smart_disable_emojis_remove_dns_prefetch', 10, 2 );
	}
	add_action( 'init', 'light_and_smart_disable_emojis' );

	/**
	* Filter function used to remove the tinymce emoji plugin.
	*
	* @param array $plugins
	* @return array Difference betwen the two arrays
	*/


	function light_and_smart_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
	return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
	return array();
	}
	}

	/**
	* Remove emoji CDN hostname from DNS prefetching hints.
	*
	* @param array $urls URLs to print for resource hints.
	* @param string $relation_type The relation type the URLs are printed for.
	* @return array Difference betwen the two arrays.
	*/

	function light_and_smart_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' == $relation_type ) {
	/** This filter is documented in wp-includes/formatting.php */

	$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

	$urls = array_diff( $urls, array( $emoji_svg_url ) );
	}

	return $urls;
	}
} // END Remove emojies scripts

// If Remove WP generator set to TRUE
if ( $remove_generator_meta_tag_1 = $light_and_smart_options['remove_generator_meta_tag_1'] ) { 
	remove_action('wp_head', 'wp_generator');
}

/* If any of the enhance options is empty, no relevant code is added 
* and changes are not applied to website 
*/

/* **===Adding Google Analytics gtag.js===** */
if ( $gaid = $light_and_smart_options['google_analytics_id_2'] ) {

	function light_and_smart_add_GAnal() {
		global $gaid;
	?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $gaid; ?>"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', '<?php echo $gaid; ?>');
	</script>

	<?php
	}

	add_action('wp_head', 'light_and_smart_add_GAnal', 3);
}

// **===Adding Google Tag Manager===** /
if ( $gtmid = $light_and_smart_options['google_tag_manager_id_3'] ) {
	
	function light_and_smart_add_GTM() {
		global $gtmid;
	?>
	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo $gtmid; ?>');</script>
<!-- End Google Tag Manager -->
	<?php
	}

	function light_and_smart_add_GTM_noscript() {
		global $gtmid;
	?>
	<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $gtmid; ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<?php
	}

	add_action('wp_head', 'light_and_smart_add_GTM', 4);
	add_action( 'wp_body_open', 'light_and_smart_add_GTM_noscript' );	
}

if ( $fbid = $light_and_smart_options['facebook_pixel_id_4'] ) {
	
	function light_and_smart_fb_pixel() {
		global $fbid;
	
	?>
	<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
	n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
	document,'script','https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '<?php echo $fbid; ?>'); // Insert your pixel ID here.
	fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=<?php echo $fbid; ?>&ev=PageView&noscript=1"
	/></noscript>
	<!-- DO NOT MODIFY -->
	<!-- End Facebook Pixel Code -->

	<?php
	}
	add_action('wp_head', 'light_and_smart_fb_pixel', 10);
}

/* --END header meta tags--- */

?>
