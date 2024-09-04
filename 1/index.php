<?php
// инициализируем нужные переменные
$success = false;
$error = false;
$errorLabel = '';

// Проверка - отправлена ли форма
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Проверка существования загруженного файла
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            // Получаем расширение файла
            $file_extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

            // Проверяем, является ли файл текстовым
            if ($file_extension !== 'txt') {
                $error = true;
                throw new Exception("Ошибка: файл должен иметь расширение .txt");
            }

            $upload_dir = 'files/';

            // Проверка существования папки, если нет - создание
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    $error = true;
                    throw new Exception("Ошибка: не удалось создать директорию.");
                }
            }

            // Получение информации о файле
            $file_name = basename($_FILES['file']['name']);
            $target_file = $upload_dir . $file_name;

            // Перемещение загруженного файла в целевую папку
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
                $error = true;
                throw new Exception("Ошибка: не удалось переместить файл.");
            }

            // Чтение файла и разбиение по заданному символу
            $file_content = file_get_contents($target_file);
            $delimiter = isset($_POST['delimiter']) && !empty($_POST['delimiter']) ? $_POST['delimiter'] : "\n";
            $lines = explode($delimiter, $file_content); // Разбиваем содержимое файла

            $success = true;

        } else {
            throw new Exception("Ошибка: " . $_FILES['file']['error']);
        }
    } catch (Exception $e) {
        // Обработка исключений
        $errorLabel = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Тестовое задание</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<form action="" method="post" enctype="multipart/form-data">
    <label for="file">Выберите файл .txt:</label>
    <input type="file" name="file" id="file" accept=".txt" required>
    <label for="delimiter">Выберите разделитель (по умолчанию "\n")</label>
    <input type="text" name="delimiter" id="delimiter">
    <input type="submit" value="Загрузить">

    <!--    тут отображаем результаты-->
    <?php
    if ($success && !$error) {
        echo '<div class="circle success"></div>';
        echo 'Файл успешно загружен<br>';

        // Выводим результат
        foreach ($lines as $line) {
            // Подсчитываем количество цифр в строке
            preg_match_all('/\d/', $line, $matches);
            $count_digits = count($matches[0]);

            // Выводим строку и количество цифр
            echo htmlspecialchars($line) . " = $count_digits<br>";
        }
    }
    if (!$success && $error) {
        echo '<div class="circle error"></div>';
        echo $errorLabel;
    }
    ?>

</form>
</body>
</html>

