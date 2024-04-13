<?php
// Подключение к базе данных SQLite
$db = new PDO('sqlite:bot_database.db');

// Получение списка таблиц из базы данных
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
$tables = $result->fetchAll(PDO::FETCH_COLUMN);

// Проверка, выбрана ли таблица
$selectedTable = isset($_POST['table']) ? $_POST['table'] : (isset($tables[0]) ? $tables[0] : null);

// Получение данных из выбранной таблицы
if ($selectedTable) {
    $stmt = $db->prepare("SELECT * FROM " . $selectedTable);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Редактирование строки
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editRow'])) {
   //пока не работает
}

//удаление строки
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteRow'])) {
    $idToDelete = $_POST['deleteRow'];
    $stmt = $db->prepare("DELETE FROM " . $selectedTable . " WHERE id = :id");
    $stmt->bindParam(':id', $idToDelete, PDO::PARAM_INT);
    $stmt->execute();
    // Перенаправление обратно на страницу после удаления
    header('Location: secure.php');
    exit;

}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>таблицы</title>
</head>
<body>
<div>
    <h2>Просмотр таблицы</h2>
    <form action="secure.php" method="post">
        <select name="table" onchange="this.form.submit()">
            <?php foreach ($tables as $table): ?>
                <option value="<?= $table ?>" <?= $table === $selectedTable ? 'selected' : '' ?>>
                    <?= $table ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    <!-- Отображение данных таблицы -->
    <?php if ($selectedTable && $rows): ?>
        <table border="1">
            <thead>
                <tr>
                    <?php foreach ($rows[0] as $key => $value): ?>
                        <th><?= $key ?></th>
                    <?php endforeach; ?>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <?php foreach ($row as $value): ?>
                            <td><?= $value ?></td>
                        <?php endforeach; ?>
                        <td>
                            <form action="secure.php" method="post">
                                <input type="hidden" name="editRow" value="<?= $row['id'] ?>">
                                <input type="submit" value="Редактировать">
                            </form>
                            <form action="secure.php" method="post">
                                <input type="hidden" name="deleteRow" value="<?= $row['id'] ?>">
                                <input type="submit" value="Удалить">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
