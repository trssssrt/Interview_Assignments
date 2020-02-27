"""
    Run file in command line involving the following arguments:

    Below is a sample command:
    python3 Ally_Financial.py -g loc
    python3 Ally_Financial.py -g pass -n
    python3 Ally_Financial.py -g people
    ________
    Here is an article about Ally’s new CIO (https://www.wsj.com/articles/new-ally-financial-cio-prioritizes-ai-11579615200)

    Below is the python screening task that the director of Artificial Intelligence gave me to screen candidates. The task is somewhat open ended and there is room for interpretation on how to complete it. Please don’t overthink it. Read the prompt and then complete it in the most effective way that you find possible. Let me know if you have any questions!

    Details

    There is an API (http://api.open-notify.org/) that provides information on the International Space Station. Documentation is provided via the website, along with sample request/response.

    Task

    Implement a Python script that will accept the following command line arguments, along with any required information, and print the expected results

    loc

    print the current location of the ISS

    Example: “The ISS current location at {time} is {LAT, LONG}”

    pass

    print the passing details of the ISS for a given location

    Example: “The ISS will be overhead {LAT, LONG} at {time} for {duration}”

    people

    for each craft print the details of those people that are currently in space
"""

import requests
import json

class AllyFinancial_Screening():
    def __init__(self):
        self.base_url = 'http://api.open-notify.org/'
        self.simple_values = {
            'loc': 'iss-now.json',
            'pass': 'iss-pass.json',
            'people': 'astros.json'
            }
        self.default_coordinates = {
            'lon': str(45.0),
            'lat': str(-12.3)
        }

    def getResponse(self, _key, _input = ''):
        req = requests.get(self.base_url + self.simple_values[_key] + _input)
        return req.json()

    def printResponse(self, args):
        for key in args:
            if args[key] == 'pass':
                pass_inputs = ['lat', 'lon', 'alt', 'n']
                _input = '?'
                for pI in pass_inputs:
                    if ('-' + pI) in args:
                        if '=' in _input:
                            _input +=  '&'
                        _input += pI + '='
                        try:
                            _input += args['-' + pI]
                        except:
                            _input += self.default_coordinates[pI]

                if _input == '?':
                    _input += 'lat' + '=' + self.default_coordinates['lat']
                    _input += '&' +'lon' + '=' + self.default_coordinates['lon']
                elif 'lon' in _input and 'lat' not in _input:
                    _input += '&' + 'lat' + '=' + self.default_coordinates['lat']
                elif 'lon' not in _input and 'lat' in _input:
                    _input += '&' + 'lon' + '=' + self.default_coordinates['lon']

                resp = self.getResponse(args[key], _input)
                
                # Print Results
                print(f"The ISS will be overhead ({resp['request']['latitude']}, {resp['request']['longitude']}) at {resp['request']['datetime']} and makes {resp['request']['passes']} at an altitude of {resp['request']['altitude']} meters:")
                print("Risetime\t\t\tDuration (seconds)")
                print("_"*60) #Silly Formatting
                for _ in resp['response']:
                    print(f"{_['risetime']}\t\t\t{_['duration']}")

            elif args[key] == 'loc':
                resp = self.getResponse(args[key])
                print(f"The ISS current location at {str(resp['timestamp'])} is ({str(resp['iss_position']['latitude'])}, {str(resp['iss_position']['longitude'])})")

            elif args[key] == 'people':
                resp = self.getResponse(args[key])
                print('craft\t\t\tname')
                print('_'*40) #Silly Formatting
                for person in resp['people']:
                    print(f"{person['craft']}\t\t\t{person['name']}")

def getopts(argv):
    """Collect command-line options in a dictionary"""
    opts = {}  # Empty dictionary to store key-value pairs.
    while argv:  # While there are arguments left to parse...
        if argv[0][0] == '-':  # Found a "-name value" pair.
            if argv[0] in opts: # Check if "-name" already exists in opts
                if not isinstance(opts[argv[0]], list): # If not a list, turn into a list
                    opts[argv[0]] = [opts[argv[0]]]

                opts[argv[0]].append(argv[1])
            else:
                opts[argv[0]] = argv[1] # Add key and value to the dictionary.
            # opts[argv[0]] = argv[1]  
        argv = argv[1:]  # Reduce the argument list by copying it starting from index 1.
    if ('-g' not in opts) == ('-get' not in opts):
        return False
    return opts

if __name__ == '__main__':
    from sys import argv
    try:
        myargs = getopts(argv)

        if isinstance(myargs, bool):
            print('Error: Command Line Input MUST have either "-g" or "-get"')
        else: 
            ally_financial_screening = AllyFinancial_Screening()

            ally_financial_screening.printResponse(myargs)

    except IndexError as error:
        # Output expected IndexErrors.
        print('Data not provided after "-" input sequence')
        print(f'IndexError: {error}')
    except Exception as exception:
        # Output unexpected Exceptions.
        print(f'Exception: {exception}')
