<?php
/**
 * Twenty Twelve functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package MIT_Libraries_Parent
 * @since 1.2.1
 */

/* Globals */

$gStudy24Url = '/study/24x7/';

// Use newsBlog for live site.
	$newsBlog = 7;
	$mainSite = 1;

// Sample value: $siteRoot = "/var/www/vhosts/seangw.com/mitlibraries".
$siteRoot = $_SERVER['DOCUMENT_ROOT'];
foreach ( glob( $siteRoot . '/wp-content/themes/libraries/lib/*.php' ) as $file ) { require_once( $file ); }

/**
 * Sets up the content width value based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 625; }

/**
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Twelve supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 *       custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_setup() {
	/*
	 * Makes Twenty Twelve available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Twelve, use a find and replace
	 * to change 'twentytwelve' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentytwelve', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	// add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );
// Register Custom Navigation Walker.
require_once( 'navwalker.php' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'twentytwelve' ) );
	register_nav_menu( 'secondary', __( 'Secondary Menu', 'twentytwelve' ) );
	register_nav_menu( 'footer', __( 'Footer Menu', 'twentytwelve' ) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop.
}
add_action( 'after_setup_theme', 'twentytwelve_setup' );

/**
 * Adds support for a custom header image.
 */
// This was: require( get_template_directory() . '/inc/custom-header.php' ).
/**
 * Enqueues scripts and styles for front-end.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' ); }

	/*
	 * Loads our main stylesheet.
	 */
	wp_register_style( 'font-open-sans', '//fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,600,600italic,700,700italic', false, null, 'all' );

	wp_register_style( 'font-khula', '//fonts.googleapis.com/css?family=Khula:400,400italic,600,600italic,700,700italic', false, null, 'all' );

	wp_enqueue_style( 'twentytwelve-style', get_stylesheet_uri() );

	wp_register_style( 'libraries-global', get_template_directory_uri() . '/css/build/minified/global.css', array( 'twentytwelve-style', 'font-open-sans', 'font-khula' ), '1.5.5' );

	wp_enqueue_style( 'libraries-global' );

	wp_register_style( 'get-it', get_template_directory_uri() . '/css/build/minified/get-it.min.css', array( 'libraries-global' ), '1.5.5' );

	wp_register_style( 'hours-mobile', get_template_directory_uri() . '/css/hours-mobile.css', false, null, 'all' );

	wp_register_style( 'hours-gldatepicker', get_template_directory_uri() . '/libs/datepicker/styles/glDatePicker.default.css', false, null, 'all' );

	wp_register_style( 'hours', get_template_directory_uri() . '/css/build/minified/hours.min.css', array( 'libraries-global', 'hours-mobile', 'hours-gldatepicker' ), '1.5.5' );

	wp_register_style( 'bootstrapCSS', get_stylesheet_directory_uri() . '/css/bootstrap.css', 'false', '', false );

	wp_register_style( 'jquery.smartmenus.bootstrap', '/css/bootstrap-css/jquery.smartmenus.bootstrap.js', false, false );

	/*
	 * Loads the Internet Explorer specific stylesheet.
	 */
	wp_enqueue_style( 'twentytwelve-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentytwelve-style' ), '20121010' );
	$wp_styles->add_data( 'twentytwelve-ie', 'conditional', 'lt IE 9' );

	/*  Register JS */

	// Deregister WP Core jQuery, load Google's.
	wp_deregister_script( 'jquery' );

	wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js', array(), '1.11.1', false );

	wp_register_script( 'bootstrap-js', '//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js', array( 'jquery' ), true ); // All the bootstrap javascript goodness.

	wp_register_script( 'jquery.smartmenus', '/js/bootstrap-js/jquery.smartmenus.js', array( 'jquery' ), true ); // All the bootstrap javascript goodness.

	wp_register_script( 'bootstrap-min', '/js/bootstrap-js/bootstrap.min.js', array( 'jquery' ), true ); // All the bootstrap javascript goodness.

	wp_register_script( 'jquery.smartmenus.bootstrap.min', '/js/bootstrap-js/jquery.smartmenus.bootstrap.min.js', array( 'jquery' ), true ); // All the bootstrap javascript goodness.

	wp_register_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.js', array(), '2.8.1', false );

	wp_register_script( 'homeJS', get_template_directory_uri() . '/js/build/home.min.js', array( 'jquery', 'modernizr' ), '1.5.5', true );

	wp_register_script( 'productionJS', get_template_directory_uri() . '/js/build/production.min.js', array( 'jquery' ), '1.5.5', true );

	wp_register_script( 'hours-gldatepickerJS', get_template_directory_uri() . '/libs/datepicker/glDatePicker.min.js', false, null, true );

	wp_register_script( 'hoursJS', get_template_directory_uri() . '/js/build/hours.min.js', array( 'jquery', 'productionJS', 'hours-gldatepickerJS' ), '1.5.5', true );

	wp_register_script( 'searchJS', get_template_directory_uri() . '/js/build/search.min.js', array( 'jquery', 'modernizr' ), '1.5.5', false );

	wp_register_script( 'mapJS', get_template_directory_uri() . '/js/build/map.min.js', array( 'jquery' ), '1.5.5', true );

	wp_register_script( 'googleMapsAPI', '//maps.googleapis.com/maps/api/js?sensor=false', array(), false, true );

	wp_register_script( 'infobox', get_template_directory_uri() . '/libs/infobox/infobox.js', array( 'googleMapsAPI' ), '1.1.12', true );

	wp_register_script( 'term-hours', get_template_directory_uri() . '/js/build/term-hours.min.js', array( 'jquery', 'productionJS' ), false, true );

	wp_register_script( 'moment', '//' . $_SERVER['SERVER_NAME'] . '/app/libhours/js/vendor/moment.js', false, false, true );

	wp_register_script( 'tabletop', '//' . $_SERVER['SERVER_NAME'] . '/app/libhours/js/vendor/tabletop.js', false, false, true );

	wp_register_script( 'underscore', '//' . $_SERVER['SERVER_NAME'] . '/app/libhours/js/vendor/underscore.js', false, false, true );

	wp_register_script( 'lib-hours', '//' . $_SERVER['SERVER_NAME'] . '/app/libhours/js/libhours.js', array( 'moment', 'tabletop', 'underscore' ), false, true );

	wp_register_script( 'forms', get_template_directory_uri() . '/js/login_functions.js', array(), '1.0.0', false );

	/* All-site JS */

	wp_enqueue_script( 'modernizr' );

	wp_enqueue_script( 'lib-hours' );

	wp_enqueue_script( 'forms' );

	/* Page-specific JS & CSS */

	if ( ! is_front_page() || is_child_theme() ) {
		wp_enqueue_script( 'productionJS' );
	}

	if ( is_front_page() && ! is_child_theme() ) {
		wp_enqueue_script( 'homeJS' );
	}

	if ( is_page( 'hours' ) ) {
		wp_enqueue_style( 'hours' );
		wp_enqueue_script( 'hoursJS' );
	}

	if ( is_page( 'locations' ) ) {
		wp_enqueue_script( 'googleMapsAPI' );
		wp_enqueue_script( 'mapJS' );
		wp_enqueue_script( 'infobox' );
	}

	if ( is_page( 'search' ) ) {
		wp_enqueue_script( 'searchJS' );
	}

	if ( is_page( 'term-hours' ) ) {
		wp_enqueue_script( 'term-hours' );
	}

	if ( is_page( 'getit' ) ) {
		wp_enqueue_style( 'get-it' );
	}

	if ( is_page_template( 'nav-maine' ) ) {
		wp_enqueue_style( 'jquery.smartmenus.bootstrap' );
		wp_enqueue_script( 'bootstrap.min' );
		wp_enqueue_script( 'jquery.smartmenus.bootstrap.min' );
		wp_enqueue_script( 'jquery.smartmenus' );
	}

	if ( in_category( 'has-menu' ) ) {
		wp_enqueue_style( 'libraries-global' );
		wp_enqueue_style( 'bootstrapCSS' );
		wp_enqueue_script( 'bootstrap-js' );
	}

}

add_action( 'wp_enqueue_scripts', 'twentytwelve_scripts_styles' );



/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Twenty Twelve 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function twentytwelve_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title; }

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description"; }

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		// Translators: Page number for multi-page content.
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentytwelve' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'twentytwelve_wp_title', 10, 2 );

/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) ) {
		$args['show_home'] = true; }
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentytwelve_page_menu_args' );

/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function mitlib_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentytwelve' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s" role="complementary">',
		'after_widget' => '</aside>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'First Front Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-2',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s" role="complementary">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Second Front Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-3',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s" role="complementary">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Masthead Search Bar', 'twentytwelve' ),
		'id' => 'sidebar-search',
		'description' => __( 'Appears under the MIT Libraries masthead, and houses the search interface', 'twentytwelve' ),
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		'class' => '',
	) );
}
add_action( 'widgets_init', 'mitlib_widgets_init' );

if ( ! function_exists( 'twentytwelve_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
			<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentytwelve' ) ); ?></div>
			<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}
endif;

if ( ! function_exists( 'twentytwelve_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentytwelve_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'twentytwelve' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite class="fn">%1$s %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span> ' . __( 'Post author', 'twentytwelve' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'twentytwelve' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentytwelve' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'twentytwelve' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'twentytwelve' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // End comment_type check.
}
endif;

if ( ! function_exists( 'twentytwelve_entry_meta' ) ) :
/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own twentytwelve_entry_meta() to override in a child theme.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'twentytwelve' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentytwelve' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		// Translators: View all posts by a given author.
		esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		// Translators: Category, tag, date, and author values.
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} elseif ( $categories_list ) {
		// Translators: Category, date, and author values.
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} else {
		// Translators: Date and author values.
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

if ( ! function_exists( 'is_child_page' ) ) {
	function is_child_page() {
	global $post;     // If outside the loop.

	if ( is_page() && $post->post_parent ) {
	return $post->post_parent;
	} else {
	return false;
	}
}
}

/**
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. Single or multiple authors.
 *
 * @since Twenty Twelve 1.0
 *
 * @param array Existing class values.
 * @return array Filtered class values.
 */
function twentytwelve_body_class( $classes ) {
	global $post;

	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) ) {
		$classes[] = 'full-width'; }

	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';
		if ( has_post_thumbnail() ) {
			$classes[] = 'has-post-thumbnail'; }
		if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) ) {
			$classes[] = 'two-sidebars'; }
	}

	if ( is_child_theme() ) {
		$classes[] = 'childTheme';
	}

	if ( is_child_page() ) {
		$classes[] = 'childPage';
	}

	if ( is_page_template( 'page-selfTitle.php' ) ) {
		$classes[] = 'boxSizingOn';
	}

	if ( is_page_template( 'page-location.php' ) ) {
		$classes[] = 'locationPage';
	}

	if ( ! is_multi_author() ) {
		$classes[] = 'single-author'; }

	return $classes;
}
add_filter( 'body_class', 'twentytwelve_body_class' );

add_filter( 'wp_get_attachment_url', 'set_url_scheme' );



/**
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_content_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'twentytwelve_content_width' );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @since Twenty Twelve 1.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function twentytwelve_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}
add_action( 'customize_register', 'twentytwelve_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_customize_preview_js() {
	wp_enqueue_script( 'twentytwelve-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20120827', true );
}
add_action( 'customize_preview_init', 'twentytwelve_customize_preview_js' );


/** Unique for theme **/

function getParent( $id ) {

}
function getRoot( $post ) {
	$ar = get_post_ancestors( $post );

	$is_section = get_post_meta( $post->ID, 'is_section', 1 );

	for ( $i = 0;$i < count( $ar );$i++ ) {
		$pid = $ar[ $i ];
		$is_section = get_post_meta( $pid, 'is_section', 1 );
		if ( $is_section == 1 ) {
			return $pid;
		}
	}

	$max = count( $ar ) - 1;

	if ( $max == -1 ) {
		return $post->ID;
	} else {
		return $ar[ $max ];
	}

}

function the_breadcrumb() {
	if ( ! is_home() ) {
		echo '<a href="';
		echo get_option( 'home' );
		echo '">';
		bloginfo( 'name' );
		echo '</a> &raquo; ';
		if ( is_category() || is_single() ) {
			the_category( 'title_li=' );
			if ( is_single() ) {
				echo ' &raquo; ';
				the_title();
			}
		} elseif ( is_page() ) {
			echo the_title();
		}
	}
}

function wsf_make_link( $url, $anchortext, $title = null, $nofollow = false ) {
	if ( null === $title ) {
		$title = $anchortext;
	}
	$nofollow == true ? $rel = ' rel="nofollow"' : $rel = '';

	$link = sprintf( '<a href="%s" title="%s" %s="">%s</a>', $url, $title, $rel, $anchortext );
	return $link;
}

function showBreadTitle() {
	// Wordpess function that echoes your post title.
	$custom_title = get_post_meta( $post->ID, 'breadcrumb_override', 1 );
	$custom_title = $custom_title[0];
	// $custom_title = get_field("breadcrumb_override");
	if ( $custom_title != '' ) {
	 echo $custom_title;
	} else {
	 the_title();
	}
}

function wsf_breadcrumbs( $sep = '/', $label = 'Browsing' ) {

	global $post;

	// Do not show breadcrumbs on home or front pages.
	// So we will just return quickly.
	if ( ( is_home() || is_front_page() ) && ( ! $front_page ) ) {
	  return; }

	// Create a constant for the separator, with space padding.
	$SEP = ' ' . $sep . ' ';

	echo '<div class="breadcrumbs">';

	echo wsf_make_link( get_bloginfo( 'url' ), 'Home', get_bloginfo( 'name' ), true ) . $SEP;

	if ( is_single() ) {
	the_category( ', ' );
echo $SEP;
	} elseif ( is_page() ) {
			$parent_id = $post->post_parent;
			$parents = array();
			while ( $parent_id ) {
				$page = get_page( $parent_id );
			$parents[]  = wsf_make_link( get_permalink( $page->ID ), get_the_title( $page->ID ) ) . $SEP;
				$parent_id  = $page->post_parent;
			}
			$parents = array_reverse( $parents );
			foreach ( $parents as $parent ) {
				echo $parent;
			}
	}
	// Wordpess function that echoes your post title.
	$custom_title = get_field( 'breadcrumb_override' );

	showBreadTitle();
	 echo '</div>';
}

function cf( $name ) {
	return get_post_meta( get_the_ID(), $name, true );
}

function remove_template( $files_to_delete = array() ) {
	global $wp_themes;

	// As convenience, allow a single value to be used as a scalar without wrapping it in a useless array().
	if ( is_scalar( $files_to_delete ) ) {
		$files_to_delete = array( $files_to_delete );
	}

	// Remove TLA if it was provided.
	$files_to_delete = preg_replace( '/\.[^.]+$/', '', $files_to_delete );

	// Populate the global $wp_themes array.
	wp_get_themes();

	$current_theme_name = wp_get_theme();

	// Note that we're taking a reference to $wp_themes so we can modify it in-place.
	$template_files = &$wp_themes[ $current_theme_name ]['Template Files'];

	foreach ( $template_files as $file_path ) {
		foreach ( $files_to_delete as $file_name ) {
			if ( preg_match( '/\/' . $file_name . '\.[^.]+$/', $file_path ) ) {
				$key = array_search( $file_path, $template_files );
				if ( $key ) { unset( $template_files[ $key ] ); }
			}
		}
	}
}

function menuWithParent( $menu, $par ) {
	$menu_items = wp_get_nav_menu_items( $menu );

	$arOut = array();

	foreach ( $menu_items as $key => $item ) {
		if ( $item->menu_item_parent == $par ) {
			array_push( $arOut, $item );
		}
	}

	return $arOut;

}

if ( ! function_exists( 'better_breadcrumbs' ) ) {

	function better_breadcrumbs() {

		global $post;

		if ( is_search() ) {
			echo '<span>Search</span>';
		}

		if ( ! is_child_page() && is_page() || is_category() || is_single() ) {
			echo '<span>' . the_title() . '</span>';
			return;
		}

		if ( is_child_page() ) {
			$hideParent = get_field( 'hide_parent_breadcrumb' );
			$parentLink = get_permalink( $post->post_parent );
			$parentTitle = get_the_title( $post->post_parent );
			$startLink = '<a href="';
			$endLink = '">';
			$closeLink = '</a>';
			$parentBreadcrumb = $startLink . $parentLink . $endLink . $parentTitle . $closeLink;
			$pageTitle = get_the_title( $post );
			$pageLink = get_permalink( $post );
			$childBreadcrumb = $startLink . $pageLink . $endLink . $pageTitle . $closeLink;

			if ( $parentBreadcrumb != '' && $hideParent != 1 ) {echo '<span>' . $parentBreadcrumb . '</span>';}
			if ( $childBreadcrumb != '' ) {echo '<span>' . $pageTitle . '</span>';}
		}
	}

	add_action( 'after_setup_theme', 'better_breadcrumbs' );
}

// Check for performance issues.
function no_post_limit( $query ) {
	if ( is_home() && ! is_child_theme() ) {
	// No post limit on homepage.
	$query->set( 'posts_per_page', -1 );
	return;
	}
}
add_action( 'pre_get_posts', 'no_post_limit', 1 );

// Prevent Wordpress from "guessing" redirects instead of showing a 404 page.
if ( ! function_exists( 'stop_404_guessing' ) ) {
	add_filter( 'redirect_canonical', 'stop_404_guessing' );
	function stop_404_guessing( $url ) {
		if ( is_404() ) {
			return false;
		}
		return $url;
	}
}


// First make all metaboxes have 'normal' context.
// If you know the ids of the metaboxes, you could add them here and skip the next function altogether.
add_filter( 'get_user_option_meta-box-order_post', 'one_column_for_all', 10, 1 );
function one_column_for_all( $option ) {
	$result['normal'] = 'submitdev, postexcerpt,formatdiv,trackbacksdiv,tagsdiv,post_tag,categorydiv,postimagediv,postcustom,commentstatusdiv,slugdiv,authordiv';
	$result['side'] = '';
	$result['advanced'] = '';
	return $result;
}

// Then we add 'submitdiv' on the bottom, by creating this filter with a low priority.
// It feels a bit like overkill, because it assumes other plug-ins might be using the same filter, but still...
add_filter( 'get_user_option_meta-box-order_post', 'submitdiv_at_top', 1, 1 );
function submitdiv_at_top( $result ) {
	$result['normal'] .= 'submitdiv';
	return $result;
}

add_filter( 'get_user_option_meta-box-order_{page}', 'metabox_order' );
function metabox_order( $order ) {
	return array(
		'normal' => join(
			',',
			array(       // vvv  Arrange here as you desire.
				'submitdiv',
				'pageparentdiv',
				'dmm-meta-box',
				'prfx_meta',
				'categorydiv',
				'tagsdiv-post_tag',
				'postimagediv',
			)
		),
	);
}


/**
 * Adds custom fields to 'post' and 'experts' API endpoints
 *
 * @link http://v2.wp-api.org/extending/modifying/
 * @link https://gist.github.com/rileypaulsen/9b4505cdd0ac88d5ef51#gistcomment-1622466
 */
function mitlib_api_alter() {
	// Add custom fields to posts endpoint.
	register_rest_field( 'post',
		'meta',
		array(
			'get_callback'    => function( $data, $field, $request, $type ) {
				if ( function_exists( 'get_fields' ) ) {
					return get_fields( $data['id'] );
				}
				return array();
			},
			'update_callback' => null,
			'schema'          => null,
		)
	);

	// Add custom fields to experts endpoint.
	register_rest_field( 'experts',
		'meta',
		array(
			'get_callback'    => function( $data, $field, $request, $type ) {
				if ( function_exists( 'get_fields' ) ) {
					return get_fields( $data['id'] );
				}
				return array();
			},
			'update_callback' => null,
			'schema'          => null,
		)
	);

	// Switch featured_media field from media ID to URL of the image.
	register_rest_field( 'experts',
		'featured_media',
		array(
			'get_callback'    => 'mitlib_api_get_image',
			'update_callback' => null,
			'schema'          => null,
		)
	);
}


/**
 * This construct will swap between augmentation of the V1 and V2 API.
 */
if ( function_exists( 'register_rest_field' ) ) {
	// The register_rest_field function was introduced in the v2 API.
	// If that exists, then we call the function to augment that API.
	add_action( 'rest_api_init', 'mitlib_api_alter' );
}

/**
 * Get the value of a specified field for use in the API
 *
 * @param array  $object Details of current post.
 * @param string $field_name Name of field.
 *
 * @return mixed
 */
function mitlib_api_get_image( $object, $field_name ) {
	$link = wp_get_attachment_image_src( $object[ $field_name ], 'thumbnail-size' );
	return $link[0];
}

// Allows SVGs to be uploaded through media.
function cc_mime_types( $mimes ) {
$mimes['svg'] = 'image/svg+xml';
return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

/**
 * Force URLs in srcset attributes into HTTPS scheme.
 *
 * @link https://wordpress.org/support/topic/responsive-images-src-url-is-https-srcset-url-is-http-no-images-loaded?replies=19#post-7767555
 */
function ssl_srcset( $sources ) {
	foreach ( $sources as &$source ) {
		$source['url'] = set_url_scheme( $source['url'], 'https' );
	}

	return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'ssl_srcset' );

