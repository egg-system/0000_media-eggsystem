{ 
  "@context":"http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement":
  [
    {"@type": "ListItem","position": 1,"item":{"@id": "<?php echo home_url(); ?>","name": "ホーム"}},
    <?php
        //年月日を取得
        $y = get_query_var('year');
        $m = get_query_var('monthnum');
        $d = get_query_var('day');
        //年月日のアーカイブのリンクを取得
        $linkY = get_year_link($y);
        $linkM = get_month_link($y,$m);
        $linkD = get_month_link($y,$m,$d);
        if(is_day()){
            echo '    {"@type": "ListItem","position": 2,"item":{"@id": "'. esc_url($linkY).'","name": "'. esc_html($y).'年"}},'.PHP_EOL;
            echo '    {"@type": "ListItem","position": 3,"item":{"@id": "'. esc_url($linkM).'","name": "'. esc_html($m).'月"}},'.PHP_EOL;
            echo '    {"@type": "ListItem","position": 4,"item":{"@id": "'. esc_url($linkD).'","name": "'. esc_html($d). '日"}}'.PHP_EOL;
        } elseif(is_month()){
            echo '    {"@type": "ListItem","position": 2,"item":{"@id": "'. esc_url($linkY).'","name": "'. esc_html($y).'年"}},'.PHP_EOL;
            echo '    {"@type": "ListItem","position": 3,"item":{"@id": "'. esc_url($linkM).'","name": "'. esc_html($m).'月"}}'.PHP_EOL;
        } elseif(is_year()) {
            echo '    {"@type": "ListItem","position": 2,"item":{"@id": "'. esc_url($linkY).'","name": "'. esc_html($y).'年"}}'.PHP_EOL;
        }
    ?>
  ]
}