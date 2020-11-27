<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission</title>
</head>
<body>
    <?php
        #投稿フォーム
        if(isset($_POST["submit_post"])==true)
        {
          $name_post = $_POST["name_post"];
          $str_post=$_POST["str_post"];
          $pass_post=$_POST["pass_post"];
          #名前チェック
          if($name_post=="")
          {
              echo "Name is empty";
          }
          else
          {
              #コメントチェック
              if($str_post=="")
              {
                  echo "Comment is empty";
              }
              else
              {
                  #パスチェック
                  if($pass_post=="")
                  {
                      echo "Pass is empty";
                  }
                  else
                  {
                      $filename_post="mission3-5.txt";
                      $fp_post=fopen($filename_post,"a");
                      $count_post = count( file( $filename_post ) )+1;
                      $date_post= date("Y年m月d日 H時i分s秒");
                      $str_post =$count_post."<>".$name_post."<>".$str_post."<>".$date_post."<>".$pass_post.PHP_EOL;
                      fwrite($fp_post,$str_post);
                        
                  }
              }
                 
          }
            
        }

        #削除フォーム
        if(isset($_POST["submit_del"])==true)
        {
          $num_del=$_POST["num_del"];
          $pass_del=$_POST["pass_del"];
          $filename_del="mission3-5.txt";
          if($num_del=="")
          {
            echo "Number is empty.";
          }
          else
          {
              if($pass_del=="")
              {
                echo "Pass is empty.";
              }
              else
              {
                $filename_del="mission3-5.txt";
                $items_del=file($filename_del,FILE_IGNORE_NEW_LINES);
                $flag_del=0;
                #削除
                foreach($items_del as &$item_del)
                {
                  $prints_del=explode("<>",$item_del);
                  #削除した後はインデックスをずらす．
                  if($num_del==$prints_del[0])
                  {
                    if(strcmp($pass_del,$prints_del[4])==0)
                    {
                      unset($items_del[$num_del-1]);
                      $flag_del=1;
                    }
                    else
                    {
                      echo "Invarid password.";
                      break;
                    }
                  }
                  else
                  {
                      if($flag_del==1)
                      {
                          $prints_del[0]=$prints_del[0]-1;
                          $item_del=implode("<>",$prints_del);
                      }

                  }
              }
              foreach($items_del as &$item_del)
              {
                  $item_del=$item_del.PHP_EOL;
              }
              $items_del=array_values($items_del);
              file_put_contents("mission3-5.txt",$items_del);
            }
          }
        }
        
        #編集フォーム
        if(isset($_POST["edit"])) 
        {
          if($_POST["number_ed"]=="")
          {
              echo "Invalid Number";
          }
          else
          {
              if($_POST["pass_ed2"]=="")
              {
                echo "Invalid Pass.";
              }
              else
              {
                $filename_ed = "mission3-5.txt";
                $items_ed = file($filename_ed,FILE_IGNORE_NEW_LINES);
                // 編集用データ格納変数
                $editNumber = '';
                $editName = '';
                $editComment = '';          
                // ここは編集番号よりデータを求める所
                // データ件数分処理
                foreach($items_ed as $item_ed) 
                {
                  // <>で分割して配列に
                  $bbsrowData = explode("<>", $item_ed);
                  if($bbsrowData[0] == $_POST["number_ed"]) 
                  {
                    if(strcmp($bbsrowData[4],$_POST["pass_ed2"])==0)
                    {
                      $editNumber = $bbsrowData[0];
                      $editName = $bbsrowData[1];
                      $editComment = $bbsrowData[2];
                      $editPass = $bbsrowData[4];
                      echo $editPass;
                      // 即抜ける
                      break;
                    }
                  }
                }
              } 
          }
        }
        
        if(isset($_POST["normal"])) 
        {
          // 書き込みか上書きかをするところ

          // 書き込むデータを作る
          $filename_ed = "mission3-5.txt";
          $items_ed = file($filename_ed);
          $date_ed=date("Y年m月d日 H時i分s秒");
          $writeData = $_POST['post_ed'] . "<>" . $_POST['name_ed'] . "<>" . $_POST['comment']."<>".$date_ed."<>".$_POST['pass_ed'].PHP_EOL;
          // 編集番号があればデータループして場所を特定して上書きする
          if(isset($_POST["post_ed"])) 
          {
            // データ件数分処理(&で参照にしてる)
            foreach($items_ed as &$item_ed) 
            {
              // <>で分割して配列に
              $bbsrowData = explode("<>", $item_ed);
              // 編集番号のところだったら上書き
              if($bbsrowData[0] == $_POST["post_ed"]) 
              {
                $item_ed = $writeData;
              }
            }
          }

          // ファイルに書き込む(implodeで配列を改行付き文字列へ)
          file_put_contents("mission3-5.txt", $items_ed);
        }
    ?>
    
[投稿フォーム]
  <div>名前:</div>
  <form action="" method="post">
  <div><input type="text" name="name_post"placeholder="名前"></div>
  <div>コメント:</div>
  <div><input type="text" name="str_post"placeholder="コメント"></div>
  <div>パスワード:</div>
  <div><input type="text" name="pass_post" placeholder="パスワード"></div>
  <div><input type="submit" name="submit_post"></div>
  <br>
[削除フォーム]
  <div>投稿削除ナンバー:</div>
  <div><input type="number_ed" name="num_del"placeholder="削除番号"></div>
  <div>パスワード:</div>
  <div><input type="text" name="pass_del" placeholder="パスワード"></div>
  <div><input type="submit" name="submit_del"></div>
  <br>
  </form>
[編集フォーム]
  <form action="" method="POST">
  <input type="hidden" name="post_ed" value="<?php echo $editNumber; ?>">
  <input type="hidden" name="pass_ed" value="<?php echo $editPass; ?>">
  <input type="text" name="name_ed" value="<?php echo $editName; ?>">
  <br />
  <textarea name="comment" rows="4" cols="40"><?php echo $editComment; ?></textarea>
  <br />
  <input type="submit" name="normal" value="送信">
  </form>
  <form action="" method="POST">
  編集したい番号を入力
  <input type="text" name="number_ed" value="">
  <input type="text" name="pass_ed2" value="">
  <input type="submit" name="edit" value="送信">
  </form>
  <hr>

  <?php
    $items_print=file("mission3-5.txt");
    foreach($items_print as $item_print)
    {
      $str_print=explode("<>",$item_print);
      for($j=0;$j<count($str_print)-1;$j++)
      {
        echo $str_print[$j]." ";
      }
      echo "<br>";
    }
  ?>
</body>
<html>