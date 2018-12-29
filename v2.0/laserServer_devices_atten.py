
# System Imports
import sys
import serial

# Local Imports
import laserTools          as Tools
import laserServer_message as Message

# ===============================================================================
# Attenuator functions ==========================================================
# ===============================================================================

def start():
    ser          = serial.Serial()
    ser.port     = "/dev/serial/by-path/platform-3f980000.usb-usb-0:1.5:1.0-port0"
    ser.baudrate = 9600
    ser.bytesize = serial.EIGHTBITS
    ser.parity   = serial.PARITY_NONE
    ser.stopbits = serial.STOPBITS_ONE
    ser.timeout  = 0.1
    ser.rtscts   = False
    ser.dsrdtr   = False
    ser.open()
    return ser

def queue_handler(device, queue, trigger, end, db):
    trigger.wait()
    while not end.is_set():
        while (len(queue)>0):
            message = queue[0]
            response = run_command(device, message)
            Message.parse(response, db)
            Tools.print_interaction(message, response)
            queue.pop(0)
        trigger.clear()
        trigger.wait()

def run_command(device, command):
    device.write(str.encode(command + "\r"))
    response = device.read(1024)
    response = response.decode("utf-8")
    return response