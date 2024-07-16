<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = "hello";
    echo json_encode(['result' => $result]);
} else {
    echo 'Invalid request method.';
}