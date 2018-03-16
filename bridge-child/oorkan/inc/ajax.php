<?php
    security();
 ?>
<?php
	if (!function_exists('ajax_classes')) {
	/**
	 * Function that adds classes for ajax animation on body element
	 * @param $classes array of current body classes
 	 * @return array array of changed body classes
	 */
	function ajax_classes($classes) {
		global $qode_options_proya;
		$qode_animation="";
		if (isset($_SESSION['qode_animation'])) $qode_animation = $_SESSION['qode_animation'];
		if(($qode_options_proya['page_transitions'] === "0") && ($qode_animation == "no")) :
			$classes[] = '';
		elseif($qode_options_proya['page_transitions'] === "1" && (empty($qode_animation) || ($qode_animation != "no"))) :
			$classes[] = 'ajax_updown';
			$classes[] = 'page_not_loaded';
		elseif($qode_options_proya['page_transitions'] === "2" && (empty($qode_animation) || ($qode_animation != "no"))) :
			$classes[] = 'ajax_fade';
			$classes[] = 'page_not_loaded';
		elseif($qode_options_proya['page_transitions'] === "3" && (empty($qode_animation) || ($qode_animation != "no"))) :
			$classes[] = 'ajax_updown_fade';
			$classes[] = 'page_not_loaded';
		elseif($qode_options_proya['page_transitions'] === "4" && (empty($qode_animation) || ($qode_animation != "no"))) :
			$classes[] = 'ajax_leftright';
			$classes[] = 'page_not_loaded';
		elseif(!empty($qode_animation) && $qode_animation != "no") :
			$classes[] = 'page_not_loaded';
		else:
		$classes[] ="";
		endif;

		return $classes;
	}

	add_filter('body_class','ajax_classes');
}


if(!function_exists('qode_ajax_meta')) {
	/**
	 * Function that echoes meta data for ajax
	 *
	 * @since 5.0
	 * @version 0.2
	 */
	function qode_ajax_meta() {
		global $qode_options_proya;

        ?>

        <div class="seo_title"><?php wp_title(''); ?></div>

        <?php

        if(isset($qode_options_proya['disable_qode_seo']) && $qode_options_proya['disable_qode_seo'] == 'no') {
            $seo_description = get_post_meta(qode_get_page_id(), "qode_seo_description", true);
            $seo_keywords = get_post_meta(qode_get_page_id(), "qode_seo_keywords", true);
            ?>



            <?php if ($seo_description !== '') { ?>
                <div class="seo_description"><?php echo $seo_description; ?></div>
            <?php } else if ($qode_options_proya['meta_description']) { ?>
                <div class="seo_description"><?php echo $qode_options_proya['meta_description']; ?></div>
            <?php } ?>
            <?php if ($seo_keywords !== '') { ?>
                <div class="seo_keywords"><?php echo $seo_keywords; ?></div>
            <?php } else if ($qode_options_proya['meta_keywords']) { ?>
                <div class="seo_keywords"><?php echo $qode_options_proya['meta_keywords']; ?></div>
            <?php }
        }
	}

	add_action('qode_ajax_meta', 'qode_ajax_meta');
}


// TOOLS

if(!function_exists('qode_is_ajax')) {
	/**
	 * Function that checks if current request is ajax request
	 * @return bool whether it's ajax request or not
	 *
	 * @version 0.1
	 */
	function qode_is_ajax() {
		return !empty( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ]) && strtolower( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ]) == 'xmlhttprequest';
	}
}

if(!function_exists('qode_is_ajax_enabled')) {
	/**
	 * Function that checks if ajax is enabled.
	 * @return bool
	 *
	 * @version 0.1
	 */
	function qode_is_ajax_enabled() {
		global $qode_options_proya;

		$has_ajax = false;

		if(isset($qode_options_proya['page_transitions']) && $qode_options_proya['page_transitions'] !== '0') {
			$has_ajax = true;
		}

		return $has_ajax;
	}
}

if(!function_exists('qode_grid_elements_ajax_disable')) {
	/**
	 * Function that disables ajax transitions if grid elements are enabled in theme options
	 */
	function qode_grid_elements_ajax_disable() {
		global $qode_options_proya;

		if(qode_vc_grid_elements_enabled()) {
			$qode_options_proya['page_transitions'] = '0';
		}
	}

	add_action('wp', 'qode_grid_elements_ajax_disable');
}



if(!function_exists('qode_is_ajax_header_animation_enabled')) {
    /**
     * Function that checks if header animation with ajax is enabled.
     * @return boolean
     *
     * @version 0.1
     */
    function qode_is_ajax_header_animation_enabled() {
        global $qode_options_proya;

        $has_header_animation = false;

        if(isset($qode_options_proya['page_transitions']) && $qode_options_proya['page_transitions'] !== '0' && isset($qode_options_proya['ajax_animate_header']) && $qode_options_proya['ajax_animate_header'] == 'yes') {
            $has_header_animation = true;
        }

        return $has_header_animation;
    }
}



//json on ajax

if(!function_exists('qode_remove_yoast_json_on_ajax')) {
	/**
	 * Function that removes yoast json ld script
	 * that stops page transition to work on home page
	 * Hooks to wpseo_json_ld_output in order to disable json ld script
	 * @return bool
     *
     * @param $data array json ld data that is being passed to filter
	 *
	 * @version 0.2
	 */
	function qode_remove_yoast_json_on_ajax($data) {
		//is current request made through ajax?
		if(qode_is_ajax()) {
			//disable json ld script
			return array();
		}

		return $data;
	}

	//is yoast installed and it's version is greater or equal of 1.6?
	if(defined('WPSEO_VERSION') && version_compare(WPSEO_VERSION, '1.6') >= 0) {
		add_filter('wpseo_json_ld_output', 'qode_remove_yoast_json_on_ajax');
	}
}



 ?>