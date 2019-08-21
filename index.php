<?php 
require_once('head.php');
require_once('phpQuery.php'); // phpQueryの読み込み

if($_GET['word']){ 
  $word = $_GET['word']; // ユーザーが入力した単語
}else{
  $word = '料理';
}

?> <!-- ヘッダー読み込み -->

<!-- ナビ -->
<nav class='navbar navbar-expand-sm bg-dark-red mb-3'>
  <div class='container'>
    <h4 class='my-2 text-light'>
      無限食欲
    </h4>
    <p class='text-light mb-0'>〜 食欲を開放しよう！ 〜</p>
  </div>
</nav>


<!-- 入力フォーム -->
<div class='container'>
  <form action='./' method='get'>
    <div class='form-gruop row mx-lg-0'>

      <input name='word' value='<?php echo $word ?>' class='form-control col-12 col-lg-4' placeholder='単語を入力'>　<!-- 既に単語入ってたらそれを入れる -->

      <span class='text-center col-12 col-lg-auto mt-lg-auto'>
      　<input type='submit' value='検索' class='btn btn-dark-red'> <!-- 検索ボタン -->
      </span>

    </div>
  </form>
</div>

<!-- おすすめメニュー -->
<?php $recomend_foods = ['飯テロ', 'オムライス', 'ジブリ飯', '鍋', 'ステーキ', 'ハンバーグ', '肉汁溢れ', '鉄板焼き', 'チーズ', 'ピザ']; ?>

<!-- おすすめボタン -->
<div class='row px-3 mb-3 mx-0'>
  <?php foreach($recomend_foods as $food): ?>
  <div class='mx-2 my-'>
    <a href='?word=<?php echo $food; ?>' class='btn btn-sm btn-outline-light'>
      <?php echo $food; ?>
    </a>
  </div>
  <?php endforeach ?>
</div>



<?php

// 入力データを整形
$word = str_replace(' ', '%20', $word); // 半角スペースだとエラーになるので%20に直す
$word = urlencode($word);

// Google画像検索 入力単語+gif
$url = 'https://www.google.com/search?tbm=isch&q='.$word.'+gif'; 


// リクエストなど指定
$context = stream_context_create(array(
  'http' => array(
    'method' => 'GET',
    'header' => 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36',
    'ignore_errors' => true // SSL化した時に必要
  ),
  'ssl' =>array( // SSL化した時に必要
    'verify_peer'=>FALSE,
    'verify_peer_name'=>FALSE,
  ),
));



// データ取得
$html = file_get_contents($url, false, $context); // htmlを取得 

// なぜ20枚まで？
// echo $html;
// exit;

foreach( phpQuery::newDocument($html)->find('#rso .rg_meta.notranslate') as $image ){

  // $imageはxmlデータ
  $contents = $image->textContent; // xmlからは「->」で指定
  // $contentsはjsonデータ
  $data = json_decode($contents, true); // 連想配列として扱う

  $gifurl = $data['ou']; // GIF画像のURL

  echo '<img src="'.$gifurl.'">'; // 画像表示

}

?>

