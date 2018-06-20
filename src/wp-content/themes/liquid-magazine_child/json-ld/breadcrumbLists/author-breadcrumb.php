{ 
  "@context":"http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement":
  [
    {"@type": "ListItem","position": 1,"item":{"@id": "<?php echo home_url(); ?>","name": "ホーム"}},
    <?php
        //執筆者のIDを取得
        $userId = get_query_var('author');
        //執筆者の名前を取得
        $authorName = get_the_author_meta( 'display_name', $userId );
        echo '    {"@type": "ListItem","position": 2,"item":{"@id": "'. esc_url(get_author_posts_url($userId)). '","name": "'. esc_html($authorName) . '"}}'.PHP_EOL;
    ?>
  ]
}