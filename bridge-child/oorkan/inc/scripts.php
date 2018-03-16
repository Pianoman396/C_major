<?php
    security();
 ?>
<?php
	/******* THEME STYLES ******/
	/* Add css */
	if (!function_exists('qode_styles')) {
    function qode_styles() {
        global $qode_options_proya;
        global $wp_styles;
        global $is_chrome;
        global $is_safari;
        global $qode_toolbar;
        global $qode_landing;
        global $qode_tour_popup;
        global $woocommerce;

        wp_enqueue_style("default_style", QODE_ROOT . "/style.css");
        qode_icon_collections()->enqueueStyles();
        wp_enqueue_style("stylesheet", QODE_ROOT . "/css/stylesheet.min.css");

        if ($woocommerce) {
            wp_enqueue_style("woocommerce", QODE_ROOT . "/css/woocommerce.min.css");
            if(!empty($qode_options_proya['responsiveness']) && $qode_options_proya['responsiveness'] == 'yes') {
                wp_enqueue_style("woocommerce_responsive", QODE_ROOT . "/css/woocommerce_responsive.min.css");
            }
        }

        wp_enqueue_style("qode_print", QODE_ROOT . "/css/print.css");

        preg_match( "#Chrome/(.+?)\.#", $_SERVER['HTTP_USER_AGENT'], $match );
        if(!empty($match)){ $version = $match[1];}else{ $version = 0; }
        $mac_os = strpos($_SERVER['HTTP_USER_AGENT'], "Macintosh; Intel Mac OS X");

        if($is_chrome && ($mac_os !== false) && ($version > 21)) {
            wp_enqueue_style("mac_stylesheet", QODE_ROOT . "/css/mac_stylesheet.css");
        }

        if($is_chrome || $is_safari) {
            wp_enqueue_style("webkit", QODE_ROOT . "/css/webkit_stylesheet.css");
        }

        if($is_safari) {
            wp_enqueue_style("safari", QODE_ROOT . "/css/safari_stylesheet.css");
        }

		if(qode_timetable_schedule_installed()){
			wp_enqueue_style("qode_timetable", QODE_ROOT . "/css/timetable-schedule.min.css");
			wp_enqueue_style("qode_timetable_responsive", QODE_ROOT . "/css/timetable-schedule-responsive.min.css");
		}

		if (file_exists(dirname(__FILE__) ."/css/style_dynamic.css") && qode_is_css_folder_writable() && !is_multisite()) {
			wp_enqueue_style("style_dynamic", QODE_ROOT . "/css/style_dynamic.css", array(), filemtime(dirname(__FILE__) ."/css/style_dynamic.css"));
		} else {
			wp_enqueue_style("style_dynamic", QODE_ROOT . "/css/style_dynamic.php");
		}


        $responsiveness = "yes";
        if (isset($qode_options_proya['responsiveness']))
            $responsiveness = $qode_options_proya['responsiveness'];
        if ($responsiveness != "no"):
            wp_enqueue_style("responsive", QODE_ROOT . "/css/responsive.min.css");

			if (file_exists(dirname(__FILE__) ."/css/style_dynamic_responsive.css") && qode_is_css_folder_writable() && !is_multisite())
            	wp_enqueue_style("style_dynamic_responsive", QODE_ROOT . "/css/style_dynamic_responsive.css", array(), filemtime(dirname(__FILE__) ."/css/style_dynamic_responsive.css"));
            else
            	wp_enqueue_style("style_dynamic_responsive", QODE_ROOT . "/css/style_dynamic_responsive.php");
        endif;

		$vertical_area = "no";
		if (isset($qode_options_proya['vertical_area'])){
			$vertical_area = $qode_options_proya['vertical_area'];
		}
		if($vertical_area == "yes" && $responsiveness != "no"){
			wp_enqueue_style("vertical_responsive", QODE_ROOT . "/css/vertical_responsive.min.css");
		}

        //is toolbar turned on?
        if (isset($qode_toolbar)) {
            //include toolbar specific styles
            wp_enqueue_style("qode_toolbar", QODE_ROOT . "/css/toolbar.css");
        }

        //is landing turned on?
        if (isset($qode_landing)) {
            //include landing page specific styles
            wp_enqueue_style("qode_landing", get_home_url() . "/demo-files/landing/css/landing_stylesheet_stripped.css");
        }

        //is tour popup on?
        if (isset($qode_tour_popup)) {
            //include tour popup specific styles
            wp_enqueue_style("qode_tour_popup", get_home_url() . "/demo-files/landing/css/tour_popup_stylesheet.css");
        }

        //include Visual Composer styles
        if (class_exists('WPBakeryVisualComposerAbstract')) {
            wp_enqueue_style( 'js_composer_front' );
        }

		if(is_rtl()) {
			wp_enqueue_style('qode-rtl', QODE_ROOT.'/rtl.css');
		}

		if (file_exists(dirname(__FILE__) ."/css/custom_css.css") && qode_is_css_folder_writable() && !is_multisite())
        	wp_enqueue_style("custom_css", QODE_ROOT . "/css/custom_css.css", array(), filemtime(dirname(__FILE__) ."/css/custom_css.css"));
       	else
        	wp_enqueue_style("custom_css", QODE_ROOT . "/css/custom_css.php");
    }

	add_action('wp_enqueue_scripts', 'qode_styles');

	/******* THEME SCRIPTS ******/
/* Add js */

if (!function_exists('qode_scripts')) {
    function qode_scripts() {
        global $qode_options_proya;
        global $is_chrome;
        global $is_opera;
        global $is_IE;
        global $qode_toolbar;
        global $qode_landing;
        global $qode_tour_popup;
        global $woocommerce;

        $smooth_scroll = true;
        if(isset($qode_options_proya['smooth_scroll']) && $qode_options_proya['smooth_scroll'] == "no"){
            $smooth_scroll = false;
        }

        // Try to comment one by one this scripts and see if the pages are craching or not
        wp_enqueue_script('jquery');

		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-widget');
		wp_enqueue_script('jquery-ui-accordion');
		wp_enqueue_script('jquery-ui-autocomplete');
		wp_enqueue_script('jquery-ui-button');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-droppable');
		wp_enqueue_script('jquery-ui-menu');
		wp_enqueue_script('jquery-ui-mouse');
		wp_enqueue_script('jquery-ui-position');
		wp_enqueue_script('jquery-ui-progressbar');
		wp_enqueue_script('jquery-ui-selectable');
		wp_enqueue_script('jquery-ui-resizable');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-ui-spinner');
		wp_enqueue_script('jquery-ui-tooltip');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-effects-core');
		wp_enqueue_script('jquery-effects-blind');
		wp_enqueue_script('jquery-effects-bounce');
		wp_enqueue_script('jquery-effects-clip');
		wp_enqueue_script('jquery-effects-drop');
		wp_enqueue_script('jquery-effects-explode');
		wp_enqueue_script('jquery-effects-fade');
		wp_enqueue_script('jquery-effects-fold');
		wp_enqueue_script('jquery-effects-highlight');
		wp_enqueue_script('jquery-effects-pulsate');
		wp_enqueue_script('jquery-effects-scale');
		wp_enqueue_script('jquery-effects-shake');
		wp_enqueue_script('jquery-effects-slide');
		wp_enqueue_script('jquery-effects-transfer');

        wp_enqueue_script("plugins", QODE_ROOT."/js/plugins.js",array(),false,true);

        wp_enqueue_script("carouFredSel", QODE_ROOT."/js/jquery.carouFredSel-6.2.1.min.js",array(),false,true);
        wp_enqueue_script("lemmonSlider", QODE_ROOT."/js/lemmon-slider.min.js",array(),false,true);
        wp_enqueue_script("one_page_scroll", QODE_ROOT."/js/jquery.fullPage.min.js",array(),false,true);
        wp_enqueue_script("mousewheel", QODE_ROOT."/js/jquery.mousewheel.min.js",array(),false,true);
        wp_enqueue_script("touchSwipe", QODE_ROOT."/js/jquery.touchSwipe.min.js",array(),false,true);
        wp_enqueue_script("isotope", QODE_ROOT."/js/jquery.isotope.min.js",array(),false,true);
        wp_enqueue_script("stretch", QODE_ROOT."/js/jquery.stretch.js",array(),false,true);

        $mac_os = strpos($_SERVER['HTTP_USER_AGENT'], "Macintosh; Intel Mac OS X");
        if($smooth_scroll && $mac_os == false){
            wp_enqueue_script("TweenLite", QODE_ROOT."/js/TweenLite.min.js",array(),false,true);
			if(!qode_layer_slider_installed() || !qode_revolution_slider_installed()){
				wp_enqueue_script("ScrollToPlugin", QODE_ROOT."/js/ScrollToPlugin.min.js",array(),false,true);
			}
            wp_enqueue_script("smoothPageScroll", QODE_ROOT."/js/smoothPageScroll.min.js",array(),false,true);
        }


        if ( $is_IE ) {
            wp_enqueue_script("html5", QODE_ROOT."/js/html5.js",array(),false,false);
        }
        if((isset($qode_options_proya['enable_google_map']) && $qode_options_proya['enable_google_map'] == "yes") || qode_is_ajax_enabled() || qode_has_google_map_shortcode()) :

			if( (isset($qode_options_proya['google_maps_api_key']) && $qode_options_proya['google_maps_api_key'] != "")) {

				$google_maps_api_key = $qode_options_proya['google_maps_api_key'];
				wp_enqueue_script("google_map_api", "https://maps.googleapis.com/maps/api/js?key=" . $google_maps_api_key,array(),false,true);

			} else {

				wp_enqueue_script("google_map_api", "https://maps.googleapis.com/maps/api/js",array(),false,true);

			}


        endif;

		if (file_exists(dirname(__FILE__) ."/js/default_dynamic.js") && qode_is_js_folder_writable() && !is_multisite()) {
			wp_enqueue_script("default_dynamic", QODE_ROOT."/js/default_dynamic.js",array(), filemtime(dirname(__FILE__) ."/js/default_dynamic.js"),true);
		} else {
			wp_enqueue_script("default_dynamic", QODE_ROOT."/js/default_dynamic.php",array(),false,true);
		}

        wp_enqueue_script("default", QODE_ROOT."/js/default.min.js",array(),false,true);

		if (file_exists(dirname(__FILE__) ."/js/custom_js.js") && qode_is_js_folder_writable() && !is_multisite()) {
			wp_enqueue_script("custom_js", QODE_ROOT."/js/custom_js.js",array(), filemtime(dirname(__FILE__) ."/js/custom_js.js"),true);
		} else {
			wp_enqueue_script("custom_js", QODE_ROOT."/js/custom_js.php",array(),false,true);
		}

        global $wp_scripts;
        $wp_scripts->add_data('comment-reply', 'group', 1 );
        if ( is_singular() ) wp_enqueue_script( "comment-reply");

        $has_ajax = false;
        $qode_animation = "";
        if (isset($_SESSION['qode_proya_page_transitions']))
            $qode_animation = $_SESSION['qode_proya_page_transitions'];
        if (($qode_options_proya['page_transitions'] != "0") && (empty($qode_animation) || ($qode_animation != "no")))
            $has_ajax = true;
        elseif (!empty($qode_animation) && ($qode_animation != "no"))
            $has_ajax = true;

        if ($has_ajax) :
            wp_enqueue_script("ajax", QODE_ROOT."/js/ajax.min.js",array(),false,true);
        endif;
        wp_enqueue_script( 'wpb_composer_front_js' );

        if(isset($qode_options_proya['use_recaptcha']) && $qode_options_proya['use_recaptcha'] == "yes") :
    			$url = 'https://www.google.com/recaptcha/api.js';
			$url = add_query_arg( array(
				'onload' => 'qodeRecaptchaCallback',
				'render' => 'explicit' ), $url );
        	wp_enqueue_script("qode-recaptcha", $url,array(),false,true);
        endif;

		//is toolbar enabled?
		if(isset($qode_toolbar)) {
			//include toolbar specific script
			wp_enqueue_script("qode_toolbar", QODE_ROOT."/js/toolbar.js",array(),false,true);
		}

		//is landing enabled?
		if(isset($qode_landing)) {
			wp_enqueue_script("mixitup", get_home_url() . "/demo-files/landing/js/jquery.mixitup.js",array(),false,true);
			wp_enqueue_script("mixitup_pagination", get_home_url() . "/demo-files/landing/js/jquery.mixitup-pagination.js",array(),false,true);
            wp_enqueue_script("qode_cookie", get_home_url() . "/demo-files/landing/js/js.cookie.js",array(),false,true);
            wp_enqueue_script("qode_landing", get_home_url() . "/demo-files/landing/js/landing_default.js",array(),false,true);
		}

        //is tour popup enabled?
        if(isset($qode_tour_popup)) {
            wp_enqueue_script("qode_cookie", get_home_url() . "/demo-files/landing/js/js.cookie.js",array(),false,true);
            wp_enqueue_script("qode_tour_popup", get_home_url() . "/demo-files/landing/js/tour_popup_default.js",array(),false,true);
        }

        if($woocommerce) {
            wp_enqueue_script("woocommerce-qode", QODE_ROOT."/js/woocommerce.js",array(),false,true);
	        wp_enqueue_script('select2');
        }

		wp_localize_script( 'default', 'QodeAdminAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );


		echo '<script type="application/javascript">var QodeAjaxUrl = "'.admin_url('admin-ajax.php').'"</script>';

    }

	add_action('wp_enqueue_scripts', 'qode_scripts');
}

}
/*Because of the bug when Revolution slider, Layer Slider and Smooth Scroll are enabled together (greensock.js doesn't have included ScrollTo so it need to be included before)*/

if(!function_exists('qode_scrollto_script')) {

	function qode_scrollto_script(){
		global $qode_options_proya;

		$smooth_scroll = true;
		if(isset($qode_options_proya['smooth_scroll']) && $qode_options_proya['smooth_scroll'] == "no"){
			$smooth_scroll = false;
		}
		$mac_os = strpos($_SERVER['HTTP_USER_AGENT'], "Macintosh; Intel Mac OS X");
		if($smooth_scroll && $mac_os == false && qode_layer_slider_installed() && qode_revolution_slider_installed()) {
			wp_enqueue_script("ScrollToPlugin", QODE_ROOT . "/js/ScrollToPlugin.min.js", array(), false, false);
		}
	}

	add_action('wp_enqueue_scripts', 'qode_scrollto_script', 1);

}

if(!function_exists('qode_localize_no_ajax_pages')) {
    /**
     * Function that outputs no_ajax_obj javascript variable that is used default_dynamic.php.
     * It is used for no ajax pages functionality
     *
     * Function hooks to wp_enqueue_scripts and uses wp_localize_script
     *
     * @see http://codex.wordpress.org/Function_Reference/wp_localize_script
     *
     * @uses qode_get_objects_without_ajax()
     * @uses qode_get_pages_without_ajax()
     * @uses qode_get_wpml_pages_for_current_page()
     * @uses qode_get_woocommerce_pages()
     *
     * @version 0.1
     */
    function qode_localize_no_ajax_pages() {
        global $qode_options_proya;

        //is ajax enabled?
        if(qode_is_ajax_enabled()) {
            $no_ajax_pages = array();

            //get objects that have ajax disabled and merge with main array
            $no_ajax_pages = array_merge($no_ajax_pages, qode_get_objects_without_ajax());

            //is wpml installed?
            if(qode_is_wpml_installed()) {
                //get translation pages for current page and merge with main array
                $no_ajax_pages = array_merge($no_ajax_pages, qode_get_wpml_pages_for_current_page());
            }

            //is woocommerce installed?
            if(qode_is_woocommerce_installed()) {
                //get all woocommerce pages and products and merge with main array
                $no_ajax_pages = array_merge($no_ajax_pages, qode_get_woocommerce_pages());
                $no_ajax_pages = array_merge($no_ajax_pages, qode_get_woocommerce_archive_pages());
            }

            //do we have some internal pages that won't to be without ajax?
            if (isset($qode_options_proya['internal_no_ajax_links'])) {
                //get array of those pages
                $options_no_ajax_pages_array = explode(',', $qode_options_proya['internal_no_ajax_links']);

                if(is_array($options_no_ajax_pages_array) && count($options_no_ajax_pages_array)) {
                    $no_ajax_pages = array_merge($no_ajax_pages, $options_no_ajax_pages_array);
                }
            }

            //add logout url to main array
            $no_ajax_pages[] = htmlspecialchars_decode(wp_logout_url());

            //finally localize script so we can use it in default_dynamic
            wp_localize_script( 'default_dynamic', 'no_ajax_obj', array(
                'no_ajax_pages' => $no_ajax_pages
            ));
        }
    }

    add_action('wp_enqueue_scripts', 'qode_localize_no_ajax_pages');
}

// Generate dynamic scripts and Styles

if (!function_exists('qode_generate_dynamic_css_and_js')){
    /**
     * Function that gets content of dynamic assets files and puts that in static ones
     */
    function qode_generate_dynamic_css_and_js() {

        $qode_options_proya = get_option('qode_options_proya');
        if(qode_is_css_folder_writable()) {
            $css_dir = get_template_directory().'/css/';

            ob_start();
            include_once('css/style_dynamic.php');
            $css = ob_get_clean();
            file_put_contents($css_dir.'style_dynamic.css', $css, LOCK_EX);

            ob_start();
            include_once('css/style_dynamic_responsive.php');
            $css = ob_get_clean();
            file_put_contents($css_dir.'style_dynamic_responsive.css', $css, LOCK_EX);

            ob_start();
            include_once('css/custom_css.php');
            $css = ob_get_clean();
            file_put_contents($css_dir.'custom_css.css', $css, LOCK_EX);
        }

        if(qode_is_js_folder_writable()) {
            $js_dir = get_template_directory().'/js/';

            ob_start();
            include_once('js/default_dynamic.php');
            $js = ob_get_clean();
            file_put_contents($js_dir.'default_dynamic.js', $js, LOCK_EX);

            ob_start();
            include_once('js/custom_js.php');
            $js = ob_get_clean();
            file_put_contents($js_dir.'custom_js.js', $js, LOCK_EX);
        }
    }

    if(!is_multisite()) {
        add_action('qode_after_theme_option_save', 'qode_generate_dynamic_css_and_js');
    }
}


 ?>