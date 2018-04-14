<?php

use Faker\Factory;
use Illuminate\Support\Str;

if (! function_exists('faker')) {
	function faker ($locale = null) {
		return Factory::create($locale ?? Factory::DEFAULT_LOCALE);
	}
}

function toBytes($value)
{
	if (func_num_args() === 2) {
		list ($value, $metric) = func_get_args();
	} else {
		$value = substr($value, 0, -2);
		$metric = substr($value, -2);
	}

    switch(Str::upper($metric)){
        case "KB":
            return $value*1024;
        case "MB":
            return $value*pow(1024,2);
        case "GB":
            return $value*pow(1024,3);
        case "TB":
            return $value*pow(1024,4);
        case "PB":
            return $value*pow(1024,5);
        default:
            return $value;
    }
}
