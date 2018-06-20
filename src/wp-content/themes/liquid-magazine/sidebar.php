           <div class="<?php liquid_col_options('sidebar'); ?> sidebar">
            <div class="sidebar-inner">
             <div class="row widgets">
                <?php if(! dynamic_sidebar('sidebar')){ ?>
                 <!-- no widget -->
                 <div class="col-xs-12 cats">
                  <?php get_search_form(); ?>
                  <div class="ttl"><?php esc_html_e( 'Categories', 'liquid-magazine' ); ?></div>
                  <ul class="list-unstyled">
                    <?php wp_list_categories('title_li='); ?>
                  </ul>
                 </div>
                 <div class="col-xs-12 tags">
                  <div class="ttl"><?php esc_html_e( 'Tags', 'liquid-magazine' ); ?></div>
                  <ul class="list-unstyled">
                    <?php
                    $args = array(
                        'taxonomy' => 'post_tag',
                        'title_li' => ''
                    );
                    wp_list_categories( $args );
                    ?>
                  </ul>
                 </div>
                <?php } ?>
             </div>
            </div>
           </div>