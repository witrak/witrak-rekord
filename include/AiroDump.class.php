<?php
class AiroDump {

	public function ParseLog($data){
		$data = str_replace("\r", "", $data); // FUCK WINDOWS NEWLINE !!!

		$data_new = $data; 

		/* Do some cleanup */

		do{
			$data = $data_new; 

			$data_new = str_replace("  ", " ", $data); // Remove duplicate comas
		}while($data != $data_new);

		$data = explode("\n\n", $data); // Explode into blocks

		for($i = 0; $i < count($data); $i++){
			$data[$i] = trim($data[$i], "\n"); // Remove newline from first argument of block

			$data[$i] = str_replace(", ", ",", $data[$i]); // Remove space from begining of value
		}

		if($data[count($data) - 1] == ""){
			unset($data[count($data) - 1]); //Remove empty block
		}

		/* Cleanup done */

		for($i = 0; $i < count($data); $i++){
			$new = Array();
			$original = $data[$i];

			$original = explode("\n", $original);

			foreach($original as $line){
				$new[] = str_getcsv($line);
			}
			
			$data[$i] = $new;
		}

		/* Group probes */

		for($j = 0; $j < count($data[1]); $j++){
			if(count($data[1][$j]) > 7){

				$new = Array();

				$count = count($data[1][$j]);

				for($i = 6; $i < $count; $i++){
					$new[] = $data[1][$j][$i];
					unset($data[1][$j][$i]);
				}

				$data[1][$j][6] = $new;
			}
			else if($data[1][$j][6] != ""){
				$data[1][$j][6] = Array($data[1][$j][6]);
			}
			else
			{
				$data[1][$j][6] = Array();
			}
		}
		return $data;
	}
}

