{ 
  "@context":"http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement":
  [
    {"@type": "ListItem","position": 1,"item":{"@id": "<?php echo home_url(); ?>","name": "ホーム"}},
    <?php
        $tagName = single_tag_title('', false);
        $tag = get_term_by('name', $tagName, 'post_tag');
        $link = get_tag_link($tag->term_id);
        echo '    {"@type": "ListItem","position": 2,"item":{"@id": "'. esc_url($link). '","name": "'. esc_html($tagName) . '"}}'.PHP_EOL;
    ?>
  ]
}