<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# blog: http://ogp.me/ns/blog#">
<meta charset="utf-8">
<?php 
$col_options = get_option('col_options');
$seo_options = get_option('seo_options');
if (empty($col_options['viewport'])) {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">'."\n";
}else{
    echo $col_options['viewport']."\n";
}
if (empty($seo_options['meta'])) {
    if (is_single()){
        echo '<meta name="description" content="'; setup_postdata($post); echo strip_tags(get_the_excerpt()); echo '">'."\n";
        $prev_post = get_previous_post();
        $next_post = get_next_post();
        if (!empty( $prev_post )) {
            echo '<link rel="prev bookmark" href="'.get_permalink( $prev_post->ID ).'" title="'.htmlspecialchars($prev_post->post_title).'">'."\n";
        }
        if (!empty( $next_post )) {
            echo '<link rel="next bookmark" href="'.get_permalink( $next_post->ID ).'" title="'.htmlspecialchars($next_post->post_title).'">'."\n";
        }
    } else {
        echo '<meta name="description" content="'; bloginfo('description'); echo '">'."\n";
    }
    echo '<meta name="author" content="'; bloginfo('name'); echo '">'."\n";
} ?>
<link rel="start" href="<?php echo home_url(); ?>" title="<?php esc_html_e( 'TOP', 'liquid-magazine' ); ?>">
<?php if (empty($seo_options['ogp'])) { ?>
<!-- OGP -->
<meta property="og:type" content="blog">
<meta property="og:site_name" content="<?php bloginfo('name'); ?>">
<?php
if ( is_single() || is_page() ){
    echo '<meta property="og:description" content="'; setup_postdata($post); echo strip_tags(get_the_excerpt()); echo '">'."\n";
    echo '<meta property="og:title" content="'; the_title(); echo ' | '; bloginfo('name'); echo '">'."\n";
    echo '<meta property="og:url" content="'; the_permalink(); echo '">'."\n";
    //thumb
    $src = "";
    if(has_post_thumbnail($post->ID)){
        // アイキャッチ画像を設定済みの場合
        $thumbnail_id = get_post_thumbnail_id($post->ID);
        $src_info = wp_get_attachment_image_src($thumbnail_id, 'large');
        $src = $src_info[0];
    }else{
        // アイキャッチが設定されていない場合
        if(preg_match('/<img([ ]+)([^>]*)src\=["|\']([^"|^\']+)["|\']([^>]*)>/',$post->post_content,$img_array)){
            $src = $img_array[3];
        }else{
            $src = get_stylesheet_directory_uri().'/images/noimage.png';
        }
    }
    echo '<meta property="og:image" content="'.$src.'">'."\n";
} else {
    echo '<meta property="og:description" content="'; bloginfo('description'); echo '">'."\n";
    echo '<meta property="og:title" content="'; bloginfo('name'); echo '">'."\n";
    echo '<meta property="og:url" content="'; echo esc_url(home_url('/')); echo '">'."\n";
     $img_options = get_theme_mod('img_options');
     if(!empty( $img_options['img01']) ){
         echo '<meta property="og:image" content="'.$img_options['img01'].'">'."\n";
     }
}
?>
<!-- twitter:card -->
<meta name="twitter:card" content="summary_large_image">
<?php $sns_options = get_option('sns_options');
    if(!empty($sns_options['twitter'])){
    $twitter_site = preg_replace('(^https?://twitter.com/)', '', $sns_options['twitter']);
    echo '<meta name="twitter:site" content="@'.$twitter_site.'">'."\n";
}
?>
<?php } ?>
<?php wp_head(); ?>
<?php if(!is_front_page()) get_template_part('/json-ld/json-ld'); ?>

<!--[if lt IE 9]>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<script type="text/javascript" src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script type="text/javascript" src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<?php $html_options = get_option('html_options'); if (!empty( $html_options['ga'] )){ ?>
<!-- GA -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo $html_options['ga']; ?>', 'auto');
  ga('send', 'pageview');

</script>
<?php } ?>
<!-- CSS -->
<style type="text/css">
    /*  customize  */
    <?php $options =  get_option('color_options'); ?>
    <?php if(!empty($options['color'])){ ?>
    body, .post_time, .post_cat {
        color: <?php echo esc_html($options['color']); ?> !important;
    }
    <?php } ?>
    <?php if(!empty($options['color2'])){ ?>
    a, a:hover, a:active, a:visited {
        color: <?php echo esc_html($options['color2']); ?>;
    }
    .liquid_bg, .carousel-indicators .active, .main, .sns a, .post_views, .post-stats {
        background-color: <?php echo esc_html($options['color2']); ?> !important;
    }
    .liquid_bc, .post_body h1 span, .post_body h2 span, .ttl span,
    .archive .ttl_h1, .search .ttl_h1, .breadcrumb, .formbox a, .post_views {
        border-color: <?php echo esc_html($options['color2']); ?>;
    }
    .liquid_color, .navbar .current-menu-item, .navbar .current-menu-parent, .navbar .current_page_item {
        color: <?php echo esc_html($options['color2']); ?> !important;
    }
    <?php } ?>
    <?php if(!empty($options['color_link'])){ ?>
    a, a:hover, a:active, a:visited {
        color: <?php echo esc_html($options['color_link']); ?>;
    }
    <?php } ?>
    <?php if(!empty($options['color3'])){ ?>
    .post_body a, .post_body a:hover, .post_body a:active, .post_body a:visited {
        color: <?php echo esc_html($options['color3']); ?>;
    }
    <?php } ?>
    <?php $background_color = get_theme_mod('background_color');
    if(!empty($background_color)){ ?>
    .dropdown-menu, .dropdown-item:focus, .dropdown-item:hover {
        background-color: #<?php echo $background_color; ?>;
    }
    <?php } ?>
    <?php if(!empty($col_options['card'])){ ?>
    /* card */
    .col-md-8 .list_big .post_links .post_thumb span {
        height: 149px;
    }
    @media (max-width: 74.9em) {
        .col-md-8 .list_big .post_links .post_thumb span {
            height: 121px;
        }
    }
    <?php } ?>
    /*  custom head  */
    <?php $head_options = get_option('head_options'); ?>
    <?php if(!empty($head_options['css'])){ ?>
    <?php echo $head_options['css']; ?>
    <?php } ?>
</style>
<?php if(!empty($head_options['meta'])){ ?>
<?php echo $head_options['meta']; ?>
<?php } ?>
</head>

<body <?php body_class(); ?>>

<!-- FB -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.4";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<a id="top"></a>
 <div class="wrapper">
    <?php if ( is_active_sidebar( 'headline' ) ) { ?>
    <div class="row widgets">
        <?php dynamic_sidebar( 'headline' ); ?>
    </div>
    <?php } ?>
    <div class="headline">
     <div class="container">
      <div class="row">
       <div class="col-sm-6">
        <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>" class="logo">
           <?php if(get_header_image()): ?>
            <img src="<?php header_image(); ?>" alt="<?php bloginfo('name'); ?>">
           <?php else: ?>
            <?php echo bloginfo('name'); ?>
           <?php endif; ?>
        </a>
       </div>
       <div class="col-sm-6">
        <div class="logo_text">
        <?php if ( is_single() || is_page() || is_category() ){ ?>
            <div class="subttl"><?php bloginfo('description'); ?></div>
        <?php } else { ?>
            <h1 class="subttl"><?php bloginfo('description'); ?></h1>
        <?php } ?>
        </div>
        <div class="sns hidden-sm-down">
    <?php $sns_options = get_option('sns_options'); ?>
    <?php if(!empty($sns_options['facebook'])){ ?>
    <a href="<?php echo $sns_options['facebook']; ?>" target="_blank"><i class="icon icon-facebook"></i></a>
    <?php } ?>
    <?php if(!empty($sns_options['twitter'])){ ?>
    <a href="<?php echo $sns_options['twitter']; ?>" target="_blank"><i class="icon icon-twitter"></i></a>
    <?php } ?>
    <?php if(!empty($sns_options['google-plus'])){ ?>
    <a href="<?php echo $sns_options['google-plus']; ?>" target="_blank"><i class="icon icon-google-plus"></i></a>
    <?php } ?>
    <?php if(!empty($sns_options['tumblr'])){ ?>
    <a href="<?php echo $sns_options['tumblr']; ?>" target="_blank"><i class="icon icon-tumblr"></i></a>
    <?php } ?>
    <?php if(!empty($sns_options['instagram'])){ ?>
    <a href="<?php echo $sns_options['instagram']; ?>" target="_blank"><i class="icon icon-instagram"></i></a>
    <?php } ?>
    <?php if(!empty($sns_options['youtube'])){ ?>
    <a href="<?php echo $sns_options['youtube']; ?>" target="_blank"><i class="icon icon-youtube"></i></a>
    <?php } ?>
    <?php if(!empty($sns_options['flickr'])){ ?>
    <a href="<?php echo $sns_options['flickr']; ?>" target="_blank"><i class="icon icon-flickr2"></i></a>
    <?php } ?>
    <?php if(!empty($sns_options['pinterest'])){ ?>
    <a href="<?php echo $sns_options['pinterest']; ?>" target="_blank"><i class="icon icon-pinterest"></i></a>
    <?php } ?>
    <?php if(!empty($sns_options['custom'])){ ?>
    <?php echo $sns_options['custom']; ?>
    <?php } ?>
    <?php if(empty($sns_options['feed'])){ ?>
    <a href="<?php bloginfo('rss2_url'); ?>"><i class="icon icon-feed2"></i></a>
    <?php } ?>
        </div>
       </div>
      </div>
     </div>
    </div>
   
    <nav class="navbar navbar-light bg-faded">
     <div class="container">
        <!-- Global Menu -->
        <?php if ( has_nav_menu( 'global-menu' ) ): ?>
        <?php wp_nav_menu(array(
            'theme_location'  => 'global-menu',
            'menu_class'      => 'nav navbar-nav',
            'container'       => false,
            'items_wrap'      => '<ul id="%1$s" class="%2$s nav navbar-nav">%3$s</ul>'
        )); ?>
          <button type="button" class="navbar-toggle collapsed">
            <span class="sr-only"><?php esc_html_e( 'Toggle navigation', 'liquid-magazine' ); ?></span>
            <span class="icon-bar top-bar"></span>
            <span class="icon-bar middle-bar"></span>
            <span class="icon-bar bottom-bar"></span>
          </button>
        <?php else: ?>
            <!-- Global Menu is not set. -->
        <?php endif; ?>
     </div>
     <div class="container searchform_nav none hidden-md-up">
      <?php get_search_form(); ?>
     </div>
    </nav>
  
    <?php if ( is_active_sidebar( 'page_header' ) ) { ?>
    <div class="row widgets page_header">
        <?php dynamic_sidebar( 'page_header' ); ?>
    </div>
    <?php } ?>
    <?php if ( empty($col_options['head']) && is_archive() || is_search() ) { ?>
    <div class="hero">
        <div class="hero_img" data-adaptive-background data-ab-css-background>&nbsp;</div>
    </div>
    <?php } ?>