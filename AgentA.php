<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $directory = $_POST['directory'];
    $pipeFile = $_POST['pipeFile'];

    readFilesAndSendData($directory, $pipeFile);
}

function readFilesAndSendData($directory, $pipeFile) {
    $files = glob($directory . '/*.txt');
    $wordCounts = [];

    foreach ($files as $file) {
        $content = file_get_contents($file);
        $words = str_word_count($content, 1);
        foreach ($words as $word) {
            if (!isset($wordCounts[$file][$word])) {
                $wordCounts[$file][$word] = 0;
            }
            $wordCounts[$file][$word]++;
        }
    }

    file_put_contents($pipeFile, json_encode($wordCounts) . PHP_EOL, FILE_APPEND);
    echo "Data sent to master.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent A</title>
</head>
<body>
    <h1>Agent A</h1>
    <form method="POST">
        <label for="directory">Directory Path:</label>
        <input type="text" id="directory" name="directory" required>
        <br>
        <label for="pipeFile">Pipe File Path:</label>
        <input type="text" id="pipeFile" name="pipeFile" required>
        <br>
        <button type="submit">Run Agent A</button>
    </form>
</body>
</html>