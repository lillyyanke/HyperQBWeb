import sys
from flask import Flask, request, jsonify
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

@app.route('/process', methods=['POST'])
def process():
    data = request.json
    model = data.get('modelInput', '')
    property = data.get('propertyInput', '')
    
    result = add_inputs(model, property)
    
    return jsonify({'result': result})

def add_inputs(model, property):
    try:
        # Convert inputs to integers and add them
        result = int(model) + int(property)
    except ValueError:
        # If conversion fails, concatenate strings
        result = model + property
    return result

if __name__ == '__main__':
    app.run(debug=True)



