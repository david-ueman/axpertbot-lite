<?php

/**
 * This scripts executes luzparaeleko_minimal_query.py and POST the results.
 *
 * This script executes luzparaeleko_minimal_query.py, which queries the local
 * Axpert Inverter through USB. Then, this script POSTs the data to another
 * SERVER so that it can be used.
 *
 * PHP version 7
 *
 * LICENSE: Copyright (C) <2020> David Ortiz itcoop@encamino.es
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @category   CategoryName
 * @package    PackageName
 * @author     David Ortiz <itcoop@encamino.es>
 * @copyright  2020 ITCooP
 * @license    https://www.gnu.org/licenses
 */

    /*
    if (isset($argv[1])){
        $devSerialNumberId = $argv[1];
    } else {
        throw new Exception('Expected device serial number ID.');
    }
    */
    if (isset($argv[1]))
        $devSerialNumberId = $argv[1];
    else
        $devSerialNumberId = "";

	//Executing the script tha will exploit all the data
	$reading = shell_exec("python3 " . dirname(__FILE__) . "/inverter_data_read.py " . $devSerialNumberId); 

	//Formatting the data
	$reading_values = explode(" ", $reading);

	$data = array(
        'Inverter serial number' => $reading_values[0],
		'AC output voltage' => $reading_values[2],
		'AC output frequency' => $reading_values[3],
		'AC output apparent power' => $reading_values[4],
		'AC output active power' => $reading_values[5],
		'Output load percent' => $reading_values[6],
		'BUS voltage' => $reading_values[7],
		'Battery voltage' => $reading_values[8],
		'Battery charging current' => $reading_values[9],
		'Battery capacity' => $reading_values[10],
		'Inverter heat sink temperature' => $reading_values[11]
	);

	//Creating the connection to the server to which the data will be POSTED
	$url = 'scripts.eslaeko.net/luzparaeleko/store_data_reading.php';
	$ch = curl_init($url);

	//Formatting the data string
	$postString = http_build_query($data, '', '&');

	//Setting our options
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	//Get the response
	$response = curl_exec($ch);
	curl_close($ch);

	print($response);
?>

