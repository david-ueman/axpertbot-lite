# axpertbot-lite
A set of programs to read and post the general status parameters of an Axpert Inverter-Charger through USB

# About

Axpertbot-lite is a set of programs designed to run as cron jobs in a raspberry or any other computer to **query, read, and post the general status parameters and values of an Axpert Inverter-charger** (such as battery voltage,  output power) through a USB connection, so that this information can be available without the need to be insitu.

These set of programs, and particularly the connection method to the inverter through the USB, is based and inspired on the project [axpertbot](https://github.com/ciberflaite/axpertbot) created by [ciberflaite](https://github.com/ciberflaite).

# Installation

## Parts of the application

This application is divided in two parts:

**local** : The scripts that should be installed in the rapsberry connected to the inverter (or inverters) by USB. This raspberry should have internet connection so that the information can be posted to a server.

**serverside** : The scripts that should be hosted in the cloud and can receive the information posted by the raspberry.

## Prerequisites

* A linux environment with root privileges to store and run the _local part_ of the application
* A web server reachable by the raspberry with capabilities to run PHP scripts
* A database server to store the data sent by the raspberry

## Requirements in the Raspberry connected to the inverter(s)

* python3
* python3-usb
* python3-crc16

* php
* php-curl

# Requirements in the Server side

* php
* MySQL Server or alike

## Installation Steps

### serverside (cloud)

1. Put the scripts corresponding to the serverside in the webserver and make them reachables.

2. Create tables in the SQL Server running the script create_table.sql

3. Update the line in which the link to the database is created so that it corresponds to your database location, user and password.

    ```$link = mysqli_connect("url.mysql.db", "database-name", "database-password",```

### localside (Raspberry)

1. Get a running copy of a Linux environment. All development and tests of this application were made in Raspbian, so the following instructions are intented for Raspbian installations.

2. Download or clone the project into your linux environment.

3. Install PHP

    ```sudo apt-get install php```

4. Install needed PHP libraries

    ```sudo apt-get install php-curl```

3. Install Python3

    ```sudo apt-get install python3```

4. Install Pip, the recommended tool for installing Python packages

    ```sudo apt-get install python3-pip```

5. Install needed Python3 libraries with pip. **Notice** that you have to install the libraries using sudo, since the axpertbot-lite needs to run with root privileges to access the USB

    ```sudo pip3 install crc16```
    ```sudo pip3 install pyusb```

6. Go to the **local** folder and check that you can communicate with the inverter-charger by running the inverter_data_read.py specifying the serial number of the inverter-chager that you want to query. Replace the `92931712101193` with the serial number of your inverter.

    ```sudo python3 inverter_data_read.py 92931712101193```

    you should get a string similar to:

    ```92931712101193 00.0 230.0 49.9 0390 0343 007 398 49.60 000 074 0036 0000 058.6 49.36 00007 00110000 00 00 00000 010Aç```

7. Run the script that tries to post the information to the server

    ```sudo php inverter_data_send.php 92931712101193```

8. Check that the data was stored in the database by getting the information through the script that reads the data from the database

    ```wget -O /dev/stdout http://your-server-name/show_last_reading.php?network_id=92931712101193```


