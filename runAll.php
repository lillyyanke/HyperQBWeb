<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {

    $model_1_init = isset($_POST['model_1_init']) ? $_POST['model_1_init'] : '';
    $model_1_init_select = isset($_FILES['model_1_init_select']) ? $_FILES['model_1_init_select']['name'] : '';

    $model_1_trans = isset($_POST['model_1_trans']) ? $_POST['model_1_trans'] : '';
    $model_1_trans_select = isset($_FILES['model_1_trans_select']) ? $_FILES['model_1_trans_select']['name'] : '';

    $model_2_init = isset($_POST['model_2_init']) ? $_POST['model_2_init'] : '';
    $model_2_init_select = isset($_FILES['model_2_init_select']) ? $_FILES['model_2_init_select']['name'] : '';

    $model_2_trans = isset($_POST['model_2_trans']) ? $_POST['model_2_trans'] : '';
    $model_2_trans_select = isset($_FILES['model_2_trans_select']) ? $_FILES['model_2_trans_select']['name'] : '';

    $p_hq = isset($_POST['p_hq']) ? $_POST['p_hq'] : '';
    $p_hq_select = isset($_FILES['p_hq_select']) ? $_FILES['p_hq_select']['name'] : '';

    $number_k = isset($_POST['number_k']) ? $_POST['number_k'] : '';
    $quantifier_f = isset($_POST['quantifier_f']) ? $_POST['quantifier_f'] : '';
    $semantics = isset($_POST['semantics']) ? $_POST['semantics'] : '';
    
    $test_folder = isset($_POST['test_folder']) ? $_POST['test_folder'] : '';

    // Log inputs for debugging
    error_log("Input: model_1_init=$model_1_init");
    error_log(print_r($_FILES, true)); // Log $_FILES contents for debugging
    error_log("Input: model_1_trans=$model_1_trans");
    error_log("Input: model_1_trans_select=$model_1_trans_select");

    error_log("Input: model_2_trans=$model_2_trans");
    error_log("Input: model_2_trans_select=$model_2_trans_select");

    // Define the expected output file path, does this need to change?
    $outputFolder ='test' . $test_folder;
    error_log("test folder=$test_folder");
    //$outputFile = './test/HQ.qcir';
    $outputFile = './' . $outputFolder . '/HQ.qcir';

    // Define the file path where the content will be saved
    $inputI1 = $outputFolder . '/I_1.bool';

    if (!empty($model_1_init)) {
        // Save the content to the file
        if (file_put_contents('./'.$outputFolder.'/I_1.bool', $model_1_init) === false) {
            echo json_encode(['error' => 'Failed to create input file for I_1.bool']);
            exit;
        }
    } 
    else if (!empty($model_1_init_select)) {
        error_log("Input: model_1_init_select=$model_1_init_select");
        // Handle file upload
        // Move uploaded file to desired location 
        $targetPath = './' . $outputFolder;
        $newFilename = '/I_1.bool';
        $filename = basename($_FILES['model_1_init_select']['name']);
        $uploadedFile = $targetPath . $newFilename;
        error_log("Uploaded file =$uploadedFile");

        if (!(move_uploaded_file($_FILES['model_1_init_select']['tmp_name'], $uploadedFile))) {
            echo json_encode(['error' => 'Failed to move uploaded file to I_1.bool']);
            exit;
        }
    } 
    else {
        if (!(file_exists('./' . $inputI1))){
            echo json_encode(['error' => 'No input provided or file does not exist for I_1.bool.']);
            exit;
        }
    }

    $inputI2 = $outputFolder . '/I_2.bool';

    if (!empty($model_2_init)) {
        // Save the content to the file
        if (file_put_contents('./'.$outputFolder.'/I_2.bool', $model_2_init) === false) {
            echo json_encode(['error' => 'Failed to create input file for I_2.bool']);
            exit;
        }
    } 
    else if (!empty($model_2_init_select)) {
        error_log("Input: model_1_init_select=$model_2_init_select");
        // Handle file upload
        // Move uploaded file to desired location 
        $targetPath = './' . $outputFolder;
        $newFilename = '/I_2.bool';
        $filename = basename($_FILES['model_2_init_select']['name']);
        $uploadedFile = $targetPath . $newFilename;
        if (!(move_uploaded_file($_FILES['model_2_init_select']['tmp_name'], $uploadedFile))) {
            echo json_encode(['error' => 'Failed to move uploaded file to I_2.bool']);
            exit;
        }
    } 
    else {
        if (!(file_exists('./' . $inputI2))){
            echo json_encode(['error' => 'No input provided or file does not exist for I_2.bool.']);
            exit;
        }
    }

    $inputR1 = $outputFolder . '/R_1.bool';
    if (!empty($model_1_trans)) {

        // Save the content to the file
        if (file_put_contents('./'.$outputFolder.'/R_1.bool', $model_1_trans) === false) {
            echo json_encode(['error' => 'Failed to create input file for R_1.bool']);
            exit;
        }
    } 
    else if (!empty($model_1_trans_select)) {
        // Handle file upload
        // Move uploaded file to desired location 
        $targetPath = './' . $outputFolder;
        $filename = basename($_FILES['model_1_trans_select']['name']);
        $newFilename = '/R_1.bool';
        $uploadedFile = $targetPath . $newFilename;

        if (!(move_uploaded_file($_FILES['model_1_trans_select']['tmp_name'], $uploadedFile))) {
            echo json_encode(['error' => 'Failed to move uploaded file to R_1.bool']);
            exit;
        }
    } 
    else {
        if (!(file_exists('./' . $inputR1))){
            echo json_encode(['error' => 'No input provided or file does not exist for R_1.bool.']);
            exit;
        }
    }


    $inputR2 = $outputFolder . '/R_2.bool';
    if (!empty($model_2_trans)) {

        // Save the content to the file
        if (file_put_contents('./'.$outputFolder.'/R_2.bool', $model_2_trans) === false) {
            echo json_encode(['error' => 'Failed to create input file for R_2.bool']);
            exit;
        }
    } 
    else if (!empty($model_2_trans_select)) {
        // Handle file upload
        // Move uploaded file to desired location 
        $targetPath = './' . $outputFolder;
        $filename = basename($_FILES['model_2_trans_select']['name']);
        $newFilename = '/R_2.bool';
        $uploadedFile = $targetPath . $newFilename;

        if (!(move_uploaded_file($_FILES['model_2_trans_select']['tmp_name'], $uploadedFile))) {
            echo json_encode(['error' => 'Failed to move uploaded file for R_2.bool']);
            exit;
        }
    } 
    else {
        if (!(file_exists('./' . $inputR2))){
            echo json_encode(['error' => 'No input provided or file does not exist for R_2.bool.']);
            exit;
        }
    }


    error_log("Input: P_hq=$p_hq");
    
    $inputP = $outputFolder . '/P.hq';
    if (!empty($p_hq)) {

        // Save the content to the file
        if (file_put_contents('./'.$outputFolder.'/P.hq', $p_hq) === false) {
            echo json_encode(['error' => 'Failed to create input file for P.hq']);
            exit;
        }
    } 
    else if (!empty($p_hq_select)) {
        // Handle file upload
        // Move uploaded file to desired location 
        $targetPath = './' . $outputFolder;
        $filename = basename($_FILES['p_hq_select']['name']);
        $newFilename = '/P.hq';
        $uploadedFile = $targetPath . $newFilename;

        if (!(move_uploaded_file($_FILES['p_hq_select']['tmp_name'], $uploadedFile))) {
            echo json_encode(['error' => 'Failed to move uploaded file to P.hq']);
            exit;
        }
    } 
    else {
        if (!(file_exists('./' . $inputP))){
            echo json_encode(['error' => 'No input provided or file does not exist for P.hq.']);
            exit;
        }
    }

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