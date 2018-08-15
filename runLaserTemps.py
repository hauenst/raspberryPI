#!/usr/bin/python

# Libraries
import csv
import time
import math
import numpy as np
import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt

# Reading data
with open('/var/www/html/runLaserTemps.csv') as csvfile:
    data = list(csv.reader(csvfile))

# Global parameters
height = 2.44615;

# Processing data
curr = time.time()
times = np.array([(int(row[0])-curr)/3600 for row in data])
temp1 = np.array([float(row[1]) for row in data])
temp2 = np.array([float(row[2]) for row in data])
temp3 = np.array([float(row[3]) for row in data])
temp4 = np.array([float(row[4]) for row in data])
temp1_01 = temp1[times >= -01]
temp2_01 = temp2[times >= -01]
temp3_01 = temp3[times >= -01]
temp4_01 = temp4[times >= -01]
times_01 = times[times >= -01]
temp1_12 = temp1[times >= -12]
temp2_12 = temp2[times >= -12]
temp3_12 = temp3[times >= -12]
temp4_12 = temp4[times >= -12]
times_12 = times[times >= -12]

# Plotting diode data (last hour)
plt.figure(num=None, figsize=(4.88, height), dpi=65)
plt.plot(times_01, temp1_01, 'r', label="Diode")
xmin = -0.25*int(math.ceil(-min(times_01)/0.25))
if (xmin == 0):
    xmin = -0.25
plt.xlim((xmin, max(times_01)))
plt.xlabel("Time [hours]")
#plt.ylabel("Temp [$^\circ$C]")
plt.subplots_adjust(left=0.125, right=0.97, top=1.0, bottom=0.2)
#plt.legend(loc=9, columnspacing=1.2, bbox_to_anchor=(0.44, -0.23), ncol=4)
plt.show()
plt.savefig('/var/www/html/images/laserTemps_dio_m01.png')

# Plotting crystal data (last hour)
plt.figure(num=None, figsize=(4.88, height), dpi=65)
plt.plot(times_01, temp2_01, 'b', label="Crystal")
xmin = -0.25*int(math.ceil(-min(times_01)/0.25))
if (xmin == 0):
    xmin = -0.25
plt.xlim((xmin, max(times_01)))
plt.xlabel("Time [hours]")
#plt.ylabel("Temp [$^\circ$C]")
plt.subplots_adjust(left=0.125, right=0.97, top=1.0, bottom=0.2)
#plt.legend(loc=9, columnspacing=1.2, bbox_to_anchor=(0.44, -0.23), ncol=4)
plt.show()
plt.savefig('/var/www/html/images/laserTemps_cry_m01.png')

# Plotting sinks data (last hour)
plt.figure(num=None, figsize=(4.88, height), dpi=65)
plt.plot(times_01, temp3_01, label="Electronic Sink")
plt.plot(times_01, temp4_01, label="Heat Sink")
xmin = -0.25*int(math.ceil(-min(times_01)/0.25))
if (xmin == 0):
    xmin = -0.25
plt.xlim((xmin, max(times_01)))
plt.xlabel("Time [hours]")
plt.ylabel("Temp [$^\circ$C]")
plt.subplots_adjust(left=0.125, right=0.97, top=1.0, bottom=0.3)
plt.legend(loc=9, columnspacing=1.2, bbox_to_anchor=(0.44, -0.23), ncol=4)
plt.show()
plt.savefig('/var/www/html/images/laserTemps_sin_m01.png')

# Plotting sinks data (last 12 hours)
plt.figure(num=None, figsize=(4.88, height), dpi=65)
plt.plot(times_12, temp3_12, label="Electronic Sink")
plt.plot(times_12, temp4_12, label="Heat Sink")
xmin = -1*int(math.ceil(-min(times_12)))
if (xmin == 0):
    xmin = -1
plt.xlim((xmin, max(times_12)))
plt.xlabel("Time [hours]")
plt.ylabel("Temp [$^\circ$C]")
plt.subplots_adjust(left=0.125, right=0.97, top=1.0, bottom=0.3)
plt.legend(loc=9, columnspacing=1.2, bbox_to_anchor=(0.44, -0.23), ncol=4)
plt.show()
plt.savefig('/var/www/html/images/laserTemps_sin_m12.png')

