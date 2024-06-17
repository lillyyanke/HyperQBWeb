<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {

    $model_1_init = isset($_POST['model_1_init']) ? $_POST['model_1_init'] : '';
    $model_1_init_select = isset($_FILES['model_1_init_select']) ? $_FILES['model_1_init_select']['name'] : '';

    $model_1_trans = isset($_POST['model_1_trans']) ? $_POST['model_1_trans'] : '';
    $model_1_trans_select = isset($_FILES['model_1_trans_select']) ? $_FILES['model_1_trans_select']['name'] : '';

    // Log inputs for debugging
    error_log("Input: model_1_init=$model_1_init");
    error_log(print_r($_FILES, true)); // Log $_FILES contents for debugging
    error_log("Input: model_1_trans=$model_1_trans");
    error_log("Input: model_1_trans_select=$model_1_trans_select");
    //error_log("Input: model_1_init_select=$model_1_init_select");

    // Define the expected output file path
    $outputFile = './test/HQ.qcir';

    if (!empty($model_1_init)) {
        //$inputI1 = '-I ' . escapeshellarg($model_1_init);
        //$inputI1 = '-I ' . $model_1_init;
        // Define the file path where the content will be saved
        $inputI1 = 'test/I_1.bool';

        // Save the content to the file
        if (file_put_contents('./test/I_1.bool', $model_1_init) === false) {
            echo json_encode(['error' => 'Failed to create input file']);
            exit;
        }
    } 
    else if (!empty($model_1_init_select)) {
        error_log("Input: model_1_init_select=$model_1_init_select");
        // Handle file upload
        // Move uploaded file to desired location 
        $targetPath = './test/';
        $newFilename = 'I_1.bool';
        $filename = basename($_FILES['model_1_init_select']['name']);
        $uploadedFile = $targetPath . $newFilename;

        if (!(move_uploaded_file($_FILES['model_1_init_select']['tmp_name'], $uploadedFile))) {
            //$inputI1 = '-I ' . escapeshellarg($uploadedFile);
        //} else {
            echo json_encode(['error' => 'Failed to move uploaded file']);
            exit;
        }
    } 
    else {
        echo json_encode(['error' => 'No input provided or file does not exist.']);
        exit;
    }
    //error_log("Input: help me=$inputI1");

    // Determine the input for -I flag
    $inputR1 = '';
    if (!empty($model_1_trans)) {
        // Define the file path where the content will be saved
        $inputR1 = 'test/R_1.bool';

        // Save the content to the file
        if (file_put_contents('./test/R_1.bool', $model_1_trans) === false) {
            echo json_encode(['error' => 'Failed to create input file']);
            exit;
        }
    } 
    else if (!empty($model_1_trans_select)) {
        // Handle file upload
        // Move uploaded file to desired location 
        $targetPath = './test/';
        $filename = basename($_FILES['model_1_trans_select']['name']);
        $newFilename = 'R_1.bool';
        $uploadedFile = $targetPath . $newFilename;

        if (move_uploaded_file($_FILES['model_1_trans_select']['tmp_name'], $uploadedFile)) {
            $inputR1 = '-R test/' . $model_1_trans_select;
        } else {
            echo json_encode(['error' => 'Failed to move uploaded file']);
            exit;
        }
    } 
    else {
        echo json_encode(['error' => 'No input provided or file does not exist.']);
        exit;
    }

    #-I test/I_1.bool -R test/R_1.bool -J test/I_2.bool -S test/R_2.bool -P test/P.hq -k 3 -F AA -f qcir -o test/HQ.qcir -sem PES -n --fast -new NN

    // Call the first executable and get its output
    $commandGen = './bin/genqbf -I test/I_1.bool -R test/R_1.bool -J test/I_2.bool -S test/R_2.bool -P test/P.hq -k 3 -F AA -f qcir -o test/HQ.qcir -sem PES -n --fast -new NN';


    // $commandGen = sprintf(
    //     './bin/genqbf %s %s -J %s -S %s -P %s -k %d -F %s -f qcir -o %s -sem %s -n --fast -new %s',
    //     $inputI1, $inputR1, 'test/I_2.bool', 'test/R_2.bool',
    //     'test/P.hq', 3, 'AA', 'test/HQ.qcir', 
    //     'PES', 'NN'
    // );

    
    $genOutput = [];
    $genStatus = null;
    error_log("Command: $commandGen");
    exec($commandGen . ' 2>&1' , $genOutput, $genStatus);
    error_log("Command error: " . implode("\n", $genOutput));
    if ($genStatus != null) {
        $result = implode("\n", $genOutput);
        echo json_encode(['result' => $result]);
        exit;
    }

    error_log("Command output: $genOutput[0]");
    
    // Check if the output file is generated
    if (is_file($outputFile)) {
        // If the file is generated, run the second command
        $commandQuabs = './bin/quabs ' . escapeshellarg($outputFile);
        $quabsOutput = [];
        $quabsStatus = null;
        // Execute the quabs command and get its output
        error_log("Executing second command: $commandQuabs");
        exec($commandQuabs . ' 2>&1', $quabsOutput, $quabsStatus);

        if ($quabsStatus != null) {
            $result = implode("\n", $quabsOutput);
            echo json_encode(['result' => $result]);
            exit;
        }
        
        error_log("Second command output: $quabsOutput");
        $result = $quabsOutput;

     } 
    else {
        $result = is_null($genOutput) ? 'Error executing command' : 'File not generated';
    }

    echo json_encode(['result' => $result]);
}

    catch (Exception $e) {
        // Log the error message
        error_log("Error: " . $e->getMessage());

        // Return the error message as part of the JSON response
        echo json_encode(['error' => $e->getMessage()]);
    }
}
 
    else {
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Method Not Allowed']);
}