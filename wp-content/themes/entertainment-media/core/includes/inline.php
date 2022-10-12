<?php


$entertainment_media_custom_css = '';

	/*---------------------------Transform -------------------*/
	
	$entertainment_media_text_transform = get_theme_mod( 'menu_text_transform_entertainment_media','CAPITALISE');
    if($entertainment_media_text_transform == 'CAPITALISE'){

		$entertainment_media_custom_css .='#main-menu ul li a{';

			$entertainment_media_custom_css .='text-transform: capitalize ; font-size: 14px !important;';

		$entertainment_media_custom_css .='}';

	}else if($entertainment_media_text_transform == 'UPPERCASE'){

		$entertainment_media_custom_css .='#main-menu ul li a{';

			$entertainment_media_custom_css .='text-transform: uppercase ; font-size: 14px !important';

		$entertainment_media_custom_css .='}';

	}else if($entertainment_media_text_transform == 'LOWERCASE'){

		$entertainment_media_custom_css .='#main-menu ul li a{';

			$entertainment_media_custom_css .='text-transform: lowercase ; font-size: 14px !important';

		$entertainment_media_custom_css .='}';
	}

	