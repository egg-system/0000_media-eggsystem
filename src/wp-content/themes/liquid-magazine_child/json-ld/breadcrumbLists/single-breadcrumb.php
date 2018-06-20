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
        //属していてる直接のカテゴリーの情報を出力
        $i++;
        echo '    {"@type": "ListItem","position": '.$i.',"item":{"@id": "'. get_category_link($cat -> term_id). '","name": "'. $cat-> cat_name . '"}},'.PHP_EOL;
        //表示されている投稿ページの情報を出力
        $i++;
        echo '    {"@type": "ListItem","position": '.$i.',"item":{"@id": "'. esc_url(get_permalink()). '","name": "'. esc_html(get_the_title()) . '"}}'.PHP_EOL;
    ?>
  ]
}