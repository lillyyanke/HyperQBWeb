<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modelInput = isset($_POST['modelInput']) ? $_POST['modelInput'] : '';
    $propertyInput = isset($_POST['propertyInput']) ? $_POST['propertyInput'] : '';

    // Log inputs for debugging
    error_log("Inputs: model=$modelInput, property=$propertyInput");

    // Call the Python executable and get its output
    $command = './dist/add ' . escapeshellarg($modelInput) . ' ' . escapeshellarg($propertyInput);
    $output = shell_exec($command);
    $result = is_null($output) ? 'Error executing command' : trim($output);

    echo json_encode(['result' => $result]);

} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed']);
}

