<?php get_header(); ?>
   
    <div <?php post_class('detail'); ?>>
        <div class="container">
          <div class="row">
           <div class="<?php liquid_col_options('mainarea'); ?> mainarea">

           <?php if (have_posts()) : ?>
           <?php while (have_posts()) : the_post(); ?>
           
            <h1 class="ttl_h1 entry-title" title="<?php the_title(); ?>"><?php the_title(); ?></h1>
            
            <!-- pan -->
            <ul class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
              <li><a href="<?php echo home_url(); ?>" itemprop="url"><span itemprop="title">TOP</span></a></li>
              <?php $cat = get_the_category();
                  if(!empty($cat)){
                      echo "<li>";
                      $catstr = get_category_parents($cat[0]->term_id,TRUE,'</li><li>'); 
                      $search = array('href', '">', '</a>');
                      $replace = array('itemprop="url" href', '"><span itemprop="title">', '</span></a>');
                      $catstr = str_replace($search, $replace, $catstr);
                      echo substr($catstr, 0, strlen($catstr) -4 );
                  }elseif( get_post_type() != "post" && get_post_type_archive_link(get_post_type()) ){
                      echo '<li><a itemprop="url" href="'.get_post_type_archive_link(get_post_type()).'"><span itemprop="title">'.get_post_type_object(get_post_type())->label.'</span></a></li>';
                  } ?>
              <li class="active"><?php the_title(); ?></li>
            </ul>
            
           
            <div class="detail_text">

                <?php //share
                $share_options = get_option('share_options');
                if(isset($share_options['all']) && $share_options['all'] == "2" || empty($share_options['all'])){
                    get_template_part('share');
                }
                ?>

                <div class="post_meta">
                <span class="post_time">
                 <i class="icon icon-clock" title="<?php echo get_the_time("Y/m/d H:i"); ?>"></i><?php if ( get_the_date() != get_the_modified_date() ) : ?> <i class="icon icon-spinner11" title="<?php echo get_the_modified_date("Y/m/d H:i"); ?>"></i><?php endif; ?> <time class="date updated"><?php echo get_the_date(); ?></time>
                </span>
                <?php if(!empty($cat)){ ?>
                    <span class="post_cat"><i class="icon icon-folder"></i>
                    <?php the_category(', '); ?>
                    </span>
                <?php }elseif( get_post_type() != "post" && get_post_type_archive_link(get_post_type()) ){ ?>
                    <span class="post_cat"><i class="icon icon-folder"></i>
                    <a href="<?php echo get_post_type_archive_link(get_post_type()); ?>"><?php echo get_post_type_object(get_post_type())->label; ?></a>
                    </span>
                <?php } ?>
                </div>
                
                <?php //thumbnail
                $col_options = get_option('col_options');
                if(empty($col_options['thumbnail'])){
                    if(has_post_thumbnail()) { the_post_thumbnail(); }   
                }
                ?>
                
                <?php if ( is_active_sidebar( 'main_head' ) ) { ?>
                <div class="row widgets">
                    <?php dynamic_sidebar( 'main_head' ); ?>
                </div>
                <?php } ?>
                
                <!-- content -->
                <div class="post_body"><?php the_content(); ?></div>
                <?php
                // ページング
                $args = array(
                    'before' => '<nav><ul class="page-numbers">', 
                    'after' => '</ul></nav>', 
                    'link_before' => '<li>', 
                    'link_after' => '</li>'
                );
                wp_link_pages( $args );
                ?>
                
                <?php if ( is_active_sidebar( 'main_foot' ) ) { ?>
                <div class="row widgets">
                    <?php dynamic_sidebar( 'main_foot' ); ?>
                </div>
                <?php } ?>
                
                <!-- author -->
                <?php if(empty($col_options['author'])){ ?>
                <div class="authorbox vcard author">
                   <?php $aid = get_the_author_meta('ID'); ?>
                   <?php $aurl = get_the_author_meta('user_url'); ?>
                   <?php echo get_avatar( $aid, $size = "100" ); ?>
                   <?php esc_html_e( 'by', 'liquid-magazine' ); ?> <span class="fn"><?php the_author_posts_link(); ?></span>
                   <div class="prof"><?php echo get_the_author_meta('description'); ?></div>
                   <?php if(!empty($aurl)) { ?>
                   <div class="user_url"><a href="<?php echo $aurl; ?>" target="_blank"><?php echo $aurl; ?></a></div>
                   <?php } ?>
                </div>
                <?php } ?>

                <!-- tags -->
                <?php the_tags( '<ul class="list-inline tag"><li>', '</li><li>', '</li></ul>' ); ?>
                <?php if( get_post_type() != "post" ){
                    the_taxonomies( array( 'post' => $post->ID, 'before' => '<ul class="list-inline tag"><li>', 'sep' => '</li><li>', 'after' => '</li></ul>', 'template' => '%s: %l' ) );
                } ?>
                
                <?php //share
                if(isset($share_options['all']) && $share_options['all'] == "2" || empty($share_options['all'])){
                    get_template_part('share');
                }
                ?>
                
                <!-- form -->
                <?php if(!empty($col_options['form'])){ ?>
                <div class="formbox">
                    <?php if(!empty($col_options['form2'])){
                       $formbox = $col_options['form2'];
                    }else{
                       $formbox = esc_html__( 'Contact', 'liquid-magazine' );
                    } ?>
                    <a href="<?php echo $col_options['form']; ?>"><i class="icon icon-mail"></i> <?php echo $formbox; ?></a>
                </div>
                <?php } ?>
                
                <!-- SNS -->
                <?php $sns_options = get_option('sns_options'); ?>
                <?php if(!empty($sns_options['facebook']) || !empty($sns_options['twitter'])){ ?>
                <div class="followbox">
                 <div class="ttl"><i class="icon icon-user-plus"></i> <?php esc_html_e( 'Follow', 'liquid-magazine' ); ?></div>
                 <div class="follow">
                  <div class="follow_wrap">
                    <?php 
                        $attr = array(
                            'data-adaptive-background' => 1
                        );
                        if(has_post_thumbnail()) {
                            echo '<div class="follow_img">';
                            the_post_thumbnail('thumbnail', $attr);
                            echo '</div>';
                        }
                    ?>
                   <div class="follow_sns">
                    <div class="share">
                    <?php if(!empty($sns_options['facebook'])){ ?>
                    <a href="<?php echo $sns_options['facebook']; ?>" target="_blank" class="share_facebook" title="Facebookでフォローする"><i class="icon icon-facebook"></i> Facebook</a>
                    <?php } ?>
                    <?php if(!empty($sns_options['twitter'])){ ?>
                    <a href="<?php echo $sns_options['twitter']; ?>" target="_blank" class="share_twitter" title="Twitterでフォローする"><i class="icon icon-twitter"></i> Twitter</a>
                    <?php } ?>
                    </div>
                   </div>
                  </div>
                 </div>
                </div>
                <?php } ?>
                
            </div>
           <?php endwhile; ?>
           <div class="detail_comments">
               <?php comments_template(); ?>
           </div>
           <?php else : ?>
               <p><?php esc_html_e( 'No articles', 'liquid-magazine' ); ?></p>
               <?php get_search_form(); ?>
           <?php endif; ?>         
           
           
            <nav>
              <ul class="pager">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                if (!empty( $prev_post )) {
                    echo '<li class="pager-prev"><a href="'.get_permalink( $prev_post->ID ).'" title="'.htmlspecialchars($prev_post->post_title).'">'.esc_html__( '&laquo; Prev', 'liquid-magazine' ).'</a></li>';
                }
                //if (!empty( $cat )) {
                //    echo '<li class="pager-archive">';
                //    the_category('</li><li class="pager-archive">');
                //    echo '</li>';
                //}
                if (!empty( $next_post )) {
                    echo '<li class="pager-next"><a href="'.get_permalink( $next_post->ID ).'" title="'.htmlspecialchars($next_post->post_title).'">'.esc_html__( 'Next &raquo;', 'liquid-magazine' ).'</a></li>';
                } ?>
                </ul>
            </nav>
            
           
           <div class="recommend">
           <div class="ttl"><i class="icon icon-list"></i> <?php esc_html_e( 'Recommend', 'liquid-magazine' ); ?></div>
              <div class="row">
                <?php 
                //post_class
                if( empty($col_options['card']) ) {
                    $classes = array( 'list', 'col-md-12' );
                } else {
                    $classes = array( 'list', 'col-lg-4', 'list_big' );
                }
                ?>
               <?php
                  //recommend
                  $original_post = $post;
                  $tags = wp_get_post_tags($post->ID);
                  $tagIDs = array();
                  if ($tags) {
                      $tagcount = count($tags);
                      for ($i = 0; $i < $tagcount; $i++) {
                          $tagIDs[$i] = $tags[$i]->term_id;
                      }
                      $args=array(
                      'tag__in' => $tagIDs,
                      'post__not_in' => array($post->ID),
                      'posts_per_page' => 4,
                      'ignore_sticky_posts' => 1
                      );
                  }elseif(!empty($cat)){
                      $args=array(
                      'cat' => $cat[0]->cat_ID,
                      'post__not_in' => array($post->ID),
                      'posts_per_page' => 4,
                      'ignore_sticky_posts' => 1
                      );
                  }else{
                      $args=array(
                      'post__not_in' => array($post->ID),
                      'posts_per_page' => 4,
                      'ignore_sticky_posts' => 1
                      );
                  }
                  $my_query = new WP_Query($args);
                  if( $my_query->have_posts() ) {
                  while ($my_query->have_posts()) : $my_query->the_post();
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
                ?>

               <article <?php post_class( $classes );?>>
                 <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" class="post_links">
                  <div class="list-block">
                   <div class="post_thumb" style="background-image: url('<?php echo $src; ?>')"><span>&nbsp;</span></div>
                   <div class="list-text">
                       <span class="post_time"><i class="icon icon-clock"></i> <?php echo get_the_date(); ?></span>
                       <h3 class="list-title post_ttl"><?php the_title(); ?></h3>
                   </div>
                  </div>
                 </a>
               </article>
                <?php endwhile; wp_reset_query(); ?>
                <?php } else { ?>
                <div class="col-xs-12"><?php esc_html_e( 'No articles', 'liquid-magazine' ); ?></div>
                <?php } ?>
              </div>
            </div>
            

        <!-- PopularPosts -->
        <?php liquid_popular_post(0); ?>
            
            
           </div><!-- /col -->
           <?php get_sidebar(); ?>
           
         </div>
        </div>
    </div>


<?php get_footer(); ?>