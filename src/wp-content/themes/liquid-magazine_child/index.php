<?php get_header(); ?>
    
         <!-- cover -->
         <?php 
         $col_options = get_option('col_options');
         $img_options = get_theme_mod('img_options');
         $text_options = get_option('text_options');
         $video_options = get_theme_mod('video_options');
         $video_options_url = get_option('video_options');
         ?>
         <?php if( !empty( $video_options["mp4"] ) && !wp_is_mobile() ){ ?>
             <div class="cover"><div class="cover_inner">
                <video src="<?php echo $video_options["mp4"]; ?>" autoplay loop>
                  <?php if(!empty($img_options["img01"])){ ?><img src="<?php echo $img_options["img01"]; ?>" alt="placeholder"><?php } ?>
                </video>
                <?php if(!empty($text_options["text01"])){ ?>
                  <div class="main"><h3><?php echo $text_options["text01"]; ?></h3></div>
                <?php } ?>
             </div></div>
         <?php }elseif( !empty( $video_options_url["url"] ) && !wp_is_mobile() ){
             preg_match('/\?v=([^&]+)/', $video_options_url["url"], $match);
             $ytid = $match[1]; ?>
             <div class="cover"><div class="cover_inner">
                <iframe width="100%" height="" src="https://www.youtube.com/embed/<?php echo $ytid; ?>?rel=0&amp;controls=0&amp;showinfo=0&amp;autoplay=1" frameborder="0" allowfullscreen></iframe>
                <?php if(!empty($text_options["text01"])){ ?>
                  <div class="main"><h3><?php echo $text_options["text01"]; ?></h3></div>
                <?php } ?>
             </div></div>
         <?php }elseif( !empty( $img_options["img01"] ) ){ ?>
             <div class="cover">

<div class="smartphone-slide-list">
<img class="smartphone-slide-img1" src="https://media.eggsystem.co.jp/wp-content/uploads/2018/04/top-interview-phone2.png">
<img class="smartphone-slide-img2" src="https://media.eggsystem.co.jp/wp-content/uploads/2018/04/top-interview-phone2.png">
</div><!--smartphone-slide-list-->

             <div id="carousel-generic" class="carousel slide" data-ride="carousel">
             
              <div class="carousel-inner" role="listbox">
                <?php 
                    $textop[1] = ""; $textop[2] = ""; $textop[3] = "";
                    if(!empty($text_options["text01"])){ 
                        $textop[1] = '<div class="main"><h3>'.$text_options["text01"].'</h3></div>';
                    }
                    if(!empty($text_options["text02"])){ 
                        $textop[2] = '<div class="main"><h3>'.$text_options["text02"].'</h3></div>';
                    }
                    if(!empty($text_options["text03"])){ 
                        $textop[3] = '<div class="main"><h3>'.$text_options["text03"].'</h3></div>';
                    }
                ?>
                <?php 
                    if(!empty($img_options["img01"])){
                        echo '<div class="carousel-item active"><img src="'.$img_options["img01"].'" alt="">'.$textop[1].'</div>';
                    }
                    if(!empty($img_options["img02"])){
                        echo '<div class="carousel-item"><img src="'.$img_options["img02"].'" alt="">'.$textop[2].'</div>';
                    }
                    if(!empty($img_options["img03"])){
                        echo '<div class="carousel-item"><img src="'.$img_options["img03"].'" alt="">'.$textop[3].'</div>';
                    }
                ?>
              </div>
              
              <?php if(!empty( $img_options['img02']) ){ ?>
              <!-- Controls -->
              <a class="left carousel-control" href="#carousel-generic" role="button" data-slide="prev">
                <span class="icon icon-arrow-left2" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="right carousel-control" href="#carousel-generic" role="button" data-slide="next">
                <span class="icon icon-arrow-right2" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
              <!-- Indicators -->
              <ol class="carousel-indicators">
                <?php 
                    if(!empty($img_options["img01"])){
                        echo '<li data-target="#carousel-generic" data-slide-to="0" class="active"></li>';
                    }
                    if(!empty($img_options["img02"])){
                        echo '<li data-target="#carousel-generic" data-slide-to="1"></li>';
                    }
                    if(!empty($img_options["img03"])){
                        echo '<li data-target="#carousel-generic" data-slide-to="2"></li>';
                    }
                ?>
              </ol>
              <?php } ?>
             
              </div>
              </div>
              
          <?php } elseif( empty($col_options['head']) ) { ?>
          <div class="hero">
             <div class="hero_img" data-adaptive-background data-ab-css-background>&nbsp;</div>
          </div>
          <?php } ?>
        <!-- /cover -->  



    <div class="mainpost">
     <div class="container">         
         
      <div class="row">
       <div class="<?php liquid_col_options('mainarea'); ?> mainarea">
                           
          <?php if ( is_active_sidebar( 'top_header' ) ) { ?>
          <div class="row widgets">
            <?php dynamic_sidebar( 'top_header' ); ?>
          </div>
          <?php } ?>
                    
          <div class="ttl"><i class="icon icon-list"></i> <?php esc_html_e( 'What&rsquo;s New', 'liquid-magazine' ); ?></div>
          <div class="row" id="main">
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
                //post_class
                if( empty($col_options['card']) ) {
                    if($i < 3){
                        $classes = array( 'list', 'col-md-6', 'list_big' );
                    }else{
                        $classes = array( 'list', 'col-md-12' );
                    }
                } else {
                    $classes = array( 'list', 'col-lg-4', 'list_big' );
                }
                ?>
               <?php if($i == 7){ ?>
                    <div class="col-xs-12"><div class="ttl"><i class="icon icon-list"></i> <?php esc_html_e( 'Articles', 'liquid-magazine' ); ?></div></div>
               <?php } ?>
               <article <?php post_class( $classes );?>>
                 <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" class="post_links">
                  <div class="list-block">
                   <div class="post_thumb" style="background-image: url('<?php echo $src; ?>')"><span>&nbsp;</span></div>
                   <div class="list-text">
                       <span class="post_time"><i class="icon icon-clock"></i> <?php echo get_the_date(); ?></span>
                       <?php if($cat){ echo '<span class="post_cat"><i class="icon icon-folder"></i> '.$cat_name.'</span>';} ?>
                       <h3 class="list-title post_ttl">
                           <?php the_title(); ?>
                       </h3>
                   </div>
                  </div>
                 </a>
               </article>
               <?php if($i == 6 && empty($j)){ ?>
                    <?php dynamic_sidebar( 'top_footer' ); ?>
                    <?php //PopularPosts
                        liquid_popular_post(0); ?>
                    <?php $j = 1; ?>
               <?php } ?>
               <?php 
                //endforeach;
                endwhile;
                else : 
                echo '<!-- <div class="col-xs-12 noarticles">'.esc_html__( 'No articles', 'liquid-magazine' ).'</div> -->';
                endif;
		       ?>
               <?php if(empty($j)){ ?>
                    <?php dynamic_sidebar( 'top_footer' ); ?>
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
