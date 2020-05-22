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
# argument 4 nombre de decimal pour lux evolution future

import time
import board
import busio
import adafruit_tsl2561
import sys
 
# lecture des arguments passés à la commande pour initialisé nos variables
GAIN = int( sys.argv[1] )
integration_time = int( sys.argv[2] )
interest = int( sys.argv[3] )

# Initialisation du bus I2C
i2c = busio.I2C(board.SCL, board.SDA)
 
# Creation de l'instance TSL2561 dans le bus I2C
tsl = adafruit_tsl2561.TSL2561(i2c)
 
# activation du capteur de lumière
tsl.enabled = True
time.sleep(1) #pause pour attendre l'initialisation physique du capteur
 
# init gain 0=1x, 1=16x
tsl.gain = GAIN
 
# int integration time (0=13.7ms, 1=101ms, 2=402ms, or 3=manual)
tsl.integration_time = integration_time
 
# lecture individuel des données
broadband = tsl.broadband
infrared = tsl.infrared
lux = tsl.lux

if interest == 1:
    print("{:0.2f}".format(lux))
if interest == 2:
    print("{}".format(broadband))
if interest == 3:
    print("{}".format(infrared))

# désactivation du capteur de luminsité pour economie d'energie
tsl.enabled = False