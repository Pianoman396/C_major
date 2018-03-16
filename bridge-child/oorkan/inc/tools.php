<?php
    security();
 ?>
<?php

if (!function_exists('comparePortfolioOptions')) {
	/**
	 * Function that compares two portfolio options for sorting
	 * @param $a int first option
	 * @param $b int second option
	 * @return int result of comparison
	 */
	function comparePortfolioOptions($a, $b){
		if (isset($a['optionlabelordernumber']) && isset($b['optionlabelordernumber'])) {
		if ($a['optionlabelordernumber'] == $b['optionlabelordernumber']) {
			return 0;
		}
		return ($a['optionlabelordernumber'] < $b['optionlabelordernumber']) ? -1 : 1;
	  }
	  return 0;
	}
}

// Move everything related to categories to category.php and require in functions.php

if (!function_exists('getPortfolionavigationPostCategoryAndTitle')) {
    /**
     * Function that compares two portfolio options for sorting
     * @param $post
     * @return html of navigation
     */
    function getPortfolionavigationPostCategoryAndTitle($post){
        $html_info = '<span class="post_info">';
        $categories = wp_get_post_terms($post->ID, 'portfolio_category');
        $html_info .= '<span class="categories">';
        $k = 1;
        foreach ($categories as $cat) {
            $html_info .= $cat->name;
            if (count($categories) != $k) {
                $html_info .= ', ';
            }
            $k++;
        }
        $html_info .= '</span>';

        if($post->post_title != '') {
            $html_info .= '<span class="h5">'.$post->post_title.'</span>';
        }
        $html_info .= '</span>';
        return $html_info;
    }
}




// himi che, heto -> poxarinel php-i sscanf function-ov

if (!function_exists('qode_hex2rgb')) {
	/**
	 * Function that transforms hex color to rgb color
	 * @param $hex string original hex string
	 * @return array array containing three elements (r, g, b)
	 */
	function qode_hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);

		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		//return implode(",", $rgb); // returns the rgb values separated by commas
		return $rgb; // returns an array with the rgb values
	}
}

if(!function_exists('qode_addslashes')) {
	/**
	 * Function that checks if magic quotes are turned on (for older versions of php) and returns escaped string
	 * @param $str string string to be escaped
	 * @return string escaped string
	 */
	function qode_addslashes($str) {

		$str = addslashes($str);

		return $str;
	}
}



if(!function_exists('qode_is_product_category')) {
	function qode_is_product_category() {
		return function_exists('is_product_category') && is_product_category();
	}
}




if(!function_exists('qode_is_wpml_installed')) {
	/**
	 * Function that checks if WPML plugin is installed
	 * @return bool
	 *
	 * @version 0.1
	 */
	function qode_is_wpml_installed() {
		return defined('ICL_SITEPRESS_VERSION');
	}
}

if(!function_exists('qode_is_css_folder_writable')) {
	/**
	 * Function that checks if css folder is writable
	 * @return bool
	 *
	 * @version 0.1
	 * @uses is_writable()
	 */
	function qode_is_css_folder_writable() {
		$css_dir = get_template_directory().'/css';

		return is_writable($css_dir);
	}
}

if(!function_exists('qode_is_js_folder_writable')) {
	/**
	 * Function that checks if js folder is writable
	 * @return bool
	 *
	 * @version 0.1
	 * @uses is_writable()
	 */
	function qode_is_js_folder_writable() {
		$js_dir = get_template_directory().'/js';

		return is_writable($js_dir);
	}
}

if(!function_exists('qode_assets_folders_writable')) {
	/**
	 * Function that if css and js folders are writable
	 * @return bool
	 *
	 * @version 0.1
	 * @see qode_is_css_folder_writable()
	 * @see qode_is_js_folder_writable()
	 */
	function qode_assets_folders_writable() {
		return qode_is_css_folder_writable() && qode_is_js_folder_writable();
	}
}

// gnac shortcodes.php

 if(!function_exists('qode_has_shortcode')) {
	/**
	 * Function that checks whether shortcode exists on current page / post
	 * @param string shortcode to find
	 * @param string content to check. If isn't passed current post content will be used
	 * @return bool whether content has shortcode or not
	 */
	function qode_has_shortcode($shortcode, $content = '') {
		$has_shortcode = false;

		if ($shortcode) {
			//if content variable isn't past
			if($content == '') {
				//take content from current post
				$current_post = get_post(get_the_ID());
				$content = $current_post->post_content;
			}

			//does content has shortcode added?
			if (stripos($content, '[' . $shortcode) !== false) {
				$has_shortcode = true;
			}
		}

		return $has_shortcode;
	}
}



if(!function_exists('qode_rgba_color')) {
	/**
	 * Function that generates rgba part of css color property
	 * @param $color string hex color
	 * @param $transparency float transparency value between 0 and 1
	 * @return string generated rgba string
	 */
	function qode_rgba_color($color, $transparency) {
		if($color !== '' && $transparency !== '') {
			$rgba_color = '';

			$rgb_color_array = qode_hex2rgb($color);
			$rgba_color .= 'rgba('.implode(', ', $rgb_color_array).', '.$transparency.')';

			return $rgba_color;
		}
	}
}





if(!function_exists('qode_visual_composer_custom_shortcodce_css')){
	function qode_visual_composer_custom_shortcodce_css(){
		if(qode_visual_composer_installed()){
			if(is_page() || is_single() || is_singular('portfolio_page')){
				$shortcodes_custom_css = get_post_meta( qode_get_page_id(), '_wpb_shortcodes_custom_css', true );
				if ( ! empty( $shortcodes_custom_css ) ) {
					echo '<style type="text/css" data-type="vc_shortcodes-custom-css-'.qode_get_page_id().'">';
					echo $shortcodes_custom_css;
					echo '</style>';
				}
				$post_custom_css = get_post_meta( qode_get_page_id(), '_wpb_post_custom_css', true );
				if ( ! empty( $post_custom_css ) ) {
					echo '<style type="text/css" data-type="vc_custom-css-'.qode_get_page_id().'">';
					echo $post_custom_css;
					echo '</style>';
				}
			}
		}
	}
	add_action('qode_visual_composer_custom_shortcodce_css', 'qode_visual_composer_custom_shortcodce_css');
}

if (!function_exists('qode_vc_grid_elements_enabled')) {

	/**
	 * Function that checks if Visual Composer Grid Elements are enabled
	 *
	 * @return bool
	 */
	function qode_vc_grid_elements_enabled() {

		global $qode_options_proya;
		$vc_grid_enabled = false;

		if (isset($qode_options_proya['enable_grid_elements']) && $qode_options_proya['enable_grid_elements'] == 'yes') {

			$vc_grid_enabled = true;

		}

		return $vc_grid_enabled;

	}

}

if(!function_exists('qode_visual_composer_grid_elements')) {

	/**
	 * Removes Visual Composer Grid Elements post type if VC Grid option disabled
	 * and enables Visual Composer Grid Elements post type
	 * if VC Grid option enabled
	 */
	function qode_visual_composer_grid_elements() {

		global $qode_options_proya;

		if(!qode_vc_grid_elements_enabled()){

			remove_action( 'init', 'vc_grid_item_editor_create_post_type' );

		}
	}

	add_action('vc_after_init', 'qode_visual_composer_grid_elements', 12);
}



if(!function_exists('qode_get_vc_version')) {
    /**
     * Return Visual Composer version string
     *
     * @return bool|string
     */
    function qode_get_vc_version()
    {
        if (qode_visual_composer_installed()) {
            return WPB_VC_VERSION;
        }

        return false;
    }
}


if(!function_exists('qode_set_blog_word_count')) {
	/**
	* Function that sets global blog word count variable used by qode_excerpt function
	*/
	function qode_set_blog_word_count($word_count_param) {
		global $word_count;

		$word_count = $word_count_param;
	}
}



if (!function_exists('compareSlides')) {
	function compareSlides($a, $b){
		if (isset($a['ordernumber']) && isset($b['ordernumber'])) {
		if ($a['ordernumber'] == $b['ordernumber']) {
			return 0;
		}
		return ($a['ordernumber'] < $b['ordernumber']) ? -1 : 1;
	  }
	  return 0;
	}
}


 ?>