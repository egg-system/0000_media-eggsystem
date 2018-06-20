<?php get_header(); ?>

    <div class="detail page">
        <div class="container">
          <div class="row">
           <div class="<?php liquid_col_options('mainarea'); ?> mainarea">
          
           <?php if (have_posts()) : ?>
           <?php while (have_posts()) : the_post(); ?>
            <h1 class="ttl_h1" title="<?php the_title(); ?>"><?php the_title(); ?></h1>

            <!-- pan -->
            <?php
                $cat_name = get_the_title($post->post_parent);
                $cat_slug = get_page_link($post->post_parent);
            ?>
            <ul class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
              <li><a href="<?php echo home_url(); ?>" itemprop="url"><span itemprop="title">TOP</span></a></li>
              <?php if($post->post_parent){ echo '<li><a href="'.$cat_slug.'" itemprop="url"><span itemprop="title">'.$cat_name.'</span></a></li>'; } ?>
              <li class="active"><?php the_title(); ?></li>
            </ul>


            <div class="detail_text">
               
                <?php //share
                $share_options = get_option('share_options');
                if(empty($share_options['all'])){
                    get_template_part('share');
                }
                ?>
                
                <div class="post_meta">
                <?php //thumbnail
                $col_options = get_option('col_options');
                if(empty($col_options['thumbnail'])){
                    if(has_post_thumbnail()) { the_post_thumbnail(); }   
                }
                ?>
                </div>
                
                <?php if ( is_active_sidebar( 'page_head' ) ) { ?>
                <div class="row widgets">
                    <?php dynamic_sidebar( 'page_head' ); ?>
                </div>
                <?php } ?>
                
                <div class="post_body"><?php the_content(); ?></div>
                
                <?php if ( is_active_sidebar( 'page_foot' ) ) { ?>
                <div class="row widgets">
                    <?php dynamic_sidebar( 'page_foot' ); ?>
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

            
           </div><!-- /col -->
           <?php get_sidebar(); ?>
           
         </div>
        </div>
    </div>

   
<?php get_footer(); ?>
