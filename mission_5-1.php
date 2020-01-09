<?php

$dsn="データベース名";//まずはデータベースに接続する
$user="ユーザー名";
$password="パスワード";
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

	/*$sql="CREATE TABLE IF NOT EXISTS tblname"//テーブルを作成する
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "namae char(32),"
	. "komennto TEXT,"
        . "nitiji TEXT,"
	.");";
        // $sql->"alter table tblname add column pasua TEXT";
	$stmt = $pdo->query($sql);
	$sql ='SHOW CREATE TABLE tblname';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
                
	}*/

if(!empty($_POST["namae"])&&($_POST["komennto"])&&($_POST["pasua"])){//名前、コメント、パスワードが入力されていた時
  	$namae=$_POST["namae"];
  	$komennto=$_POST["komennto"];
  	$nitiji=date("Y/m/d H:i:s");
	$pasua=$_POST["pasua"];

	if(!empty($_POST["hennsyuu"])){//編集番号があるとき
	$sql = 'SELECT * FROM tblname';//読み込む
	$stmt=$pdo->query($sql);
	$results= $stmt -> fetchall();//fetchallで配列にする
		foreach ($results as $row){//$resultsをrowとして一つずつ取り出して調べる
			if($_POST["hennsyuu"]== $row['id'] and $_POST["pasua"]==$row['pasua']){//編集番号と投稿番号が一致した時
			$hennsyuu=$row['id']; //変更する投稿番号
			$sql = 'update tblname set namae=:namae,komennto=:komennto,nitiji=:nitiji where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':namae', $namae, PDO::PARAM_STR);
			$stmt->bindParam(':komennto', $komennto, PDO::PARAM_STR);
			$stmt->bindParam(':id', $hennsyuu, PDO::PARAM_INT);
        		$stmt->bindParam(':nitiji',$nitiji,PDO::PARAM_STR); 
			$stmt->execute();
			}
		}
        }else{//編集番号が無いとき
        $sql = $pdo -> prepare("INSERT INTO tblname (namae, komennto,nitiji,pasua) VALUES (:namae,:komennto,:nitiji,:pasua)");//中身を書き込む
	$sql -> bindParam(':namae', $namae, PDO::PARAM_STR);
	$sql -> bindParam(':komennto', $komennto, PDO::PARAM_STR);
        $sql -> bindParam(':pasua',$pasua,PDO::PARAM_STR); 
        $sql -> bindParam(':nitiji',$nitiji,PDO::PARAM_STR); 
        $sql -> execute();
        }
}

if(!empty ($_POST["sakujyo"])){//削除番号が書き込まれていた場合の処理(パスワード付き)
$sakujyo=$_POST["sakujyo"];
$sql = 'SELECT * FROM tblname';
//$sql -> bindParam(':pasub',$pasub,PDO::PARAM_STR); 
$stmt=$pdo->query($sql);
$results= $stmt -> fetchall();//fetchallで配列にする
	foreach ($results as $row){//$resultsをrowとして一つずつ取り出して調べる

		if($sakujyo==$row["id"] and $_POST["pasub"]==$row["pasua"]){//投稿番号が削除番号と一致していた場合
		$id = $sakujyo;//削除機能を用いる
		$sql = 'delete from tblname where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id', $sakujyo, PDO::PARAM_INT);
		$stmt->execute();

                }
	}
}
		
if(!empty($_POST["hennsyuubangou"])){//編集番号が入力されていた場合の処理(パスワード付き)
$pasua=$_POST["pasua"];
$pasuc=$_POST["pasuc"];
$hennsyuubangou=$_POST["hennsyuubangou"];
$namae=$_POST["namae"];
$komennto=$_POST["komennto"];
$sql = 'SELECT * FROM tblname';
$stmt=$pdo->query($sql);
$results= $stmt -> fetchall();//fetchallで配列にする
	foreach ($results as $row){//$resultsをrowとして一つずつ取り出して調べる		
		if($hennsyuubangou==$row["id"] and $_POST["pasuc"]==$row["pasua"] ){//編集番号と投稿番号が一致していればフォームに表示できるようにする
			$edit_name=$row["namae"];
			$edit_komennto=$row["komennto"];
			$hennsyuu=$row["id"];
		}
	}
}
		

?>

<html>
<head>
<title>入力フォーム</title>
<meta_charest="utf-8">
</head>
<body>
<form method="post", action=""> <!--フォームを作る-->
<p>名前：<input type="text" name="namae"value="<?php if(!empty($edit_name)){echo $edit_name;}?>"></p>
<p>コメント：<input type="text" name="komennto"value="<?php if(!empty($edit_komennto)){echo $edit_komennto;}?>"></p>
パスワード：<input type ="password" name="pasua">
<input type="submit"name="btn" value="送信">
<p>削除対象番号：<input type="text" name="sakujyo"></p>
パスワード：<input type="password" name="pasub">
<input type="submit"name="sakujyobtn"value="削除">
<p>編集対象番号:<input type="text" name="hennsyuubangou"></p>
パスワード：<input type="password" name="pasuc">
<input type="submit"name="hennsyuubtn"value="編集">
<input type="hidden" name="hennsyuu" value="<?php if(!empty($hennsyuubangou)){echo $hennsyuubangou;}?>" >
</form>
</body>
</html>

<?php

$sql = 'SELECT * FROM tblname';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row["namae"].',';
		echo $row["komennto"].',';
                echo $row["nitiji"]."<br>";
		echo "<hr>";
	}

?>