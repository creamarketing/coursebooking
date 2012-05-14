<?php

/**
 * Useful helper functions
 *
 * @author andreas
 */
class CourseHelper {
	
	public static function date_DDMMYYYY_to_YYYYDDMM($inDate, $output_timestamp = false) {		
		$explodedInDate = explode(' ', $inDate);
		if (count($explodedInDate) != 2) {
			// Missing time
			$explodedInDate = explode(' ', ($inDate . ' 00:00:00'));
			if (count($explodedInDate) != 2) 
				throw new Exception('Invalid format on input dat, expected DD.MM.YYYY HH:mm:ss.');
		}
		
		$explodedDate = explode('.', $explodedInDate[0]);
		$explodedTime = explode(':', $explodedInDate[1]);
		
		if (count($explodedTime) < 2 || count($explodedDate) < 3) 
			throw new Exception('Invalid format on input dat, expected DD.MM.YYYY HH:mm:ss.');
		elseif (count($explodedTime) == 2) // seconds are optional
			$explodedTime[2] = 0;
		
		$outDate = array();
		
		$outDate['H'] = $explodedTime[0];	// hour
		$outDate['m'] = $explodedTime[1];	// minute
		$outDate['s'] = $explodedTime[2];	// second
		
		$outDate['D'] = $explodedDate[0];	// day
		$outDate['M'] = $explodedDate[1];	// month
		$outDate['Y'] = $explodedDate[2];	// year
		
		if ($output_timestamp == true)
			return mktime($outDate['H'], $outDate['m'], $outDate['s'], $outDate['M'], $outDate['D'], $outDate['Y']);
		
		return ($outDate['Y'] . '.' . 
				$outDate['M'] . '.' . 
				$outDate['D'] . ' ' .
				$outDate['H'] . ':' .
				$outDate['m'] . ':' .
				$outDate['s']);
	}
}

?>
