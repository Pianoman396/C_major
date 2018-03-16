<?php
    security();
 ?>
<?php



/* Register Menus */

if (!function_exists('qode_register_menus')) {
	/**
	 * Function that registers menu positions
	 */
	function qode_register_menus() {
		global $qode_options_proya;

		if((isset($qode_options_proya['header_bottom_appearance']) && $qode_options_proya['header_bottom_appearance'] != "stick_with_left_right_menu") || (isset($qode_options_proya['vertical_area']) && $qode_options_proya['vertical_area'] == "yes")){
			//header and left menu location
			register_nav_menus(
				array('top-navigation' => __( 'Top Navigation', 'qode')
				)
			);
		}

		//popup menu location
		register_nav_menus(
			array('popup-navigation' => __( 'Fullscreen Navigation', 'qode')
			)
		);

		if((isset($qode_options_proya['header_bottom_appearance']) && $qode_options_proya['header_bottom_appearance'] == "stick_with_left_right_menu") && (isset($qode_options_proya['vertical_area']) && $qode_options_proya['vertical_area'] == "no")){
			//header left menu location
			register_nav_menus(
				array('left-top-navigation' => __( 'Left Top Navigation', 'qode')
				)
			);

			//header right menu location
			register_nav_menus(
				array('right-top-navigation' => __( 'Right Top Navigation', 'qode')
				)
			);
		}
	}

	add_action( 'after_setup_theme', 'qode_register_menus' );
}




if(!function_exists('qode_is_main_menu_set')) {
	/**
	 * Function that checks if any of main menu locations are set.
	 * Checks whether top-navigation location is set, or left-top-navigation and right-top-navigation is set
	 * @return bool
	 *
	 * @version 0.1
	 */
	function qode_is_main_menu_set() {
		$has_top_nav = has_nav_menu('top-navigation');
		$has_divided_nav = has_nav_menu('left-top-navigation') && has_nav_menu('right-top-navigation');

		return $has_top_nav || $has_divided_nav;
	}
}



if(!function_exists('qode_get_side_menu_icon_html')) {
    /**
     * Function that outputs html for side area icon opener.
     * Uses $qodeIconCollections global variable
     * @return string generated html
     */
    function qode_get_side_menu_icon_html() {
        global $qodeIconCollections, $qode_options_proya;

        $icon_html = '';

        $icon_pack = qodef_option_get_value('side_area_button_icon_pack');

        if(isset($icon_pack) && $icon_pack !== '') {
            $icon_collection_obj = $qodeIconCollections->getIconCollection($icon_pack);
            $icon_field_name = 'side_area_icon_'. $icon_collection_obj->param;

            $side_area_icon = qodef_option_get_value($icon_field_name);

            if(isset($side_area_icon) && $side_area_icon !== ''){

                if (method_exists($icon_collection_obj, 'render')) {
                    $icon_html = $icon_collection_obj->render($side_area_icon);
                }
            }
        }

        return $icon_html;
    }
}

if(!function_exists('qode_get_mobile_menu_icon_html')) {
    /**
     * Function that outputs html for side area icon opener.
     * Uses $qodeIconCollections global variable
     * @return string generated html
     */
    function qode_get_mobile_menu_icon_html() {
        global $qodeIconCollections, $qode_options_proya;

        $icon_html = '';

        $icon_pack = qodef_option_get_value('mobile_menu_button_icon_pack');

        if(isset($icon_pack) && $icon_pack !== '') {
            $icon_collection_obj = $qodeIconCollections->getIconCollection($icon_pack);
            $icon_field_name = 'mobile_menu_icon_'. $icon_collection_obj->param;

            $mobile_menu_icon = qodef_option_get_value($icon_field_name);

            if(isset($mobile_menu_icon) && $mobile_menu_icon !== ''){

                if (method_exists($icon_collection_obj, 'render')) {
                    $icon_html = $icon_collection_obj->render($mobile_menu_icon);
                }
            }
        }

        return $icon_html;
    }
}


 ?>