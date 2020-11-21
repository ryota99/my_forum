<?php
//データベースに接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//データベースに接続

//テーブルが存在しなければ作成する。
$sql = "CREATE TABLE IF NOT EXISTS tbtest"
." (". "id INT AUTO_INCREMENT PRIMARY KEY,". "name char(32),". "comment TEXT,". "time char(32)". ");";
//変数sqlにSQL文を代入
$stmt = $pdo->query($sql);
/*
query:SQL文をデータベースに届けるような役割
pdoには接続しているデータベースの情報が格納されている。
*/
//テーブルが存在しなければ作成する。

//テーブルに情報を追加
if(isset($_POST["submit"]) && $_POST["editID"]==null && $_POST["password"]=="123"){
    if(empty($_POST["editID2"])){//新規送信
        $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, time) VALUES (:name, :comment, :time)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':time', $time, PDO::PARAM_STR);
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $time = date("Y年m月d日 H時i分s秒");
        if($name!=null && $comment!=null){
            $sql -> execute();
        }else{
            echo "入力内容が空です";
        }
    }else{//編集
        $id = $_POST["editID2"];
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $sql = 'UPDATE tbtest SET name=:name,comment=:comment WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if($name!=null && $comment!=null){
            $stmt -> execute();
        }else{
            echo "入力内容が空です";
        }
    }
}

//テーブルに情報を追加

//テーブル内の情報を削除
else if(isset($_POST["delete"]) && $_POST["password"]=="123"){
    $id = $_POST["deleteID"];
    $sql = 'delete from tbtest where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if($id!=null){
        $stmt -> execute();
    }else{
        echo "入力内容が空です";
    }
}
//テーブル内の情報を削除

//テーブル内の情報を見えない欄に入れる用
else if(isset($_POST["edit"]) && $_POST["password"]=="123"){
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        if($row['id'] == $_POST["editID"]){
            $editname = $row['name'];
            $editcomment = $row['comment'];
        }
    }
    echo $_POST["editID"]."番目のコメントを編集中です。";
}
//テーブル内の情報を見えない欄に入れる用

//テーブルを削除
if(isset($_POST["reset"]) && $_POST["password"]=="123"){
    $sql = 'DROP TABLE tbtest';
    $stmt = $pdo->query($sql);
}
//テーブルを削除

//テーブル内の情報を表示
echo "<hr>";
$sql = 'SELECT * FROM tbtest';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['time'].'<br>';
    echo "<hr>";
}
//テーブル内の情報を表示
?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>my_forum</title>
</head>
<body>
    <form action="" method="post">
        <input type="hidden" name="editID2" value="<?php echo $_POST["editID"];?>" placeholder="後で隠す">
        名前
        <input type="text" name="name" value="<?php echo $editname;?>" placeholder="名前"><br>
        コメント
        <input type="text" name="comment" value="<?php echo $editcomment;?>" placeholder="コメント">
        <input type="submit" name="submit" value="送信"><br><br>
        削除番号
        <input type="text" name="deleteID" placeholder="削除する番号">
        <input type="submit" name="delete" value="削除"><br><br>
        編集番号
        <input type="text" name="editID" placeholder="編集する番号">
        <input type="submit" name="edit" value="編集"><br><br>
        パスワード
        <input type="text" name="password" placeholder="パスワード"><br><br>
        リセット用ボタン
        <input type="submit" name="reset" value="リセット"><br><br>
    </form>
</body>