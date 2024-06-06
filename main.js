
// Function to store the input value in the 'model' variable
function storeModel() {
    // Get the value from the input text box
    const modelInput = document.getElementById('modelInput').ariaValueMax;

    // Store the value in the 'model' variable
    const model = modelInput;

}

// Function to store the input value in the 'property' variable
function storeModel() {
    // Get the value from the input text box
    const propertyInput = document.getElementById('propertyInput').ariaValueMax;

    // Store the value in the 'property' variable
    const property = propertyInput;

}

// Function to store both of the inputs
function storeModelAndProperty() {
    console.log("Hello world");
    // Get the value from the model input text box
    const modelInput = document.getElementById('modelInput').value;

    // Get the value from the property input text box
    const propertyInput = document.getElementById('propertyInput').value;

    // Store the value in the 'model' variable
    const model = modelInput;

    // Store the value in the 'property' variable
    const property = propertyInput;

    console.log(model);
    console.log(property);

    const result = model + '\n' + property;

    //Output result to textarea
    document.getElementById('output').value = result;

}