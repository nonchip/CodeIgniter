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
 * MS SQL Utility Class
 *
 * @category	Database
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/database/
 */
class CI_DB_mssql_utility extends CI_DB_utility {

	/**
	 * List databases
	 *
	 * @return	string
	 */
	public function _list_databases()
	{
		return 'EXEC sp_helpdb'; // Can also be: EXEC sp_databases
	}

	// --------------------------------------------------------------------

	/**
	 * Optimize table query
	 *
	 * Generates a platform-specific query so that a table can be optimized
	 *
	 * @param	string	the table name
	 * @return	string
	 */
	public function _optimize_table($table)
	{
		// Only supported in MSSQL 2005 and newer
		return 'ALTER INDEX all ON '.$this->db->protect_identifiers($table).' REORGANIZE';
	}

	// --------------------------------------------------------------------

	/**
	 * Repair table query
	 *
	 * Generates a platform-specific query so that a table can be repaired
	 *
	 * @param	string	the table name
	 * @return	bool
	 */
	public function _repair_table($table)
	{
		// Not supported in MSSQL
		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * MSSQL Export
	 *
	 * @param	array	Preferences
	 * @return	bool
	 */
	public function _backup($params = array())
	{
		// Currently unsupported
		return $this->db->display_error('db_unsuported_feature');
	}

}

/* End of file mssql_utility.php */
/* Location: ./system/database/drivers/mssql/mssql_utility.php */