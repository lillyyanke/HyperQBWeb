import sys

def add_inputs(model, property):
    try:
        # Convert inputs to integers and add them
        result = int(model) + int(property)
    except ValueError:
        # If conversion fails, concatenate strings
        result = model + property
    return result

if __name__ == '__main__':
    #Get command line arguments
    num1 = sys.argv[1]
    num2 = sys.argv[2]

    result = add_inputs(num1, num2)
    print(result)



