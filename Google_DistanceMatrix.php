<?php

class Google_DistanceMatrix{

	// Send information to Google
	static function fetch($origin_latitude,$origin_longitude, $destination_latitude,$destination_longitude){
		
		try{
		
			if(empty($origin_latitude) || empty($origin_longitude) || empty($destination_latitude) || empty($destination_longitude)) throw new TL_Error('Invalid co-ordinates');
			
			$apiURL = sprintf('https://maps.googleapis.com/maps/api/distancematrix/json?key=%s&origins=%f,%f&destinations=%f,%f&mode=driving&sensor=false&units=metric',CONST_GOOGLE_API_SERVER_KEY,$origin_latitude,$origin_longitude, $destination_latitude,$destination_longitude);
		
			// Create cURL
			$ch = curl_init();
				
			curl_setopt($ch,CURLOPT_URL,$apiURL);

			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
			
			curl_setopt($ch, CURLOPT_HEADER, 0);
			  
			// Execute the post
			$result = curl_exec($ch);
		
			// Close the connection
			curl_close($ch);
		
			// Return the result
			$ret = json_decode($result,true);
		
			if(!is_array($ret)) throw new TL_Error('In valid data');
			
			if($ret['status'] != 'OK') throw new TL_Error('In valid data');
			
			$arr_return = array();
			
			$arr_return['destination_addresses'] = $ret['destination_addresses'][0];
			
			$arr_return['origin_addresses'] = $ret['origin_addresses'][0];
			
			$ctr_option = 0 ;
			
			if(isset($ret['rows'])){
				
				foreach($ret['rows'] as $row){
					
					if(isset($row['elements'])){
						
						foreach($row['elements'] as $distancerow){
					
							$arr_return['ways'][$ctr_option]['distance'] =  $distancerow['distance']['text'];
							$arr_return['ways'][$ctr_option]['distance_in_meter'] =  $distancerow['distance']['value'];
							
							$arr_return['ways'][$ctr_option]['duration'] =  $distancerow['duration']['text'];
							$arr_return['ways'][$ctr_option]['duration_in_sec'] =  $distancerow['duration']['value'];							
							
							$ctr_option++;
						}
					}
				}
			}
			
			return $arr_return;
			
		}catch(TL_Error $e){
			
			return false;
			
		}
			
			
	}   	
	
	
}
