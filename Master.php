<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pipeFiles = explode(',', $_POST['pipeFiles']);
    aggregateAndDisplayData($pipeFiles);
}

function aggregateAndDisplayData($pipeFiles) {
    $consolidatedData = [];

    foreach ($pipeFiles as $pipeFile) {
        if (file_exists($pipeFile)) {
            $content = file_get_contents($pipeFile);
            $data = json_decode($content, true);

            foreach ($data as $file => $words) {
                foreach ($words as $word => $count) {
                    if (!isset($consolidatedData[$file][$word])) {
                        $consolidatedData[$file][$word] = 0;
                    }
                    $consolidatedData[$file][$word] += $count;
                }
            }
        }
    }

    displayData($consolidatedData);
}

function displayData($data) {
    echo "<h2>Aggregated Data:</h2><pre>";
    foreach ($data as $file => $words) {
        foreach ($words as $word => $count) {
            echo "$file:$word:$count\n";
        }
    }
    echo "</pre>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Master</title>
</head>
<body>
    <h1>Master Process</h1>
    <form method="POST">
        <label for="pipeFiles">Pipe File Paths (comma-separated):</label>
        <input type="text" id="pipeFiles" name="pipeFiles" required>
        <br>
        <button type="submit">Run Master</button>
    </form>
</body>
</html>