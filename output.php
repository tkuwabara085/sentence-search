<?php
//variables for test
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!isset($_POST['qinj'])){
        $qinj=array();
        $qinj[]=0;
    }else{
        $qinj = $_POST['qinj'];
    }
    if(!isset($_POST['qine'])){
        $qine=array();
        $qine[]=0;
    }else{
        $qine = $_POST['qine'];
    }
    if(!isset($_POST['ainj'])){
        $ainj=array();
        $ainj[]=0;
    }else{
        $ainj = $_POST['ainj'];
    }
    if(!isset($_POST['aine'])){
        $aine=array();
        $aine[]=0;
    }else{
        $aine = $_POST['aine'];
    }
}
// ライブラリの読み込み
require_once('TCPDF-main/tcpdf.php');
 
// TCPDFインスタンスを作成
$orientation = 'P'; // 用紙の向き。Lにすると横になる
$unit = 'mm'; // 単位
$format = 'A4'; // 用紙フォーマット
$unicode = true; // ドキュメントテキストがUnicodeの場合にTRUEとする
$encoding = 'UTF-8'; // 文字コード
$diskcache = false; // ディスクキャッシュを使うかどうか
$tcpdf = new TCPDF($orientation, $unit, $format, $unicode, $encoding, $diskcache);
 
$tcpdf->AddPage();
  
$tcpdf->SetFont("kozgopromedium", "", 10);
  
$html = <<< EOF
<style>
h1 {
    font-size: 20px; // 文字の大きさ
    text-align: center; // テキストを真ん中に寄せる
}
p {
    font-size: 12px; // 文字の大きさ
    color: #000000; // 文字の色
    text-align: left; // テキストを左に寄せる
    line-height:24px;
}
 
.tbl{
    border:solid 1px #ccc;
    width:100%;
}
.tbl th{
    border:solid 1px #ccc;
    padding:20px;
    text-align:center;
}
.tbl td{
    border:solid 1px #ccc;
    padding:20px;
    text-align:center;
    height:50px;
}
li{
    listy-style:none;
}
.num{
    width:30px;
}
h3{
    border-bottom:solid 1px #ccc;
}
h2{
    font-size:12px;
}
</style>
<div class="wrapper">
<h1>英語例文問題</h1>
    <p>英文は和訳し、和文は英訳しなさい。</p>
<table class="tbl">
    <tr><th>問題</th><th>解答</th></tr>
EOF;
foreach($qinj as $item){
    $html.="<tr><td>$item</td><td></td></tr>";
}
foreach($qine as $item){
    $html.="<tr><td>$item</td><td></td></tr>";
}
$html.= <<< EOF
</table>
</div>
EOF;
 
$tcpdf->writeHTML($html);
// ファイルを出力
$fileName = 'sample.pdf';
$pdfData = $tcpdf->Output(rawurlencode($fileName), 'S');
 
// ブラウザにそのまま表示
header('Content-Type: application/pdf');
header("Content-Disposition: inline; filename*=UTF-8''".rawurlencode($fileName));
echo $pdfData;
 
?>