#!/usr/bin/python

# Libraries
import csv
import time
import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt

# Reading data
with open('/var/www/html/runLaserTemps.csv') as csvfile:
    data = list(csv.reader(csvfile))

# Processing data
curr = time.time()
times = [(int(row[0])-curr)/3600 for row in data]
temp1 = [float(row[1]) for row in data]
temp2 = [float(row[2]) for row in data]
temp3 = [float(row[3]) for row in data]
temp4 = [float(row[4]) for row in data]

# Plotting data
plt.figure(num=None, figsize=(4.88, 3), dpi=65)
plt.plot(times, temp1, label="Diode")
plt.plot(times, temp2, label="Crystal")
plt.plot(times, temp3, label="Electronic sink")
plt.plot(times, temp4, label="Heat sink")
xmin = int(0.5*round(min(times)/0.5))
if (xmin == 0):
    xmin = -0.5
plt.xlim((xmin, 0))
plt.xlabel("Time [hours]")
plt.ylabel("Temp [$^\circ C$]")
plt.subplots_adjust(left=0.15, right=0.97, top=1.0, bottom=0.3)
plt.legend(loc=9, columnspacing=1.2, bbox_to_anchor=(0.44, -0.23), ncol=4)
plt.show()
plt.savefig('/var/www/html/images/laserTemps.png')
