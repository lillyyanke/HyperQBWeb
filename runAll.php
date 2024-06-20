<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
    $model_1_init = $_POST['model_1_init'] ?? '';
    $model_1_trans = $_POST['model_1_trans'] ?? '';
    $model_2_init = $_POST['model_2_init'] ?? '';
    $model_2_trans = $_POST['model_2_trans'] ?? '';
    $p_hq = $_POST['p_hq'] ?? '';
    $number_k = $_POST['number_k'] ?? '';
    $quantifier_f = $_POST['quantifier_f'] ?? '';
    $semantics = $_POST['semantics'] ?? '';
    $test_folder = $_POST['test_folder'] ?? '';

    
    $model_1_init_select = isset($_FILES['model_1_init_select']) ? $_FILES['model_1_init_select']['name'] : '';
    $model_1_trans_select = isset($_FILES['model_1_trans_select']) ? $_FILES['model_1_trans_select']['name'] : '';
    $model_2_init_select = isset($_FILES['model_2_init_select']) ? $_FILES['model_2_init_select']['name'] : '';
    $model_2_trans_select = isset($_FILES['model_2_trans_select']) ? $_FILES['model_2_trans_select']['name'] : '';
    $p_hq_select = isset($_FILES['p_hq_select']) ? $_FILES['p_hq_select']['name'] : '';


    // Log inputs for debugging
    // error_log("Input: model_1_init=$model_1_init");
    // error_log(print_r($_FILES, true)); // Log $_FILES contents for debugging
    // error_log("Input: model_1_trans=$model_1_trans");
    // error_log("Input: model_1_trans_select=$model_1_trans_select");
    // error_log("Input: model_2_trans=$model_2_trans");
    // error_log("Input: model_2_trans_select=$model_2_trans_select");

    // Define the expected output file path, does this need to change?
    $outputFolder = 'test' . $test_folder;

    if (!is_dir($outputFolder)) {
        mkdir($outputFolder, 0755, true);
    }
    // For storing the result
    $outputFile = './' . $outputFolder . '/HQ.qcir';

    error_log("Output Folder=$outputFolder");
    // if (!(file_exists('./' . $outputFolder))){
    //     error_log("Make test folder");
    // }

    function processFile($outputFolder, $fileName, $fileContent, $fileUpload) {
        $filePath = $outputFolder . '/' . $fileName;
    
        // Check if content is provided directly
        if (!empty($fileContent)) {
            if (!file_exists($filePath)) {
                error_log("File $filePath does not exist, creating a new file");
                if (file_put_contents($filePath, $fileContent) === false) {
                    echo json_encode(['error' => "Failed to create input file for $fileName"]);
                    exit;
                }
            } else {
                error_log("File $filePath exists!");
            }
        } 
        // Check if a file upload is provided
        else if (!empty($fileUpload)) {
            error_log("Input: $fileName upload=$fileUpload");
            // Handle file upload
            $targetPath = './' . $outputFolder;
            $uploadedFile = $targetPath . '/' . $fileName;
    
            if (!move_uploaded_file($_FILES[$fileUpload]['tmp_name'], $uploadedFile)) {
                echo json_encode(['error' => "Failed to move uploaded file to $fileName"]);
                exit;
            }
        } 
        // Ensure that the file exists if no new content or upload is provided
        else {
            if (!file_exists($filePath)) {
                echo json_encode(['error' => "No input provided or file does not exist for $fileName."]);
                exit;
            }
        }
        return $filePath;
    }

    // Process each file
    $inputI1 = processFile($outputFolder, 'I_1.bool', $model_1_init, 'model_1_init_select');
    $inputI2 = processFile($outputFolder, 'I_2.bool', $model_2_init, 'model_2_init_select');
    $inputR1 = processFile($outputFolder, 'R_1.bool', $model_1_trans, 'model_1_trans_select');
    $inputR2 = processFile($outputFolder, 'R_2.bool', $model_2_trans, 'model_2_trans_select');
    $inputP = processFile($outputFolder, 'P.hq', $p_hq, 'p_hq_select'); 

    #-I test/I_1.bool -R test/R_1.bool -J test/I_2.bool -S test/R_2.bool -P test/P.hq -k 3 -F AA -f qcir -o test/HQ.qcir -sem PES -n --fast -new NN

    $HQ = $outputFolder . '/HQ.qcir';
    // Call the first executable and get its output
    //$commandGen = './bin/genqbf -I test/I_1.bool -R test/R_1.bool -J test/I_2.bool -S test/R_2.bool -P test/P.hq -k 3 -F AA -f qcir -o test/HQ.qcir -sem PES -n --fast -new NN';
    //$commandGen = './bin/genqbf -I test2/I_1.bool -R test2/R_1.bool -J test2/I_2.bool -S test2/R_2.bool -P test2/P.hq -k 3 -F AA -f qcir -o test2/HQ.qcir -sem PES -n --fast -new NN';
    //$commandGen = './bin/genqbf -I test3/I_1.bool -R test3/R_1.bool -J test3/I_2.bool -S test3/R_2.bool -P test3/P.hq -k 3 -F AA -f qcir -o test3/HQ.qcir -sem PES -n --fast -new NN';
    $commandGen = sprintf(
        './bin/genqbf -I %s -R %s -J %s -S %s -P %s -k %s -F %s -f qcir -o %s -sem %s -n --fast -new %s',
        $inputI1, $inputR1, $inputI2, $inputR2,
        $inputP, $number_k, $quantifier_f, $HQ, 
        $semantics, 'NN'
    );

    
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