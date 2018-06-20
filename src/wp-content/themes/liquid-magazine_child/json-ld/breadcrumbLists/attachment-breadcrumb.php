{ 
  "@context":"http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement":
  [
    {"@type": "ListItem","position": 1,"item":{"@id": "<?php echo home_url(); ?>","name": "ホーム"}},
    <?php
        //パンくずの階層用
        $i=1;
        //ベージごとに必要な情報のベースを取得。先祖の有無判断に利用。
        $obj = get_queried_object();
        //先祖の挿入元のページがあれば(0でなければ)分岐
        if($obj -> parent != 0){
            $i++;
            echo '    {"@type": "ListItem","position": '.$i.',"item":{"@id": "'. esc_url(get_permalink($pageAncestor)).'","name": "'. esc_html(get_the_title($pageAncestor)). '"}},'.PHP_EOL;
        }
        //表示されている固定ページの情報を出力
        $i++;
        echo '    {"@type": "ListItem","position": '.$i.',"item":{"@id": "'. esc_url(get_permalink()). '","name": "'. esc_html(get_the_title()) . '"}}'.PHP_EOL;
    ?>
  ]
}