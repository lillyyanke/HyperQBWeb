

// Function to store both of the inputs
function storeModelAndProperty() {
    // Get the value from the model input text box
    const modelInput = document.getElementById('modelInput').value;
    // Get the value from the property input text box
    const propertyInput = document.getElementById('propertyInput').value;

    fetch('http://127.0.0.1:5000/process', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({modelInput: modelInput, propertyInput: propertyInput}),
    })
    
    .then(response => response.json())
    .then(data => {
        document.getElementById('output').value = data.result;
    })
    .catch(error => console.error('Error:', error));

}
 