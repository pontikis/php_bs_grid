<?php

function is_positive_integer($str) {
	return (is_numeric($str) && $str > 0 && $str == round($str));
}

function trim_integer($str) {
	return ltrim(trim($str), "0");
}

function sanitize_int($param, $default_value, $arr_to_belong, $arr_has_key) {
	$result = $default_value;
	if(isset($_POST[$param])) {
		if(is_positive_integer($_POST[$param])) {
			$tmp = trim_integer($_POST[$param]);
			$result = (int)$tmp;

			if($arr_to_belong) {
				if(!in_array($tmp, $arr_to_belong)) {
					$result = $default_value;
				}
			}

			if($arr_has_key) {
				if(!array_key_exists($tmp, $arr_has_key)) {
					$result = $default_value;
				}
			}

		}
	}
	return $result;
}

/**
 * Check if a string (of any locale) is a valid date(time)
 *
 * DateTime::createFromFormat requires PHP >= 5.3
 *
 * @param string $str_dt
 * @param string $str_dateformat
 * @param string $str_timezone (If timezone is invalid, php will throw an exception)
 * @param array $intl international options
 * @return bool|int
 */
function isValidDateTimeString($str_dt, $str_dateformat, $str_timezone = null, $intl = array()) {
	if(extension_loaded('intl') && $intl) {
		$formatter = new IntlDateFormatter($intl['locale'], $intl['datetype'], $intl['timetype'], $intl['timezone'], $intl['calendar'], $intl['pattern']);
		return $formatter->parse($str_dt);
	} else {
		if($str_timezone) {
			$date = DateTime::createFromFormat($str_dateformat, $str_dt, new DateTimeZone($str_timezone));
		} else {
			$date = DateTime::createFromFormat($str_dateformat, $str_dt);
		}
		$a_err = DateTime::getLastErrors(); // compatibility with php 5.3
		return $date && $a_err['warning_count'] == 0 && $a_err['error_count'] == 0;
	}
}


/**
 * Check if current dateformat requires localization
 *
 *
 * @param null $datefomat
 * @return bool
 */
function dateformat_i18n($dateformat = null) {
	global $conf;
	$df = isset($dateformat) ? $dateformat : $_SESSION['user_dateformat'];
	return array_key_exists('php_datetime_intl', $conf['dt']['dateformat'][$df]);
}



/**
 * Convert a date(time) string or timestamp to another format or timezone or locale
 *
 * DateTime::createFromFormat requires PHP >= 5.3
 * IntlDateFormatter requires PHP 5 >= 5.3.0, PECL intl >= 1.0.0
 * Optional: php intl extension http://php.net/intl
 *
 * 'dateformat' documented: http://www.php.net/manual/en/datetime.createfromformat.php
 * 'intl pattern' documented: http://userguide.icu-project.org/formatparse/datetime
 *
 * intl options example
 * $intl = array(
 *     'locale' => 'en_US',
 *     'datetype' => 0, // FULL
 *     'timetype' => 0, // FULL
 *     'timezone' => null,
 *     'calendar' => 1, // GREGORIAN
 *     'pattern' => 'EEEE, d MMMM yyyy HH:mm:ss'
 * )
 *
 * @param string|int $dt datetime string or timestamp integer
 * @param string $tz1 source timezone
 * @param string $df1 source dateformat
 * @param string $tz2 destination timezone
 * @param string $df2 destination dateformat
 * @param array $intl international options
 * @param int $error_level
 * @return string
 */
function date_convert($dt, $tz1, $df1, $tz2, $df2, $intl = array(), $error_level = E_USER_ERROR) {

	$is_timestamp = (gettype($dt) == 'integer');

	if(!$is_timestamp && !$dt) {
		return '';
	}
	if(!in_array($tz1, timezone_identifiers_list())) { // check source timezone
		trigger_error(__FUNCTION__ . ': Invalid source timezone ' . $tz1, $error_level);
		return false;
	}
	if(!in_array($tz2, timezone_identifiers_list())) { // check destination timezone
		trigger_error(__FUNCTION__ . ': Invalid destination timezone ' . $tz2, $error_level);
		return false;
	}

	// create DateTime object
	if($is_timestamp) { // timestamp given
		$d = new DateTime('now', new DateTimeZone($tz1));
		$d->setTimestamp($dt);
	} else {
		$d = DateTime::createFromFormat($df1, $dt, new DateTimeZone($tz1));
	}
	// check source datetime
	$a_err = DateTime::getLastErrors(); // compatibility with php 5.3
	if($d && $a_err['warning_count'] == 0 && $a_err['error_count'] == 0) {
		if($tz2 && $tz1 !== $tz2) {
			// convert timezone
			$d->setTimeZone(new DateTimeZone($tz2));
		}
		// convert dateformat
		if(extension_loaded('intl') && $intl) {
			try {
				$formatter = new IntlDateFormatter($intl['locale'], $intl['datetype'], $intl['timetype'], $intl['timezone'], (int)$intl['calendar'], $intl['pattern']);
				$res = $formatter->format($d);
			} catch(Exception $e) {
				trigger_error(__FUNCTION__ . ': Invalid intl options ' . $e->getMessage(), $error_level);
				return false;
			}
		} else {
			$res = $d->format($df2);
		}

	} else {
		trigger_error(__FUNCTION__ . ': Invalid source datetime ' . $dt . ', ' . $df1 . ' ' . print_r($a_err, true), $error_level);
		return false;
	}
	return $res;
}



function decode_usr_datetime_short($dt) {
	global $conf;
	$tz1 = C_SERVER_TIMEZONE;
	$df1 = C_SERVER_DATEFORMAT;
	$tz2 = $_SESSION['user_timezone'];
	$df2 = $conf['dt']['dateformat'][$_SESSION['user_dateformat']]['php_datetime_short'];

	$intl = array();
	if(extension_loaded('intl') && array_key_exists('php_datetime_short_intl', $conf['dt']['dateformat'][$_SESSION['user_dateformat']])) {
		$intl = array(
			'locale' => $_SESSION['locale'],
			'datetype' => IntlDateFormatter::FULL,
			'timetype' => IntlDateFormatter::FULL,
			'timezone' => $_SESSION['user_timezone'],
			'calendar' => $_SESSION['user_calendar'],
			'pattern' => $conf['dt']['dateformat'][$_SESSION['user_dateformat']]['php_datetime_short_intl']
		);
	}
	return date_convert($dt, $tz1, $df1, $tz2, $df2, $intl);
}


function encode_usr_datetime_short($dt) {
	global $conf;
	$tz1 = $_SESSION['user_timezone'];
	$df1 = $conf['dt']['dateformat'][$_SESSION['user_dateformat']]['php_datetime_short'];
	$tz2 = C_SERVER_TIMEZONE;
	$df2 = C_SERVER_DATEFORMAT;
	return date_convert($dt, $tz1, $df1, $tz2, $df2);
}


function ci_ai($str) {
	return removeAccents(mb_strtolower($str));
}



/**
 * Replace accented characters with non accented
 *
 * @param $str
 * @return mixed
 * @link http://myshadowself.com/coding/php-function-to-convert-accented-characters-to-their-non-accented-equivalant/
 */
function removeAccents($str) {
	$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
	$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
	return str_replace($a, $b, $str);
}
