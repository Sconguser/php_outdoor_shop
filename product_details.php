<?php
require_once "bootstrap_include.php";
require_once "connect.php";
require_once "aux_func.php";
require_once "db_converters.php";
session_start();
if (!isset($_SESSION['user_data']) || !isset($_POST['item_id'])) {
    header('Location: shop.php');
    exit();
}
$connection = @new mysqli($host, $db_user, $db_password, $db_name);
if ($connection->connect_errno != 0 && $debug == 1) {
    echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
} else {
    $item_id = $_POST['item_id'];
    try {
        if ($result = $connection->query("SELECT * FROM items where id=$item_id")) {
            if ($result->num_rows <= 0) {
                throw new Exception('Nie ma takiego produktu, coś poszło nie tak');
            } else {
                $_SESSION['focused_product'] = $result->fetch_assoc();
            }
        }
    } catch (Exception $e) {
        $_SESSION['e_productDetails'] = $e;
        $connection->close();
        header('Location:shop.php');
    }
}
$connection->close();
if (isset($_POST['comment'])) {
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0 && $debug == 1) {
        echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    } else {
        $comment = $_POST['comment'];
        if (empty($comment)) {
            $_SESSION['e_addComment'] = 'Nie można dodać pustego komentarza';
        } else {
            $user_id = $_SESSION['user_data']['id'];
            $rating = $_POST['rating'];
            $anonym = 0;
            if (isset($_POST['anonym'])) {
                $anonym = 1;
            }
            $item_id = $_SESSION['focused_product']['id'];
            try {
                $comment = htmlentities($comment, ENT_QUOTES, "UTF-8");

                if ($result = $connection->query(sprintf("INSERT INTO comments VALUES(NULL, $user_id, $item_id, $rating, '%s', $anonym, now())",
                    mysqli_real_escape_string($connection, $comment),))) {
                    $_SESSION['e_addComment'] = 'Pomyślnie dodano komenatrz';
                } else {
                    throw new Exception("Nie udało się dodać komentarza");
                }
                $connection->close();
            } catch (Exception $e) {
                $_SESSION['e_addComment'] = $e;
                $connection->close();
            }
        }
    }
}
if (isset($_POST['delete_comment_id'])) {
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0 && $debug == 1) {
        echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    } else {
        $comment_id = $_POST['delete_comment_id'];
        try {
            if ($result = $connection->query("DELETE FROM comments WHERE id=$comment_id")) {
                $_SESSION['e_deleteComment'] = 'Pomyślnie usunięto komenatrz';
            } else {
                throw new Exception("Nie udało się usunąć komentarza");
            }
            $connection->close();
        } catch (Exception $e) {
            $_SESSION['e_deleteComment'] = $e;
            $connection->close();
        }
    }
}


$connection = @new mysqli($host, $db_user, $db_password, $db_name);
if ($connection->connect_errno != 0 && $debug == 1) {
    echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
} else {
    $item_id = $_POST['item_id'];
    try {
        $result = $connection->query("SELECT * FROM comments WHERE item_id = $item_id");
        if ($result->num_rows > 0) {
            $int = 0;
            while ($comment = $result->fetch_assoc()) {
                $_SESSION['comments'][$comment['id']] = $comment;
                $int++;
            }
        } else {
            $_SESSION['e_comments'] = 'Nie ma żadnych komentarzy do tego produktu';
        }
    } catch (Exception $e) {
        $_SESSION['e_productDetails'] = $e;
        $connection->close();
        header('Location:shop.php');
    }
}
$connection->close();


function commentField($item_id)
{
    echo '<form method="POST">';
    echo '<label for="comment"><b>Wyraź swoją opinię:</b></label>';
    echo '<br/>';
    $rand = rand();
    $_SESSION['rand'] = $rand;
    echo '<input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />';
    ratingSelect();
    echo '<br/>';
    echo '<textarea id="comment" name="comment"
          rows="5" cols="66">';
    echo '</textarea>';
    echo '<br/>';
    echo '<label>';
    echo '<input type="checkbox" name="anonym" value=0/>' . 'Komentuj jako anonim';
    echo '</label>';
    echo '<input type="hidden" name="item_id" value="' . $item_id . '"/>';
    echo '<br/>';
    echo '<button type="submit" class="btn btn-primary btn-sm">Dodaj komentarz</button>';
    echo '</form>';
}

/**
 * @return void
 */
function ratingSelect(): void
{
    echo '<select name ="rating">';
    echo ' <option value=1>1 gwiazdka</option>';
    echo ' <option value=2>2 gwiazdki</option>';
    echo ' <option value=3>3 gwiazdki</option>';
    echo ' <option value=4>4 gwiazdki</option>';
    echo ' <option value=5>5 gwiazdek</option>';
    echo '</select>';
}

function adminCommentActions($id, $item_id): void
{
    echo '<div>';
    echo '<form method="post">';
    echo '<input type="hidden" name="delete_comment_id" value="' . $id . '"/>';
    echo '<input type="hidden" name="item_id" value="' . $item_id . '"/>';
    echo '<button type="submit" class="btn btn-primary btn-sm">Usuń ten komentarz</button>';
    echo '</form>';
    echo '</div>';
}

function commentSection(): void
{
    foreach ($_SESSION['comments'] as $comment => $cur) {
        echo '<div class="card">';
        echo userIdToNameAndLastName($cur['user_id'], $cur['is_anonym']);
        echo '<br/>';
        echo '<h1 style="font-size: 10px" >' . $cur['date'] . '</h1>';
        echo 'Ocena: ';
        echo ratingToString($cur['rating']);
        echo '<br/>';
        echo $cur['comment'];
        echo '</br>';
        if ($_SESSION['user_data']['is_admin']) {
            adminCommentActions($cur['id'], $_POST['item_id']);
        }
        echo '</div>';
        echo '<br/>';
    }
}

function commentPart($item_id)
{
    echo '<hr class="border border-primary border-3 opacity-75">';
    echo '<div class="container">';
    echo '<b> Komentarze: </b></br>';
    if (isset($_SESSION['comments'])) {
        commentSection();
        unset($_SESSION['comments']);
    } else {
        echo 'Narazie brak komentarzy';
    }
    echo '<br/>';
    commentField($item_id);
    echo '</div>';
//    echo '</span>';

}

?>


<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Szczegóły produktu</title>
</head>

<body>
<div class="container">
    <?php
    if ($_SESSION['user_data']['is_admin']) {
        require_once "admin_navbar.php";
    } else {
        require_once "navbar.php";
    }
    if (isset($_SESSION['e_addComment'])) {
        showAlert($_SESSION['e_addComment']);
        unset($_SESSION['e_addComment']);
    }
    if (isset($_SESSION['e_deleteComment'])) {
        showAlert($_SESSION['e_deleteComment']);
        unset($_SESSION['e_deleteComment']);
    }

    if (isset($_SESSION['focused_product'])) {
        echo '<br/>';
        echo '<div class = "row">';
        echo '<div class ="col-sm">';
        echo '<div class="card">';
        productInfoSection($_SESSION['focused_product']);
        echo '</div>';
        echo '<br/>';
        echo '<div class="card">';
        showProductDescription($_SESSION['focused_product']);
        echo '</div>';
        echo '<div class="card">';
        buySection($_SESSION['focused_product']);
        echo '</div>';
        echo '</div>';
        echo '<div class ="col-sm">';
        echo showProductImage($_SESSION['focused_product']['id']);
        echo '</div>';
        echo '</div>';
        commentPart($_SESSION['focused_product']['id']);
        unset($_SESSION['focused_product']);
    }

    ?>
</div>
</body>

</html>
