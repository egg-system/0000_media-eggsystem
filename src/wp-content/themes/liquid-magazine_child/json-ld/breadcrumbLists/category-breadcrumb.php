{ 
  "@context":"http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement":
  [
    {"@type": "ListItem","position": 1,"item":{"@id": "<?php echo home_url(); ?>","name": "ホーム"}},
    <?php
        //パンくずの階層用
        $i=1;
        //カテゴリーに関する情報を取得
        $categories = get_the_category($post->ID);
        $cat = $categories[0];
        //カテゴリーアーカイブのタイトルを取得
        $cattitle = get_the_archive_title();
        //先祖のカテゴリーがあれば(0でなければ)分岐
        if($cat -> parent != 0){
            //先祖のカテゴリーを配列で取得
            $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
            //$ancestorsの配列から一つ一つ$ancestorに取り出してなくなるまでくりかえす
            foreach($ancestors as $ancestor){
                $i++;
                echo '    {"@type": "ListItem","position": '.$i.',"item":{"@id": "'. get_category_link($ancestor).'","name": "'. get_cat_name($ancestor). '"}},'.PHP_EOL;
            }
        }
        //表示されているカテゴリーの情報を出力
        $i++;
        echo '    {"@type": "ListItem","position": '.$i.',"item":{"@id": "'. get_category_link($cat -> term_id). '","name": "'. $cattitle . '"}}'.PHP_EOL;
    ?>
  ]
}