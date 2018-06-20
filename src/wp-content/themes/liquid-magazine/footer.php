<div class="foot">
  <div class="container sns">
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
     
<div class="pagetop">
    <a href="#top"><i class="icon icon-arrow-up2"></i></a>
</div>

<?php if ( is_active_sidebar( 'page_footer' ) ) { ?>
<div class="row widgets page_footer">
    <?php dynamic_sidebar( 'page_footer' ); ?>
</div>
<?php } ?>

<footer>
        <div class="container">
          <?php if ( is_active_sidebar( 'footer' ) ) { ?>
          <div class="row widgets">
            <?php dynamic_sidebar( 'footer' ); ?>
          </div>
          <?php } ?>
        </div>
        
        <div class="copy">
        <?php esc_html_e( '(C)', 'liquid-magazine' ); ?> <?php echo date('Y'); ?> <a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a><?php esc_html_e( '. All rights reserved.', 'liquid-magazine' ); ?>
        <!-- Powered by -->
        <?php $lqd_options = get_option('lqd_options'); if(!empty($lqd_options['ls']) && !empty($lqd_options['cp'])): ?>
        <?php else: ?>
        <?php esc_html_e( 'Theme by', 'liquid-magazine' ); ?> <a href="https://lqd.jp/wp/" rel="nofollow" title="<?php esc_html_e( 'Responsive WordPress Theme LIQUID PRESS', 'liquid-magazine' ); ?>"><?php esc_html_e( 'LIQUID PRESS', 'liquid-magazine' ); ?></a>.
        <?php endif; ?>
        <!-- /Powered by -->
        </div>

    </footer>
      
</div><!--/site-wrapper-->

<?php wp_footer(); ?>

<!-- JS -->
<?php $col_options = get_option('col_options');
if(!empty($col_options['dropdown'])){ ?>
<script>liquid_dropdown();</script>
<?php } ?>

</body>
</html>