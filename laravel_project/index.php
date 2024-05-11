<?php
require 'db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To-Do List</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="main-section">
        <div class="add-section">
            <form action="app/add.php" method="POST" autocomplete="off">
                <?php if (isset($_GET['mess']) && $_GET['mess'] == 'error') { ?>
                <input type="text" name="title" style="border-color: #ff6666" placeholder="This field is rquired" />
                <button type="submit">Add &nbsp; <span>&#43;</span></button>

                <?php } else { ?>
                <input type="text" name="title" placeholder="What do you need to do?" />
                <button type="submit">Add &nbsp; <span>&#43;</span></button>
                <?php } ?>
            </form>
        </div>
        <?php
        $todos = $conn->query("SELECT * FROM todos ORDER BY id DESC");
        ?>
        <div class="show-todo-section">
            <div>
                <img src="./img/f.png" width="100%" />
                <img src="./img/Ellipsis.gif" width="80px">
            </div>
            <div>
                <?php if ($todos->num_rows <= 0) { ?>
                <div class="todo-item">

                </div>

                <?php } ?>
            </div>
        </div>

        <?php

        $servername = "127.0.0.1:3306";
        $username = "root";
        $password = "";
        $dbname = "todolist";


        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $stmt = $pdo->query("SELECT * FROM todos ORDER BY id DESC");


            while ($todo = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="todo-item">
            <span id="<?php echo $todo['id']; ?>" class="remove-to-do">x</span>
            <?php if ($todo['checked']) { ?>
            <input type="checkbox" class="check-box" data-todo-id="<?php echo $todo['id']; ?>" checked />
            <h2 class="checked"><?php echo $todo['title'] ?></h2>
            <?php } else { ?>
            <input type="checkbox" data-todo-id="<?php echo $todo['id']; ?>" class="check-box" />
            <h2><?php echo $todo['title'] ?></h2>
            <?php } ?>
            <br>
            <small>created: <?php echo $todo['date_time'] ?></small>
        </div>
        <?php
            }
        } catch (PDOException $e) {
            die("La connexion a échoué : " . $e->getMessage());
        }
        ?>
    </div>
    </div>

    <script src="js/jquery-3.2.1.min.js"></script>

    <script>
    $(document).ready(function() {
        $('.remove-to-do').click(function() {
            const id = $(this).attr('id');

            $.post("app/remove.php", {
                    id: id
                },
                (data) => {
                    if (data) {
                        $(this).parent().hide(600);
                    }
                }
            );
        });

        $(".check-box").click(function(e) {
            const id = $(this).attr('data-todo-id');

            $.post('app/check.php', {
                    id: id
                },
                (data) => {
                    if (data != 'error') {
                        const h2 = $(this).next();
                        if (data === '1') {
                            h2.removeClass('checked');
                        } else {
                            h2.addClass('checked');
                        }
                    }
                }
            );
        });
    });
    </script>
</body>

</html>