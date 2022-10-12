<?php
/**
 * Core 404 Hero content.
 */
return array(
	'title'      => __( 'Core 404 Hero', 'hypebiz' ),
	'categories' => array( 'hypebiz-basic' ),
	'content'    => '<!-- wp:group {"tagName":"main","style":{"spacing":{"padding":{"bottom":"0px"}}},"layout":{"wideSize":"","contentSize":""}} -->
			<main class="wp-block-group" style="padding-bottom:0px"><!-- wp:cover {"url":"' . esc_url( HYPEBIZ_URI ) . 'assets/img/african-american-african-descent-afro-analyzing-beverage-black-people-1570921-pxhere.com.webp","id":260,"dimRatio":60,"overlayColor":"fifth","focalPoint":{"x":"0.48","y":"0.51"},"contentPosition":"center center"} -->
			<div class="wp-block-cover"><span aria-hidden="true" class="wp-block-cover__background has-fifth-background-color has-background-dim-60 has-background-dim"></span><img class="wp-block-cover__image-background wp-image-260" alt="" src="' . esc_url( HYPEBIZ_URI ) . 'assets/img/african-american-african-descent-afro-analyzing-beverage-black-people-1570921-pxhere.com.webp" style="object-position:48% 51%" data-object-fit="cover" data-object-position="48% 51%"/><div class="wp-block-cover__inner-container"><!-- wp:group {"tagName":"main","style":{"spacing":{"padding":{"top":"260px","bottom":"260px"}}},"textColor":"seventh","layout":{"inherit":true}} -->
			<main class="wp-block-group has-seventh-color has-text-color" style="padding-top:260px;padding-bottom:260px"><!-- wp:columns -->
			<div class="wp-block-columns"><!-- wp:column -->
			<div class="wp-block-column"><!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"bottom":"20px"}},"typography":{"fontSize":"64px"}},"textColor":"white"} -->
			<h2 class="has-text-align-center has-white-color has-text-color" style="font-size:64px;margin-bottom:20px">Page Not Found</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","textColor":"white"} -->
			<p class="has-text-align-center has-white-color has-text-color">It looks like nothing was found at this location. Maybe try a search?</p>
			<!-- /wp:paragraph -->

			<!-- wp:spacer {"height":"20px"} -->
			<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- wp:search {"label":"Search...","showLabel":false,"width":75,"widthUnit":"%","buttonText":"Search","buttonUseIcon":true,"align":"center","style":{"border":{"radius":"0px","width":"0px","style":"none"}},"backgroundColor":"third","textColor":"white"} /--></div>
			<!-- /wp:column --></div>
			<!-- /wp:columns --></main>
			<!-- /wp:group --></div></div>
			<!-- /wp:cover --></main>
			<!-- /wp:group -->',
);
