<?php
    security();
 ?>
<?php
	if(!function_exists('qode_google_fonts_styles')) {
	/**
	 * Function that includes google fonts defined anywhere in the theme
	 */
	function qode_google_fonts_styles() {
		global $qode_options_proya, $qodeFramework, $qode_toolbar;

		$font_weight_str 		= '100,200,300,400,500,600,700,800,900,300italic,400italic';
		$default_font_string 	= 'Raleway:'.$font_weight_str;

        $font_sipmle_field_array = array();
        if(is_array($qodeFramework->qodeOptions->getOptionsByType('fontsimple')) && count($qodeFramework->qodeOptions->getOptionsByType('fontsimple'))){
            $font_sipmle_field_array = $qodeFramework->qodeOptions->getOptionsByType('fontsimple');
        }

        $font_field_array = array();
        if(is_array($qodeFramework->qodeOptions->getOptionsByType('font')) && count($qodeFramework->qodeOptions->getOptionsByType('font'))){
            $font_field_array = $qodeFramework->qodeOptions->getOptionsByType('font');
        }

        $available_font_options = array_merge($font_sipmle_field_array, $font_field_array);

		//define available font options array
		$fonts_array = array();
		foreach($available_font_options as $font_option) {
			//is font set and not set to default and not empty?
			if(isset($qode_options_proya[$font_option]) && $qode_options_proya[$font_option] !== '-1' && $qode_options_proya[$font_option] !== '' && !qode_is_native_font($qode_options_proya[$font_option])) {
				$font_option_string = $qode_options_proya[$font_option].':'.$font_weight_str;
				if(!in_array($font_option_string, $fonts_array)) {
					$fonts_array[] = $font_option_string;
				}

			}
		}

		//add google fonts set in slider
		$args = array( 'post_type' => 'slides', 'posts_per_page' => -1);
		$loop = new WP_Query( $args );

		//for each slide defined
		while ( $loop->have_posts() ) : $loop->the_post();

			//is font family for title option chosen?
			if(get_post_meta(get_the_ID(), "qode_slide-title-font-family", true) != "") {
				$slide_title_font_family = get_post_meta(get_the_ID(), "qode_slide-title-font-family", true);
				$slide_title_font_string = $slide_title_font_family . ":".$font_weight_str;
				if(!in_array($slide_title_font_string, $fonts_array) && !qode_is_native_font($slide_title_font_family)) {
					//include that font
					array_push($fonts_array, $slide_title_font_string);
				}
			}

			//is font family defined for slide's text?
			if(get_post_meta(get_the_ID(), "qode_slide-text-font-family", true) != "") {
				$slide_text_font_family = get_post_meta(get_the_ID(), "qode_slide-text-font-family", true);
				$slide_text_font_string = $slide_text_font_family . ":".$font_weight_str;
				if(!in_array($slide_text_font_string, $fonts_array) && !qode_is_native_font($slide_text_font_family)) {
					//include that font
					array_push($fonts_array, $slide_text_font_string);
				}
			}

			//is font family defined for slide's subtitle?
			if(get_post_meta(get_the_ID(), "qode_slide-subtitle-font-family", true) != "") {
				$slide_subtitle_font_family = get_post_meta(get_the_ID(), "qode_slide-subtitle-font-family", true);
				$slide_subtitle_font_string = $slide_subtitle_font_family .":".$font_weight_str;
				if(!in_array($slide_subtitle_font_string, $fonts_array) && !qode_is_native_font($slide_subtitle_font_family)) {
					//include that font
					array_push($fonts_array, $slide_subtitle_font_string);
				}

			}
		endwhile;

		wp_reset_postdata();

		$fonts_array = array_diff($fonts_array, array("-1:".$font_weight_str));
		$google_fonts_string = implode( '|', $fonts_array);

		//is google font option checked anywhere in theme?
		if(count($fonts_array) > 0) {
			//include all checked fonts
			printf("<link href='//fonts.googleapis.com/css?family=".$default_font_string."|%s&subset=latin,latin-ext' rel='stylesheet' type='text/css'>\r\n", str_replace(' ', '+', $google_fonts_string));
		} else {
			//include default google font that theme is using
			printf("<link href='//fonts.googleapis.com/css?family=".$default_font_string."' rel='stylesheet' type='text/css'>\r\n");
		}

		if(isset($qode_toolbar)){
			printf("<link href='//fonts.googleapis.com/css?family=Raleway:400,600' rel='stylesheet' type='text/css'>\r\n");
		}
	}

	add_action('wp_enqueue_scripts', 'qode_google_fonts_styles');
}

//GOOGLE MAP SHORDCODE CHAKE FUNCTION

if(!function_exists('qode_has_google_map_shortcode')) {
	/**
	 * Function that checks Qode Google Map shortcode exists on a page
	 * @return bool
	 */
	function qode_has_google_map_shortcode() {
		$google_map_shortcode = 'qode_google_map';

		$slider_field = get_post_meta(qode_get_page_id(), 'qode_revolution-slider', true);

		$has_shortcode = qode_has_shortcode($google_map_shortcode) || qode_has_shortcode($google_map_shortcode, $slider_field);

		if($has_shortcode) {
			return true;
		}

		return false;
	}
}





 ?>