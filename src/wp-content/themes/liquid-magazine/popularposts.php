<?php

if(empty($col_options)){
    $col_options = get_option('col_options');
}

function liquid_popular_post_col2( $content, $p, $instance ){
    $output = liquid_popular_post_parts( $content, $p, $instance );
    $output = '<article class="list list_big popularpost col-md-6">'.$output.'</article>';
    return $output;
}

function liquid_popular_post_col3( $content, $p, $instance ){
    $output = liquid_popular_post_parts( $content, $p, $instance );
    $output = '<article class="list list_big popularpost col-md-4">'.$output.'</article>';
    return $output;
}

function liquid_popular_post_col4( $content, $p, $instance ){
    $output = liquid_popular_post_parts( $content, $p, $instance );
    $output = '<article class="list list_big popularpost col-md-3">'.$output.'</article>';
    return $output;
}

function liquid_popular_post_parts( $content, $p, $instance ){
    global $col_options;
    //thumb(wpp)
    $src = "";
    if(has_post_thumbnail($p->id)){
        // アイキャッチ画像を設定済みの場合
        $thumbnail_id = get_post_thumbnail_id($p->id);
        $src_info = wp_get_attachment_image_src($thumbnail_id, 'full');
        $src = $src_info[0];
    }else{
        // アイキャッチが設定されていない場合(wpp)
        $post = get_post($p->id);
        if(preg_match('/<img([ ]+)([^>]*)src\=["|\']([^"|^\']+)["|\']([^>]*)>/',$post->post_content,$img_array)){
            $src = $img_array[3];
        }else{
            $src = get_stylesheet_directory_uri().'/images/noimage.png';
        }
    }
    //views
    if(empty($col_options['views'])){
        $views = '<span class="post_views"><i class="icon icon-eye"></i> ' . $p->pageviews . '</span>';
    }else{
        $views = '';
    }
    //output
    $output = '<a href="' . get_the_permalink($p->id) . '" title="' . esc_attr($p->title) . '" class="post_links"><div class="list-block"><div class="post_thumb" style="background-image: url(' . $src . ')"><span>&nbsp;</span></div><div class="list-text"><h3 class="list-title post_ttl">' . $p->title . '</h3></div></div>' . $views . '</a>';
    return $output;
}

function liquid_popular_post( $wpp_column ) {
    global $col_options;
    if (function_exists('wpp_get_mostpopular')) {
        
        if ( !empty($wpp_column) && $wpp_column == 2 ){
            $limit = 4;
        } elseif( !empty($wpp_column) ) {
            $limit = $wpp_column;
        } elseif( empty($col_options['card']) ) {
            $limit = 4;
        } else {
            $limit = 6;
        }
        
        if(empty($col_options['range'])){
            $range = 'monthly';
        }else{
            $range = $col_options['range'];
        }
        
        $date_format = get_option( 'date_format' );
            
        $wpp = array (
            'range'             => $range, //daily, weekly, monthly
            'order_by'          => 'views', //views, comments, avg
            'limit'             => $limit,
            'post_type'         => 'post',
            'title_length'      => '36',
            'stats_comments'    => true,
            'stats_views'       => true,
            'stats_author'      => true,
            'stats_date'        => true,
            'stats_date_format' => $date_format,
            'stats_category'    => true,
            'wpp_start'         => '<!-- wpp --><div class="popularbox"><div class="col-xs-12"><div class="ttl"><i class="icon icon-trophy"></i> '.esc_html__( 'What&rsquo;s Hot', 'liquid-magazine' ).'</div></div>',
            'wpp_end'           => '</div><!-- /wpp -->'
        );
        $wpp_cate = array (
            'cat'               => get_query_var('cat'), //category
            'range'             => $range, //daily, weekly, monthly
            'order_by'          => 'views', //views, comments, avg
            'limit'             => $limit,
            'post_type'         => 'post',
            'title_length'      => '36',
            'stats_comments'    => true,
            'stats_views'       => true,
            'stats_author'      => true,
            'stats_date'        => true,
            'stats_date_format' => $date_format,
            'stats_category'    => true,
            'wpp_start'         => '<!-- wpp --><div class="popularbox"><div class="col-xs-12"><div class="ttl"><i class="icon icon-trophy"></i> '.get_the_archive_title().': '.esc_html__( 'What&rsquo;s Hot', 'liquid-magazine' ).'</div></div>',
            'wpp_end'           => '</div><!-- /wpp -->'
        );
        $wpp_widget = array (
            'range'             => $range, //daily, weekly, monthly
            'order_by'          => 'views', //views, comments, avg
            'limit'             => $limit,
            'post_type'         => 'post',
            'title_length'      => '36',
            'stats_comments'    => true,
            'stats_views'       => true,
            'stats_author'      => true,
            'stats_date'        => true,
            'stats_date_format' => $date_format,
            'stats_category'    => true,
            'wpp_start'         => '<!-- wpp --><div class="container"><div class="row popularbox">',
            'wpp_end'           => '</div></div><!-- /wpp -->'
        );
        
        if ( empty($wpp_column) && empty($col_options['wpp']) ) {
            if( empty($col_options['card']) ) {
                add_filter( 'wpp_post', 'liquid_popular_post_col2', 10, 3 );
            }else{
                add_filter( 'wpp_post', 'liquid_popular_post_col3', 10, 3 );
            }
            //html
            if(is_home()) {
                wpp_get_mostpopular($wpp);
            }elseif(is_category()) {
                wpp_get_mostpopular($wpp_cate);
                wpp_get_mostpopular($wpp);
            }elseif(is_archive()) {
                wpp_get_mostpopular($wpp);
            }elseif(is_search()) {
                wpp_get_mostpopular($wpp);
            }else{
                echo '<div class="row">';
                wpp_get_mostpopular($wpp);
                echo '</div>';
            }
            if( empty($col_options['card']) ) {
                remove_filter( 'wpp_post', 'liquid_popular_post_col2' );
            }else{
                remove_filter( 'wpp_post', 'liquid_popular_post_col3' );
            }
        } elseif ( !empty($wpp_column) && $wpp_column == 2 ){
            add_filter( 'wpp_post', 'liquid_popular_post_col2', 10, 3 );
            wpp_get_mostpopular($wpp_widget);
            remove_filter( 'wpp_post', 'liquid_popular_post_col2' );
        } elseif ( !empty($wpp_column) && $wpp_column == 3 ){
            add_filter( 'wpp_post', 'liquid_popular_post_col3', 10, 3 );
            wpp_get_mostpopular($wpp_widget);
            remove_filter( 'wpp_post', 'liquid_popular_post_col3' );
        } elseif ( !empty($wpp_column) && $wpp_column == 4 ){
            add_filter( 'wpp_post', 'liquid_popular_post_col4', 10, 3 );
            wpp_get_mostpopular($wpp_widget);
            remove_filter( 'wpp_post', 'liquid_popular_post_col4' );
        }
    }
}
?>