<?php
/**
 * Course Landing Page Pattern
 *
 * @package sensei-lms
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"backgroundColor":"primary","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull has-primary-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
	<!-- wp:media-text {"align":"full","mediaPosition":"right","mediaId":4530,"mediaLink":"http://sensei.test/?attachment_id=4530","mediaType":"image","mediaSizeSlug":"full","verticalAlignment":"center","imageFill":true,"style":{"color":{"text":"#ffffff"},"elements":{"link":{"color":{"text":"var:preset|color|background"}}},"spacing":{"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"backgroundColor":"primary","className":"sensei-pattern-media"} -->
	<div
		class="wp-block-media-text alignfull has-media-on-the-right is-stacked-on-mobile is-vertically-aligned-center is-image-fill sensei-pattern-media has-primary-background-color has-text-color has-background has-link-color"
		style="color:#ffffff;margin-top:0;margin-right:0;margin-bottom:0;margin-left:0">
		<div class="wp-block-media-text__content">
			<!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained","contentSize":"449px","justifyContent":"right"}} -->
			<div class="wp-block-group">
				<!-- wp:group {"style":{"spacing":{"padding":{"bottom":"200px","top":"200px","right":"0em","left":"0"},"blockGap":"2.5rem","margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"427px","justifyContent":"right"}} -->
				<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:200px;padding-right:0em;padding-bottom:200px;padding-left:0">
					<!-- wp:post-title {"level":1} /-->

					<!-- wp:sensei-lms/button-take-course {"backgroundColor":"tertiary","textColor":"primary"} -->
					<div
						class="wp-block-sensei-lms-button-take-course is-style-default wp-block-sensei-button wp-block-button has-text-align-left">
						<button
							class="wp-block-button__link has-primary-color has-tertiary-background-color has-text-color has-background"><?php echo esc_html__( 'Take Course', 'sensei-lms' ); ?></button>
					</div>
					<!-- /wp:sensei-lms/button-take-course -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<figure class="wp-block-media-text__media" style="background-image:url(<?php echo esc_url( Sensei()->assets->get_image( 'patterns-course-landing-page-06.jpg' ) ); ?>);background-position:50% 50%">
			<img src="<?php echo esc_url( Sensei()->assets->get_image( 'patterns-course-landing-page-06.jpg' ) ); ?>" alt="" class="wp-image-4530 size-full"/></figure>
	</div>
	<!-- /wp:media-text -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|80","right":"var:preset|spacing|30","bottom":"var:preset|spacing|80","left":"var:preset|spacing|30"},"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"constrained","contentSize":"660px","wideSize":"1200px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--30)">
	<!-- wp:heading {"style":{"typography":{"letterSpacing":"-0.01em","fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"bottom":"1.25rem"}}},"textColor":"primary","fontFamily":"system"} -->
	<h2 class="has-primary-color has-text-color has-system-font-family"
		style="margin-bottom:1.25rem;font-style:normal;font-weight:600;letter-spacing:-0.01em"><?php echo esc_html__( 'Heading Two', 'sensei-lms' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"0","left":"0"},"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
	<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><?php echo esc_html__( 'La serie prevede 6 episodi, con uscita settimanale dall’8 gennaio 2021. Gli episodi saranno disponibili all’ascolto su Spotify, Spreaker, Apple Podcast e su tutte le principali app gratuite per l’ascolto dei Podcast.', 'sensei-lms' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"0","left":"0"},"margin":{"top":"2.5rem","bottom":"0"},"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
	<div class="wp-block-columns" style="margin-top:2.5rem;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
		<!-- wp:column {"width":"48%","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-column" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;flex-basis:48%">
			<!-- wp:image {"id":1309,"height":592,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"4px","width":"1px"}},"borderColor":"primary"} -->
			<figure class="wp-block-image size-full is-resized has-custom-border"><img
					src="<?php echo esc_url( Sensei()->assets->get_image( 'patterns-course-landing-page-02.jpg' ) ); ?>"
					alt="" class="has-border-color has-primary-border-color wp-image-1309"
					style="border-width:1px;border-radius:4px" height="592"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"bottom","width":"52%"} -->
		<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:52%"></div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0px","bottom":"0px"},"blockGap":{"top":"0","left":"0"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-top" style="margin-top:0px;margin-bottom:0px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
		<!-- wp:column {"verticalAlignment":"top","width":"45%","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
		<div class="wp-block-column is-vertically-aligned-top" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;flex-basis:45%"></div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"top","width":"","style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
		<div class="wp-block-column is-vertically-aligned-top" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
			<!-- wp:quote {"align":"left","className":"is-style-quote-no-spacing is-style-default","fontSize":"small","fontFamily":"system"} -->
			<blockquote
				class="wp-block-quote has-text-align-left is-style-quote-no-spacing is-style-default has-system-font-family has-small-font-size">
				<!-- wp:paragraph {"align":"left","fontSize":"medium","fontFamily":"body"} -->
				<p class="has-text-align-left has-body-font-family has-medium-font-size"><?php echo esc_html__( 'Blog managed by Prof Jean M. Bichè, Chair of Professor of Creative Writing at Université Sacré-Cœur Paris.', 'sensei-lms' ); ?></p>
				<!-- /wp:paragraph --><cite><span
						style="text-decoration: underline;"><?php echo esc_html__( 'The Author', 'sensei-lms' ); ?></span></cite>
			</blockquote>
			<!-- /wp:quote -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","right":"var:preset|spacing|40","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|50","margin":{"top":"0","bottom":"0"}}},"backgroundColor":"primary","textColor":"white","className":"has-system-font-family","layout":{"type":"constrained","contentSize":"1000px","wideSize":"1200px"}} -->
<div
	class="wp-block-group alignfull has-system-font-family has-white-color has-primary-background-color has-text-color has-background"
	style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)">
	<!-- wp:group {"layout":{"type":"constrained","contentSize":"720px"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"style":{"typography":{"lineHeight":"1"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white","fontSize":"x-large","fontFamily":"system"} -->
		<h2 class="has-white-color has-text-color has-system-font-family has-x-large-font-size"
			style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;line-height:1"><?php echo esc_html__( 'What you will learn', 'sensei-lms' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:group {"style":{"border":{"bottom":{"width":"1px"}},"spacing":{"padding":{"bottom":"1.21rem"},"blockGap":"var:preset|spacing|30"}},"textColor":"tertiary","layout":{"type":"default"}} -->
		<div class="wp-block-group has-tertiary-color has-text-color" style="border-bottom-width:1px;padding-bottom:1.21rem">
			<!-- wp:heading {"textAlign":"left","level":4,"style":{"spacing":{"margin":{"top":"0","right":"0","left":"0","bottom":"1.25rem"},"padding":{"top":"0","right":"0","bottom":"0","left":"0"}},"typography":{"fontStyle":"normal","fontWeight":"600","letterSpacing":"-1px","fontSize":"1.5rem","lineHeight":"1"}},"fontFamily":"system"} -->
			<h4 class="has-text-align-left has-system-font-family"
				style="margin-top:0;margin-right:0;margin-bottom:1.25rem;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:1.5rem;font-style:normal;font-weight:600;letter-spacing:-1px;line-height:1">
				<?php echo esc_html__( 'How to Write', 'sensei-lms' ); ?></h4>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontFamily":"body"} -->
			<p class="has-body-font-family"><?php echo esc_html__( 'Sed id risus eleifend, suscipit ante a, malesuada purus. Curabitur sapien ligula, ullamcorper sit amet elit vitae, rutrum tempus.', 'sensei-lms' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"style":{"border":{"bottom":{"width":"1px"}},"spacing":{"padding":{"bottom":"1.21rem","top":"0"},"blockGap":"var:preset|spacing|30"}},"textColor":"tertiary","layout":{"type":"constrained"}} -->
		<div class="wp-block-group has-tertiary-color has-text-color" style="border-bottom-width:1px;padding-top:0;padding-bottom:1.21rem">
			<!-- wp:heading {"textAlign":"left","level":4,"style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"1.25rem","left":"0"},"padding":{"top":"0","right":"0","bottom":"0","left":"0"}},"typography":{"fontStyle":"normal","fontWeight":"600","letterSpacing":"-1px","fontSize":"1.5rem"}},"fontFamily":"system"} -->
			<h4 class="has-text-align-left has-system-font-family"
				style="margin-top:0;margin-right:0;margin-bottom:1.25rem;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:1.5rem;font-style:normal;font-weight:600;letter-spacing:-1px">
				<?php echo esc_html__( 'The right pen', 'sensei-lms' ); ?></h4>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontFamily":"body"} -->
			<p class="has-body-font-family"><?php echo esc_html__( 'Sed id risus eleifend, suscipit ante a, malesuada purus. Curabitur sapien ligula, ullamcorper sit amet elit vitae, rutrum tempus.', 'sensei-lms' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"style":{"border":{"bottom":{"width":"1px"}},"spacing":{"padding":{"bottom":"1.21rem"},"blockGap":"var:preset|spacing|30"}},"textColor":"tertiary","layout":{"type":"constrained"}} -->
		<div class="wp-block-group has-tertiary-color has-text-color" style="border-bottom-width:1px;padding-bottom:1.21rem">
			<!-- wp:heading {"textAlign":"left","level":4,"style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"1.25rem","left":"0"},"padding":{"top":"0","right":"0","bottom":"0","left":"0"}},"typography":{"fontStyle":"normal","fontWeight":"600","letterSpacing":"-1px","fontSize":"1.5rem"}},"fontFamily":"system"} -->
			<h4 class="has-text-align-left has-system-font-family"
				style="margin-top:0;margin-right:0;margin-bottom:1.25rem;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:1.5rem;font-style:normal;font-weight:600;letter-spacing:-1px">
				<?php echo esc_html__( 'Paper or digital', 'sensei-lms' ); ?></h4>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontFamily":"body"} -->
			<p class="has-body-font-family"><?php echo esc_html__( 'Sed id risus eleifend, suscipit ante a, malesuada purus. Curabitur sapien ligula, ullamcorper sit amet elit vitae, rutrum tempus.', 'sensei-lms' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"style":{"border":{"bottom":{"width":"1px"}},"spacing":{"padding":{"bottom":"1.21rem"},"blockGap":"var:preset|spacing|40"}},"textColor":"tertiary","layout":{"type":"constrained"}} -->
		<div class="wp-block-group has-tertiary-color has-text-color" style="border-bottom-width:1px;padding-bottom:1.21rem">
			<!-- wp:heading {"textAlign":"left","level":4,"style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"1.25rem","left":"0"},"padding":{"top":"0","right":"0","bottom":"0","left":"0"}},"typography":{"fontStyle":"normal","fontWeight":"600","letterSpacing":"-1px","fontSize":"1.5rem"}},"fontFamily":"system"} -->
			<h4 class="has-text-align-left has-system-font-family"
				style="margin-top:0;margin-right:0;margin-bottom:1.25rem;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:1.5rem;font-style:normal;font-weight:600;letter-spacing:-1px">
				<?php echo esc_html__( 'The blank paper', 'sensei-lms' ); ?></h4>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontFamily":"body"} -->
			<p class="has-body-font-family"><?php echo esc_html__( 'Curabitur pellentesque lorem vel fermentum suscipit. Vivamus pretium arcu lectus, in volutpat mi condimentum.', 'sensei-lms' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"style":{"spacing":{"padding":{"top":"2.5rem","right":"0rem"}}},"layout":{"type":"default"}} -->
		<div class="wp-block-group" style="padding-top:2.5rem;padding-right:0rem"><!-- wp:sensei-lms/course-actions -->
			<!-- wp:sensei-lms/button-take-course {"backgroundColor":"tertiary","textColor":"primary"} -->
			<div
				class="wp-block-sensei-lms-button-take-course is-style-default wp-block-sensei-button wp-block-button has-text-align-left">
				<button
					class="wp-block-button__link has-primary-color has-tertiary-background-color has-text-color has-background"><?php echo esc_html__( 'Take Course', 'sensei-lms' ); ?></button>
			</div>
			<!-- /wp:sensei-lms/button-take-course -->

			<!-- wp:sensei-lms/button-continue-course {"backgroundColor":"tertiary","textColor":"primary"} -->
			<div
				class="wp-block-sensei-lms-button-continue-course is-style-default wp-block-sensei-button wp-block-button has-text-align-left">
				<a class="wp-block-button__link has-primary-color has-tertiary-background-color has-text-color has-background"><?php echo esc_html__( 'Continue', 'sensei-lms' ); ?></a>
			</div>
			<!-- /wp:sensei-lms/button-continue-course -->

			<!-- wp:sensei-lms/button-view-results {"className":"is-style-default","backgroundColor":"tertiary","textColor":"primary"} -->
			<div
				class="wp-block-sensei-lms-button-view-results is-style-default wp-block-sensei-button wp-block-button has-text-align-left">
				<a class="wp-block-button__link has-primary-color has-tertiary-background-color has-text-color has-background"><?php echo esc_html__( 'Visit Results', 'sensei-lms' ); ?></a>
			</div>
			<!-- /wp:sensei-lms/button-view-results -->
			<!-- /wp:sensei-lms/course-actions -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|80","right":"var:preset|spacing|30","bottom":"var:preset|spacing|80","left":"var:preset|spacing|30"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"830px","justifyContent":"center"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--30)">
	<!-- wp:columns {"isStackedOnMobile":false,"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
	<div class="wp-block-columns is-not-stacked-on-mobile" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
		<!-- wp:column {"style":{"border":{"width":"0px","style":"none"},"spacing":{"blockGap":"0"}}} -->
		<div class="wp-block-column" style="border-style:none;border-width:0px">
			<!-- wp:image {"id":4005,"width":395,"height":593,"sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"4px","width":"1px"}},"borderColor":"primary"} -->
			<figure class="wp-block-image size-large is-resized has-custom-border"><img
					src="<?php echo esc_url( Sensei()->assets->get_image( 'patterns-course-landing-page-01.jpg' ) ); ?>"
					alt=""
					class="has-border-color has-primary-border-color wp-image-4005"
					style="border-width:1px;border-radius:4px" width="395" height="593"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:group {"style":{"spacing":{"blockGap":"1.25rem"}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group">
				<!-- wp:heading {"style":{"typography":{"textTransform":"none","fontStyle":"normal","fontWeight":"600"}},"fontFamily":"system"} -->
				<h2 class="has-system-font-family"
					style="font-style:normal;font-weight:600;text-transform:none"><?php echo esc_html__( 'Meet Jeff', 'sensei-lms' ); ?></h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.3"}}} -->
				<p style="line-height:1.3"><?php echo esc_html__( 'Curabitur pellentesque lorem vel fermentum suscipit. Vivamus pretium arcu lectus, in volutpat mi condimentum.', 'sensei-lms' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:group {"style":{"spacing":{"margin":{"top":"1.95rem"}}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group" style="margin-top:1.95rem">
					<!-- wp:sensei-lms/button-contact-teacher {"className":"is-style-default"} -->
					<div
						class="wp-block-sensei-lms-button-contact-teacher is-style-default wp-block-sensei-button wp-block-button has-text-align-left">
						<a class="wp-block-button__link"><?php echo esc_html__( 'Contact Teacher', 'sensei-lms' ); ?></a>
					</div>
					<!-- /wp:sensei-lms/button-contact-teacher -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"5rem","right":"0","bottom":"5rem","left":"0"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"primary","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-primary-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:5rem;padding-right:0;padding-bottom:5rem;padding-left:0"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"var:preset|spacing|30","bottom":"0","left":"var:preset|spacing|30"}}},"layout":{"type":"constrained","contentSize":"830px"}} -->
	<div class="wp-block-group" style="padding-top:0;padding-right:var(--wp--preset--spacing--30);padding-bottom:0;padding-left:var(--wp--preset--spacing--30)"><!-- wp:columns {"verticalAlignment":"center","isStackedOnMobile":false,"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"blockGap":{"top":"2.5rem","left":"2.5rem"}}},"backgroundColor":"primary"} -->
		<div class="wp-block-columns are-vertically-aligned-center is-not-stacked-on-mobile has-primary-background-color has-background" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:column {"verticalAlignment":"center","width":"50%","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"background"} -->
			<div class="wp-block-column is-vertically-aligned-center has-background-color has-text-color" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;flex-basis:50%"><!-- wp:group {"style":{"spacing":{"padding":{"bottom":"0"},"blockGap":"1.25rem"}},"layout":{"type":"constrained","justifyContent":"right","contentSize":"370px"}} -->
				<div class="wp-block-group" style="padding-bottom:0"><!-- wp:heading {"style":{"typography":{"fontStyle":"normal","fontWeight":"600","letterSpacing":"-0.01em","lineHeight":"1.1"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"fontSize":"x-large","fontFamily":"system"} -->
					<h2 class="has-system-font-family has-x-large-font-size" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-style:normal;font-weight:600;letter-spacing:-0.01em;line-height:1.1"><?php echo esc_html__( 'Jeff at work', 'sensei-lms' ); ?></h2>
					<!-- /wp:heading -->

					<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.3","fontSize":"1.5rem"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"fontFamily":"body"} -->
					<p class="has-body-font-family" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:1.5rem;line-height:1.3"><?php echo esc_html__( 'Sed id risus eleifend, suscipit ante a, malesuada purus. Curabitur sapien ligula, ullamcorper sit amet elit vitae, rutrum tempus.', 'sensei-lms' ); ?></p>
					<!-- /wp:paragraph -->

					<!-- wp:group {"style":{"spacing":{"margin":{"top":"1.94rem"}}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-group" style="margin-top:1.94rem"><!-- wp:sensei-lms/course-actions -->
						<!-- wp:sensei-lms/button-take-course {"backgroundColor":"tertiary","textColor":"primary"} -->
						<div class="wp-block-sensei-lms-button-take-course is-style-default wp-block-sensei-button wp-block-button has-text-align-left"><button class="wp-block-button__link has-primary-color has-tertiary-background-color has-text-color has-background"><?php echo esc_html__( 'Take Course', 'sensei-lms' ); ?></button></div>
						<!-- /wp:sensei-lms/button-take-course -->

						<!-- wp:sensei-lms/button-continue-course {"backgroundColor":"tertiary","textColor":"primary"} -->
						<div class="wp-block-sensei-lms-button-continue-course is-style-default wp-block-sensei-button wp-block-button has-text-align-left"><a class="wp-block-button__link has-primary-color has-tertiary-background-color has-text-color has-background"><?php echo esc_html__( 'Continue', 'sensei-lms' ); ?></a></div>
						<!-- /wp:sensei-lms/button-continue-course -->

						<!-- wp:sensei-lms/button-view-results {"className":"is-style-default","backgroundColor":"tertiary","textColor":"primary"} -->
						<div class="wp-block-sensei-lms-button-view-results is-style-default wp-block-sensei-button wp-block-button has-text-align-left"><a class="wp-block-button__link has-primary-color has-tertiary-background-color has-text-color has-background"><?php echo esc_html__( 'Visit Results', 'sensei-lms' ); ?></a></div>
						<!-- /wp:sensei-lms/button-view-results -->
						<!-- /wp:sensei-lms/course-actions --></div>
					<!-- /wp:group --></div>
				<!-- /wp:group --></div>
			<!-- /wp:column -->

			<!-- wp:column {"verticalAlignment":"center","width":"50%","layout":{"type":"default"}} -->
			<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%"><!-- wp:image {"id":4618,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"4px"}},"className":"is-style-default"} -->
				<figure class="wp-block-image size-full has-custom-border is-style-default"><img src="<?php echo esc_url( Sensei()->assets->get_image( 'patterns-course-landing-page-04.jpg' ) ); ?>" alt="" class="wp-image-4618" style="border-radius:4px"/></figure>
				<!-- /wp:image --></div>
			<!-- /wp:column --></div>
		<!-- /wp:columns --></div>
	<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"5rem","right":"0","bottom":"5rem","left":"0"},"blockGap":"0rem","margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1000px","wideSize":"1200px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:5rem;padding-right:0;padding-bottom:5rem;padding-left:0">
	<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
		<!-- wp:group {"style":{"spacing":{"blockGap":"2.5rem","padding":{"left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"layout":{"type":"constrained","contentSize":"660px"}} -->
		<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
			<!-- wp:image {"align":"center","id":3172,"sizeSlug":"thumbnail","linkDestination":"none","style":{"border":{"radius":"120px","width":"1px"}},"borderColor":"primary","className":"is-style-rounded"} -->
			<figure class="wp-block-image aligncenter size-thumbnail has-custom-border is-style-rounded"><img
					src="<?php echo esc_url( Sensei()->assets->get_image( 'patterns-course-landing-page-05.jpg' ) ); ?>"
					alt=""
					class="has-border-color has-primary-border-color wp-image-3172"
					style="border-width:1px;border-radius:120px"/></figure>
			<!-- /wp:image -->

			<!-- wp:paragraph {"align":"center","style":{"typography":{"textTransform":"uppercase","lineHeight":"1.0"}},"fontSize":"x-large","fontFamily":"heading"} -->
			<p class="has-text-align-center has-heading-font-family has-x-large-font-size" style="line-height:1.0;text-transform:uppercase"><?php echo esc_html__( '“I always wanted to write, and thanks to Cours, I got it right. My writing is clearer, and I can finally get my message across.”', 'sensei-lms' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"align":"center","fontSize":"x-small","fontFamily":"system"} -->
			<p class="has-text-align-center has-system-font-family has-x-small-font-size"><strong>Maya Green</strong> -
				<?php echo esc_html__( 'Student', 'sensei-lms' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0"},"margin":{"top":"0","bottom":"5rem"},"blockGap":"0"}},"layout":{"type":"constrained","contentSize":"1000px","wideSize":"1200px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:5rem;padding-top:0;padding-bottom:0">
	<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":"0"}},"backgroundColor":"primary","textColor":"background","layout":{"type":"default"}} -->
	<div class="wp-block-group has-background-color has-primary-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">
		<!-- wp:group {"style":{"spacing":{"blockGap":"2.5rem"}},"layout":{"type":"constrained","contentSize":"660px"}} -->
		<div class="wp-block-group">
			<!-- wp:heading {"style":{"spacing":{"padding":{"top":"0px","right":"0px","bottom":"0px","left":"18px"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}},"typography":{"textTransform":"uppercase"}},"textColor":"background"} -->
			<h2 class="has-background-color has-text-color"
				style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:18px;text-transform:uppercase"><?php echo esc_html__( 'course lessons', 'sensei-lms' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:sensei-lms/course-outline {"className":"is-style-default"} -->
			<!-- wp:sensei-lms/course-outline-lesson {"title":"<?php echo esc_html__( 'How to write', 'sensei-lms' ); ?>"} /-->

			<!-- wp:sensei-lms/course-outline-lesson {"title":"<?php echo esc_html__( 'The right pen', 'sensei-lms' ); ?>"} /-->

			<!-- wp:sensei-lms/course-outline-lesson {"title":"<?php echo esc_html__( 'Paper or digital', 'sensei-lms' ); ?>"} /-->

			<!-- wp:sensei-lms/course-outline-lesson {"title":"<?php echo esc_html__( 'The blank paper', 'sensei-lms' ); ?>"} /-->

			<!-- wp:sensei-lms/course-outline-lesson {"title":"<?php echo esc_html__( 'Putting it all together', 'sensei-lms' ); ?>"} /-->
			<!-- /wp:sensei-lms/course-outline -->

			<!-- wp:group {"style":{"spacing":{"padding":{"left":"var:preset|spacing|40"}}},"layout":{"type":"constrained","justifyContent":"left"}} -->
			<div class="wp-block-group" style="padding-left:var(--wp--preset--spacing--40)">
				<!-- wp:sensei-lms/button-take-course {"className":"is-style-secondary","backgroundColor":"tertiary","textColor":"primary"} -->
				<div
					class="wp-block-sensei-lms-button-take-course is-style-secondary wp-block-sensei-button wp-block-button has-text-align-left">
					<button
						class="wp-block-button__link has-primary-color has-tertiary-background-color has-text-color has-background"><?php echo esc_html__( 'Take Course', 'sensei-lms' ); ?></button>
				</div>
				<!-- /wp:sensei-lms/button-take-course -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
