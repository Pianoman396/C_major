<?php
    security();
 ?>
<?php

if(!function_exists('qode_title_text')) {
	/**
	 * Function that echoes title text.
	 *
	 * @see qode_get_title_text()
	 *
	 * @since 4.3
	 * @version 0.1
	 */
	function qode_title_text() {
		echo qode_get_title_text();
	}
}

if(!function_exists('qode_wp_title')) {
	/**
	 * Function that sets page's title. Hooks to wp_title filter
	 * @param $title string current page title
	 * @param $sep string title separator
	 * @return string changed title text if SEO plugins aren't installed
	 *
	 * @since 5.0
	 * @version 0.3
	 */
	function qode_wp_title($title, $sep) {
		global $qode_options_proya;

		//is SEO plugin installed?
		if(qode_seo_plugin_installed()) {
			//don't do anything, seo plugin will take care of it
		} else {
			//get current post id
			$id = qode_get_page_id();

			$sep = ' | ';
			$title_prefix = get_bloginfo('name');
			$title_suffix = '';

			//set unchanged title variable so we can use it later
			$unchanged_title = $title;

			//is qode seo enabled?
			if(isset($qode_options_proya['disable_qode_seo']) && $qode_options_proya['disable_qode_seo'] !== 'yes') {
				//get current post seo title
				$seo_title = get_post_meta($id, "qode_seo_title", true);

				//is current post seo title set?
				if($seo_title !== '') {
					$title_suffix = $seo_title;
				}
			}

			//title suffix is empty, which means that it wasn't set by qode seo
			if(empty($title_suffix)) {
				//if current page is front page append site description, else take original title string
				$title_suffix = is_front_page() ? get_bloginfo('description') : $unchanged_title;
			}

			//concatenate title string
			$title  = $title_prefix.$sep.$title_suffix;

			//return generated title string
			return $title;
		}
	}

	add_filter('wp_title', 'qode_wp_title', 10, 2);
}

 ?>