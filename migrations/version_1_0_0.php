<?php
/**
*
* @package Color Picker Show extension
* @copyright (c) 2015 Electrix
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace electrix\cpickershow\migrations;

class version_1_0_0 extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(
			array('config.add', array('cpickershow_version', '1.0.0')),
		);
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'user_cpickershow'	=> array('TINT:2', 0),
				),
			),
		);
	}

	/**
	* Drop the columns schema from the tables
	*
	* @return array Array of table schema
	* @access public
	*/
	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'user_cpickershow',
				),
			),
		);
	}
}
