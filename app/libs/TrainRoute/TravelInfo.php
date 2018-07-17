<?php

namespace Libs\TrainRoute;

class TravelInfo {
	function __construct($from, $to, $day, $month) {
		$this->from = $from;
		$this->to = $to;
		$this->day = $day;
		$this->month = $month;
	}
}