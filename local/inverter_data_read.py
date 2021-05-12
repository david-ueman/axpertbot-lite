import inverter_utilities
import sys
import string

#We expect for the Serial ID number of the inverter to query.
listOfArguments = sys.argv

#if a serial number is specified in the command line, 
#we search for that specific device

if len(listOfArguments) > 1 :
    argDeviceSerialNumber = listOfArguments[1]
else :
    argDeviceSerialNumber = None

inverter = inverter_utilities.getDevice(0x0665, 0x5161, argDeviceSerialNumber)

output = ""
if inverter :
    output = inverter_utilities.getDeviceValues(inverter)
    output = str.lstrip(output)
    output = str.rstrip(output)

print (output)
