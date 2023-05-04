<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="css_for_messanger.css" rel="stylesheet" type="text/css">
    <title>Messenger</title>
</head>
<body>
<div class="wrapper">
    <h1>Гостевая книга</h1>
    <?php
    ///BD CONNECTION
    $user_name  = 'root';
    $localhost  = 'localhost';
    $db_name    = 'db';
    $conn_to_db = new PDO(
        "mysql:host=localhost;dbname=db",
        $user_name,
        '',
        [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false #dont allow duplicate VALUES(:data,:data) => error
        ]
    );
    /////
    /// START
    if (!empty($_POST['name']) and !empty($_POST['message'])) {
        /////// insert data INTO DB
        $name    = $_POST['name'];
        $message = $_POST['message'];

        $sql = "INSERT INTO messanger (id, messages, user_names, time) 
                           VALUES (null,'$message','$name',now() )";

        $conn_to_db->query($sql);
        ///////// page system
        $page         = $_GET['page'] ?? 1;
        ////how many messages show in page
        $elem_in_page = 3;
        ///range for LIMIT IN sql
        $from         = ($page - 1) * $elem_in_page;

        ////how many elems in db
        $sql          = "SELECT count(*) as count FROM messanger";
        $num_of_elems = $conn_to_db->query($sql)->fetch();
        $count        = $num_of_elems['count'];

        ////selecting elems
        $sql  = "SELECT messages,user_names,time  FROM messanger LIMIT $from,$elem_in_page";
        $data = $conn_to_db->query($sql)->fetchAll();
        ////making links to pages (nothing unusual)
        $pages = '';
        $prev  = $page - 1;
        if ($prev < 1) {/////i create this if() to prevent unexpected issues
            $prev = ceil($count / $elem_in_page);
        }
        $pages   .= "<a href='?page=$prev'><<</a>";
        for ($i = 1; $i <= ceil($count / $elem_in_page); $i++) {
            if ($i == $page) {
                $pages .= "<a href='?page=$i' class='active'>$i</a>";
            } else {
                $pages .= "<a href='?page=$i'>$i</a>";
            }
        }
        $next = $page + 1;
        if ($next > ceil($count / $elem_in_page)) {////the same story as with prev
            $next = 1;
        }
        $pages .= "<a href='?page=$next'>>></a>";
        echo $pages;
        ///////////message system(little successful-reg_html)
        $ht_tag = '';
        foreach ($data as $value) {
            $ht_tag .= "<p><strong>$value[time]</strong>";
            $ht_tag .= " $value[user_names]</p>";
            $ht_tag .= "<p style='font-size: large'>$value[messages]</p>";
        }
        echo $ht_tag;

        ?>
        <div class="border-true">
            <h2>Запись успешно сохранена!</h2>
        </div><br>

        <?php
    }
    else {

        //////page system
        $page         = $_GET['page'] ?? 1;
        $elem_in_page = 3;
        $from         = ($page - 1) * $elem_in_page;
        $sql          = "SELECT count(*) as count FROM messanger";
        $num_of_elems = $conn_to_db->query($sql)->fetch();
        $count        = $num_of_elems['count'];
        $sql          = "SELECT messages,user_names,time  FROM messanger LIMIT $from,$elem_in_page";
        $data         = $conn_to_db->query($sql)->fetchAll();
        $pages        = '';
        $prev         = $page - 1;
        if ($prev < 1) {
            $prev = ceil($count / $elem_in_page);
        }
        $pages .= "<a href='?page=$prev'><<</a>";
        for ($i = 1; $i <= ceil($count / $elem_in_page); $i++) {
            if ($i == $page) {
                $pages .= "<a href='?page=$i' class='active'>$i</a>";
            } else {
                $pages .= "<a href='?page=$i'>$i</a>";
            }
        }
        $next = $page + 1;
        if ($next > ceil($count / $elem_in_page)) {
            $next = 1;
        }
        $pages .= "<a href='?page=$next'>>></a>";
        echo $pages;
        ////message system
        $ht_tag = '';
        foreach ($data as $value) {
            $ht_tag .= "<p><strong>$value[time]</strong>";
            $ht_tag .= " $value[user_names]</p>";
            $ht_tag .= "<p style='font-size: large'>$value[messages]</p>";
        }
        echo $ht_tag;
      ?>
    <div class="border-false">
        <h2>Запись не сохранена!(форма пуста)</h2>
    </div>
    <br>
    <?php
    }
    ?>

    <form method="post">
        <label>
            <input
                    name="name" type="text"
                    placeholder="your name"
                    class="input"
            >
        </label><br>
        <label>
            <input
                    class="textarea"
                    name="message"
                    type="text"
                    placeholder="message"
            ><br>
        </label>
        <input type="submit" value="save" class="button">
    </form><br>
    <a href="#">go-top</a>
    <a href="../WEB/MY_SITE.php">get-back-to-site</a>
</div>
</body>
</html>