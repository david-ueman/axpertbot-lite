#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import usb.core, usb.util, usb.control
import crc16
import time
import re
import sys

# COMMAND+CRC16
def getCommand(cmd):
    cmd = cmd.encode('utf-8')
    crc = crc16.crc16xmodem(cmd).to_bytes(2,'big')
    cmd = cmd+crc
    cmd = cmd+b'\r'
    while len(cmd)<8:
        cmd = cmd+b'\0'
    return cmd

# SEND COMMAND
def sendCommand(dev, cmd):
    dev.ctrl_transfer(0x21, 0x9, 0x200, 0, cmd)

# RESULT COMMAND
def getResult(dev, timeout=100):
    res=""
    i=0
    while '\r' not in res and i<20:
        try:
            res+="".join([chr(i) for i in dev.read(0x81, 8, timeout) if i!=0x00])
            #print(res)
        except usb.core.USBError as e:
            if e.errno == 110:
                pass
            else:
                raise
        i+=1
    return res

# REMOVE NON-NUMERICAL CHARACTERS FROM STRING
def getOnlyNumbers(string):
    return re.sub("[^0-9]", "", string)

def getDevice(vendorId, productId, serialNumberId = None):

    device = None
    devList = usb.core.find(find_all=True, 
                            idVendor=vendorId, 
                            idProduct=productId)

    # For each device that we find...
    for dev in devList:
        serialNumberOutput = getSerialNumber(dev)

        if serialNumberId is None:
            #if no serial number id was specified, we'll return the first device found
            device = dev
            break
        else:
            if serialNumberId == serialNumberOutput:
                #if serial number id was specified and 
                #it matches with the device, we'll return that device
                device = dev
                break

    return device

def getSerialNumber(dev):

    interface = 0

    if dev.is_kernel_driver_active(interface):
        dev.detach_kernel_driver(interface)
    dev.set_interface_altsetting(0,0)

    #We query for the serial number of the inverter.
    #Output of the 'QID' command is not always the 
    #full Serial Number. Probably our fault while reading; 
    #we resend the query until a coherent number appears. 
    serialNumberOutput = ""
    while len(serialNumberOutput) < 11:
        #output example: (92931712101193ÎÈ
        sendCommand(dev, getCommand("QID"))
        serialNumberOutput = str(getResult(dev))
    
    #Output of the 'QID' command is not always the same and 
    #contains characters not corresponding with a Serial Number;
    #probably our fault while reading, so we hardfix it.
    serialNumberOutput = getOnlyNumbers(serialNumberOutput)

    return serialNumberOutput

def getDeviceValues(dev):

    interface = 0

    if dev.is_kernel_driver_active(interface):
        dev.detach_kernel_driver(interface)
    dev.set_interface_altsetting(0,0)

    #We query for the inverter general status values
    sendCommand(dev, getCommand("QPIGS"))
    inverterValues = getResult(dev)
    #output example:
    #getResult() = "(000.0 00.0 229.9 50.0 0000 0000 000 342 49.53 000 063 0027 0000 000.0 00.00 00000 00010000 00 00 00000 010àN"

    #Output of the 'QPIGS' command is not always the same.
    #sometimes it starts with a "(", sometimes it doesn't,
    #probably our fault while reading; this is a hardfix
    if (inverterValues.startswith("(")):
        inverterValues = inverterValues.split(" ")
        del inverterValues[0]
    else:
        inverterValues = inverterValues.split(" ")

    #Adding the Serial Number of the device to the general status values
    inverterValues.insert(0, getSerialNumber(dev))

    #We format the output...
    res = ""
    for value in inverterValues:
        res = res + " " + value

    return res





