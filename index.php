<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>mywords</title>
</head>
<body>
    <?php
    var_dump($_REQUEST);
    $id_father=0;
    $text="";
    $translate="";


    $db = new SQLite3('mydb.db');

    if (isset($_REQUEST['delwordid'])) {
        $id=$_REQUEST['delwordid'];
        $sql="delete from tbl_word where id=$id";
        $db->exec($sql);
    }

    if (isset($_REQUEST['save_new'])) {

        $id_father=(isset($_REQUEST['id_father']))?$_REQUEST['id_father']:0;
        $id_language=$_REQUEST['id_language'];
        $text=$_REQUEST['text'];
        $translate=$_REQUEST['translate'];
        
        $row = $db->query("select count(*) as cc from tbl_word where text='$text'")->fetchArray();
        if ($row['cc']==0){//Here I check that we do not have duplicate data
            $sql="insert into tbl_word (id_language,id_father,text,translate,timecreate) values ($id_language,$id_father,'$text','$translate',".time().")";        
            echo $sql;
            $res=$db->exec($sql);
        }
        // var_dump($res);
    }
    if (isset($_REQUEST['editwordid']) || $id_father!=0) {
        $id=isset($_REQUEST['editwordid'])?$_REQUEST['editwordid']:$id_father;
        $sql="select * from tbl_word where id=$id";
        echo $sql;
        $results = $db->query($sql);
        $row = $results->fetchArray();
        // var_dump($row);
        $id_language=$row['id_language'];
        $text=$row['text'];
        $translate=$row['translate'];

    }    

    ?>
    <form action="?" method="post">
        <select name="id_language" id="">
            <?php
        $results = $db->query('SELECT * FROM tbl_language');
        while ($row = $results->fetchArray()) {
            ?>
            <option value="<?php echo($row['id']);?>"><?php echo($row['language_name']);?></option>
            <?php
        }
        ?>
        </select><a href="?">Reset</a><br>
        <textarea name="text" id="text" cols="60" rows="10" onselect="fn_selectTextAria();"><?php echo $text;?></textarea>
        <textarea name="translate" id="translate" cols="60" rows="10" ><?php echo $translate;?></textarea>
        <br>
        <input type="submit" value="save new" name="save_new" class="form_btn">
    </form>
    <form action="?" method="post">
        <br>
        <input type="hidden" name="id_father" value="<?php echo $id;?>">
        <input type="hidden" name="id_language" value="<?php echo $id_language;?>">
        <input type="text" name="text" id="small_text" placeholder="text" class="bigtext">
        <input type="text" name="translate" id="translate_text" placeholder="translate" class="bigtext">
        <input type="submit" value="add" name="save_new" class="form_btn">
    </form>


<script>
    function fn_selectTextAria(){
        // tmp=document.getElementById('text').ariaSelected.match();
        document.getElementById('small_text').value=window.getSelection();

        alert(tmp);
    }
</script>

    <table border=1>
        <tr>
            <th>id</th>
            <th>id_language</th>
            <th>id_father</th>
            <th>text</th>
            <th>translate</th>
            <th>time_create</th>
            <th>OP</th>
        </tr>
        <?php
        $results = $db->query('SELECT * FROM tbl_word');
        while ($row = $results->fetchArray()) {
            ?>
            <tr>
                <td><?php echo($row['id']);?></td>
                <td><?php echo($row['id_language']);?></td>
                <td><?php echo($row['id_father']);?></td>
                <td><?php echo($row['text']);?></td>
                <td><?php echo($row['translate']);?></td>
                <td><?php echo($row['timecreate']);?></td>
                <td>
                    <a  href="javascript:return();" onclick="if(!confirm('Delete? '))return;location.href='?delwordid=<?php echo($row['id']);?>'" class="del"><img src = "delete-2-svgrepo-com.svg" alt="delete" width="20px"/></a>
                    <a href="?editwordid=<?php echo($row['id']);?>" class="edit"><img src = "edit-svgrepo-com.svg" alt="delete" width="20px"/></a>







                </td>
            </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>