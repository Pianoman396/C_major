<?php
require_once("oorkan/inc/variables.php");
//$qode_toolbar = true;
//$qode_landing = true;
//$qode_tour_popup = true;

include_once get_template_directory().'/theme-includes.php';


if(isset($qode_toolbar) && $qode_toolbar === true) {
	if (!function_exists('myStartSession')) {
		/**
		 * Function that sets session after theme is activated hook. Hooks to after_setup_theme action
		 */
		function myStartSession() {
			if(!session_id()) {
				session_start();
			}
			if (!empty($_GET['animation'])) {
				$_SESSION['qode_animation'] = $_GET['animation'];
			}

			if (isset($_SESSION['qode_animation']) && $_SESSION['qode_animation'] == "off") {
				$_SESSION['qode_animation'] = "";
			}
		}

		add_action('after_setup_theme', 'myStartSession', 1);
	}

	if (!function_exists('myEndSession')) {
		/**
		 * Function that ends session on wp_login and wp_logout action
		 */
		function myEndSession() {
			session_destroy();
		}

		add_action('wp_logout', 'myEndSession');
		add_action('wp_login', 'myEndSession');
	}
}



/* OORKAN */
	// require styles and scripts
	/*
		1. davidq->eric - store this path oorkan/inc/ in variable  ** doed
		2. remove theme- prefix from inc files ** doed
		3. include security in theme files (if(!defined("ABSPATH"))) ** doed
		4. create variables.php and define the path oorkan/inc/ in it ** doed
		5. require variables.php at the beggining ** doed
		6. WooCommerce doesn't exist, try to play and comment unnecessarry phps ** doed
	*/

	function security(){
    if(!defined("ABSPATH"))
        die("Can't watch");
	}

	require_once(INC_PATH . "/scripts.php");
	require_once(INC_PATH . "/google-scripts.php");
	require_once(INC_PATH . "/admin-scripts.php");
	require_once(INC_PATH . "/ajax.php");

	require_once(INC_PATH . "/woocommerce.php");
	require_once(INC_PATH . "/meta.php");
	require_once(INC_PATH . "/front.php");
	require_once(INC_PATH . "/image.php");
	require_once(INC_PATH . "/tools.php");
	require_once(INC_PATH . "/title.php");
	require_once(INC_PATH . "/pages.php");
	require_once(INC_PATH . "/menu.php");
/* OORKAN */






if (!isset( $content_width )) $content_width = 1060;






/* Excerpt more */

if (!function_exists('qode_excerpt_more')) {
	/**
	 * Function that adds three dots on excerpt
	 * @param $more string current more string
	 * @return string changed more string
	 */
	function qode_excerpt_more( $more ) {
		return '...';
	}
	add_filter('excerpt_more', 'qode_excerpt_more');
}

if (!function_exists('qode_excerpt_length')) {
	/**
	 * Function that changes excerpt length based on theme options
	 * @param $length int original value
	 * @return int changed value
	 */
	function qode_excerpt_length( $length ) {
		global $qode_options_proya;
		if($qode_options_proya['number_of_chars']){
			 return $qode_options_proya['number_of_chars'];
		} else {
			return 45;
		}
	}

	add_filter( 'excerpt_length', 'qode_excerpt_length', 999 );
}

if (!function_exists('the_excerpt_max_charlength')) {
	/**
	 * Function that sets character length for social share shortcode
	 * @param $charlength string original text
	 * @return string shortened text
	 */
	function the_excerpt_max_charlength($charlength) {
		global $qode_options_proya;
		if(isset($qode_options_proya['twitter_via']) && !empty($qode_options_proya['twitter_via'])) {
			$via = " via " . $qode_options_proya['twitter_via'] . " ";
		} else {
			$via = 	"";
		}
		$excerpt = get_the_excerpt();
		$charlength = 140 - (mb_strlen($via) + $charlength);

		if ( mb_strlen( $excerpt ) > $charlength ) {
			$subex = mb_substr( $excerpt, 0, $charlength);
			$exwords = explode( ' ', $subex );
			$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			if ( $excut < 0 ) {
				return mb_substr( $subex, 0, $excut );
			} else {
				return $subex;
			}
		} else {
			return $excerpt;
		}
	}
}

if(!function_exists('qode_excerpt')) {
	/**
	* Function that cuts post excerpt to the number of word based on previosly set global
	* variable $word_count, which is defined in qode_set_blog_word_count function.
	 *
	 * It current post has read more tag set it will return content of the post, else it will return post excerpt
	 *
	 * @changed in 4.3 version
	*/
	function qode_excerpt() {
		global $qode_options_proya, $word_count, $post;

		//does current post has read more tag set?
		if(qode_post_has_read_more()) {
			global $more;

			//override global $more variable so this can be used in blog templates
			$more = 0;
			echo get_the_content('');
		}

		//is word count set to something different that 0?
        elseif($word_count != '0') {
			//if word count is set and different than empty take that value, else that general option from theme options
            $word_count = isset($word_count) && $word_count !== "" ? $word_count : $qode_options_proya['number_of_chars'];

			//if post excerpt field is filled take that as post excerpt, else that content of the post
            $post_excerpt = $post->post_excerpt != "" ? $post->post_excerpt : strip_tags($post->post_content);

			//remove leading dots if those exists
            $clean_excerpt = strlen($post_excerpt) && strpos($post_excerpt, '...') ? strstr($post_excerpt, '...', true) : $post_excerpt;

			//if clean excerpt has text left
			if($clean_excerpt !== '') {
				//explode current excerpt to words
				$excerpt_word_array = explode (' ', $clean_excerpt);

				//cut down that array based on the number of the words option
				$excerpt_word_array = array_slice ($excerpt_word_array, 0, $word_count);

				//add exerpt postfix
				$excert_postfix		= apply_filters('qode_excerpt_postfix', '...');

				//and finally implode words together
				$excerpt 			= implode (' ', $excerpt_word_array).$excert_postfix;

				//is excerpt different than empty string?
				if($excerpt !== '') {
					echo '<p itemprop="description" class="post_excerpt">'.$excerpt.'</p>';
				}
			}
        }
	}
}



if(!function_exists('qode_get_attachment_id_from_url')) {
	/**
	 * Function that retrieves attachment id for passed attachment url
	 * @param $attachment_url
	 * @return null|string
	 */
	function qode_get_attachment_id_from_url($attachment_url) {
		global $wpdb;
		$attachment_id = '';

		//is attachment url set?
		if($attachment_url !== '') {
			//prepare query
			$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$attachment_url'";

			//get attachment id
			$attachment_id = $wpdb->get_var($query);
		}

		//return it
		return $attachment_id;
	}
}



if(!function_exists('qode_seo_plugin_installed')) {
	/**
	 * Function that checks if popular seo plugins are installed
	 * @return bool
	 */
	function qode_seo_plugin_installed() {
		//is YOAST installed?
		if(defined('WPSEO_VERSION')) {
			return true;
		}

		return false;
	}
}



if(!function_exists('qode_contact_form_7_installed')) {
	/**
	 * Function that checks if contact form 7 installed
	 * @return bool
	 */
	function qode_contact_form_7_installed() {
		//is Contact Form 7 installed?
		if(defined('WPCF7_VERSION')) {
			return true;
		}

		return false;
	}
}

if(!function_exists('qode_revolution_slider_installed')) {
	/**
	 * Function that checks if revolution slider installed
	 * @return bool
	 */
	function qode_revolution_slider_installed() {
		//is Revolution Slider installed?
		if(class_exists('RevSliderFront')) {
			return true;
		}
		return false;
	}
}

if(!function_exists('qode_layer_slider_installed')) {
	/**
	 * Function that checks if layer slider installed
	 * @return bool
	 */
	function qode_layer_slider_installed() {
		//is Layer Slider installed?
		if(defined('LS_PLUGIN_VERSION')) {
			return true;
		}
		return false;
	}
}

if(!function_exists('qode_timetable_schedule_installed')) {
	/**
	 * Function that checks if timetable installed
	 * @return bool
	 */
	function qode_timetable_schedule_installed() {
		//checking for this dummy function because plugin doesn't have constant or class
		//that we can hook to. Poorly coded plugin
		return function_exists('timetable_load_textdomain');
	}
}

if(!function_exists('qode_post_has_read_more')) {
	/**
	 * Function that checks if current post has read more tag set
	 * @return int position of read more tag text. It will return false if read more tag isn't set
	 */
	function qode_post_has_read_more() {
		global $post;

		return strpos($post->post_content, '<!--more-->');
	}
}



if(!function_exists('rewrite_rules_on_theme_activation')) {
	/**
	 * Function that sets rewrite rules when our theme is activated
	 */
	function rewrite_rules_on_theme_activation() {
		flush_rewrite_rules();
	}

	add_action( 'after_switch_theme', 'rewrite_rules_on_theme_activation' );
}

if(!function_exists('qode_maintenance_mode')) {
    /**
     * Function that redirects user to desired landing page if maintenance mode is turned on in options
     */
    function qode_maintenance_mode() {
        global $qode_options_proya;

        $protocol = is_ssl() ? "https://" : "http://";
        if(isset($qode_options_proya['qode_maintenance_mode']) && $qode_options_proya['qode_maintenance_mode'] == 'yes' && isset($qode_options_proya['qode_maintenance_page']) && $qode_options_proya['qode_maintenance_page'] != ""
        && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))
        && !is_admin()
        && !is_user_logged_in()
        && $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] != get_permalink($qode_options_proya['qode_maintenance_page'])
        ) {

            wp_redirect(get_permalink($qode_options_proya['qode_maintenance_page']));
            exit;
        }
    }

    if(isset($qode_options_proya['qode_maintenance_mode']) && $qode_options_proya['qode_maintenance_mode'] == 'yes') {
        add_action('init', 'qode_maintenance_mode', 1);
    }
}

if(!function_exists('qode_visual_composer_installed')) {
	/**
	 * Function that checks if visual composer installed
	 * @return bool
	 */
	function qode_visual_composer_installed() {
		//is Visual Composer installed?
		if(class_exists('WPBakeryVisualComposerAbstract')) {
			return true;
		}

		return false;
	}
}

 ?>