{ 
  "@context":"http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement":
  [
    {"@type": "ListItem","position": 1,"item":{"@id": "<?php echo home_url(); ?>","name": "ホーム"}},
    <?php
        //ベージごとに必要な情報のベースを取得。先祖の有無判断に利用。
        $obj = get_queried_object();
        $i=1;
        //先祖の固定ページがあれば(0でなければ)分岐
        if($obj -> post_parent != 0){
            //先祖の固定ページを配列で取得
            $pageAncestors = array_reverse( $post -> ancestors );
            //$ancestorsの配列から一つ一つ$ancestorに取り出してなくなるまでくりかえす
            foreach ($pageAncestors as $pageAncestor) {
                $i++;
                echo '    {"@type": "ListItem","position": '.$i.',"item":{"@id": "'. esc_url(get_permalink($pageAncestor)).'","name": "'. esc_html(get_the_title($pageAncestor)). '"}},'.PHP_EOL;
            }
        }
        //表示されている固定ページの情報を出力
        $i++;
        echo '    {"@type": "ListItem","position": '.$i.',"item":{"@id": "'. esc_url(get_permalink()). '","name": "'. esc_html(get_the_title()) . '"}}'.PHP_EOL;
    ?>
  ]
}