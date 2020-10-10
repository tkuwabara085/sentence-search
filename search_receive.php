<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!isset($_POST['grade'])){
        $grade=0;
    }else{
        $grade = $_POST['grade'];
    }
    if(!isset($_POST['sections'])){
        $sections=array();
        $sections[]=0;
    }else{
        $sections = $_POST['sections'];
    }
    $grade = (int)$grade;
    $numChecked=count($sections);
    $search=implode(",",$sections);
    $search=$grade.",".$search;
    $search=explode(",",$search);
    $sqlIn=substr(str_repeat(',?',$numChecked),1);
}
$dsn = 'mysql:dbname=test;host=localhost;charset=utf8';
$user = 'root';
$password = '';

$data = [];

try{
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT english, japanese FROM example_sentence WHERE grade = ?  AND section IN ($sqlIn)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($search);
    $count = $stmt->rowCount();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

}catch (PDOException $e){
    echo($e->getMessage());
    die();
}
$dataNum=count($data);//データの件数を取得
$qinj=array();
$qine=array();
$ainj=array();
$aine=array();
for($i=0; $i<=$dataNum-1; $i++) {
    $array[] = $i;
    }//0からデータの件数－1まで順番に数字を並べた配列
    if($dataNum>=10){
        //ここからは英和5個ずつ取り出すアルゴリズム
        $indexArray=array_rand($array,10);
        for($i=0;$i<=4;$i++){
            $target=$indexArray[$i];
            $qinj[]=$data[$target]['japanese'];
            $ainj[]=$data[$target]['english'];
        }
        for($i=5;$i<=9;$i++){
            $target=$indexArray[$i];
            $qine[]=$data[$target]['english'];
            $aine[]=$data[$target]['japanese'];
        }
    }else{
        //ここには全部のうち英和半分ずつ取り出すアルゴリズム
        $ja=ceil($dataNum/2);//奇数なら和文英訳が一つ多くなるように
        $en=$dataNum-$ja;//この2行で英文和訳と和文英訳それぞれの問題数を決定
        for($i=0;$i<=$ja-1;$i++){
            $target=$array[$i];
            $qinj[]=$data[$target]['japanese'];
            $ainj[]=$data[$target]['english'];
        }
        for($i=$ja;$i<=$dataNum-1;$i++){
            $target=$array[$i];
            $qine[]=$data[$target]['english'];
            $aine[]=$data[$target]['japanese'];
        }
    }
?>

<html>
<html lang=ja>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">  
<title>英語例文検索システム</title>
<link rel="stylesheet" href="common.css">
</head>
<body>
    <div id="wrap">
    <h1>英語例文検索システム</h1>
    <p><?php if(count($data)===0){echo '検索に失敗しました。もう一度入力内容を確認して検索して下さい。';}?></p>
<h2>ランダム出題</h2>
<p>日本語、英語合わせて10個の文が表示されます。英語は日本語に、日本語は英語に直して下さい。</p>
<p>ただし、選んだ章の例文が10個に満たない場合はすべての文について英語・日本語のいずれかが表示されます。</p>
<div class="box">
    <h3>問題</h3>
    
<?php
    if($dataNum>=10){
        for($i=0;$i<=4;$i++){
            echo '<div class="card"><p>'.$qinj[$i].'</p><p v-show="show" class="answer">'.$ainj[$i].'</p></div>';
            echo '<div class="card"><p>'.$qine[$i].'</p><p v-show="show" class="answer">'.$aine[$i].'</p></div>';
        }
    }else{
        //ここには全部のうち英和半分ずつ取り出すアルゴリズム
        for($i=0;$i<=$ja-1;$i++){
            echo '<div class="card"><p>'.$qinj[$i].'</p><p v-show="show" class="answer">'.$ainj[$i].'</p></div>';
        }
        for($i=0;$i<=$en-1;$i++){
            echo '<div class="card"><p>'.$qine[$i].'</p><p v-show="show" class="answer">'.$aine[$i].'</p></div>';
        }
    }
?>
<button @click="show=!show">答えを見る/隠す</button>
</div>
<form action="output.php" method="POST">
    <?php
    foreach($qinj as $questionj){?>
<input type="hidden" name="qinj[]" value="<?php echo $questionj; ?>">
    <?php
    }
    foreach($ainj as $answerj){?>
<input type="hidden" name="ainj[]" value="<?php echo $answerj; ?>">
    <?php
    }
    foreach($qine as $questione){?>
<input type="hidden" name="qine[]" value="<?php echo $questione; ?>">
    <?php
    }
    foreach($aine as $answere){?>
<input type="hidden" name="aine[]" value="<?php echo $answere; ?>">
<?php
}
?>
<input type="submit" value="PDF出力" class="submit">
</form>
<h2>例文一覧</h2>
<button @click="showall=!showall">例文一覧を見る/隠す</button>
<div v-show="showall">
    <?php 
    foreach($data as $row): 
        ?>
    <div class="ex-card">
    <p class="english"><?php echo $row['english'];?></p>
    <p class="japanese"><?php echo $row['japanese'];?></p>
    </div>
    <?php endforeach; ?>

</table>
    </div>
    <h2>検索</h2>
        <p>英語例文を検索できます。</p>
        <form action="" method="POST">
            <h3>学年</h3>
            <p>一つ選んで下さい</p>
            <ul class="flex-container">
                <li><label>中1<input type="radio" name="grade" value=1 v-model="grade"></label></li>
                <li><label>中2<input type="radio" name="grade" value=2 v-model="grade"></label></li>
                <li><label>中3<input type="radio" name="grade" value=3 v-model="grade"></label></li>
            </ul>
            <h3>章</h3>
            <p>複数選択可</p>
            <div v-show="grade>0">
                <label><input type="checkbox" id="all" name="all" v-model="selectAll">全選択</label>
                <ul class="flex-container" id="lists">
                    <li v-for="(item,index) in sectionlist" :key="item" v-show="grade>0"><label>{{item}}<input type="checkbox" name="sections[]" :value="index+1" class="list" v-model="sections" :disabled="correspond1"></label></li>
                    <li v-for="(item,index) in sectionadd1" :key="item" v-show="grade>=2"><label>{{item}}<input type="checkbox" name="sections[]" :value="index+18" class="list" v-model="sections" :disabled="correspond2"></label></li>
                    <li v-for="(item,index) in sectionadd2" :key="item" v-show="grade>=3"><label>{{item}}<input type="checkbox" name="sections[]" :value="index+20" class="list" v-model="sections" :disabled="correspond3"></label></li>
                    <li v-for="(item,index) in sectioncom" :key="item" v-show="grade==2"><label>補講<input type="checkbox" name="sections[]" :value="index" class="list" v-model="sections" :disabled="correspond4"></label></li>
                </ul>   
            </div>
            <div v-show="grade==0">
                <p>始めに学年を選択して下さい。</p>
            </div>
            <input type="submit" value="検索する" class="submit" :disabled="unchecked">
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="common.js"></script>
</body>
</html>
