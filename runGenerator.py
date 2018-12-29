#!/usr/bin/python3

import vxi11
import sys

generator = vxi11.Instrument(sys.argv[1])
print(generator.ask(sys.argv[2]))
