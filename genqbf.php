<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputArgs = isset($_POST['inputArgs']) ? $_POST['inputArgs'] : '';
    
    // Log inputs for debugging
    error_log("Input: arguments=$inputArgs");

    // Define the expected output file path
    $outputFile = './test/HQ.qcir';

    // Call the Python executable and get its output
    //$command = './bin/genqbf ' . escapeshellarg($inputArgs);
    $command = $inputArgs;
    $binOutput = shell_exec($command);
    error_log("Command: $command");
    error_log("Command output: $binOutput");
    
    // Check if the output file is generated
    if (is_file($outputFile)) {
        $result = 'File generated successfully: ' . $outputFile;
    } else {
        $result = is_null($binOutput) ? 'Error executing command' : 'File not generated';
    }

    echo json_encode(['result' => $result]);

} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed']);
}