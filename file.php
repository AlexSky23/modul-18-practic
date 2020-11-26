<?php
include 'config.php';

$err = [];
$msg = [];

$imageFileName = $_GET['name'];
$commentFilePath = COMMENT_DIR . '/' . $imageFileName . '.txt';

//если комм-т был отправлен
if (!empty($_POST['comment'])) {
    $comment = trim($_POST['comment']);

    //валидация коммента
    if ($comment === '') {
        $err[] = 'Не введен комментарий!';
    }

    //если нет ош., то записать комм.
    if (empty($err)) {
        
        $comment = strip_tags($comment);
        $comment = str_replace(array(["/r/n", "/r", "/n", "//r", "//n", "//r//n"]), "<br/>", $comment);
        $comment = $comment . ' : ' . date('d.m.Y H:i');

        // Дозапись текста в файл
        file_put_contents($commentFilePath, $comment . "\n", FILE_APPEND);

        $msg[] = 'Комментарий добавлен';
    }
}

// получение списка комментов
$comments = file_exists($commentFilePath)
    ? file($commentFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
    : [];
    ?>

<!doctype html>
<html lang="en">

<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>PHP_practic - Галлерея картинок</title>
    <link rel="stylesheet" href="style.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" 
    integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

</head>
<body>
<div class="container pt-4">
        <h1 class="mb-4"><a href="<?php echo URL; ?>">Галлерея картинок</a></h1>
        
                <!-- message error on/off-->
                <?php foreach($err as $er):?>
        <div class="alert alert-danger"><?php echo $er; ?></div>
        <?php endforeach; ?>

        <?php foreach($msg as $ms):?>
        <div class="alert alert-danger"><?php echo $ms; ?></div>
        <?php endforeach; ?>

        <h2 class="mb-4">Файл <?php echo $imageFileName; ?>:</h2>

        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2">
                <img src="<?php echo URL . '/' . UPLOAD_DIR . '/' . $imageFileName ?>" 
                class="img-thumbnail mb-4" alt="<?php echo $imageFileName ?> ">

                <h3>Комментарии</h3>

                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $key => $comment): ?>
                        <p class="<?php echo (($key % 2) > 0)? 'bg-light' : ''; ?>" >
                        <?php echo $comment; ?>
                        </p>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Оставьте первый коммент!</p>
                <?php endif; ?>

                <!--добавление коммента -->
                    <form method="post">
                        <div class="form-group">
                            <label for="comment">Ваш коммент</label>
                            <textarea class="form-control" name="comment" id="comment" rows="3" required></textarea>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </form>
            </div>
        </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
    integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
    integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
    crossorigin="anonymous"></script>
</body>
</html>