<?php
$dsn = 'mysql:host=localhost;dbname=******;charsetr=utf8';
$user = '******';
$password = '******';
$yLossImg = 'https://nkrw.net/黄色ロスオブコンタクト.png';
$yBentImg = 'https://nkrw.net/黄色ベントニー.png';
$rLossImg = 'https://nkrw.net/赤ロスオブコンタクト.png';
$rBentImg = 'https://nkrw.net/赤ベントニー.png';

$mode = htmlspecialchars(filter_input(INPUT_POST,'mode'));
$tNum = htmlspecialchars(filter_input(INPUT_POST,'id'));
$reload = htmlspecialchars(filter_input(INPUT_POST,'reload'));
if($reload == "リロード"){
    $_POST['pF'] = "";
}
// 黄色パドルもしくは赤色パドルを入力する際にデータベースを更新
if($mode === 'update'){
        try {
            $pdo = new PDO($dsn, $user, $password);
            ini_set('date.timezone', 'Asia/Tokyo');
            $time = date("H:i");
            if($_POST['pF'] == 1){
                $sql = "UPDATE judge5 SET YellowBent = NULL WHERE id = :tNum;";  
                $stmh = $pdo->prepare($sql);
                $stmh->bindValue(':tNum',$tNum,PDO::PARAM_STR);
                $stmh->execute();    
            }else if($_POST['pF'] == 2){
                $sql = "UPDATE judge5 SET YellowLoss = NULL WHERE id = :tNum;";
                $stmh = $pdo->prepare($sql);
                $stmh->bindValue(':tNum',$tNum,PDO::PARAM_STR);
                $stmh->execute();    
            }else if($_POST['pF'] == 3){
                $sql = "UPDATE judge5 SET RedCard = NULL, RedTime = NULL WHERE id = :tNum;";
                $stmh = $pdo->prepare($sql);
                $stmh->bindValue(':tNum',$tNum,PDO::PARAM_STR);
                $stmh->execute();    
            }else if($_POST['pF'] == 4){
                $sql = "UPDATE judge5 SET RedCard = NULL, RedTime = NULL WHERE id = :tNum;";
                $stmh = $pdo->prepare($sql);
                $stmh->bindValue(':tNum',$tNum,PDO::PARAM_STR);
                $stmh->execute();    
            }
            $pdo = null;
        } catch (PDOException $e) {
            echo 'データベースにアクセスできません!' . $e->getMessage();
            exit;
        }
    }

// 1.データベースをテーブルに反映→２に続く
try {
    $pdo = new PDO($dsn, $user, $password);
    $table_data = array();
    $sql = "SELECT * FROM judge5 ORDER BY ord;";
    $stmh = $pdo->prepare($sql);
    $stmh->execute();
} catch (PDOException $e) {
    echo 'データベースにアクセスできません!' . $e->getMessage();
    exit;
}
    
?>

<div class="wrap">
    <link rel="stylesheet" href="dstyle.css">
<h1>審判No.5</h1>
<h2>修正モード中</h2>
<div class="menu">
<input type="number"  id="inputNum" name="inputNum" placeholder="No.を入力">
<input type="submit"  id="submitNum" value="パドル入力画面へ" onclick="inputNumber_click()">
<form method="POST">
<input type="submit"  id="reloadBtn" name="reload" value="リロード"/>
</form>
<form action="group5.php">
<button id="judgeMode">ジャッジモードに戻る</button>
</form>
</div>
    <body>
        <div id="easyModal" class="modal">
            <div class="modal-header">
                <div id="title"></div>
                <span id="modalClose">&times;</span>
            </div>
            <div class="modal-content">
                <div class="modal-body">
                    <form  method="post">
                        <input type="image" src=<?php echo $yBentImg ?> alt="黄色ベントニー" class="paddle" id="yellowBent" name="YB">
                        <input type="image" src=<?php echo $yLossImg ?> alt="黄色ロスオブコンタクト" class="paddle" id="yellowLoss" name="YL">
                        <input type="image" src=<?php echo $rBentImg ?> alt="赤色ベントニー" class="paddle" id="redBent" name="RB">
                        <input type="image" src=<?php echo $rLossImg ?> alt="赤色ロスオブコンタクト" class="paddle" id="redLoss" name="RL">
                        <input type="hidden" id="targetNum" name="id" value="1">
                        <input type="hidden" name="mode" value="update">
                        <input type="hidden" id="paddleFlag" name="pF" value="">
                    </form>
                    <button id="no" onclick="nofunc()">キャンセル</button>
                </div>
            </div>
        </div>

        <table class="csv_list" id="csv_table">
            <thead class="table_head">
            <th class="info" id="ord">ORD.</th>
                <th class="info" id="no">NO.</th>
                <th class="info" id="name">選手名</th>
                <th class="info" id="belongs">所属</th>
                <th id="yellow">></th>
                <th id="yellow">~</th>
                <th id="red">RC</th>
                <th id="red">Time</th>
            </thead>
            <tbody>
                <?php
                // 2.データベースをテーブルに反映させる
                $num = 0;
                while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                    $num++;
                ?>
                    <tr>
                        <td>
                        <div id=<?= htmlspecialchars($row['ord']) ?>></div><?= htmlspecialchars($row['ord']) ?>
                        </td>
                        <td><input type="submit" class="NoBtn" id=<?= htmlspecialchars($num) ?> value=<?= htmlspecialchars($row['id']) ?> onclick="number_click(<?= $row['id'] ?>)"></td>
                        <td class="textcenter"><?= htmlspecialchars($row['name']) ?></td>
                        <td class="textcenter"><?= htmlspecialchars($row['Belongs']) ?></td>
                        <td class="textcenter">
                            <div id=Bent<?= htmlspecialchars($num) ?>></div><?php echo substr($row['YellowBent'],0,5) ?>
                        </td>
                        <td class="textcenter">
                            <div id=Loss<?= htmlspecialchars($num) ?>></div><?php echo substr($row['YellowLoss'],0,5) ?>                   
                        </td>
                        <td class="textcenter">
                            <div id=Red<?= htmlspecialchars($num) ?>></div><?= htmlspecialchars($row['RedCard']) ?>
                        </td>
                        <td class="textcenter">
                            <div id=Time<?= htmlspecialchars($num) ?>></div><?php echo substr($row['RedTime'],0,5) ?>
                        </td>
                    </tr>
                <?php
                }
                // データベース接続解除
                $pdo = null;
                ?>
            </tbody>
        </table>
        <script type="text/javascript" src="judge.js"></script>
    </body>