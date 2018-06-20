{ 
  "@context":"http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement":
  [
    {"@type": "ListItem","position": 1,"item":{"@id": "<?php echo home_url(); ?>","name": "ホーム"}},
    <?php
        echo '    {"@type": "ListItem","position": 2,"item":{"@id": "'. esc_url(get_search_link()). '","name": "「'. esc_html(get_search_query()) . '」で検索した結果"}}'.PHP_EOL;
    ?>
  ]
}