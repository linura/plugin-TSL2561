#!/usr/bin/env python3
# -*- coding: utf-8 -*-
# 
###################################################################################################
#
# This file is part of Jeedom.
#
# Jeedom is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# Jeedom is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
#
###################################################################################################
# 
# LEBANSAIS C 
# 20/05/2020

# argument 1 type de sonde gain -> 0=1x, 1=16x
# argument 2 integration time (0=13.7ms, 1=101ms, 2=402ms, or 3=manual)
# argument 3 lecture souhaitée 1 - lux 2 broadband 3 infrared
# argument 4 nombre de decimal pour lux

import time
import board
import busio
import adafruit_tsl2561
import sys
 

GAIN = int( sys.argv[1] )
integration_time = int( sys.argv[2] )
interest = int( sys.argv[3] )
# Create the I2C bus
i2c = busio.I2C(board.SCL, board.SDA)
 
# Create the TSL2561 instance, passing in the I2C bus
tsl = adafruit_tsl2561.TSL2561(i2c)
 
# Enable the light sensor
tsl.enabled = True
time.sleep(1)
 
# Set gain 0=1x, 1=16x
tsl.gain = GAIN
 
# Set integration time (0=13.7ms, 1=101ms, 2=402ms, or 3=manual)
tsl.integration_time = integration_time
 
# Get raw (luminosity) readings individually
broadband = tsl.broadband
infrared = tsl.infrared
lux = tsl.lux
nb_decimal = int( sys.argv[4] )
# Print results
#if interest == 1:
#    print("{:06." + nb_decimal + "f}".format(lux))
if interest == 1:
    print("{:0.2f}".format(lux))
if interest == 2:
    print("{}".format(broadband))
if interest == 3:
    print("{}".format(infrared))

# Disble the light sensor (to save power)
tsl.enabled = False