<?php
/*
Author: LIQUID DESIGN
Author URI: https://lqd.jp/
*/

// is_admin
if ( !is_admin() ) {
    $home = home_url();
    header("Location: {$home}");
}
// get
if(!empty($_GET["range"])){
    $range = htmlspecialchars($_GET["range"]);
}else{
    $range = 'all';
}
if(!empty($_GET["sw"])){
    $sw = htmlspecialchars($_GET["sw"]);
}else{
    $sw = '';
}
?>
<div class="wrap liquid_theme_contents <?php echo $range; ?>">
<h1>コンテンツ分析 β</h1>
<hr>
<script src="https://lqd.jp/wp/data/jquery.tablesorter.min.js" type="text/javascript"></script>
<script src="https://lqd.jp/wp/data/admin.min.js"></script>
<link rel="stylesheet" property="stylesheet" href="https://lqd.jp/wp/data/admin.css">
<?php
$json_admin_url = "https://lqd.jp/wp/data/liquid-magazine-admin.json";
$json_admin = wp_remote_get($json_admin_url);
$json_admin = json_decode($json_admin['body']);
if(!empty($json_admin->contents)){
    echo '<div class="notice notice-info"><p>'.$json_admin->contents.'</p></div>';
}
?>
<ul class="subsubsub">
    <li>期間: </li>
    <li><a href="?page=liquid_theme_contents" class="all">All</a></li>
    <li><a href="?page=liquid_theme_contents&range=daily" class="daily">日</a></li>
    <li><a href="?page=liquid_theme_contents&range=weekly" class="weekly">週</a></li>
    <li><a href="?page=liquid_theme_contents&range=monthly" class="monthly">月</a></li>
    <li>エクスポート: </li>
	<li><a href="javascript:void(0)" class="export">CSV</a></li>
</ul>
<form action="themes.php" method="get">
<p class="search-box">
    <input type="hidden" name="page" value="liquid_theme_contents">
    <input type="hidden" name="range" value="<?php echo $range; ?>">
    <a href="#help">[ ? ]</a>
	<label for="post-search-input">キーワード分析:</label>
	<input type="search" id="post-search-input" name="sw" value="<?php echo $sw; ?>" placeholder="キーワード">
	<input type="submit" id="search-submit" class="button" value="分析">
</p>
</form>
<table class="wp-list-table widefat fixed striped posts"><thead><tr><th scope="col" class="manage-column def">ID</th><th scope="col" class="manage-column">作成者</th><th scope="col" class="manage-column cttl">タイトル</th><th scope="col" class="manage-column">タイトル文字数</th><th scope="col" class="manage-column">本文文字数</th><th scope="col" class="manage-column">タイトル出現数</th><th scope="col" class="manage-column">本文出現数</th><th scope="col" class="manage-column">画像</th><th scope="col" class="manage-column">公開日</th><th scope="col" class="manage-column">カテゴリ</th><th scope="col" class="manage-column">日数</th><th scope="col" class="manage-column">閲覧数</th><th scope="col" class="manage-column">コメント数</th><th scope="col" class="manage-column">Facebook</th><th scope="col" class="manage-column">はてブ</th><th scope="col" class="manage-column">Twitter</th><th scope="col" class="manage-column">SERP</th><th scope="col" class="manage-column">スコア</th></tr></thead><tbody id="the-list">
<?php 
//day diff
function day_diff($date1, $date2) {
    $timestamp1 = strtotime($date1);
    $timestamp2 = strtotime($date2);
    $seconddiff = abs($timestamp2 - $timestamp1);
    $daydiff = $seconddiff / (60 * 60 * 24);
    return $daydiff;
}
    
$args = array( 'posts_per_page' => 100, 'order' => 'DESC', 'post_type' => 'any' );//order=ASC

$posts = get_posts( $args );
foreach ( $posts as $post ) : setup_postdata( $post );

//thumb(wpp)
$src = "";
if(has_post_thumbnail($post->ID)){
    // アイキャッチ画像を設定済みの場合
    $thumbnail_id = get_post_thumbnail_id($post->ID);
    $src_info = wp_get_attachment_image_src($thumbnail_id, 'medium');
    $src = $src_info[0];
}else{
    // アイキャッチが設定されていない場合
    $src = get_stylesheet_directory_uri().'/images/noimage.png';
}

//word count
$words = strip_tags($post->post_content);
$word = mb_strlen($words);

//tweet link
$permalink = get_the_permalink($post->ID);
$search = array('http://','https://');
$link = str_replace($search, "", $permalink);

// wpp
if (function_exists('wpp_get_mostpopular')) {
    $wpp = 1;
}else{
    $pageviews = 'Disable';
}
    
//day diff, pageviews
if(!empty($_GET["range"]) && $_GET["range"] == 'daily'){
    $day = 1;
    if(!empty($wpp)){
        $pageviews = wpp_get_views($post->ID, 'daily', true);//daily, weekly, monthly, all
    }
}elseif(!empty($_GET["range"]) && $_GET["range"] == 'weekly'){
    $day = 7;
    if(!empty($wpp)){
        $pageviews = wpp_get_views($post->ID, 'weekly', true);//daily, weekly, monthly, all
    }
}elseif(!empty($_GET["range"]) && $_GET["range"] == 'monthly'){
    $day = 30;
    if(!empty($wpp)){
        $pageviews = wpp_get_views($post->ID, 'monthly', true);//daily, weekly, monthly, all
    }
}else{
    $today = date('Y/m/d');
    $day = day_diff($today, $post->post_date);
    if(!empty($wpp)){
        $pageviews = wpp_get_views($post->ID, 'all', true);//daily, weekly, monthly, all
    }
}

//sw
if(!empty($sw)){
    $swttl = mb_substr_count(strtoupper($post->post_title), strtoupper($sw));
    $swword = mb_substr_count(strtoupper($words), strtoupper($sw));
}else{
    $swttl = '-';
    $swword = '-';
}

//td
echo '<tr class="contentlist">';
echo '<td><a href="post.php?post='. $post->ID .'&action=edit" title="edit">'. $post->ID .'</a></td>';
echo '<td>'. get_the_author_meta('display_name', $post->post_author) .'</td>';
echo '<td><a href="'. $permalink . '" title="'. $permalink . '" target="_blank" class="contentlink">'. $post->post_title .'</a></td>';
echo '<td class="length"></td>';
echo '<td>'. $word .'</td>';
echo '<td class="swttl">'. $swttl .'</td>';
echo '<td class="swword">'. $swword .'</td>';
echo '<td><a href="'. $src .'" target="_blank"><img src="'. $src .'" width="80" alt=""></a></td>';
echo '<td>'. date('Y/m/d (D) H:i', strtotime($post->post_date)) .'</td>';
echo '<td>'. get_the_category_list( ",", "", $post->ID).'</td>';
echo '<td class="day">'. floor($day) .'</td>';
echo '<td class="pageviews">'. str_replace(',','',$pageviews) .'</td>';
echo '<td>'. $post->comment_count .'</td>';
echo '<td class="facebook">...</td>';
echo '<td class="hatena">...</td>';
echo '<td><a href="https://twitter.com/search?f=tweets&q='. urlencode($link) .'" target="_blank">List</a></td>';
echo '<td><a href="https://www.google.co.jp/search?q='. urlencode($permalink) .'" target="_blank">View</a></td>';
echo '<td class="score">...</td>';
echo '</tr>';

endforeach;
wp_reset_postdata();

?>
</tbody></table>
<p id="help">
[ ? ]
<br>コンテンツの重要キーワードを指定すると、コンテンツ内のキーワード出現数が表示されます。
<br>スコアは、閲覧数や日数、シェア数などから算出した独自のコンテンツ指標です。数値が大きいほど高パフォーマンスです。
<br>アドバイス情報は自動的に表示されます。効果を保証するものではありません。
<br>項目名をクリックして昇順および降順で並び替えします。IDをクリックして編集画面に遷移します。
<hr>
閲覧数の集計にはプラグイン「WordPress Popular Posts」が必要です。期間で集計される項目は閲覧数のみです。
<br>直近100件を表示します。正確さを保証するものではありません。シェア数は取得できなかった場合も0と表示されます。
<br>※当機能はβ版です。予告なく変更または休止する場合があります。当機能についてはサポート外とさせて頂きます。
<hr>
<a href="https://lqd.jp/wp/" target="_blank">LIQUID PRESS</a> のご利用ありがとうございます。
</p>
</div>