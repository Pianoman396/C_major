<?php
    security();
 ?>
<?php
	if(!function_exists('qode_header_meta')) {
	/**
	 * Function that echoes meta data if our seo is enabled
	 */
	function qode_header_meta() {
		global $qode_options_proya;

		if(isset($qode_options_proya['disable_qode_seo']) && $qode_options_proya['disable_qode_seo'] == 'no') {

			$seo_description = get_post_meta(qode_get_page_id(), "qode_seo_description", true);
			$seo_keywords = get_post_meta(qode_get_page_id(), "qode_seo_keywords", true);
			?>

			<?php if($seo_description) { ?>
				<meta name="description" content="<?php echo $seo_description; ?>">
			<?php } else if($qode_options_proya['meta_description']){ ?>
				<meta name="description" content="<?php echo $qode_options_proya['meta_description'] ?>">
			<?php } ?>

			<?php if($seo_keywords) { ?>
				<meta name="keywords" content="<?php echo $seo_keywords; ?>">
			<?php } else if($qode_options_proya['meta_keywords']){ ?>
				<meta name="keywords" content="<?php echo $qode_options_proya['meta_keywords'] ?>">
			<?php }
		}

	}

	add_action('qode_header_meta', 'qode_header_meta');
}

if(!function_exists('qode_user_scalable_meta')) {
	/**
	 * Function that outputs user scalable meta if responsiveness is turned on
	 * Hooked to qode_header_meta action
	 */
	function qode_user_scalable_meta() {
		global $qode_options_proya;

		//is responsiveness option is chosen?
		if (isset($qode_options_proya['responsiveness']) && $qode_options_proya['responsiveness'] !== 'no') { ?>
			<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
		<?php }	else { ?>
			<meta name="viewport" content="width=1200,user-scalable=no">
		<?php }
	}

	add_action('qode_header_meta', 'qode_user_scalable_meta');
}

if(!function_exists('qode_get_attachment_meta')) {
	/**
	 * Function that returns attachment meta data from attachment id
	 * @param $attachment_id
	 * @param array $keys sub array of attachment meta
	 * @return array|mixed
	 */
	function qode_get_attachment_meta($attachment_id, $keys = array()) {
		$meta_data = array();

		//is attachment id set?
		if(!empty($attachment_id)) {
			//get all post meta for given attachment id
			$meta_data = get_post_meta($attachment_id, '_wp_attachment_metadata', true);

			//is subarray of meta array keys set?
			if(is_array($keys) && count($keys)) {
				$sub_array = array();

				//for each defined key
				foreach($keys as $key) {
					//check if that key exists in all meta array
					if(array_key_exists($key, $meta_data)) {
						//assign key from meta array for current key to meta subarray
						$sub_array[$key] = $meta_data[$key];
					}
				}

				//we want meta array to be subarray because that is what used wants to get
				$meta_data = $sub_array;
			}
		}

		//return meta array
		return $meta_data;
	}
}


if(!function_exists('qode_get_attachment_meta_from_url')) {
	/**
	 * Function that returns meta array for give attachment url
	 * @param $attachment_url
	 * @param array $keys sub array of attachment meta
	 * @return array|mixed
	 *
	 * @see qode_get_attachment_id_from_url()
	 * @see qode_get_attachment_meta()
	 *
	 * @version 0.1
	 */
	function qode_get_attachment_meta_from_url($attachment_url, $keys = array()) {
		$attachment_meta = array();

		//get attachment id for attachment url
		$attachment_id 	= qode_get_attachment_id_from_url($attachment_url);

		//is attachment id set?
		if(!empty($attachment_id)) {
			//get post meta
			$attachment_meta = qode_get_attachment_meta($attachment_id, $keys);
		}

		//return post meta
		return $attachment_meta;
	}
}


 ?>