
<html>
 <html lang = "ja">
<head>
  <meta charset = "utf-8">
</head>

<body>

<?php
	//入力前の初期値設定
	$edit_name="名前";
	$edit_comment="コメント";

	//入力フォームのデータを受け取る
	$name= ($_POST['name']);
	$comment = ($_POST['comment']);
	$jikan=date('Y年m月d日 H:i:s');
	$kariedit= ($_POST['kariedit']);
	$delete= ($_POST['delete']);
	$edit=($_POST['edit']);
	$pass = ($_POST['pass']);
	$delete_passward= ($_POST['delete_passward']);
	$edit_passward = ($_POST['edit_passward']);

	
	//データベースに接続する
	$dsn='データベース名';
	 $user='ユーザー名';
	 $passward='パスワード';
	 $pdo=new PDO($dsn, $user, $passward, array(PDO::ATTR_ERRMODE =>
	 PDO::ERRMODE_WARNING));

	//テーブルを作成する
	 $sql="CREATE TABLE IF NOT EXISTS site(id INT PRIMARY KEY AUTO_INCREMENT,name char(32),comment TEXT,jikan TEXT,pass TEXT);";
	 $stmt=$pdo->query($sql);

	//新規投稿
	if (!empty($name) && !empty($comment)  && !empty($pass) && empty($kariedit)){
	 $sql=$pdo -> prepare("INSERT INTO site(name, comment,jikan,pass) VALUES(:name,:comment,:jikan,:pass)");
	 $sql -> bindParam(':name',$name,PDO::PARAM_STR);
	 $sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
	 $sql -> bindParam(':jikan',$jikan,PDO::PARAM_STR);
	 $sql -> bindParam(':pass',$pass,PDO::PARAM_STR);

	 $name= ($_POST['name']);
	 $comment = ($_POST['comment']);
	 $jikan=date('Y年m月d日 H:i:s');
	 $pass = ($_POST['pass']);
	 $sql -> execute();
	}

	//削除機能
	if(!empty($delete)){
	
	 $sql = 'SELECT pass from site WHERE id = :id';
	 $site_pass= $pdo->prepare($sql);
	 $site_pass->bindParam(':id',$delete,PDO::PARAM_INT);
	 $site_pass->execute();
	 $sitepass = $site_pass->fetch();//データベースに保存されている特定の投稿のパスワード取得
	  
	  if($delete_passward == $sitepass['pass']){//削除フォームに入力されたパスワードと一致した場合
	   $sql = 'delete from site where id=:id';
	   $stmt= $pdo->prepare($sql);
	   $stmt->bindParam(':id',$delete,PDO::PARAM_INT);
	   $stmt->execute();}

	  else{
	   $error="パスワードが違います！";}

	}//削除機能おわり


	//編集機能
	if (!empty($edit)){
	 $sql = 'SELECT * from site WHERE id = :id';
	 $site_pass= $pdo->prepare($sql);
	 $site_pass->bindParam(':id',$edit,PDO::PARAM_INT);
	 $site_pass->execute();
	 $sitepass = $site_pass->fetch();//データベースに保存されている特定の投稿のパスワード取得
	 
	  
	  if($edit_passward == $sitepass['pass']){//編集フォームに入力されたパスワードと一致した場合
	   //入力フォームに表示する
	   $edit_name=$sitepass['name'];
	   $edit_comment=$sitepass['comment'];
	   $edit_kariedit= $sitepass['id'];
	  }
	  else{
	    $error="パスワードが違います！";}
	}
	
	   //編集する
	    if(!empty($kariedit)){
	     
	     $name= ($_POST['name']);
	     $comment = ($_POST['comment']);
	     $jikan=date('Y年m月d日 H:i:s');
	     $pass = ($_POST['pass']);

	    
	     $sql='update site set name=:name,comment=:comment,jikan=:jikan,pass=:pass where id=:id';
	     $stmt=$pdo->prepare($sql);
	     $stmt->bindParam(':name',$name,PDO::PARAM_STR);
	     $stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
	     $stmt->bindParam(':jikan',$jikan,PDO::PARAM_STR);
	     $stmt->bindParam(':pass',$pass,PDO::PARAM_STR);
	     $stmt->bindParam(':id',$kariedit,PDO::PARAM_INT);
	   
	      $stmt->execute();
	     }//編集機能おわり
            

	echo $error ;//エラーがある場合表示する
?>

 
<!--名前・コメント・パスワードの入力フォームを設置-->
 <form method= "post" action="mission_4.php">
	<input type="text" name="name" value= "<?php echo $edit_name ;?>"><br>
 	<input type="text" name="comment" value="<?php echo $edit_comment;?>"><br>
	<input type="passward" name="pass" value ="パスワード" >
	<input type="submit"><br>
	<input type="hidden" name="kariedit" value= "<?php echo $edit_kariedit ;?>"><br>
 </form>

<!--削除用の入力フォームを設置-->
<form method= "post" action="mission_4.php">
	<input type="text" name="delete" value="削除対象番号"><br>
	<input type="passward" name="delete_passward" value ="パスワード" >
	<input type="submit" value="削除"><br>
</form>

<!--編集用の入力フォームを設置-->
<form method= "post" action="mission_4.php">
	<input type="text" name="edit" value="編集対象番号"><br>
	<input type="passward" name="edit_passward" value ="パスワード" >
	<input type="submit" value="編集"><br>
</form>

<?php
	//データを表示する
	 $sql = 'SELECT * FROM site';
	 $stmt = $pdo -> query($sql);
	 $results = $stmt->fetchAll();
	 foreach($results as $row){
	  echo $row['id'].',';
	  echo $row['name'].',';
	  echo $row['comment'].',';
	  echo $row['jikan'].'<br>';
	 }

?>

</body>
</html>


	
