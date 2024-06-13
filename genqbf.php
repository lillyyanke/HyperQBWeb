<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputArgs = isset($_POST['inputArgs']) ? $_POST['inputArgs'] : '';
    
    // Log inputs for debugging
    error_log("Input: arguments=$inputArgs");

    // Define the expected output file path
    $outputFile = './test/HQ.qcir';

    // Call the first executable and get its output
    $commandGen = './bin/genqbf ' . $inputArgs;
    $genOutput = shell_exec($commandGen);
    error_log("Command: $commandGen");
    error_log("Command output: $genOutput");
    
    // Check if the output file is generated
    if (is_file($outputFile)) {
        // If the file is generated, run the second command
        $commandQuabs = './bin/quabs ' . escapeshellarg($outputFile);

        // Execute the quabs command and get its output
        error_log("Executing second command: $commandQuabs");
        $quabsOutput = shell_exec($commandQuabs);
        
        error_log("Second command output: $quabsOutput");
        $result = $quabsOutput;

    } else {
        $result = is_null($genOutput) ? 'Error executing command' : 'File not generated';
    }

    echo json_encode(['result' => $result]);

} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed']);
}