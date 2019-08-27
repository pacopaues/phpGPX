<?php
/**
 * PauseCalculator.php
 *
 * @author Francesc Pau
 * @since  08/2019
 */

namespace phpGPX\Helpers;

use phpGPX\Helpers\GeoHelper;
use phpGPX\Models\Point;
use phpGPX\phpGPX;

class PauseCalculator
{
	/**
	 * @param Point[]|array $points
	 * @return int
	 */
	public static function calculate(array $points)
	{
		$pause = 0;

		$pointCount = count($points);

		$lastConsideredPoint = null;

		for ($p = 0; $p < $pointCount; $p++) {
			$curPoint = $points[$p];
			
			// skip the first point
			if ($p === 0) {
				$lastConsideredPoint = $curPoint;
				continue;
			}
			//Calculate the time and speed between point
			$duration = $curPoint->time->getTimestamp() - $lastConsideredPoint->time->getTimestamp();
			$speed = $curPoint->difference / $duration;
			//Check if paused
			if ($duration > 10 && $speed < 0.5){
				$pause += $duration;
			}

			$lastConsideredPoint = $curPoint;
		}
		
		return $pause;
	}
}
