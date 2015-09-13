<?php

namespace Reservation\Common\Util;

class ConvertNumberToWord {

	var $word;

	function calculateTenth($twoDigitData) {
		$firstDigit = floor($twoDigitData / 10);
		$secondDigit = $twoDigitData % 10;

		switch ($firstDigit) {
		case 0:
			$firstString = "";
			break;

		case 1:
			switch ($secondDigit) {
			case 0:
				return "ten";
				break;

			case 1:
				return "eleven";
				break;

			case 2:
				return "twelve";
				break;

			case 3:
				return "thirteen";
				break;

			case 4:
				return "fourteen";
				break;

			case 5:
				return "fifteen";
				break;

			case 6:
				return "sixteen";
				break;

			case 7:
				return "seventeen";
				break;

			case 8:
				return "eighteen";
				break;

			case 9:
				return "nineteen";
				break;

			default:
				return "Error";

			}
			break;

		case 2:
			$firstString = "twenty";
			break;

		case 3:
			$firstString = "thirty";
			break;

		case 4:
			$firstString = "forty";
			break;

		case 5:
			$firstString = "fifty";
			break;

		case 6:
			$firstString = "sixty";
			break;

		case 7:
			$firstString = "seventy";
			break;

		case 8:
			$firstString = "eighty";
			break;

		case 9:
			$firstString = "ninety";
			break;

		default:
			return "Error";
		}

		switch ($secondDigit) {
		case 0:
			$secondString = "";
			break;

		case 1:
			$secondString = "one";
			break;
		case 2:
			$secondString = "two";
			break;

		case 3:
			$secondString = "three";
			break;

		case 4:
			$secondString = "four";
			break;

		case 5:
			$secondString = "five";
			break;

		case 6:
			$secondString = "six";
			break;

		case 7:
			$secondString = "seven";
			break;

		case 8:
			$secondString = "eight";
			break;

		case 9:
			$secondString = "nine";
			break;

		default:
			return "Error";
		}

		return $firstString . " " . $secondString;

	}

	function calculateLastSeven($num) {
		$length = strlen($num);
		if ($length > 5) {
			$tenth = substr($num, -2, 2);
			$hundred = substr($num, -3, 1);

			if ($hundred == 0)
				$hundredString = "";
			else
				$hundredString = " hundred ";

			$thousand = substr($num, -5, 2);

			if ($thousand == 0)
				$thousandString = "";
			else
				$thousandString = " thousand ";

			if ($length == 6)
				$lakh = substr($num, -6, 1);
			else
				$lakh = substr($num, -7, 2);

			if ($lakh == 0)
				$lakhString = "";
			else
				$lakhString = " lakh ";

			return $this->calculateTenth($lakh) . $lakhString
					. $this->calculateTenth($thousand) . $thousandString
					. $this->calculateTenth($hundred) . $hundredString
					. $this->calculateTenth($tenth);
		} else if ($length < 6 && $length > 3) {
			$tenth = substr($num, -2, 2);
			$hundred = substr($num, -3, 1);

			if ($hundred == 0)
				$hundredString = "";
			else
				$hundredString = " hundred ";

			$thousand = substr($num, -5, 2);

			if ($length == 4)
				$thousand = substr($num, -4, 1);
			else
				$thousand = substr($num, -5, 2);

			if ($thousand == 0)
				$thousandString = "";
			else
				$thousandString = " thousand ";

			return $this->calculateTenth($thousand) . $thousandString
					. $this->calculateTenth($hundred) . $hundredString
					. $this->calculateTenth($tenth);
		} else if ($length < 4 && $length > 2) {
			$tenth = substr($num, -2, 2);
			$hundred = substr($num, -3, 1);

			if ($hundred == 0)
				$hundredString = "";
			else
				$hundredString = " hundred ";

			return $this->calculateTenth($hundred) . $hundredString
					. $this->calculateTenth($tenth);
		} else if ($length < 3) {
			return $this->calculateTenth($num);
		}
 else {
			return "morethan7";
		}
	}

	function convert($string) {
		$totalLength = strlen($string);
		$startString = substr($string, 0, $totalLength % 7);
		$converted = $this->calculateLastSeven($startString);

		$start = $totalLength % 7;
		//	$i = 0;

		while ($part = substr($string, $start, 7)) {
			$croreString = ($startString != 0) ? ' crore ' : '';
			// 		if($startString != 0)
			// 			$croreString = " crore ";
			$converted .= $croreString . $this->calculateLastSeven($part);
			$start += 7;
		}

		return $converted;
	}

// 	function numberToWord($string) {
// 		$narr = explode(".", $string);
// 		$this->word = ucwords($this->convert($narr[0]));
// 		if (isset($narr[1]) and $narr[1] != "") {
// 			$this->decimal = $this->decimalConvert($narr[1]);
// 			$this->word = $this->word . " " . $this->decimal;
// 		}
// 		return $this->word;
// 	}

	function decimalConvert($decimalval) {
		//return " And ".substr($decimalval,0,2)."/100";
		$ss = number_format('.' . $decimalval, 2, '.', '');
		$narr = explode('.', $ss);
		$n = $narr[1];
		return " And " . $n . "/100";
	}
	
	
	function numberToWord($number) {
	
		$hyphen      = '-';
		$conjunction = ' and ';
		$separator   = ', ';
		$negative    = 'negative ';
		$decimal     = ' point ';
		$dictionary  = array(
				0                   => 'zero',
				1                   => 'one',
				2                   => 'two',
				3                   => 'three',
				4                   => 'four',
				5                   => 'five',
				6                   => 'six',
				7                   => 'seven',
				8                   => 'eight',
				9                   => 'nine',
				10                  => 'ten',
				11                  => 'eleven',
				12                  => 'twelve',
				13                  => 'thirteen',
				14                  => 'fourteen',
				15                  => 'fifteen',
				16                  => 'sixteen',
				17                  => 'seventeen',
				18                  => 'eighteen',
				19                  => 'nineteen',
				20                  => 'twenty',
				30                  => 'thirty',
				40                  => 'fourty',
				50                  => 'fifty',
				60                  => 'sixty',
				70                  => 'seventy',
				80                  => 'eighty',
				90                  => 'ninety',
				100                 => 'hundred',
				1000                => 'thousand',
				1000000             => 'million',
				1000000000          => 'billion',
				1000000000000       => 'trillion',
				1000000000000000    => 'quadrillion',
				1000000000000000000 => 'quintillion'
		);
	
		if (!is_numeric($number)) {
			return false;
		}
	
		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
					'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
					E_USER_WARNING
			);
			return false;
		}
	
		if ($number < 0) {
			return $negative . $this->numberToWord(abs($number));
		}
	
		$string = $fraction = null;
	
		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}
	
		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = $number / 100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				if ($remainder) {
					$string .= $conjunction . $this->numberToWord($remainder);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number % $baseUnit;
				$string = $this->numberToWord($numBaseUnits) . ' ' . $dictionary[$baseUnit];
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= $this->numberToWord($remainder);
				}
				break;
		}
	
		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $this->decimalConvert($fraction);
		}
	
		return $string;
	}
}
?>