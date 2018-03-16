<?php
    security();
 ?>
<?php
	/* Add class on body boxed layout */

if (!function_exists('qode_page_loading_effect_classes')) {
	/**
	 * Function that adds class on body for page loading effect
	 * @param $classes array of current body classes
	 * @return array array of changed body classes
	 */
	function qode_page_loading_effect_classes($classes) {

		if(qode_options()->getOptionValue('page_loading_effect') == 'yes') :
			$classes[] = 'qode-page-loading-effect-enabled';
		endif;

		return $classes;
	}

	add_filter('body_class','qode_page_loading_effect_classes');
}

/* Add class on body boxed layout */

if (!function_exists('boxed_class')) {
	/**
	 * Function that adds class on body for boxed layout
	 * @param $classes array of current body classes
	 * @return array array of changed body classes
	 */
	function boxed_class($classes) {
		global $qode_options_proya;

		if(isset($qode_options_proya['boxed']) && $qode_options_proya['boxed'] == "yes" && isset($qode_options_proya['transparent_content']) && $qode_options_proya['transparent_content'] == 'no') :
			$classes[] = 'boxed';
		else:
		$classes[] ="";
		endif;

		return $classes;
	}

	add_filter('body_class','boxed_class');
}


/* Add class on body for vertical menu */

if (!function_exists('vertical_menu_class')) {

	/**
	 * Function that adds classes on body element for vertical menu
	 * @param $classes array of current body classes
	 * @return array array of changed body classes
	 */
	function vertical_menu_class($classes) {
		global $qode_options_proya;
        global $wp_query;

		if(isset($qode_options_proya['vertical_area']) && $qode_options_proya['vertical_area'] =='yes') {
            $classes[] = 'vertical_menu_enabled';

            //left menu type class?
            if(isset($qode_options_proya['vertical_area_type']) && $qode_options_proya['vertical_area_type'] != '') {
                switch ($qode_options_proya['vertical_area_type']) {
                    case 'hidden':
                        $classes[] = ' vertical_menu_hidden';
						if(isset($qode_options_proya['vertical_logo_bottom']) && $qode_options_proya['vertical_logo_bottom'] !== '') {
							$classes[] = 'vertical_menu_hidden_with_logo';
						}
                        break;
                }
            }

			if(isset($qode_options_proya['vertical_area_type']) && $qode_options_proya['vertical_area_type'] =='hidden') {
				if(isset($qode_options_proya['vertical_area_width']) && $qode_options_proya['vertical_area_width']=='width_290'){
					 $classes[] = ' vertical_menu_width_290';
				}
				elseif(isset($qode_options_proya['vertical_area_width']) && $qode_options_proya['vertical_area_width']=='width_350'){
					 $classes[] = ' vertical_menu_width_350';
				}
				elseif(isset($qode_options_proya['vertical_area_width']) && $qode_options_proya['vertical_area_width']=='width_400'){
					 $classes[] = ' vertical_menu_width_400';
				}
				else{
					$classes[] = ' vertical_menu_width_260';
				}
			}

        }

        $id = $wp_query->get_queried_object_id();

		if(qode_is_woocommerce_page()) {
			$id = get_option('woocommerce_shop_page_id');
		}

        if(isset($qode_options_proya['vertical_area_transparency']) && $qode_options_proya['vertical_area_transparency'] =='yes' && get_post_meta($id, "qode_page_vertical_area_transparency", true) != "no"){
            $classes[] = ' vertical_menu_transparency vertical_menu_transparency_on';
        }else if(get_post_meta($id, "qode_page_vertical_area_transparency", true) == "yes"){
            $classes[] = ' vertical_menu_transparency vertical_menu_transparency_on';
        }

		return $classes;
    }

	add_filter('body_class','vertical_menu_class');
}


if (!function_exists('elements_animation_on_touch_class')) {
	/**
	 * Function that adds classes on body element for disabled animations on touch devices
	 * @param $classes array of current body classes
	 * @return array array of changed body classes
	 */
	function elements_animation_on_touch_class($classes) {
		global $qode_options_proya;

		$isMobile = (bool)preg_match('#\b(ip(hone|od|ad)|android|opera m(ob|in)i|windows (phone|ce)|blackberry|tablet'.
										'|s(ymbian|eries60|amsung)|p(laybook|alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]'.
										'|mobile|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT'] );

		if(isset($qode_options_proya['elements_animation_on_touch']) && $qode_options_proya['elements_animation_on_touch'] == "no" && $isMobile == true) :
			$classes[] = 'no_animation_on_touch';
		else:
		$classes[] ="";
		endif;

		return $classes;
	}

	add_filter('body_class','elements_animation_on_touch_class');
}

/* Add class on body for content negative margin */

if (!function_exists('content_negative_margin')) {

	/**
	 * Function that adds classes on body element for negative margin for content
	 * @param $classes array of current body classes
	 * @return array array of changed body classes
	 */
	function content_negative_margin($classes) {
        global $qode_options_proya;


        if(isset($qode_options_proya['vertical_area']) && $qode_options_proya['vertical_area'] =='no' && isset($qode_options_proya['move_content_up']) && $qode_options_proya['move_content_up'] == 'yes'){
            $classes[] = 'content_top_margin';
        }


        return $classes;
    }

	add_filter('body_class','content_negative_margin');
}

if(!function_exists('qode_hidden_title_body_class')) {
	/**
	 * Function that adds class to body element if title is hidden for current page
	 * @param $classes array of currently added classes for body element
	 * @return array array of modified classes
	 */
	function qode_hidden_title_body_class($classes) {
		$page_id = qode_get_page_id();
		if($page_id) {
			if(qode_is_title_hidden()) {
				$classes[] = 'qode-title-hidden';
			}
		}

		return $classes;
	}

	add_filter('body_class', 'qode_hidden_title_body_class');
}

if(!function_exists('qode_paspartu_body_class')) {
    /**
     * Function that adds paspartu class to body.
     * @param $classes array of body classes
     * @return array with paspartu body class added
     */
    function qode_paspartu_body_class($classes) {
        global $qode_options_proya;

        if(isset($qode_options_proya['paspartu']) && $qode_options_proya['paspartu'] == 'yes') {
            $classes[] = 'paspartu_enabled';

            if((isset($qode_options_proya['paspartu_on_top']) && $qode_options_proya['paspartu_on_top'] == 'yes' && isset($qode_options_proya['paspartu_on_top_fixed']) && $qode_options_proya['paspartu_on_top_fixed'] == 'yes') ||
                (isset($qode_options_proya['vertical_area']) && $qode_options_proya['vertical_area'] == "yes" && isset($qode_options_proya['vertical_menu_inside_paspartu']) && $qode_options_proya['vertical_menu_inside_paspartu'] == 'yes')) {
                $classes[] = 'paspartu_on_top_fixed';
            }

            if((isset($qode_options_proya['paspartu_on_bottom']) && $qode_options_proya['paspartu_on_bottom'] == 'yes' && isset($qode_options_proya['paspartu_on_bottom_fixed']) && $qode_options_proya['paspartu_on_bottom_fixed'] == 'yes') ||
                (isset($qode_options_proya['vertical_area']) && $qode_options_proya['vertical_area'] == "yes" && isset($qode_options_proya['vertical_menu_inside_paspartu']) && $qode_options_proya['vertical_menu_inside_paspartu'] == 'yes')) {
                $classes[] = 'paspartu_on_bottom_fixed';
            }

            if(isset($qode_options_proya['vertical_area']) && $qode_options_proya['vertical_area'] == "yes" && isset($qode_options_proya['vertical_menu_inside_paspartu']) && $qode_options_proya['vertical_menu_inside_paspartu'] == 'no') {
                $classes[] = 'vertical_menu_outside_paspartu';
            }

            if(isset($qode_options_proya['vertical_area']) && $qode_options_proya['vertical_area'] == "yes" && isset($qode_options_proya['vertical_menu_inside_paspartu']) && $qode_options_proya['vertical_menu_inside_paspartu'] == 'yes') {
                $classes[] = 'vertical_menu_inside_paspartu';
            }

        }

        return $classes;
    }

    add_filter('body_class', 'qode_paspartu_body_class');
}

/* Add class on body depending on content width */

if (!function_exists('qode_content_width_class')) {
    /**
     * Function that adds class on body depending on content width
     * @param $classes array of current body classes
     * @return array array of changed body classes
     */
    function qode_content_width_class($classes){
        global $qode_options_proya;

        $classes[] = "";
        if (isset($qode_options_proya['initial_content_width']) && $qode_options_proya['initial_content_width'] !== "grid_1100") {
            $classes[] = 'qode_' . $qode_options_proya['initial_content_width'];
        }
        return $classes;
    }

    add_filter('body_class','qode_content_width_class');
}

if(!function_exists('qode_side_menu_body_class')) {
	/**
	 * Function that adds body classes for different side menu styles
	 * @param $classes array original array of body classes
	 * @return array modified array of classes
	 */
    function qode_side_menu_body_class($classes) {
            global $qode_options_proya;

			if(isset($qode_options_proya['enable_side_area']) && $qode_options_proya['enable_side_area'] == 'yes') {

					if(isset($qode_options_proya['side_area_type']) && $qode_options_proya['side_area_type'] == 'side_menu_slide_from_right') {
						$classes[] = 'side_menu_slide_from_right';
					}

					else if(isset($qode_options_proya['side_area_type']) && $qode_options_proya['side_area_type'] == 'side_menu_slide_with_content') {
						$classes[] = 'side_menu_slide_with_content';
						$classes[] = $qode_options_proya['side_area_slide_with_content_width'];
				   }

				   else {
						$classes[] = 'side_area_uncovered_from_content';
					}
			}

        return $classes;
    }

    add_filter('body_class', 'qode_side_menu_body_class');
}

if(!function_exists('qode_full_screen_menu_body_class')) {
    /**
     * Function that adds body classes for different full screen menu types
     * @param $classes array original array of body classes
     * @return array modified array of classes
     */
    function qode_full_screen_menu_body_class($classes) {
        global $qode_options_proya;

        if(isset($qode_options_proya['enable_popup_menu']) && $qode_options_proya['enable_popup_menu'] == 'yes') {
            if(isset($qode_options_proya['popup_menu_animation_style']) && !empty($qode_options_proya['popup_menu_animation_style'])) {
                $classes[] = 'qode_' . $qode_options_proya['popup_menu_animation_style'];
            }
        }

        return $classes;
    }

    add_filter('body_class', 'qode_full_screen_menu_body_class');
}

if(!function_exists('qode_overlapping_content_body_class')) {
    /**
     * Function that adds transparent content class to body.
     * @param $classes array of body classes
     * @return array with transparent content body class added
     */
    function qode_overlapping_content_body_class($classes) {
        global $qode_options_proya;

        if(isset($qode_options_proya['overlapping_content']) && $qode_options_proya['overlapping_content'] == 'yes') {
            $classes[] = 'overlapping_content';
        }

        return $classes;
    }

    add_filter('body_class', 'qode_overlapping_content_body_class');
}

if(!function_exists('qode_vss_responsive_body_class')) {
    /**
     * Function that adds vertical split slider responsive class to body.
     * @param $classes array of body classes
     * @return array with vertical split slider responsive body class added
     */
    function qode_vss_responsive_body_class($classes) {
        global $qode_options_proya;

        if(isset($qode_options_proya['vss_responsive_advanced']) && $qode_options_proya['vss_responsive_advanced'] == 'yes') {
            $classes[] = 'vss_responsive_adv';
        }

        return $classes;
    }

    add_filter('body_class', 'qode_vss_responsive_body_class');
}

if(!function_exists('qode_footer_responsive_body_class')) {
	/**
     * Function that adds footer responsive class to body.
     * @param $classes array of body classes
     */
    function qode_footer_responsive_body_class($classes) {
        global $qode_options_proya;

        if(isset($qode_options_proya['footer_top_responsive']) && $qode_options_proya['footer_top_responsive'] === 'yes') {
            $classes[] = 'footer_responsive_adv';
        }

        return $classes;
    }

    add_filter('body_class', 'qode_footer_responsive_body_class');
}

if(!function_exists('qode_top_header_responsive_body_class')) {
    function qode_top_header_responsive_body_class($classes) {
        global $qode_options_proya;

        if(isset($qode_options_proya['hide_top_bar_on_mobile']) && $qode_options_proya['hide_top_bar_on_mobile'] === 'yes') {
            $classes[] = 'hide_top_bar_on_mobile_header';
        }

        return $classes;
    }

    add_filter('body_class', 'qode_top_header_responsive_body_class');
}

if(!function_exists('qode_content_sidebar_responsive_body_class')) {
    function qode_content_sidebar_responsive_body_class($classes) {
        global $qode_options_proya;

        if(isset($qode_options_proya['content_sidebar_responsiveness']) && $qode_options_proya['content_sidebar_responsiveness'] === 'yes') {
            $classes[] = 'qode-content-sidebar-responsive';
        }

        return $classes;
    }

    add_filter('body_class', 'qode_content_sidebar_responsive_body_class');
}

if(!function_exists('qode_transparent_content_body_class')) {
    /**
     * Function that adds transparent content class to body.
     * @param $classes array of body classes
     * @return array with transparent content body class added
     */
    function qode_transparent_content_body_class($classes) {
        global $qode_options_proya;

        if(isset($qode_options_proya['transparent_content']) && $qode_options_proya['transparent_content'] == 'yes') {
            $classes[] = 'transparent_content';
        }

        return $classes;
    }

    add_filter('body_class', 'qode_transparent_content_body_class');
}



if (!function_exists('theme_version_class')) {
	/**
	 * Function that adds classes on body for version of theme
	 *
	 */
	function theme_version_class($classes) {
		$current_theme = wp_get_theme();
		$theme_prefix  = 'qode';

		//is child theme activated?
		if($current_theme->parent()) {
			//add child theme version
			$classes[] = $theme_prefix.'-child-theme-ver-'.$current_theme->get('Version');

			//get parent theme
			$current_theme = $current_theme->parent();
		}

		if($current_theme->exists() && $current_theme->get('Version') != "") {
			$classes[] = $theme_prefix.'-theme-ver-'.$current_theme->get('Version');
			$classes[] = $theme_prefix.'-theme-'. strtolower($current_theme->get('Name'));
		}

		return $classes;
	}

	add_filter('body_class','theme_version_class');
}



if(!function_exists('qode_hide_initial_sticky_body_class')) {
    /**
     * Function that adds hidden initial sticky class to body.
     * @param $classes array of body classes
     * @return hidden initial sticky body class
     */
    function qode_hide_initial_sticky_body_class($classes) {
        global $qode_options_proya;

        if(isset($qode_options_proya['header_bottom_appearance']) && ($qode_options_proya['header_bottom_appearance'] == "stick" || $qode_options_proya['header_bottom_appearance'] == "stick menu_bottom" || $qode_options_proya['header_bottom_appearance'] == "stick_with_left_right_menu")){
            if(get_post_meta(qode_get_page_id(), "qode_page_hide_initial_sticky", true) !== ''){
                if(get_post_meta(qode_get_page_id(), "qode_page_hide_initial_sticky", true) == 'yes'){
                    $classes[] = 'hide_inital_sticky';
                }
            }else if(isset($qode_options_proya['hide_initial_sticky']) && $qode_options_proya['hide_initial_sticky'] == 'yes') {
                $classes[] = 'hide_inital_sticky';
            }
        }

        return $classes;
    }

    add_filter('body_class', 'qode_hide_initial_sticky_body_class');
}

 ?>