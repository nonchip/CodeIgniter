<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Open Software License version 3.0
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is
 * bundled with this package in the files license.txt / license.rst.  It is
 * also available through the world wide web at this URL:
 * http://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2012, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

/**
 * CodeIgniter Date Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/date_helper.html
 */

// ------------------------------------------------------------------------

if ( ! function_exists('now'))
{
	/**
	 * Get "now" time
	 *
	 * Returns time() based on the timezone parameter or on the "timezone"
	 * setting
	 *
	 * @param	string
	 * @return	int
	 */
	function now($timezone = NULL)
	{
		$CI			=& get_instance();

		if (is_null($timezone))
		{
			$timezone	= $CI->config->item('timezone');
		}

		$time			= time();
		if(strtolower($timezone) != 'local')
		{
			$local		= new DateTime(NULL, new DateTimeZone(date_default_timezone_get()));
			$now		= new DateTime(NULL, new DateTimeZone($timezone));
			$lcl_offset	= $local->getOffset();
			$tz_offset	= $now->getOffset();
			$offset		= $tz_offset - $lcl_offset;
			$time		= $time + $offset;
		}

		return $time;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('mdate'))
{
	/**
	 * Convert MySQL Style Datecodes
	 *
	 * This function is identical to PHPs date() function,
	 * except that it allows date codes to be formatted using
	 * the MySQL style, where each code letter is preceded
	 * with a percent sign:  %Y %m %d etc...
	 *
	 * The benefit of doing dates this way is that you don't
	 * have to worry about escaping your text letters that
	 * match the date codes.
	 *
	 * @param	string
	 * @param	int
	 * @return	int
	 */
	function mdate($datestr = '', $time = '')
	{
		if ($datestr === '')
		{
			return '';
		}

		$time = ($time === '') ? now() : $time;

		$datestr = str_replace(
			'%\\',
			'',
			preg_replace('/([a-z]+?){1}/i', '\\\\\\1', $datestr)
		);

		return date($datestr, $time);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('standard_date'))
{
	/**
	 * Standard Date
	 *
	 * Returns a date formatted according to the submitted standard.
	 *
	 * @param	string	the chosen format
	 * @param	int	Unix timestamp
	 * @return	string
	 */
	function standard_date($fmt = 'DATE_RFC822', $time = '')
	{
		$formats = array(
						'DATE_ATOM'		=>	'%Y-%m-%dT%H:%i:%s%O',
						'DATE_COOKIE'	=>	'%l, %d-%M-%y %H:%i:%s UTC',
						'DATE_ISO8601'	=>	'%Y-%m-%dT%H:%i:%s%O',
						'DATE_RFC822'	=>	'%D, %d %M %y %H:%i:%s %O',
						'DATE_RFC850'	=>	'%l, %d-%M-%y %H:%i:%s UTC',
						'DATE_RFC1036'	=>	'%D, %d %M %y %H:%i:%s %O',
						'DATE_RFC1123'	=>	'%D, %d %M %Y %H:%i:%s %O',
						'DATE_RFC2822'	=>	'%D, %d %M %Y %H:%i:%s %O',
						'DATE_RSS'		=>	'%D, %d %M %Y %H:%i:%s %O',
						'DATE_W3C'		=>	'%Y-%m-%dT%H:%i:%s%O'
						);

		if ( ! isset($formats[$fmt]))
		{
			return FALSE;
		}

		return mdate($formats[$fmt], $time);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('timespan'))
{
	/**
	 * Timespan
	 *
	 * Returns a span of seconds in this format:
	 *	10 days 14 hours 36 minutes 47 seconds
	 *
	 * @param	int	a number of seconds
	 * @param	int	Unix timestamp
	 * @param	int	a number of display units
	 * @return	string
	 */
	function timespan($seconds = 1, $time = '', $units = 7)
	{
		$CI =& get_instance();
		$CI->lang->load('date');

		if ( ! is_numeric($seconds))
		{
			$seconds = 1;
		}

		if ( ! is_numeric($time))
		{
			$time = time();
		}

		if ( ! is_numeric($units))
		{
			$units = 7;
		}

		$seconds = ($time <= $seconds) ? 1 : $time - $seconds;

		$str = array();
		$years = floor($seconds / 31557600);

		if ($years > 0)
		{
			$str[] = $years.' '.$CI->lang->line((($years	> 1) ? 'date_years' : 'date_year'));
		}

		$seconds -= $years * 31557600;
		$months = floor($seconds / 2629743);

		if (count($str) < $units && ($years > 0 OR $months > 0))
		{
			if ($months > 0)
			{
				$str[] = $months.' '.$CI->lang->line((($months	> 1) ? 'date_months' : 'date_month'));
			}

			$seconds -= $months * 2629743;
		}

		$weeks = floor($seconds / 604800);

		if (count($str) < $units && ($years > 0 OR $months > 0 OR $weeks > 0))
		{
			if ($weeks > 0)
			{
				$str[] = $weeks.' '.$CI->lang->line((($weeks	> 1) ? 'date_weeks' : 'date_week'));
			}

			$seconds -= $weeks * 604800;
		}

		$days = floor($seconds / 86400);

		if (count($str) < $units && ($months > 0 OR $weeks > 0 OR $days > 0))
		{
			if ($days > 0)
			{
				$str[] = $days.' '.$CI->lang->line((($days	> 1) ? 'date_days' : 'date_day'));
			}

			$seconds -= $days * 86400;
		}

		$hours = floor($seconds / 3600);

		if (count($str) < $units && ($days > 0 OR $hours > 0))
		{
			if ($hours > 0)
			{
				$str[] = $hours.' '.$CI->lang->line((($hours	> 1) ? 'date_hours' : 'date_hour'));
			}

			$seconds -= $hours * 3600;
		}

		$minutes = floor($seconds / 60);

		if (count($str) < $units && ($days > 0 OR $hours > 0 OR $minutes > 0))
		{
			if ($minutes > 0)
			{
				$str[] = $minutes.' '.$CI->lang->line((($minutes	> 1) ? 'date_minutes' : 'date_minute'));
			}

			$seconds -= $minutes * 60;
		}

		if (count($str) === 0)
		{
			$str[] = $seconds.' '.$CI->lang->line((($seconds	> 1) ? 'date_seconds' : 'date_second'));
		}

		return implode(', ', $str);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('days_in_month'))
{
	/**
	 * Number of days in a month
	 *
	 * Takes a month/year as input and returns the number of days
	 * for the given month/year. Takes leap years into consideration.
	 *
	 * @param	int	a numeric month
	 * @param	int	a numeric year
	 * @return	int
	 */
	function days_in_month($month = 0, $year = '')
	{
		if ($month < 1 OR $month > 12)
		{
			return 0;
		}

		if ( ! is_numeric($year) OR strlen($year) !== 4)
		{
			$year = date('Y');
		}

		if ($month == 2)
		{
			if ($year % 400 === 0 OR ($year % 4 === 0 && $year % 100 !== 0))
			{
				return 29;
			}
		}

		$days_in_month	= array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		return $days_in_month[$month - 1];
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('local_to_gmt'))
{
	/**
	 * Converts a local Unix timestamp to GMT
	 *
	 * @param	int	Unix timestamp
	 * @return	int
	 */
	function local_to_gmt($time = '')
	{
		if ($time === '')
		{
			$time = time();
		}

		return mktime(
			gmdate('H', $time),
			gmdate('i', $time),
			gmdate('s', $time),
			gmdate('m', $time),
			gmdate('d', $time),
			gmdate('Y', $time)
		);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('gmt_to_local'))
{
	/**
	 * Converts GMT time to a localized value
	 *
	 * Takes a Unix timestamp (in GMT) as input, and returns
	 * at the local value based on the timezone and DST setting
	 * submitted
	 *
	 * @param	int	Unix timestamp
	 * @param	string	timezone
	 * @param	bool	whether DST is active
	 * @return	int
	 */
	function gmt_to_local($time = '', $timezone = 'UTC', $dst = FALSE)
	{
		if ($time === '')
		{
			return now();
		}

		$time += timezones($timezone) * 3600;

		if ($dst === TRUE)
		{
			$time += 3600;
		}

		return $time;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('mysql_to_unix'))
{
	/**
	 * Converts a MySQL Timestamp to Unix
	 *
	 * @param	int	Unix timestamp
	 * @return	int
	 */
	function mysql_to_unix($time = '')
	{
		// We'll remove certain characters for backward compatibility
		// since the formatting changed with MySQL 4.1
		// YYYY-MM-DD HH:MM:SS

		$time = str_replace('-', '', $time);
		$time = str_replace(':', '', $time);
		$time = str_replace(' ', '', $time);

		// YYYYMMDDHHMMSS
		return mktime(
			substr($time, 8, 2),
			substr($time, 10, 2),
			substr($time, 12, 2),
			substr($time, 4, 2),
			substr($time, 6, 2),
			substr($time, 0, 4)
		);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('unix_to_human'))
{
	/**
	 * Unix to "Human"
	 *
	 * Formats Unix timestamp to the following prototype: 2006-08-21 11:35 PM
	 *
	 * @param	int	Unix timestamp
	 * @param	bool	whether to show seconds
	 * @param	string	format: us or euro
	 * @return	string
	 */
	function unix_to_human($time = '', $seconds = FALSE, $fmt = 'us')
	{
		$r  = date('Y', $time).'-'.date('m', $time).'-'.date('d', $time).' ';

		if ($fmt === 'us')
		{
			$r .= date('h', $time).':'.date('i', $time);
		}
		else
		{
			$r .= date('H', $time).':'.date('i', $time);
		}

		if ($seconds)
		{
			$r .= ':'.date('s', $time);
		}

		if ($fmt === 'us')
		{
			$r .= ' '.date('A', $time);
		}

		return $r;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('human_to_unix'))
{
	/**
	 * Convert "human" date to GMT
	 *
	 * Reverses the above process
	 *
	 * @param	string	format: us or euro
	 * @return	int
	 */
	function human_to_unix($datestr = '')
	{
		if ($datestr === '')
		{
			return FALSE;
		}

		$datestr = preg_replace('/\040+/', ' ', trim($datestr));

		if ( ! preg_match('/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}(?::[0-9]{1,2})?(?:\s[AP]M)?$/i', $datestr))
		{
			return FALSE;
		}

		$split = explode(' ', $datestr);

		$ex = explode('-', $split['0']);

		$year  = (strlen($ex[0]) === 2) ? '20'.$ex[0] : $ex[0];
		$month = (strlen($ex[1]) === 1) ? '0'.$ex[1]  : $ex[1];
		$day   = (strlen($ex[2]) === 1) ? '0'.$ex[2]  : $ex[2];

		$ex = explode(':', $split['1']);

		$hour = (strlen($ex[0]) === 1) ? '0'.$ex[0] : $ex[0];
		$min  = (strlen($ex[1]) === 1) ? '0'.$ex[1] : $ex[1];

		if (isset($ex[2]) && preg_match('/[0-9]{1,2}/', $ex[2]))
		{
			$sec = (strlen($ex[2]) === 1) ? '0'.$ex[2] : $ex[2];
		}
		else
		{
			// Unless specified, seconds get set to zero.
			$sec = '00';
		}

		if (isset($split[2]))
		{
			$ampm = strtolower($split[2]);

			if (substr($ampm, 0, 1) === 'p' && $hour < 12)
			{
				$hour += 12;
			}

			if (substr($ampm, 0, 1) === 'a' && $hour == 12)
			{
				$hour =  '00';
			}

			if (strlen($hour) === 1)
			{
				$hour = '0'.$hour;
			}
		}

		return mktime($hour, $min, $sec, $month, $day, $year);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('nice_date'))
{
	/**
	 * Turns many "reasonably-date-like" strings into something
	 * that is actually useful. This only works for dates after unix epoch.
	 *
	 * @param	string	The terribly formatted date-like string
	 * @param	string	Date format to return (same as php date function)
	 * @return	string
	 */
	function nice_date($bad_date = '', $format = FALSE)
	{
		if (empty($bad_date))
		{
			return 'Unknown';
		}

		// Date like: YYYYMM
		if (preg_match('/^\d{6}$/', $bad_date))
		{
			if (in_array(substr($bad_date, 0, 2), array('19', '20')))
			{
				$year  = substr($bad_date, 0, 4);
				$month = substr($bad_date, 4, 2);
			}
			else
			{
				$month  = substr($bad_date, 0, 2);
				$year   = substr($bad_date, 2, 4);
			}

			return date($format, strtotime($year.'-'.$month.'-01'));
		}

		// Date Like: YYYYMMDD
		if (preg_match('/^\d{8}$/', $bad_date))
		{
			$month = substr($bad_date, 0, 2);
			$day   = substr($bad_date, 2, 2);
			$year  = substr($bad_date, 4, 4);

			return date($format, strtotime($month.'/01/'.$year));
		}

		// Date Like: MM-DD-YYYY __or__ M-D-YYYY (or anything in between)
		if (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $bad_date))
		{
			list($m, $d, $y) = explode('-', $bad_date);
			return date($format, strtotime($y.'-'.$m.'-'.$d));
		}

		// Any other kind of string, when converted into UNIX time,
		// produces "0 seconds after epoc..." is probably bad...
		// return "Invalid Date".
		if (date('U', strtotime($bad_date)) === '0')
		{
			return 'Invalid Date';
		}

		// It's probably a valid-ish date format already
		return date($format, strtotime($bad_date));
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('timezone_menu'))
{
	/**
	 * Timezone Menu
	 *
	 * Generates a drop-down menu of timezones.
	 *
	 * @param	string	timezone
	 * @param	string	classname
	 * @param	string	menu name
	 * @return	string
	 */
	function timezone_menu($default = 'UTC', $class = '', $name = 'timezones')
	{
		$CI =& get_instance();
		$CI->lang->load('date');

		$default = ($default === 'GMT') ? 'UTC' : $default;

		$menu = '<select name="'.$name.'"';

		if ($class !== '')
		{
			$menu .= ' class="'.$class.'"';
		}

		$menu .= ">\n";

		foreach (timezones() as $key => $val)
		{
			$selected = ($default === $key) ? ' selected="selected"' : '';
			$menu .= '<option value="'.$key.'"'.$selected.'>'.$CI->lang->line($key)."</option>\n";
		}

		return $menu.'</select>';
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('timezones'))
{
	/**
	 * Timezones
	 *
	 * Returns an array of timezones. This is a helper function
	 * for various other ones in this library
	 *
	 * @param	string	timezone
	 * @return	string
	 */
	function timezones($tz = '')
	{
		// Note: Don't change the order of these even though
		// some items appear to be in the wrong order

		$zones = array(
			'UM12'		=> -12,
			'UM11'		=> -11,
			'UM10'		=> -10,
			'UM95'		=> -9.5,
			'UM9'		=> -9,
			'UM8'		=> -8,
			'UM7'		=> -7,
			'UM6'		=> -6,
			'UM5'		=> -5,
			'UM45'		=> -4.5,
			'UM4'		=> -4,
			'UM35'		=> -3.5,
			'UM3'		=> -3,
			'UM2'		=> -2,
			'UM1'		=> -1,
			'UTC'		=> 0,
			'UP1'		=> +1,
			'UP2'		=> +2,
			'UP3'		=> +3,
			'UP35'		=> +3.5,
			'UP4'		=> +4,
			'UP45'		=> +4.5,
			'UP5'		=> +5,
			'UP55'		=> +5.5,
			'UP575'		=> +5.75,
			'UP6'		=> +6,
			'UP65'		=> +6.5,
			'UP7'		=> +7,
			'UP8'		=> +8,
			'UP875'		=> +8.75,
			'UP9'		=> +9,
			'UP95'		=> +9.5,
			'UP10'		=> +10,
			'UP105'		=> +10.5,
			'UP11'		=> +11,
			'UP115'		=> +11.5,
			'UP12'		=> +12,
			'UP1275'	=> +12.75,
			'UP13'		=> +13,
			'UP14'		=> +14
		);

		if ($tz === '')
		{
			return $zones;
		}

		$tz = ($tz === 'GMT') ? 'UTC' : $tz;

		return isset($zones[$tz]) ? $zones[$tz] : 0;
	}
}

/* End of file date_helper.php */
/* Location: ./system/helpers/date_helper.php */