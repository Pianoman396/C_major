<?php
    security();
 ?>
<?php

if(!function_exists('qode_get_image_dimensions')) {
	/**
	 * Function that returns image sizes array. First looks in post_meta table if attachment exists in the database,
	 * if it doesn't than it uses getimagesize PHP function to get image sizes
	 * @param $url string url of the image
	 * @return array array of image sizes that containes height and width
	 *
	 * @see qode_get_attachment_meta_from_url()
	 * @uses getimagesize
	 *
	 * @version 0.1
	 */
	function qode_get_image_dimensions($url) {
		$image_sizes = array();

		//is url passed?
		if($url !== '') {
			//get image sizes from posts meta if attachment exists
			$image_sizes = qode_get_attachment_meta_from_url($url, array('width', 'height'));

			//image does not exists in post table, we have to use PHP way of getting image size
			if(!count($image_sizes)) {
				//can we open file by url?
				if(ini_get('allow_url_fopen') == 1 && file_exists($url)) {
					list($width, $height, $type, $attr) = getimagesize($url);
				} else {
					//we can't open file directly, have to locate it with relative path.
					$image_obj = parse_url($url);
					$image_relative_path = $_SERVER['DOCUMENT_ROOT'].$image_obj['path'];

					if(file_exists($image_relative_path)) {
						list($width, $height, $type, $attr) = getimagesize($image_relative_path);
					}
				}

				//did we get width and height from some of above methods?
				if(isset($width) && isset($height)) {
					//set them to our image sizes array
					$image_sizes = array(
						'width' => $width,
						'height' => $height
					);
				}
			}
		}

		return $image_sizes;
	}
}

if(!function_exists('qode_set_logo_sizes')) {
	/**
	 * Function that sets logo image dimensions to global qode options array so it can be used in the theme
	 */
	function qode_set_logo_sizes() {
		global $qode_options_proya;

		//get logo image size
		$logo_image_sizes = qode_get_image_dimensions($qode_options_proya['logo_image']);
		$qode_options_proya['logo_width'] = 280;
		$qode_options_proya['logo_height'] = 130;

		//is image width and height set?
		if(isset($logo_image_sizes['width']) && isset($logo_image_sizes['height'])) {
			//set those variables in global array
			$qode_options_proya['logo_width'] = $logo_image_sizes['width'];
			$qode_options_proya['logo_height'] = $logo_image_sizes['height'];
		}
	}

	//not used at the moment, so there is no need for action
	//add_action('init', 'qode_set_logo_sizes', 0);
}


if(!function_exists('qode_theme_setup')) {
    /**
     * Function that adds various features to theme. Also defines image sizes that are used in a theme
     */
    function qode_theme_setup() {
        //add post formats support
        add_theme_support('post-formats', array('gallery', 'link', 'quote', 'video', 'audio'));

        //add feedlinks support
        add_theme_support( 'automatic-feed-links' );

        //add theme support for post thumbnails
        add_theme_support( 'post-thumbnails' );

        add_image_size( 'portfolio-square', 570, 570, true );
        add_image_size( 'portfolio-portrait', 600, 800, true );
        add_image_size( 'portfolio-landscape', 800, 600, true );
        add_image_size( 'menu-featured-post', 345, 198, true );
        add_image_size( 'qode-carousel_slider', 400, 260, true );
        add_image_size( 'portfolio_slider', 500, 380, true );
        add_image_size( 'portfolio_masonry_regular', 500, 500, true );
        add_image_size( 'portfolio_masonry_wide', 1000, 500, true );
        add_image_size( 'portfolio_masonry_tall', 500, 1000, true );
        add_image_size( 'portfolio_masonry_large', 1000, 1000, true );
        add_image_size( 'portfolio_masonry_with_space', 700);
        add_image_size( 'latest_post_boxes', 539, 303, true );

        //enable rendering shortcodes in widgets
        add_filter('widget_text', 'do_shortcode');

        //enable rendering shortcodes in post excerpt
        //add_filter( 'the_excerpt', 'do_shortcode');

        //enable rendering shortcodes in call to action
        add_filter( 'call_to_action_widget', 'do_shortcode');

        load_theme_textdomain( 'qode', get_template_directory().'/languages' );
    }

    add_action('after_setup_theme', 'qode_theme_setup');
}

if (!function_exists('comparePortfolioImages')) {
	/**
	 * Function that compares two portfolio image for sorting
	 * @param $a int first image
	 * @param $b int second image
	 * @return int result of comparison
	 */
	function comparePortfolioImages($a, $b) {
		if (isset($a['portfolioimgordernumber']) && isset($b['portfolioimgordernumber'])) {
		if ($a['portfolioimgordernumber'] == $b['portfolioimgordernumber']) {
			return 0;
		}
		return ($a['portfolioimgordernumber'] < $b['portfolioimgordernumber']) ? -1 : 1;
	  }
	  return 0;
	}
}


if (!function_exists('qode_gallery_upload_get_images')) {
	/**
	 * Function that outputs gallery list item for portfolio in portfolio admin page
	 *
	 */
	function qode_gallery_upload_get_images() {
		$ids=$_POST['ids'];
		$ids=explode(",",$ids);
		foreach($ids as $id):
			$image = wp_get_attachment_image_src($id,'thumbnail', true);
			echo '<li class="qode-gallery-image-holder"><img src="'.$image[0].'"/></li>';
		endforeach;
		exit;
	}

	add_action( 'wp_ajax_qode_gallery_upload_get_images', 'qode_gallery_upload_get_images');
}


/* Use slider instead of image for post */

if (!function_exists('slider_blog')) {
    function slider_blog($post_id) {
        $sliders = get_post_meta($post_id, "qode_sliders", true);
        $slider = $sliders[1];
        if($slider) {
            $html = "";
            $html .= '<div class="flexslider"><ul class="slides">';
            $i=0;
            while (isset($slider[$i])){
                $slide = $slider[$i];

                $href = $slide[link];
                $baseurl = home_url();
                $baseurl = str_replace('http://', '', $baseurl);
                $baseurl = str_replace('www', '', $baseurl);
                $host = parse_url($href, PHP_URL_HOST);
                if($host != $baseurl) {
                    $target = 'target="_blank"';
                }
                else {
                    $target = 'target="_self"';
                }

                $html .= '<li class="slide ' . $slide[imgsize] . '">';
                $html .= '<div class="image"><img src="' . $slide[img] . '" alt="' . $slide[title] . '" /></div>';

                $html .= '</li>';
                $i++;
            }
            $html .= '</ul></div>';
        }
        return $html;
    }
}


 ?>