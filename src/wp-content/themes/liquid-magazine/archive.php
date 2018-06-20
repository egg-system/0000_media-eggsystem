<?php get_header(); ?>

    <div class="detail archive">
        <div class="container">
          <div class="row">
           <div class="<?php liquid_col_options('mainarea'); ?> mainarea">

            <?php the_archive_title( '<h1 class="ttl_h1">', '</h1>' ); ?>
            <?php if(is_category()) { ?>
                <div class="cat_info"><?php echo category_description(); ?></div>
            <?php } ?>
               
          <?php if ( is_active_sidebar( 'archive_head' ) ) { ?>
          <div class="row widgets">
            <?php dynamic_sidebar( 'archive_head' ); ?>
          </div>
          <?php } ?>
                    
          <div class="ttl"><i class="icon icon-list"></i> <?php esc_html_e( 'What&rsquo;s New', 'liquid-magazine' ); ?></div>
          
          <div class="row" id="main">
                <?php 
                //post_class
                $col_options = get_option('col_options');
                if( empty($col_options['card']) ) {
                    $classes = array( 'list', 'col-md-12' );
                    $ia = 9;
                    $ib = 8;
                } else {
                    $classes = array( 'list', 'col-lg-4', 'list_big' );
                    $ia = 7;
                    $ib = 6;
                }
                ?>
                <?php $i = 0; if (have_posts()) : while ( have_posts() ) : the_post(); $i++;
                // $postslist = get_posts(); foreach ($postslist as $post) : setup_postdata($post); ?>
                <?php 
                //cat
                $cat = get_the_category();
                if(!empty($cat)){
                    if($cat[0]->parent){
                        $parent_info = get_category($cat[0]->parent);
                        $cat_name = $parent_info->name;
                        $cat_slug = $parent_info->slug;
                    }else{
                        $cat_info = get_category($cat[0]->cat_ID);
                        $cat_name = $cat_info->name;
                        $cat_slug = $cat_info->slug;
                    }
                }
                //thumb
                $src = "";
                if(has_post_thumbnail($post->ID)){
                    // アイキャッチ画像を設定済みの場合
                    $thumbnail_id = get_post_thumbnail_id($post->ID);
                    $src_info = wp_get_attachment_image_src($thumbnail_id, 'full');
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
               <?php if($i == $ia){ ?>
                    <div class="col-xs-12"><div class="ttl"><i class="icon icon-list"></i> <?php esc_html_e( 'Articles', 'liquid-magazine' ); ?></div></div>
               <?php } ?>
               <article <?php post_class( $classes );?>>
                 <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" class="post_links">
                  <div class="list-block">
                   <div class="post_thumb" style="background-image: url('<?php echo $src; ?>')"><span>&nbsp;</span></div>
                   <div class="list-text">
                       <span class="post_time"><i class="icon icon-clock"></i> <?php echo get_the_date(); ?></span>
                       <?php if($cat){ echo '<span class="post_cat"><i class="icon icon-folder"></i> '.$cat_name.'</span>';} ?>
                       <h3 class="list-title post_ttl"><?php the_title(); ?></h3>
                   </div>
                  </div>
                 </a>
               </article>
               <?php if($i == $ib && empty($j)){ ?>
                    <?php dynamic_sidebar('archive_foot'); ?>
                    <?php //PopularPosts
                        liquid_popular_post(0); ?>
                    <?php $j = 1; ?>
               <?php } ?>
               <?php 
                //endforeach;
                endwhile;
                else : 
                echo '<div class="col-xs-12">'.esc_html__( 'No articles', 'liquid-magazine' ).'</div>';
                endif;
		       ?>
               <?php if(empty($j)){ ?>
                    <?php dynamic_sidebar('archive_foot'); ?>
                    <?php //PopularPosts
                        liquid_popular_post(0); ?>
               <?php } ?>
         </div>  

            <?php liquid_paging_nav(); ?>
                 
           </div><!-- /col -->
           <?php get_sidebar(); ?>
           
         </div>
        </div>
    </div>

<?php get_footer(); ?>
