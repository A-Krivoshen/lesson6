<?php

// Если был получен POST-запрос с файлом, то проверяем, подходит ли он
if (isset($_POST['upload'])) {

    // Определяем массив со всеми файлами из папки с тестами
    if (!empty(glob('tests/*.json'))) {
        $allFiles = glob('tests/*.json');
    } else {
        $allFiles = [0];
    }

    // Определяем загружаемый файл
    $uploadfile = 'tests/' . basename($_FILES['testfile']['name']);

    // Прогоняем файл по if'ам, если не подходит - выкидываем ошибку
    if (pathinfo($_FILES['testfile']['name'], PATHINFO_EXTENSION) !== 'json') {
        $result = "<p>Можно загружать файлы только с расширением json</p>";
    } else if ($_FILES["testfile"]["size"] > 1024 * 1024 * 1024) {
        $result = "<p>Размер файла превышает три мегабайта</p>";
    } else if (in_array($uploadfile, $allFiles, true)) {
        $result = "<p>Файл с таким именем уже существует.</p>";
    } else if (move_uploaded_file($_FILES['testfile']['tmp_name'], $uploadfile)) {
        $result = "<p>Файл корректен и успешно загружен на сервер</p>";
    } else {
        $result = "<p>Произошла ошибка</p>";
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Домашнее задание2.2</title>
</head>
<body>
<?php if (isset($_POST['upload'])): ?>
    <a href="<?php $_SERVER['HTTP_REFERER'] ?>"><div>< Назад</div></a>
    <?php echo $result; ?>
<?php endif; ?>


<?php if (!isset($_POST['create']) && !isset($_POST['upload'])): ?>

    <form id="load-json" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Загрузите свой тест в формате json</legend>
            <input type="file" name="testfile" id="uploadfile" required>
            <input type="submit" value="Добавить в базу" id="submit-upload" name="upload">
        </fieldset>
    </form>

    <div class="all-tests">
        <fieldset>
            <a href="list.php">Посмотреть все тесты >></a>
        </fieldset>
    </div>

<?php endif; ?>
</body>
</html>