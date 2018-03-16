<?php
    security();
 ?>
<?php
	if(!function_exists('qode_is_woocommerce_installed')) {
	/**
	 * Function that checks if woocommerce is installed
	 * @return bool
	 */
	function qode_is_woocommerce_installed() {
		return function_exists('is_woocommerce');
	}
}

if(!function_exists('qode_is_woocommerce_page')) {
	/**
	 * Function that checks if current page is woocommerce shop, product or product taxonomy
	 * @return bool
	 *
	 * @see is_woocommerce()
	 */
	function qode_is_woocommerce_page() {
		return function_exists('is_woocommerce') && is_woocommerce();
	}
}

if(!function_exists('qode_is_woocommerce_shop')) {
	/**
	 * Function that checks if current page is shop or product page
	 * @return bool
	 *
	 * @see is_shop()
	 */
	function qode_is_woocommerce_shop() {
		return function_exists('is_shop') && is_shop();
	}
}


if(!function_exists('qode_get_woo_shop_page_id')) {
	/**
	 * Function that returns shop page id that is set in WooCommerce settings page
	 * @return int id of shop page
	 */
	function qode_get_woo_shop_page_id() {
		if(qode_is_woocommerce_installed()) {
			return get_option('woocommerce_shop_page_id');
		}
 	}
}

if(!function_exists('qode_woocommerce_columns_class')) {
    /**
     * Function that adds number of columns class to header tag
     * @param array array of classes from main filter
     * @return array array of classes with added bottom header appearance class
     */
    function qode_woocommerce_columns_class($classes) {
        global $qode_options_proya;

        if (qode_is_woocommerce_installed()) {
            $products_list_number = 'columns-4';
            if(isset($qode_options_proya['woo_products_list_number'])){
                $products_list_number = $qode_options_proya['woo_products_list_number'];
            }

            $classes[]= $products_list_number;
        }

        return $classes;
    }

    add_filter('body_class', 'qode_woocommerce_columns_class');
}


if(!function_exists('qode_woocommerce_single_type')) {
	function qode_woocommerce_single_type() {
		$type = '';
		if (qode_is_woocommerce_installed()) {
			$type = qode_options()->getOptionValue('woo_product_single_type');
		}
		return $type;
	}
}

if(!function_exists('qode_woocommerce_single_type_class')) {
	/**
	 * Function that adds single type on body
	 * @param array array of classes from main filter
	 * @return array array of classes with added  single type class
	 */
	function qode_woocommerce_single_type_class($classes) {

		if (qode_is_woocommerce_installed()) {
			$type = qode_woocommerce_single_type();
			if(!empty($type)) {
				$class = 'qode-product-single-' . $type;
				$classes[]= $class;
			}
		}

		return $classes;
	}

	add_filter('body_class', 'qode_woocommerce_single_type_class');
}

if(!function_exists('qode_get_title_text')) {
	/**
	 * Function that returns current page title text. Defines qode_title_text filter
	 * @return string current page title text
	 *
	 * @see is_tag()
	 * @see is_date()
	 * @see is_author()
	 * @see is_category()
	 * @see is_home()
	 * @see is_search()
	 * @see is_404()
	 * @see get_queried_object_id()
	 * @see qode_is_woocommerce_installed()
	 *
	 * @since 4.3
	 * @version 0.1
	 *
	 */
	function qode_get_title_text() {
		global $qode_options_proya;

		$id 	= get_queried_object_id();
		$title 	= '';

		//is current page tag archive?
		if (is_tag()) {
			//get title of current tag
			$title = single_term_title("", false)." Tag";
		}

		//is current page date archive?
		elseif (is_date()) {
			//get current date archive format
			$title = get_the_time('F Y');
		}

		//is current page author archive?
		elseif (is_author()) {
			//get current author name
			$title = __('Author:', 'qode') . " " . get_the_author();
		}

		//us current page category archive
		elseif (is_category()) {
			//get current page category title
			$title = single_cat_title('', false);
		}

		//is current page blog post page and front page? Latest posts option is set in Settings -> Reading
		elseif (is_home() && is_front_page()) {
			//get site name from options
			$title = get_option('blogname');
		}

		//is current page search page?
		elseif (is_search()) {
			//get title for search page
			$title = __('Search', 'qode');
		}

		//is current page 404?
		elseif (is_404()) {
			//is 404 title text set in theme options?
			if($qode_options_proya['404_title'] != "") {
				//get it from options
				$title = $qode_options_proya['404_title'];
			} else {
				//get default 404 page title
				$title = __('404 - Page not found', 'qode');
			}
		}

		//is WooCommerce installed and is shop or single product page?
		elseif(qode_is_woocommerce_installed() && (qode_is_woocommerce_shop() || is_singular('product'))) {
			//get shop page id from options table
			$shop_id = get_option('woocommerce_shop_page_id');

			//get shop page and get it's title if set
			$shop = get_post($shop_id);
			if(isset($shop->post_title) && $shop->post_title !== '') {
				$title = $shop->post_title;
			}

		}

		//is WooCommerce installed and is current page product archive page?
		elseif(qode_is_woocommerce_installed() && (is_product_category() || is_product_tag())) {
			global $wp_query;

			//get current taxonomy and it's name and assign to title
			$tax 			= $wp_query->get_queried_object();
			$category_title = $tax->name;
			$title 			= $category_title;
		}

		//is current page some archive page?
		elseif (is_archive()) {
			$title = __('Archive','qode');
		}

		//current page is regular page
		else {
			$title = get_the_title($id);
		}

		$title = apply_filters('qode_title_text', $title);

		return $title;
	}
}

if(!function_exists('qode_get_woocommerce_pages')) {
	/**
	 * Function that returns all url woocommerce pages
	 * @return array array of WooCommerce pages
	 *
	 * @version 0.1
	 */
	function qode_get_woocommerce_pages() {
		$woo_pages_array = array();

		if(qode_is_woocommerce_installed()) {
			if(get_option('woocommerce_shop_page_id') != ''){ $woo_pages_array[] = get_permalink(get_option('woocommerce_shop_page_id')); }
			if(get_option('woocommerce_cart_page_id') != ''){ $woo_pages_array[] = get_permalink(get_option('woocommerce_cart_page_id')); }
			if(get_option('woocommerce_checkout_page_id') != ''){ $woo_pages_array[] = get_permalink(get_option('woocommerce_checkout_page_id')); }
			if(get_option('woocommerce_pay_page_id') != ''){ $woo_pages_array[] = get_permalink(get_option(' woocommerce_pay_page_id ')); }
			if(get_option('woocommerce_thanks_page_id') != ''){ $woo_pages_array[] = get_permalink(get_option(' woocommerce_thanks_page_id ')); }
			if(get_option('woocommerce_myaccount_page_id') != ''){ $woo_pages_array[] = get_permalink(get_option(' woocommerce_myaccount_page_id ')); }
			if(get_option('woocommerce_edit_address_page_id') != ''){ $woo_pages_array[] = get_permalink(get_option(' woocommerce_edit_address_page_id ')); }
			if(get_option('woocommerce_view_order_page_id') != ''){ $woo_pages_array[] = get_permalink(get_option(' woocommerce_view_order_page_id ')); }
			if(get_option('woocommerce_terms_page_id') != ''){ $woo_pages_array[] = get_permalink(get_option(' woocommerce_terms_page_id ')); }

			$woo_products = get_posts(array('post_type' => 'product','post_status' => 'publish', 'posts_per_page' => '-1') );

			foreach($woo_products as $product) {
				$woo_pages_array[] = get_permalink($product->ID);
			}
		}

		return $woo_pages_array;
	}
}


if(!function_exists('qode_get_woocommerce_archive_pages')) {
	/**
	 * Function that returns all url woocommerce pages
	 * @return array array of WooCommerce pages
	 *
	 * @version 0.1
	 */
	function qode_get_woocommerce_archive_pages() {
		$woo_pages_array = array();

		if(qode_is_woocommerce_installed()) {
			$terms = get_terms( array(
				'taxonomy' => array('product_cat','product_tag'),
				'hide_empty' => false,
			) );

			foreach($terms as $term) {
				$woo_pages_array[] = get_term_link($term->term_id);
			}
		}

		return $woo_pages_array;
	}
}

if(!function_exists('qode_get_page_id')) {
	/**
	 * Function that returns current page / post id.
	 * Checks if current page is woocommerce page and returns that id if it is.
	 * Checks if current page is any archive page (category, tag, date, author etc.) and returns -1 because that isn't
	 * page that is created in WP admin.
	 *
	 * @return int
	 *
	 * @version 0.1
	 *
	 * @see qode_is_woocommerce_installed()
	 * @see qode_is_woocommerce_shop()
	 */
	function qode_get_page_id() {
		if(qode_is_woocommerce_installed() && (qode_is_woocommerce_shop() || is_singular('product'))) {
			return qode_get_woo_shop_page_id();
		}

        if(is_archive() || is_search() || is_404() || (is_home() && is_front_page())) {
			return -1;
		}

		return get_queried_object_id();
	}
}


 ?>