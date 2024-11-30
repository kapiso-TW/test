<?php
session_start();

$dataFile = '/tmp/data.json';
$data = file_exists($dataFile) ? file_get_contents($dataFile) : '[]';
$data = json_decode($data, true);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['time'])) {
    $timeToRecall = $_POST['time']; // recall message's time tip
    $userName = $_SESSION['name']; // username(now)

    foreach ($data as $key => $entry) {
        // check username
        if ($entry['time'] === $timeToRecall && $entry['name'] === $userName) {
            $data[$key]['bool'] = 0; // flag
            break;
        }
    }

    // update file
    file_put_contents($dataFile, json_encode($data));
}

// back to index
header('Location: index.php');
exit;
