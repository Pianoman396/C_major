<?php
    security();
 ?>
<?php


if(!function_exists('qode_get_objects_without_ajax')) {
	/**
	 * Function that returns urls of objects that have ajax disabled.
	 * Works for posts, pages and portfolio pages.
	 * @return array array of urls of posts that have ajax disabled
	 *
	 * @version 0.2
	 */
	function qode_get_objects_without_ajax() {
		$posts_without_ajax = array();

		$posts_args =  array(
			'post_type'  => array('post', 'portfolio_page', 'page'),
			'post_status' => 'publish',
			'meta_key' => 'qode_show-animation',
			'meta_value' => 'no_animation'
		);

		$posts_query = new WP_Query($posts_args);

		if($posts_query->have_posts()) {
			while($posts_query->have_posts()) {
				$posts_query->the_post();
				$posts_without_ajax[] = get_permalink(get_the_ID());
			}
		}

		wp_reset_postdata();

		return $posts_without_ajax;
	}
}

if(!function_exists('qode_get_pages_without_ajax')) {
	/**
	 * Function that returns urls of pages that have ajax disabled
	 * @return array array of urls of pages that have ajax disabled
	 *
	 * @version 0.1
	 */
	function qode_get_pages_without_ajax() {
		$pages_without_ajax = array();

		$pages_args = array(
			'post_type'  => 'page',
			'post_status' => 'publish',
			'meta_key' => 'qode_show-animation',
			'meta_value' => 'no_animation'
		);

		$pages_query = new WP_Query($pages_args);

		if($pages_query->have_posts()) {
			while($pages_query->have_posts()) {
				$pages_query->the_post();
				$pages_without_ajax[] = get_permalink(get_the_ID());
			}
		}

		wp_reset_postdata();

		return $pages_without_ajax;
	}
}

if(!function_exists('qode_get_wpml_pages_for_current_page')) {
	/**
	 * Function that returns urls translated pages for current page.
	 * @return array array of url urls translated pages for current page.
	 *
	 * @version 0.1
	 */
	function qode_get_wpml_pages_for_current_page() {
		$wpml_pages_for_current_page = array();

		if(qode_is_wpml_installed()) {
			$language_pages = icl_get_languages('skip_missing=0');

			foreach($language_pages as $key => $language_page) {
				$wpml_pages_for_current_page[] = $language_page["url"];
			}
		}

		return $wpml_pages_for_current_page;
	}
}

if(!function_exists('qode_init_page_id')) {
	/**
	 * Function that sets global $qode_page_id variable
	 */
	function qode_init_page_id() {
		global $wp_query;
		global $qode_page_id;

		$qode_page_id = $wp_query->get_queried_object_id();
	}-

	add_action('get_header', 'qode_init_page_id');
}



if(!function_exists('qode_is_title_hidden')) {
	/**
	 * Function that check is title hidden on current page
	 * @param none
	 * @return true/false
	 */
	function qode_is_title_hidden() {
		global $qode_options_proya;
		$page_id = qode_get_page_id();

		$hide_page_title_area = false;
		if(get_post_meta($page_id, "qode_show-page-title", true) === 'yes'){
			$hide_page_title_area = true;
		}elseif(get_post_meta($page_id, "qode_show-page-title", true) === 'no'){
			$hide_page_title_area = false;
		}else{
			if(isset($qode_options_proya['dont_show_page_title']) && ($qode_options_proya['dont_show_page_title'] === 'yes')){
				$hide_page_title_area = true;
			}elseif(isset($qode_options_proya['dont_show_page_title']) && ($qode_options_proya['dont_show_page_title'] === 'no')){
				$hide_page_title_area = false;
			}
		}

		return $hide_page_title_area;
	}
}

if(!function_exists('qode_is_title_text_hidden')) {
	/**
	 * Function that check is title text hidden on current page
	 * @param none
	 * @return true/false
	 */
	function qode_is_title_text_hidden() {
		global $qode_options_proya;
		$page_id = qode_get_page_id();

		$hide_page_title_text = false;
		if(get_post_meta($page_id, "qode_show-page-title-text", true) === 'yes'){
			$hide_page_title_text = true;
		}elseif(get_post_meta($page_id, "qode_show-page-title-text", true) === 'no'){
			$hide_page_title_text = false;
		}else{
			if(isset($qode_options_proya['dont_show_page_title_text']) && ($qode_options_proya['dont_show_page_title_text'] === 'yes')){
				$hide_page_title_text = true;
			}elseif(isset($qode_options_proya['dont_show_page_title_text']) && ($qode_options_proya['dont_show_page_title_text'] === 'no')){
				$hide_page_title_text = false;
			}
		}
		return $hide_page_title_text;
	}
}

if(!function_exists('qode_is_content_below_header')) {
	/**
	 * Function that check is content below header on page
	 * @param none
	 * @return true/false
	 */
	function qode_is_content_below_header() {
		global $qode_options_proya;
		$page_id = qode_get_page_id();

		$content_below_header = false;
		if(get_post_meta($page_id, "qode_enable_content_top_margin", true) === 'yes'){
			$content_below_header = true;
		}elseif(get_post_meta($page_id, "qode_enable_content_top_margin", true) === 'no'){
			$content_below_header = false;
		}else{
			if(isset($qode_options_proya['enable_content_top_margin']) && ($qode_options_proya['enable_content_top_margin'] === 'yes')){
				$content_below_header = true;
			}elseif(isset($qode_options_proya['enable_content_top_margin']) && ($qode_options_proya['enable_content_top_margin'] === 'no')){
				$content_below_header = false;
			}
		}

		return $content_below_header;
	}
}


if(!function_exists('qode_is_archive_page')) {
	/**
	 * Function that checks if current page archive page, search, 404 or default home blog page
	 * @return bool
	 *
	 * @see is_archive()
	 * @see is_search()
	 * @see is_404()
	 * @see is_front_page()
	 * @see is_home()
	 */
	function qode_is_archive_page() {
		return is_archive() || is_search() || is_404() || (is_front_page() && is_home());
	}
}

if(!function_exists('qode_get_page_template_name')) {
	/**
	 * Returns current template file name without extension
	 * @return string name of current template file
	 */
	function qode_get_page_template_name() {
		$file_name = '';
		$file_name_without_ext = preg_replace('/\\.[^.\\s]{3,4}$/', '', basename(get_page_template()));

		if($file_name_without_ext !== '') {
			$file_name = $file_name_without_ext;
		}

		return $file_name;
	}
}

if(!function_exists('qode_is_contact_page_template')) {
	/**
	 * Checks if current template page is contact page.
	 * @param string current page. Optional parameter. If not passed qode_get_page_template_name() function will be used
	 * @return bool
	 *
	 * @see qode_get_page_template_name()
	 */
	function qode_is_contact_page_template($current_page = '') {
		if($current_page == '') {
			$current_page = qode_get_page_template_name();
		}

		return in_array($current_page, array('contact-page'));
	}
}



 ?>