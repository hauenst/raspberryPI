#!/usr/bin/python3

import serial
import sys
import datetime

att          = serial.Serial()
att.port     = "/dev/serial/by-path/platform-3f980000.usb-usb-0:1.4:1.0-port0"
att.baudrate = 19200
att.bytesize = serial.EIGHTBITS
att.parity   = serial.PARITY_NONE
att.stopbits = serial.STOPBITS_ONE
att.timeout  = 0.1
att.rtscts   = False
att.dsrdtr   = False

att.open()

att.write(str.encode(sys.argv[1] + "\r"))
response = att.read(1024)
response = response.decode("utf-8").split('\n', 1)[0]
print(response)

att.close()

log = open("runLaser.log", "a+")
log.write(str(datetime.datetime.now().strftime("%a %b %d %H:%M:%S UTC %Y")) + ": \"" + sys.argv[1] + "\"\n")
log.close
