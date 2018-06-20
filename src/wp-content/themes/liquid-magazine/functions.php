<?php
/*
Author: LIQUID DESIGN
Author URI: https://lqd.jp/
*/

// ------------------------------------
// scripts and styles
// ------------------------------------
$liquid_theme = wp_get_theme();

if ( ! function_exists( 'liquid_scripts_styles' ) ) :
function liquid_scripts_styles() {
    global $liquid_theme;
    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array() );
    wp_enqueue_style( 'icomoon', get_template_directory_uri() . '/css/icomoon.css', array() );
    wp_enqueue_style( 'liquid-style', get_stylesheet_uri(), array(), $liquid_theme->Version );
    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ) );
    wp_enqueue_script( 'adaptive-backgrounds', get_template_directory_uri() . '/js/jquery.adaptive-backgrounds.js', array( 'jquery' ), false, true );
    wp_enqueue_script( 'liquid-script', get_template_directory_uri() . '/js/common.min.js', array( 'jquery' ), false, true );
    if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'liquid_scripts_styles' );
endif;

// get_the_archive_title
if ( ! function_exists( 'liquid_custom_archive_title' ) ) :
function liquid_custom_archive_title( $title ){
    if ( is_category() ) {
        $title = single_term_title( '', false );
    }
    return $title;
}
add_filter( 'get_the_archive_title', 'liquid_custom_archive_title', 10 );
endif;

// title
add_filter( 'wp_title', 'liquid_wp_title', 10, 2 );
function liquid_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() )
		return $title;
	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );
	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";
	return $title;
}

// body_class
function liquid_class_names($classes) {
    if (is_single()){
        $cat = get_the_category();
        if(!empty($cat)){
            $parent_cat_id = $cat[0]->parent;
            if(empty($parent_cat_id)){
                $parent_cat_id = $cat[0]->cat_ID;
            }
        }else{
            $parent_cat_id = "0";
        }    
        $classes[] = "category_".$parent_cat_id;
    }
    if (is_page()){
        $page = get_post( get_the_ID() );
        $slug = $page->post_name;
        $classes[] = "page_".$slug;
    }
	return $classes;
}
add_filter('body_class', 'liquid_class_names');

// set_post_thumbnail_size(220, 165, true ); // 幅、高さ、トリミング

// カテゴリ説明でHTML使用可能
remove_filter( 'pre_term_description', 'wp_filter_kses' );

// プロフィール情報でHTML使用可能
remove_filter( 'pre_user_description', 'wp_filter_kses' );

// ウィジェットでショートコード使用可能
add_filter('widget_text', 'do_shortcode');

// Remove p tags from category description
remove_filter('term_description','wpautop');

// ビジュアルエディタ用CSS
add_editor_style();

// get_the_archive_title
function liquid_custom_archive_title( $title ){
    if ( is_category() ) {
        $title = single_term_title( '', false );
    }
    return $title;
}
add_filter( 'get_the_archive_title', 'liquid_custom_archive_title', 10 );

// excerpt_more
function liquid_new_excerpt_more($more) {
	return '...';
}
add_filter('excerpt_more', 'liquid_new_excerpt_more');

// after_setup_theme
if ( ! function_exists( 'liquid_after_setup_theme' ) ) :
function liquid_after_setup_theme() {
    add_theme_support( 'custom-background' );
    add_theme_support( 'title-tag' );
    // アイキャッチ画像、投稿とコメントのRSSフィード
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    // editor-style
    add_editor_style( 'css/editor-style.css' );
    // Set
    if ( ! isset( $content_width ) ) $content_width = 1024;
    add_theme_support( 'customize-selective-refresh-widgets' );
    // nav_menu
    register_nav_menus(array(
        'global-menu' => ('Global Menu')
    ));
    // 固定ページの抜粋対応
    add_post_type_support( 'page', 'excerpt' );
    // languages
    load_theme_textdomain( 'liquid-magazine', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'liquid_after_setup_theme' );
endif;

// no_self_ping
function liquid_no_self_ping( &$links ) {
    $home = home_url();
    foreach ( $links as $l => $link )
    if ( 0 === strpos( $link, $home ) )
    unset($links[$l]);
}
add_action( 'pre_ping', 'liquid_no_self_ping' );

// ------------------------------------
// カスタムヘッダーの設定
// ------------------------------------
$defaults = array(
	'default-image'          => get_template_directory_uri().'/images/logo.png',
	'random-default'         => false,
	'width'                  => 400,
	'height'                 => 72,
	'flex-height'            => true,
	'flex-width'             => false,
	'default-text-color'     => '333333',
	'header-text'            => false,
	'uploads'                => true,
	'admin-preview-callback' => 'liquid_admin_header_image',
	'admin-head-callback'    => 'liquid_admin_header_style',
);

function liquid_admin_header_image() {
?>
	<p class="header_preview"><?php bloginfo('description'); ?></p>
	<?php if(get_header_image()): ?>
		<img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="<?php bloginfo('name'); ?>" />
	<?php else : ?>
		<h2 class="header_preview"><?php bloginfo('name'); ?></h2>
	<?php endif; ?>
<?php
}

function liquid_admin_header_style() {
?>
<style type="text/css">
p.header_preview,h2.header_preview {
	color:#<?php echo get_header_textcolor(); ?>;
}
</style>
<?php
}
add_theme_support( 'custom-header', $defaults );

// インラインスタイル削除
function liquid_remove_recent_comments_style() {
    global $wp_widget_factory;
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}
add_action('widgets_init', 'liquid_remove_recent_comments_style');

// generator削除
remove_action('wp_head', 'wp_generator');

// ------------------------------------
// カスタム背景
// ------------------------------------
//$custom_background_defaults = array(
//	'default-color' => 'ffffff',
//	'default-image' => '',
//);
//add_theme_support( 'custom-background', $custom_background_defaults );

// ------------------------------------
// カスタマイザーで設定する項目を追加
// ------------------------------------
if ( ! function_exists( 'liquid_theme_customize_register' ) ) :
function liquid_theme_customize_register($lqd_customize) {
    //ショートカット
	//$lqd_customize->get_setting( 'blogname' )->transport          = 'postMessage';
	$lqd_customize->get_setting( 'blogdescription' )->transport   = 'postMessage';
    if ( isset( $lqd_customize->selective_refresh ) ) {
        //$lqd_customize->selective_refresh->add_partial('blogname', array(
        //    'selector' => '.headline .logo',
        //    'render_callback' => function() { bloginfo('name'); },
        //));
        $lqd_customize->selective_refresh->add_partial('blogdescription', array(
            'selector' => '.headline .subttl',
            //'render_callback' => function() { bloginfo('description'); },
        ));
        $lqd_customize->selective_refresh->add_partial('img_options[img01]', array(
            'selector' => '.cover img',
        ));
        $lqd_customize->selective_refresh->add_partial('text_options[text01]', array(
            'selector' => '.cover .main',
        ));
        $lqd_customize->selective_refresh->add_partial('sns_options[facebook]', array(
            'selector' => '.sns',
        ));
        $lqd_customize->selective_refresh->add_partial('share_options[all]', array(
            'selector' => '.share',
        ));
        $lqd_customize->selective_refresh->add_partial('col_options[form]', array(
            'selector' => '.formbox',
        ));
    }
    //テキストカラー
	$lqd_customize->add_setting( 'color_options[color]', array(
        'default' => '#333333',
		'type'    => 'option',
	));
	$lqd_customize->add_control( new WP_Customize_Color_Control(
		$lqd_customize, 'color_options[color]',
		array(
			'label' => 'テキストカラー',
			'section' => 'colors',
			'settings' => 'color_options[color]'
	)));
    //テーマカラー
	$lqd_customize->add_setting( 'color_options[color2]', array(
        'default' => '#333333',
		'type'    => 'option'
	));
	$lqd_customize->add_control( new WP_Customize_Color_Control(
		$lqd_customize, 'color_options[color2]',
		array(
			'label' => 'テーマカラー',
			'section' => 'colors',
			'settings' => 'color_options[color2]'
	)));
    //リンクカラー
	$lqd_customize->add_setting( 'color_options[color_link]', array(
        'default' => '#333333',
		'type'    => 'option'
	));
	$lqd_customize->add_control( new WP_Customize_Color_Control(
		$lqd_customize, 'color_options[color_link]',
		array(
			'label' => 'リンクカラー',
			'section' => 'colors',
			'settings' => 'color_options[color_link]'
	)));
	$lqd_customize->add_setting( 'color_options[color3]', array(
        'default' => '#00aeef',
		'type'    => 'option'
	));
	$lqd_customize->add_control( new WP_Customize_Color_Control(
		$lqd_customize, 'color_options[color3]',
		array(
			'label' => '記事内リンクカラー',
			'section' => 'colors',
			'settings' => 'color_options[color3]'
	)));
    
    //レイアウト
    $lqd_customize->add_section('col_sections', array(
        'title'    => 'レイアウト',
        'priority' => 102,
    ));
    $lqd_customize->add_setting('col_options[sidebar]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[sidebar]', array(
        'label'      => 'レイアウト',
        'description'=> '<img src="'.get_template_directory_uri().'/images/col.png" alt="カラム">',
        'section'    => 'col_sections',
        'settings'   => 'col_options[sidebar]',
        'type'     => 'select',
		'choices'  => array(
			'0' => '2カラム',
			'1'  => '1カラム',
		),
    ));
    //投稿/固定ページ
    $lqd_customize->add_setting('col_options[sidebar2]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[sidebar2]', array(
        'label'      => '投稿/固定ページのみ2カラム',
        'section'    => 'col_sections',
        'settings'   => 'col_options[sidebar2]',
        'type'     => 'select',
		'choices'  => array(
			'1'  => 'する',
			'0' => 'しない',
		),
    ));
    //カード型UI
    $lqd_customize->add_setting('col_options[card]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[card]', array(
        'label'      => 'カード型UI',
        'section'    => 'col_sections',
        'settings'   => 'col_options[card]',
        'type'     => 'select',
		'choices'  => array(
			'1'  => 'する',
			'0' => 'しない',
		),
    ));
    //ヘッダー画像自動表示
    $lqd_customize->add_setting('col_options[head]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[head]', array(
        'label'      => 'ヘッダー画像自動表示',
        'section'    => 'col_sections',
        'settings'   => 'col_options[head]',
        'type'     => 'select',
		'choices'  => array(
			'0'  => 'する',
			'1' => 'しない',
		),
    ));
    //アイキャッチ画像
    $lqd_customize->add_setting('col_options[thumbnail]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[thumbnail]', array(
        'label'      => 'アイキャッチ画像',
        'description'=> '投稿/固定ページ内にアイキャッチ画像を表示',
        'section'    => 'col_sections',
        'settings'   => 'col_options[thumbnail]',
        'type'     => 'select',
		'choices'  => array(
			'0'  => 'する',
			'1' => 'しない',
		),
    ));
    //人気記事自動表示
    $lqd_customize->add_setting('col_options[wpp]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[wpp]', array(
        'label'      => '人気記事自動表示',
        'description'=> '人気記事を自動表示しますか。',
        'section'    => 'col_sections',
        'settings'   => 'col_options[wpp]',
        'type'     => 'select',
		'choices'  => array(
			'0'  => 'する',
			'1' => 'しない',
		),
    ));
    //閲覧数表示
    $lqd_customize->add_setting('col_options[views]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[views]', array(
        'label'      => '人気記事の閲覧数表示',
        'description'=> '人気記事に閲覧数を表示しますか。',
        'section'    => 'col_sections',
        'settings'   => 'col_options[views]',
        'type'     => 'select',
		'choices'  => array(
			'0'  => 'する',
			'1' => 'しない',
		),
    ));
    //人気記事の期間
    $lqd_customize->add_setting('col_options[range]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[range]', array(
        'label'      => '人気記事の期間',
        'description'=> '保存して公開後に反映されます。',
        'section'    => 'col_sections',
        'settings'   => 'col_options[range]',
        'type'     => 'select',
		'choices'  => array(
			'0'  => 'monthly',
			'weekly' => 'weekly',
			'daily' => 'daily',
			'all' => 'all',
		),
    ));
    //投稿者表示
    $lqd_customize->add_setting('col_options[author]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[author]', array(
        'label'      => '投稿者表示',
        'description'=> '記事の下部に投稿者のプロフィールを表示しますか。',
        'section'    => 'col_sections',
        'settings'   => 'col_options[author]',
        'type'     => 'select',
		'choices'  => array(
			'0'  => 'する',
			'1' => 'しない',
		),
    ));
    //お問い合せURL
    $lqd_customize->add_setting('col_options[form]', array(
        'type' => 'option',
    ));
    $lqd_customize->add_control('col_options[form]', array(
        'label'      => 'お問い合せBOXURL',
        'description'=> '記事の下部にお問い合せBOXを表示する場合入力します。<br>例：http://example.com/form',
        'section'    => 'col_sections',
        'settings'   => 'col_options[form]',
        'type'       => 'text',
    ));
    //お問い合せラベル
    $lqd_customize->add_setting('col_options[form2]', array(
        'type' => 'option',
    ));
    $lqd_customize->add_control('col_options[form2]', array(
        'label'      => 'お問い合せBOX表示名',
        'description'=> '例：資料請求',
        'section'    => 'col_sections',
        'settings'   => 'col_options[form2]',
        'type'       => 'text',
    ));
    
    //スライドショー画像
    $lqd_customize->add_section( 'img_sections' , array(
        'title'        => 'スライドショー画像/動画',
        'description'  => '画像を3枚まで設定できます。1枚のみ固定表示も可。全て空にするとエリアが非表示になります。画像は横縦2:1位がおすすめです。動画を設定した場合はスライドしません。スマホでは動画ではなくスライドショー画像が表示されます。',
        'priority'     => 100,
    ));
    $lqd_customize->add_setting( 'img_options[img01]' );
    $lqd_customize->add_control( new WP_Customize_Image_Control(
        $lqd_customize, 'img_options[img01]', array(
            'label' => '画像1',
            'section' => 'img_sections',
            'settings' => 'img_options[img01]',
            'description' => '画像をアップロード',
    )));
    $lqd_customize->add_setting( 'img_options[img02]' );
    $lqd_customize->add_control( new WP_Customize_Image_Control(
        $lqd_customize, 'img_options[img02]', array(
            'label' => '画像2',
            'section' => 'img_sections',
            'settings' => 'img_options[img02]',
            'description' => '画像をアップロード',
    )));
    $lqd_customize->add_setting( 'img_options[img03]' );
    $lqd_customize->add_control( new WP_Customize_Image_Control(
        $lqd_customize, 'img_options[img03]', array(
            'label' => '画像3',
            'section' => 'img_sections',
            'settings' => 'img_options[img03]',
            'description' => '画像をアップロード',
    )));
    //動画
    $lqd_customize->add_setting( 'video_options[mp4]' );
    $lqd_customize->add_control( new WP_Customize_Upload_Control(
        $lqd_customize, 'video_options[mp4]', array(
            'label' => '動画（MP4/WebM）',
            'section' => 'img_sections',
            'settings' => 'video_options[mp4]',
            'description' => '動画をアップロード',
    )));
    $lqd_customize->add_setting('video_options[url]', array(
        'type'           => 'option',
    ));
    $lqd_customize->add_control('video_options[url]', array(
        'label'      => '動画（YouTube URL）',
        'section'    => 'img_sections',
        'settings'   => 'video_options[url]',
        'type'       => 'text',
    ));
    
    //テキスト
    $lqd_customize->add_section('text_sections', array(
        'title'    => 'スライドショーコピー',
        'priority' => 101,
        'description'=> 'スライドショー画像の下に表示されます。全て空にするとエリアが非表示になります。&lt;a href="xxxx"&gt;テキスト&lt;/a&gt;でリンクになります。',
    ));
    $lqd_customize->add_setting('text_options[text01]', array(
        'type'           => 'option',
    ));
    $lqd_customize->add_control('text_options[text01]', array(
        'label'      => 'スライドショーコピー1',
        'section'    => 'text_sections',
        'settings'   => 'text_options[text01]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('text_options[text02]', array(
        'type'           => 'option',
    ));
    $lqd_customize->add_control('text_options[text02]', array(
        'label'      => 'スライドショーコピー2',
        'section'    => 'text_sections',
        'settings'   => 'text_options[text02]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('text_options[text03]', array(
        'type'           => 'option',
    ));
    $lqd_customize->add_control('text_options[text03]', array(
        'label'      => 'スライドショーコピー3',
        'section'    => 'text_sections',
        'settings'   => 'text_options[text03]',
        'type'       => 'text',
    ));
    
    //SEO
    $lqd_customize->add_section('seo_sections', array(
        'title'    => 'SEO',
        'description'=> 'SEO対策関連の設定をします。外部プラグインを併用する場合は重複防止のため表示しないに設定してください。',
        'priority' => 106,
    ));
    $lqd_customize->add_setting('seo_options[meta]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('seo_options[meta]', array(
        'label'      => 'META Description、Author、Next、Prev自動表示',
        'settings'   => 'seo_options[meta]',
        'section'    => 'seo_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    $lqd_customize->add_setting('seo_options[ogp]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('seo_options[ogp]', array(
        'label'      => 'OGP、TwitterCards自動表示',
        'settings'   => 'seo_options[ogp]',
        'section'    => 'seo_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    
    //SNS
    $lqd_customize->add_section('sns_sections', array(
        'title'    => 'SNSアカウント',
        'description'=> 'URLを入力するとアイコンなどが表示されます。',
        'priority' => 107,
    ));
    $lqd_customize->add_setting('sns_options[facebook]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[facebook]', array(
        'label'      => 'Facebook URL',
        'description'=> '例：https://www.facebook.com/lqdjp',
        'settings'   => 'sns_options[facebook]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[twitter]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[twitter]', array(
        'label'      => 'Twitter URL',
        'settings'   => 'sns_options[twitter]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[google-plus]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[google-plus]', array(
        'label'      => 'Google-plus URL',
        'settings'   => 'sns_options[google-plus]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[tumblr]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[tumblr]', array(
        'label'      => 'Tumblr URL',
        'settings'   => 'sns_options[tumblr]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[instagram]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[instagram]', array(
        'label'      => 'Instagram URL',
        'settings'   => 'sns_options[instagram]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[youtube]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[youtube]', array(
        'label'      => 'YouTube URL',
        'settings'   => 'sns_options[youtube]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[flickr]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[flickr]', array(
        'label'      => 'Flickr URL',
        'settings'   => 'sns_options[flickr]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[pinterest]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[pinterest]', array(
        'label'      => 'Pinterest URL',
        'settings'   => 'sns_options[pinterest]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[custom]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[custom]', array(
        'label'      => 'カスタムSNS（HTML可）',
        'description'=> '例：&lt;a href="http://..."&gt;icon&lt;/a&gt;',
        'settings'   => 'sns_options[custom]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[feed]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[feed]', array(
        'label'      => 'Feed アイコン表示',
        'settings'   => 'sns_options[feed]',
        'section'    => 'sns_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    
    //SNSシェア
    $lqd_customize->add_section('share_sections', array(
        'title'    => 'SNSシェアボタン',
        'description'=> '投稿ページなどに表示するシェアボタンを選択します。',
        'priority' => 108,
    ));
    $lqd_customize->add_setting('share_options[all]', array('type' => 'option',));
    $lqd_customize->add_control('share_options[all]', array(
        'label'      => 'シェアボタン表示',
        'settings'   => 'share_options[all]',
        'section'    => 'share_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
			'2'  => '固定ページは表示しない',
		),
    ));
    $lqd_customize->add_setting('share_options[facebook]', array('type' => 'option',));
    $lqd_customize->add_control('share_options[facebook]', array(
        'label'      => 'Facebook',
        'settings'   => 'share_options[facebook]',
        'section'    => 'share_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    $lqd_customize->add_setting('share_options[twitter]', array('type' => 'option',));
    $lqd_customize->add_control('share_options[twitter]', array(
        'label'      => 'Twitter',
        'settings'   => 'share_options[twitter]',
        'section'    => 'share_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    $lqd_customize->add_setting('share_options[google-plus]', array('type' => 'option',));
    $lqd_customize->add_control('share_options[google-plus]', array(
        'label'      => 'Google-plus',
        'settings'   => 'share_options[google-plus]',
        'section'    => 'share_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    $lqd_customize->add_setting('share_options[hatena]', array('type' => 'option',));
    $lqd_customize->add_control('share_options[hatena]', array(
        'label'      => 'Hatena',
        'settings'   => 'share_options[hatena]',
        'section'    => 'share_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    $lqd_customize->add_setting('share_options[pocket]', array('type' => 'option',));
    $lqd_customize->add_control('share_options[pocket]', array(
        'label'      => 'Pocket',
        'settings'   => 'share_options[pocket]',
        'section'    => 'share_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    $lqd_customize->add_setting('share_options[line]', array('type' => 'option',));
    $lqd_customize->add_control('share_options[line]', array(
        'label'      => 'LINE',
        'settings'   => 'share_options[line]',
        'section'    => 'share_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
        
    //GA
    $lqd_customize->add_section('html_sections', array(
        'title'    => 'Googleアナリティクス',
        'description'=> 'Googleアナリティクスのアナリティクス設定＞プロパティ設定でトラッキングIDを確認できます。',
        'priority' => 109,
    ));
    $lqd_customize->add_setting('html_options[ga]', array('type'  => 'option',));
    $lqd_customize->add_control('html_options[ga]', array(
        'label'      => 'トラッキングID',
        'description'=> '例：UA-XXXXXXX-XX',
        'section'    => 'html_sections',
        'settings'   => 'html_options[ga]',
        'type'       => 'text',
    ));
    
    //オプション
    $lqd_customize->add_section('opt_sections', array(
        'title'    => 'オプション',
        'description'=> '【上級者向け】オプション機能は導入サポート対象外とさせて頂いております。',
        'priority' => 900,
    ));
    //viewport
    $lqd_customize->add_setting('col_options[viewport]', array(
        'type' => 'option',
    ));
    $lqd_customize->add_control('col_options[viewport]', array(
        'label'      => 'Viewportカスタマイズ',
        'description'=> 'Viewportタグを変更することができます。<br>例：&lt;meta name="viewport" content="..."&gt;',
        'section'    => 'opt_sections',
        'settings'   => 'col_options[viewport]',
        'type'     => 'textarea',
    ));
    //nav
    $lqd_customize->add_setting('col_options[dropdown]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[dropdown]', array(
        'label'      => 'ドロップダウンメニュー',
        'description'=> 'メニューの副項目を全ての端末でドロップダウンにします。デフォルトは「しない」です。',
        'section'    => 'opt_sections',
        'settings'   => 'col_options[dropdown]',
        'type'     => 'select',
		'choices'  => array(
			'1' => 'する',
			'0'  => 'しない',
		),
    ));
    //widget
    $lqd_customize->add_setting('col_options[widget]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[widget]', array(
        'label'      => 'ウィジェットのデバイス判定/カラム設定',
        'description'=> 'デバイス判定とカラム設定機能を追加します。デフォルトは「する」です。',
        'section'    => 'opt_sections',
        'settings'   => 'col_options[widget]',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    //widget_ua
    $lqd_customize->add_setting('col_options[widget_ua]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[widget_ua]', array(
        'label'      => 'ウィジェットのデバイス判定（UA）',
        'description'=> 'デバイス判定にUA判定を追加します。デフォルトは「しない」です。',
        'section'    => 'opt_sections',
        'settings'   => 'col_options[widget_ua]',
        'type'     => 'select',
		'choices'  => array(
			'1' => 'する',
			'0'  => 'しない',
		),
    ));
    //wpautop
    $lqd_customize->add_setting('col_options[wpautop]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[wpautop]', array(
        'label'      => 'タグの自動挿入',
        'description'=> '作成した記事内の改行をpタグに変換して自動挿入します。WordPressのデフォルトは「する」です。',
        'section'    => 'opt_sections',
        'settings'   => 'col_options[wpautop]',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    
    //カスタムヘッダー
    $lqd_customize->add_section('head_sections', array(
        'title'    => 'カスタムヘッダー',
        'description'=> '【上級者向け】HEADにタグやCSSを挿入します。',
        'priority' => 997,
    ));
    $lqd_customize->add_setting('head_options[meta]', array('type'  => 'option',));
    $lqd_customize->add_control('head_options[meta]', array(
        'label'      => 'カスタムHEAD',
        'description'=> '例：&lt;link rel="stylesheet" href="add.css"&gt;',
        'section'    => 'head_sections',
        'settings'   => 'head_options[meta]',
        'type'       => 'textarea',
    ));
    $lqd_customize->add_setting('head_options[css]', array('type'  => 'option',));
    $lqd_customize->add_control('head_options[css]', array(
        'label'      => 'カスタムCSS',
        'description'=> '例：.class { color: #000; }',
        'section'    => 'head_sections',
        'settings'   => 'head_options[css]',
        'type'       => 'textarea',
    ));
    
    //ライセンス
    $lqd_customize->add_section('lqd_sections', array(
        'title'    => 'ライセンスID',
        'description'=> 'LIQUID PRESS テーマのライセンスID（メールアドレス）を入力してください。<br>ライセンスIDのご購入は<a href="https://lqd.jp/wp/theme.html" target="_blank">こちら</a>。',
        'priority' => 999,
    ));
    $lqd_customize->add_setting('lqd_options[ls]', array('type'  => 'option',));
    $lqd_customize->add_control('lqd_options[ls]', array(
        'label'      => 'メールアドレス',
        'section'    => 'lqd_sections',
        'settings'   => 'lqd_options[ls]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('lqd_options[cp]', array(
        'type'  => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('lqd_options[cp]', array(
        'label'      => '著作権表示削除',
        'section'    => 'lqd_sections',
        'settings'   => 'lqd_options[cp]',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'しない',
			'1'  => 'する',
		),
    ));
}
add_action( 'customize_register', 'liquid_theme_customize_register' );
endif;

// ------------------------------------
// wp_nav_menu
// ------------------------------------
function liquid_special_nav_class( $classes, $item ) {
    $classes[] = 'nav-item hidden-sm-down';
    return $classes;
}
add_filter( 'nav_menu_css_class', 'liquid_special_nav_class', 10, 2 );

// ------------------------------------
// 投稿フォーマットを追加
// ------------------------------------
//add_theme_support( 'post-formats', array( 'aside' ) );

// ------------------------------------
// ウィジェットフック for LIQUID PRESS
// ------------------------------------
//項目
$col_options = get_option('col_options');
if(empty($col_options['widget'])){
    add_action( 'in_widget_form', 'liquid_widget_form', 10, 3 );
    add_filter( 'widget_update_callback', 'liquid_widget_update_callback', 10, 2 );
    if(!empty($col_options['widget_ua'])){
        add_filter( 'widget_display_callback', 'liquid_widget_display_callback', 10, 3 );
    }
    add_filter( 'dynamic_sidebar_params', 'liquid_dynamic_sidebar_params' );
}
function liquid_widget_form( $widget, $return, $instance ) {
    $liquid_prefix = "col-xs-";
    $liquid_devices = isset( $instance['liquid_devices'] ) ? esc_attr( $instance['liquid_devices'] ) : '';
	$liquid_classes = isset( $instance['liquid_classes'] ) ? esc_attr( $instance['liquid_classes'] ) : ''; ?>
	<hr>
	<p class="liquid_widget_form">
		<select class="liquid_devices" id="widget-<?php echo $widget->id_base; ?>-<?php echo $widget->number; ?>-liquid_devices" name="widget-<?php echo $widget->id_base; ?>[<?php echo $widget->number; ?>][liquid_devices]">
			<option value="">-- Devices --</option>
			<option value="mobile" <?php selected( 'mobile', $liquid_devices, true ); ?>>Mobile</option>
			<option value="desktop" <?php selected( 'desktop', $liquid_devices, true ); ?>>Desktop</option>
		</select>
		<select class="liquid_classes" id="widget-<?php echo $widget->id_base; ?>-<?php echo $widget->number; ?>-liquid_classes" name="widget-<?php echo $widget->id_base; ?>[<?php echo $widget->number; ?>][liquid_classes]">
			<option value="">-- Column --</option>
			<option value="<?php echo $liquid_prefix; ?>12" <?php selected( $liquid_prefix.'12', $liquid_classes, true ); ?>>1 Column</option>
			<option value="<?php echo $liquid_prefix; ?>6" <?php selected( $liquid_prefix.'6', $liquid_classes, true ); ?>>2 Column</option>
			<option value="<?php echo $liquid_prefix; ?>4" <?php selected( $liquid_prefix.'4', $liquid_classes, true ); ?>>3 Column</option>
			<option value="<?php echo $liquid_prefix; ?>3" <?php selected( $liquid_prefix.'3', $liquid_classes, true ); ?>>4 Column</option>
		</select>
	</p>
	<?php
	return $instance;
}
//保存
function liquid_widget_update_callback( $instance, $new_instance ) {
	$instance['liquid_devices'] = $new_instance['liquid_devices'];
	$instance['liquid_classes'] = $new_instance['liquid_classes'];
	return $instance;
}
//表示
function liquid_widget_display_callback( $instance, $widget, $args ) {
    if( !empty($instance['liquid_devices']) ) {
        if( $instance['liquid_devices']=="mobile" && !wp_is_mobile() ) {
            return false;
        }elseif( $instance['liquid_devices']=="desktop" && wp_is_mobile() ) {
            return false;
        }
    }
    return $instance;
}
//CSS
function liquid_dynamic_sidebar_params( $params ) {
	global $wp_registered_widgets;

	$widget_id  = $params[0]['widget_id'];
	$widget_obj = $wp_registered_widgets[$widget_id];
	$widget_opt = get_option($widget_obj['callback'][0]->option_name);
	$widget_num = $widget_obj['params'][0]['number'];

	if ( empty($widget_opt[$widget_num]['liquid_classes']) && empty($widget_opt[$widget_num]['liquid_devices']) ){
        return $params;
    }else{
        if ( !empty($widget_opt[$widget_num]['liquid_classes']) ){
            $params[0]['before_widget'] = preg_replace( '/class="col-/', "class=\"{$widget_opt[$widget_num]['liquid_classes']} old-col-", $params[0]['before_widget'], 1 );
        }
        if ( !empty($widget_opt[$widget_num]['liquid_devices']) ){
            if( $widget_opt[$widget_num]['liquid_devices']=="mobile" ){
                $liquid_devices_class = "hidden-md-up";
            }elseif( $widget_opt[$widget_num]['liquid_devices']=="desktop" ){
                $liquid_devices_class = "hidden-sm-down";
            }
            $params[0]['before_widget'] = preg_replace( '/class="col-/', "class=\"{$liquid_devices_class} col-", $params[0]['before_widget'], 1 );
        }
        return $params;
    }
}

// ------------------------------------
// ウィジェットの登録
// ------------------------------------
if ( ! function_exists( 'liquid_widgets_init' ) ) :
function liquid_widgets_init() {
    register_sidebar(array(
        'name' => 'サイドバー',
        'id' => 'sidebar',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="col-xs-12"><div class="widget %2$s">',
        'after_widget'  => '</div></div>'
    ));
    register_sidebar(array(
        'name' => 'ヘッドライン',
        'id' => 'headline',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="col-xs-12"><div class="widget %2$s">',
        'after_widget'  => '</div></div>'
    ));
    register_sidebar(array(
        'name' => 'ページヘッダー',
        'description' => '横幅100%のエリアです。',
        'id' => 'page_header',
        'before_title' => '<div class="container"><div class="ttl">',
        'after_title' => '</div></div>',
        'before_widget' => '<div id="%1$s" class="col-xs-12"><div class="widget %2$s">',
        'after_widget'  => '</div></div>'
    ));
    register_sidebar(array(
        'name' => '投稿ページ上部',
        'id' => 'main_head',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="col-xs-12"><div class="widget %2$s">',
        'after_widget'  => '</div></div>'
    ));
    register_sidebar(array(
        'name' => '投稿ページ下部',
        'id' => 'main_foot',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="col-xs-12"><div class="widget %2$s">',
        'after_widget'  => '</div></div>'
    ));
    register_sidebar(array(
        'name' => '固定ページ上部',
        'id' => 'page_head',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="col-xs-12"><div class="widget %2$s">',
        'after_widget'  => '</div></div>'
    ));
    register_sidebar(array(
        'name' => '固定ページ下部',
        'id' => 'page_foot',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="col-xs-12"><div class="widget %2$s">',
        'after_widget'  => '</div></div>'
    ));
    register_sidebar(array(
        'name' => 'アーカイブ上部',
        'id' => 'archive_head',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="col-xs-12"><div class="widget %2$s">',
        'after_widget'  => '</div></div>'
    ));
    register_sidebar(array(
        'name' => 'アーカイブ下部',
        'id' => 'archive_foot',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="col-xs-12"><div class="widget %2$s">',
        'after_widget'  => '</div></div>'
    ));
    register_sidebar(array(
        'name' => 'トップページ上部',
        'id' => 'top_header',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="col-xs-12"><div class="widget %2$s">',
        'after_widget'  => '</div></div>'
    ));
    register_sidebar(array(
        'name' => 'トップページ下部',
        'id' => 'top_footer',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="col-xs-12"><div class="widget %2$s">',
        'after_widget'  => '</div></div>'
    ));
    register_sidebar(array(
        'name' => 'ページフッター',
        'description' => '横幅100%のエリアです。',
        'id' => 'page_footer',
        'before_title' => '<div class="container"><div class="ttl">',
        'after_title' => '</div></div>',
        'before_widget' => '<div id="%1$s" class="col-sm-12"><div class="widget %2$s">',
        'after_widget'  => '</div></div>'
    ));
    register_sidebar(array(
        'name' => 'フッター',
        'description' => 'フッターはデフォルトで3カラムです。各ウィジェットの設定から変更可能です。',
        'id' => 'footer',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="col-sm-4"><div class="widget %2$s">',
        'after_widget'  => '</div></div>'
    ));
}
add_action( 'widgets_init', 'liquid_widgets_init' );
endif;

// ------------------------------------
// ウィジェットの作成
// ------------------------------------
// facebook_box
class liquid_widget_fb extends WP_Widget {
	function __construct() {
    	parent::__construct(false, $name = 'Facebook Page Plugin');
    }
    function widget($args, $instance) {
        extract( $args );
        $facebook_ttl = apply_filters( 'widget_facebook_ttl', empty( $instance['facebook_ttl'] ) ? '' : $instance['facebook_ttl'] );
        $facebook_box = apply_filters( 'widget_facebook_box', empty( $instance['facebook_box'] ) ? '' : $instance['facebook_box'] );
    	?>
        <?php echo $before_widget; ?>
        <?php if ( $facebook_ttl ) echo $before_title . $facebook_ttl . $after_title; ?>
        <div class="fb-page" data-href="<?php echo $facebook_box; ?>" data-width="500" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/facebook"><a href="https://www.facebook.com/facebook">Facebook</a></blockquote></div></div>
        <?php echo $after_widget; ?>
        <?php
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['facebook_ttl'] = trim($new_instance['facebook_ttl']);
        $instance['facebook_box'] = trim($new_instance['facebook_box']);
        return $instance;
    }
    function form($instance) {
        $facebook_ttl = isset( $instance['facebook_ttl'] ) ? esc_attr( $instance['facebook_ttl'] ) : '';
        $facebook_box = isset( $instance['facebook_box'] ) ? esc_attr( $instance['facebook_box'] ) : '';
        ?>
        <p>
           <label for="<?php echo $this->get_field_id('facebook_ttl'); ?>">
           <?php _e( 'Title:', 'liquid-magazine' ); ?>
           </label>
           <input type="text" class="widefat" rows="16" id="<?php echo $this->get_field_id('facebook_ttl'); ?>" name="<?php echo $this->get_field_name('facebook_ttl'); ?>" value="<?php echo $facebook_ttl; ?>">
        </p>
        <p>
           <label for="<?php echo $this->get_field_id('facebook_box'); ?>">
           <?php _e( 'Facebook Page URL:', 'liquid-magazine' ); ?>
           </label>
           <input type="text" class="widefat" rows="16" colls="20" id="<?php echo $this->get_field_id('facebook_box'); ?>" name="<?php echo $this->get_field_name('facebook_box'); ?>" value="<?php echo $facebook_box; ?>">
        </p>
        <?php
    }
}
function liquid_widget_fb_register() {
    register_widget( 'liquid_widget_fb' );
}
add_action( 'widgets_init', 'liquid_widget_fb_register' );

// 最近の投稿 (画像付き)
class liquid_widget_newpost extends WP_Widget {

    function __construct() {
        parent::__construct( false, $name = '最近の投稿 (画像付き)' );
    }
    function widget( $args, $instance ) {
        $cache = wp_cache_get( 'widget_recent_posts', 'widget' );
        if ( !is_array( $cache ) ) {
            $cache = array();
        }
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }
        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo $cache[ $args['widget_id'] ];
            return;
        }
        ob_start();
        extract( $args );

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) ) {
            $number = 10;
        }

        $r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
        if ( $r->have_posts() ) {
        ?>
            <?php echo $before_widget; ?>

            <?php if ( $title ) echo $before_title . $title . $after_title; ?>
            <ul class="newpost">
            <?php while ( $r->have_posts() ) : $r->the_post(); ?>
                <li>
                    <a href="<?php the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
                    <span class="post_thumb"><span><?php echo get_the_post_thumbnail( null, 'thumbnail' ); ?></span></span>
                    <span class="post_ttl"><?php if (get_the_title() ) the_title(); else the_ID(); ?></span></a>
                </li>
            <?php endwhile; ?>
            </ul>
            <?php echo $after_widget; ?>
            <?php
                wp_reset_postdata();
            }
            $cache[ $args['widget_id'] ] = ob_get_flush();
            wp_cache_set( 'widget_recent_posts', $cache, 'widget' );
        }

        function update( $new_instance, $old_instance ) {
            $instance              = $old_instance;
            $instance['title']     = strip_tags($new_instance['title']);
            $instance['number']    = (int) $new_instance['number'];
            //$this->flush_widget_cache();
 
            $alloptions = wp_cache_get( 'alloptions', 'options' );
 
            if ( isset( $alloptions['widget_recent_entries'] ) ) {
                delete_option( 'widget_recent_entries' );
            }
            return $instance;
        }
        //function flush_widget_cache() {
        //    wp_cache_delete( 'widget_recent_posts', 'widget' );
        //}
        /* ウィジェットの設定フォーム */
        function form( $instance ) {
            $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
            $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        ?>
            <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'liquid-magazine' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
 
            <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'liquid-magazine' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

        <?php
        }
}
function liquid_widget_newpost_register() {
    register_widget( 'liquid_widget_newpost' );
}
add_action( 'widgets_init', 'liquid_widget_newpost_register' );

// profile_box
class liquid_widget_pf extends WP_Widget {
	function __construct() {
    	parent::__construct(false, $name = 'プロフィール');
    }
    function widget($args, $instance) {
        extract( $args );
        $profile_ttl = apply_filters( 'widget_profile_ttl', empty( $instance['profile_ttl'] ) ? '' : $instance['profile_ttl'] );
        $profile_box = apply_filters( 'widget_profile_box', empty( $instance['profile_box'] ) ? '' : $instance['profile_box'] );
    	?>
        <?php echo $before_widget; ?>
        <?php if ( $profile_ttl ) echo $before_title . $profile_ttl . $after_title; ?>
         <?php $user = get_user_by( 'slug', $profile_box ); ?>
         <?php if ( !empty( $user ) ){ ?>
         <div class="authorbox vcard author">
           <?php $aid = $user->ID; ?>
           <?php $aurl = $user->user_url; ?>
           <div class="text-center"><?php echo get_avatar( $aid, $size = "200" ); ?></div>
           <div class="fn"><?php echo $user->display_name; ?></div>
           <div class="prof"><?php echo $user->description; ?></div>
         </div>
         <?php } ?>
        <?php echo $after_widget; ?>
        <?php
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['profile_ttl'] = trim($new_instance['profile_ttl']);
        $instance['profile_box'] = trim($new_instance['profile_box']);
        return $instance;
    }
    function form($instance) {
        $profile_ttl = isset( $instance['profile_ttl'] ) ? esc_attr( $instance['profile_ttl'] ) : '';
        $profile_box = isset( $instance['profile_box'] ) ? esc_attr( $instance['profile_box'] ) : '';
        ?>
        <p>
           <label for="<?php echo $this->get_field_id('profile_ttl'); ?>">
           <?php _e( 'Title:', 'liquid-magazine' ); ?>
           </label>
           <input type="text" class="widefat" rows="16" id="<?php echo $this->get_field_id('profile_ttl'); ?>" name="<?php echo $this->get_field_name('profile_ttl'); ?>" value="<?php echo $profile_ttl; ?>">
        </p>
        <p>
           <label for="<?php echo $this->get_field_id('profile_box'); ?>">
           <?php _e( 'User:', 'liquid-magazine' ); ?>
           </label>
           <?php $users = get_users(); ?>
           <select class="widefat" name="<?php echo $this->get_field_name('profile_box'); ?>" id="<?php echo $this->get_field_id('profile_box'); ?>">
           <?php foreach($users as $user) {
                $uid = $user->ID;
                $userData = get_userdata($uid);
                if(isset( $instance['profile_box'] ) && $instance['profile_box'] == $user->user_nicename){
                    echo '<option value="'.$user->user_nicename.'" selected>'.$user->user_nicename.'</option>';
                }else{
                    echo '<option value="'.$user->user_nicename.'">'.$user->user_nicename.'</option>';
                }
           } ?>
           </select>
        </p>
        <?php
    }
}
function liquid_widget_pf_register() {
    register_widget( 'liquid_widget_pf' );
}
add_action( 'widgets_init', 'liquid_widget_pf_register' );

// sns_box
class liquid_widget_sns extends WP_Widget {
	function __construct() {
    	parent::__construct(false, $name = 'SNS');
    }
    function widget($args, $instance) {
        extract( $args );
        $sns_ttl = apply_filters( 'widget_sns_ttl', empty( $instance['sns_ttl'] ) ? '' : $instance['sns_ttl'] );
    	?>
        <?php echo $before_widget; ?>
        <?php if ( $sns_ttl ) echo $before_title . $sns_ttl . $after_title; ?>
          <div class="sns_clone"></div>
        <?php echo $after_widget; ?>
        <?php
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['sns_ttl'] = trim($new_instance['sns_ttl']);
        return $instance;
    }
    function form($instance) {
        $sns_ttl = isset( $instance['sns_ttl'] ) ? esc_attr( $instance['sns_ttl'] ) : '';
        ?>
        <p>
           <label for="<?php echo $this->get_field_id('sns_ttl'); ?>">
           <?php _e( 'Title:', 'liquid-magazine' ); ?>
           </label>
           <input type="text" class="widefat" rows="16" id="<?php echo $this->get_field_id('sns_ttl'); ?>" name="<?php echo $this->get_field_name('sns_ttl'); ?>" value="<?php echo $sns_ttl; ?>">
        </p>
        <?php
    }
}
function liquid_widget_sns_register() {
    register_widget( 'liquid_widget_sns' );
}
add_action( 'widgets_init', 'liquid_widget_sns_register' );

// popularposts *require
if ( locate_template( 'popularposts.php' ) != '' ) :
locate_template( 'popularposts.php', true );
class liquid_widget_wpp extends WP_Widget {
	function __construct() {
    	parent::__construct(false, $name = '人気記事自動表示', array('description' => 'Required: WordPress Popular Posts'));
    }
    function widget($args, $instance) {
        extract( $args );
        $wpp_ttl = apply_filters( 'widget_wpp_ttl', empty( $instance['wpp_ttl'] ) ? '' : $instance['wpp_ttl'] );
        $wpp_target = apply_filters( 'widget_wpp_target', empty( $instance['wpp_target'] ) ? '' : $instance['wpp_target'] );
        // default column
        $wpp_column = apply_filters( 'widget_wpp_column', empty( $instance['wpp_column'] ) ? '2' : $instance['wpp_column'] );
    	?>
        <?php echo $before_widget; ?>
        <?php 
        if ( $wpp_target == "top" ){
            if ( is_home() || is_front_page() ){
                if ( $wpp_ttl ) echo $before_title . $wpp_ttl . $after_title;
                liquid_popular_post($wpp_column);
            }
        }elseif( $wpp_target == "exctop" ){
            if ( !is_home() && !is_front_page() ){
                if ( $wpp_ttl ) echo $before_title . $wpp_ttl . $after_title;
                liquid_popular_post($wpp_column);
            }
        }elseif( $wpp_target == "post" ){
            if ( is_single() ){
                if ( $wpp_ttl ) echo $before_title . $wpp_ttl . $after_title;
                liquid_popular_post($wpp_column);
            }
        }elseif( $wpp_target == "page" ){
            if ( is_page() ){
                if ( $wpp_ttl ) echo $before_title . $wpp_ttl . $after_title;
                liquid_popular_post($wpp_column);
            }
        }else{
            if ( $wpp_ttl ) echo $before_title . $wpp_ttl . $after_title;
            liquid_popular_post($wpp_column);
        } ?>
        <?php echo $after_widget; ?>
        <?php
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['wpp_ttl'] = trim($new_instance['wpp_ttl']);
        $instance['wpp_target'] = trim($new_instance['wpp_target']);
        $instance['wpp_column'] = trim($new_instance['wpp_column']);
        return $instance;
    }
    function form($instance) {
        $wpp_ttl = isset( $instance['wpp_ttl'] ) ? esc_attr( $instance['wpp_ttl'] ) : '';
        $wpp_target = isset( $instance['wpp_target'] ) ? esc_attr( $instance['wpp_target'] ) : '';
        $wpp_column = isset( $instance['wpp_column'] ) ? esc_attr( $instance['wpp_column'] ) : '';
        ?>
        <p>
           <label for="<?php echo $this->get_field_id('wpp_ttl'); ?>">
           <?php _e( 'Title:', 'liquid-magazine' ); ?>
           </label>
           <input type="text" class="widefat" rows="16" id="<?php echo $this->get_field_id('wpp_ttl'); ?>" name="<?php echo $this->get_field_name('wpp_ttl'); ?>" value="<?php echo $wpp_ttl; ?>">
        </p>
        <p>
           <label for="<?php echo $this->get_field_id('wpp_target'); ?>">
           <?php _e( 'Display:', 'liquid-magazine' ); ?>
           </label>
           <select class="widefat" id="<?php echo $this->get_field_id('wpp_target'); ?>" name="<?php echo $this->get_field_name('wpp_target'); ?>">
               <option value="">-- Target --</option>
               <option value="top" <?php selected( 'top', $wpp_target, true ); ?>>Top</option>
               <option value="exctop" <?php selected( 'exctop', $wpp_target, true ); ?>>Exclude Top</option>
               <option value="post" <?php selected( 'post', $wpp_target, true ); ?>>Post</option>
               <option value="page" <?php selected( 'page', $wpp_target, true ); ?>>Page</option>
           </select>
        </p>
        <p>
           <label for="<?php echo $this->get_field_id('wpp_column'); ?>">
           <?php _e( 'Columns number of post:', 'liquid-magazine' ); ?>
           </label>
           <select class="widefat" id="<?php echo $this->get_field_id('wpp_column'); ?>" name="<?php echo $this->get_field_name('wpp_column'); ?>">
               <option value="">-- Column --</option>
               <option value="2" <?php selected( '2', $wpp_column, true ); ?>>2 Column</option>
               <option value="3" <?php selected( '3', $wpp_column, true ); ?>>3 Column</option>
               <option value="4" <?php selected( '4', $wpp_column, true ); ?>>4 Column</option>
           </select>
        </p>
        <?php
    }
}
function liquid_widget_wpp_register() {
    register_widget( 'liquid_widget_wpp' );
}
add_action( 'widgets_init', 'liquid_widget_wpp_register' );
else:
function liquid_popular_post($wpp_column){
    return;
}
endif;

// ------------------------------------
// LIQUID PRESS functions
// ------------------------------------

//Initialize the update checker.
if ( is_admin() ) {
    //json
    $liquid_json_error = "";
    $liquid_json_url = "https://lqd.jp/wp/data/_MH3u2d9eAp7DDwH.json";
    $liquid_json = wp_remote_get($liquid_json_url);
    if ( is_wp_error( $liquid_json ) ) {
        $liquid_json_error = $liquid_json->get_error_message().$liquid_json_url;
    }else{
        $liquid_json = json_decode($liquid_json['body']);
        //update
        liquid_wp_update();
    }
}
function liquid_wp_update(){
    global $liquid_json_url;
    require ( get_template_directory() . '/theme-update-checker.php' );
    $liquid_update_checker = new ThemeUpdateChecker(
        'liquid-magazine',
        $liquid_json_url
    );
}

// theme-contents
function liquid_theme_contents_menu() {
    if ( locate_template( 'admin/contents.php' ) != '' ) {
        locate_template( 'admin/contents.php', true );
    }
}
function liquid_theme_contents() {
    add_theme_page('コンテンツ分析 β', 'コンテンツ分析 β', 'manage_options', 
               'liquid_theme_contents', 'liquid_theme_contents_menu');
}
add_action ( 'admin_menu', 'liquid_theme_contents' );

// theme-options
function liquid_theme_support_menu() {
    global $liquid_json, $liquid_json_error;
    $ls = '';
    $lqd_options = get_option('lqd_options');
    if (!empty( $lqd_options['ls'] )){
        $ls = htmlspecialchars($lqd_options['ls']);
    }
    echo '<div class="wrap lqd-info"><h1>テーマのサポート</h1>';
    echo '<iframe src="https://lqd.jp/wp/data/liquid-magazine-info.html?id='.time().'&ls='.$ls.'" frameborder="0" style="width: 100%; height: 1500px;"></iframe>';
    if ( !empty( $ls ) && !empty( $liquid_json ) && empty( $liquid_json_error ) ){
        echo '<div class="lqd-footer"><p class="alignleft"><a href="https://lqd.jp/wp/" target="_blank">LIQUID PRESS</a> のご利用ありがとうございます。</p><p class="alignright"><a href="'.$liquid_json->download_url.'" target="_blank" class="dlurl">テーマ '.$liquid_json->version.' のzipを入手する</a></p></div>';
    }
    echo '</div>';
}
add_action ( 'admin_menu', 'liquid_theme_support' );
function liquid_theme_support() {
     add_theme_page('テーマのサポート', 'テーマのサポート', 'manage_options', 
               'liquid_theme_support', 'liquid_theme_support_menu');
}

// notices
function liquid_admin_notices() {
	global $liquid_theme, $liquid_json, $pagenow, $liquid_json_error;
	if ( $pagenow == 'index.php' || $pagenow == 'themes.php' || $pagenow == 'nav-menus.php' ) {
        if( !empty($liquid_json->notices) && version_compare($liquid_theme->Version, $liquid_json->version, "<") ){
            echo '<div class="notice notice-info"><p>'.$liquid_json->notices.'</p></div>';
        }
        if(!empty($liquid_json->news)){
            echo '<div class="notice notice-info"><p>'.$liquid_json->news.'</p></div>';
        }
        if(!empty($liquid_json_error)) {
            echo '<script>console.log("'.$liquid_json_error.'");</script>';
        }
    } elseif ( $pagenow == 'edit-tags.php' ) {
        if(!empty($liquid_json->catinfo)){
            echo '<div class="notice notice-info"><p>'.$liquid_json->catinfo.'</p></div>';
        }
    } elseif ( $pagenow == 'widgets.php' ) {
        if(!empty($liquid_json->widgetsinfo)){
            echo '<div class="notice notice-info"><p>'.$liquid_json->widgetsinfo.'</p></div>';
        }
    }
}
add_action( 'admin_notices', 'liquid_admin_notices' );

//wpautop
if(!empty($col_options['wpautop'])){
    function liquid_wpautop_action() {
        remove_filter('the_excerpt', 'wpautop');
        remove_filter('the_content', 'wpautop');
    }
    add_filter( 'init', 'liquid_wpautop_action' );
    function liquid_wpautop_filter($init) {
        $init['wpautop'] = false;
        $init['apply_source_formatting'] = true;
        return $init;
    }
    add_filter( 'tiny_mce_before_init', 'liquid_wpautop_filter' );
}

//sidebar
if ( ! function_exists( 'liquid_col_options' ) ) :
function liquid_col_options($key){
    $col_options = get_option('col_options');
    //is
    if(!empty($col_options['sidebar2'])){
        if ( is_single() || is_page() ) {
            $mainarea = 'col-md-8';
            $sidebar = 'col-md-4';
        }else{
            $mainarea = 'col-md-12';
            $sidebar = 'col-md-12';
        }
    }elseif(empty($col_options['sidebar'])){
        $mainarea = 'col-md-8';
        $sidebar = 'col-md-4';
    }else{
        $mainarea = 'col-md-12';
        $sidebar = 'col-md-12';
    }
    //class
    if($key == 'mainarea'){
        echo $mainarea;
    }else{
        echo $sidebar;
    }
}
endif;

// navigation
if ( ! function_exists( 'liquid_paging_nav' ) ) :
function liquid_paging_nav() {
	global $wp_query, $wp_rewrite;
	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}
	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );
	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}
	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';
	$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';
	$links = paginate_links( array(
		'base'     => $pagenum_link,
		'format'   => $format,
		'total'    => $wp_query->max_num_pages,
		'current'  => $paged,
		'mid_size' => 4,
		'add_args' => array_map( 'urlencode', $query_args ),
		'prev_text' => __( '&laquo; Prev', 'liquid-magazine' ),
		'next_text' => __( 'Next &raquo;', 'liquid-magazine' ),
	) );
	if ( $links ) :
	?>
	<nav class="navigation">
		<ul class="page-numbers">
			<li><?php echo $links; ?></li>
		</ul>
	</nav>
	<?php
	endif;
}
endif;
?>