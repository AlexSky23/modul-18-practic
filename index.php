<?php
include 'config.php';

$err = [];
$msg = [];

// если файл отправлен
if(!empty($_FILES)) {
    //цикл файлов
    for ($i=0; $i < count($_FILES['files']['name']); $i++) { 
        $fileName = $_FILES['files']['name'][$i];

        //проверка размера
        if ($_FILES['files']['name'][$i] > UPLOAD_MAX_SIZE) {
            $err[] = 'Размер файла слишком большой' . $fileName;
            continue;
        }

        //проверка формата
        if (!in_array($_FILES['files']['type'][$i], ALLOWED_TYPES)) {
            $err[] = 'Не верный формат файла' . $fileName;
            continue;
        }
        
        $filePath = UPLOAD_DIR . '/' . basename($fileName);

        //загрузка файла
        if (!move_uploaded_file($_FILES['files']['tmp_name'][$i], $filePath)) {
            $err[] = 'Ошибка загрузки файла!' . $fileName;
            continue;
        }
    }
    if (empty($err)) {
        $msg[] = 'Файлы успешно загружены!';
    }
}

// если удаление
if (!empty($_POST['name'])) {
    $filePath = UPLOAD_DIR . '/' . $_POST['name'];
    $commentPath = COMMENT_DIR . '/' . $_POST['name'] . '.txt';

// delete img
unlink($filePath);

// del file to comment
if (file_exists($commentPath)) {
    unlink($commentPath);
}

$msg[] = 'Файл удалён';
}

// give files, except sys
$files = scandir(UPLOAD_DIR);
$files = array_filter($files, function($file){
    return !in_array($file, ['.', '..', '.gitkeep']);
});

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

        <h2>Список файлов:</h2>

        <!--show img-->
        <div class="mb-4">
            <?php if (!empty($files)): ?>
                <div class="row">
                    <?php foreach ($files as $file): ?>

                    <div class="col-12 col-sm-3 mb-4">
                        <form method="post">
                            <input type="hidden" name="name" value="<?php echo $file; ?>">
                            <button type="submit" class="close" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </form>
                        <a href="<?php echo URL . '/file.php?name=' . $file; ?>" title="Просмотреть картинку">
                            <img src="<?php echo URL . '/' . UPLOAD_DIR . '/' . $file; ?>"
                            class="img-thumbnail" alt="<?php echo $file; ?>">
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div> <!--end row-->
            <?php else: ?>
                <div class="alert alert-secondary">Загруженные картинки отсутствуют</div>
            <?php endif; ?>
        </div>

<!--form upload img-->
<form method="post" enctype="multipart/form-data">
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="files[]" id="customFile" multiple required>
        <label class="custom-file-label" for="customFile" data-browse="Select">Выбрать файлы</label>
        <small class="form-text text-muted">
                Максимальный размер файла: <?php echo UPLOAD_MAX_SIZE/1000000; ?> Mb.
                Допустимые типы файлов: <?php echo implode(', ', ALLOWED_TYPES); ?>
        </small>
    </div>
    <hr>
    <button type="submit" class="btn btn-primary">Загрузить</button>
</form>
    </div> <!-- end container -->

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