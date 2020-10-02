<?php

/**
 * @version     CVS: 1.1.35
 * @package     com_resultsdb
 * @subpackage  mod_resultsdb
 * @author      Paul Crean <pecrean@gmail.com>
 * @copyright   2020 Paul Crean
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

/**
 * Helper for mod_resultsdb
 *
 * @package     com_resultsdb
 * @subpackage  mod_resultsdb
 * @since       1.6
 */
class ModResultsdbHelper
{
	/**
	 * Retrieve component items
	 *
	 * @param   Joomla\Registry\Registry &$params module parameters
	 *
	 * @return array Array with all the elements
     *
     * @throws Exception
	 */
	public static function getList(&$params)
	{
		$app   = Factory::getApplication();
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$tableField = explode(':', $params->get('field'));
		$table_name = !empty($tableField[0]) ? $tableField[0] : '';

		/* @var $params Joomla\Registry\Registry */
		$query
			->select('*')
			->from($table_name)
			->where('state = 1');

		$db->setQuery($query, $app->input->getInt('offset', (int) $params->get('offset')), $app->input->getInt('limit', (int) $params->get('limit')));
		$rows = $db->loadObjectList();

		return $rows;
	}

	/**
	 * Retrieve component items
	 *
	 * @param   Joomla\Registry\Registry &$params module parameters
	 *
	 * @return mixed stdClass object if the item was found, null otherwise
	 */
	public static function getItem(&$params)
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		/* @var $params Joomla\Registry\Registry */
		$query
			->select('*')
			->from($params->get('item_table'))
			->where('id = ' . intval($params->get('item_id')));

		$db->setQuery($query);
		$element = $db->loadObject();

		return $element;
	}

	/**
	 * Render element
	 *
	 * @param   Joomla\Registry\Registry $table_name  Table name
	 * @param   string                   $field_name  Field name
	 * @param   string                   $field_value Field value
	 *
	 * @return string
	 */
	public static function renderElement($table_name, $field_name, $field_value)
	{
		$result = '';
		
		if(strpos($field_name, ':'))
		{
			$tableField = explode(':', $field_name);
			$table_name = !empty($tableField[0]) ? $tableField[0] : '';
			$field_name = !empty($tableField[1]) ? $tableField[1] : '';
		}
		
		switch ($table_name)
		{
			
		case '#__resultsdb_races':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'modified_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'description':
		$result = $field_value;
		break;
		case 'additional_info':
		$result = $field_value;
		break;
		case 'date':
		$result = $field_value;
		break;
		case 'runners':
		$result = $field_value;
		break;
		case 'distance':
		$result = $field_value;
		break;
		case 'ascent':
		$result = $field_value;
		break;
		case 'race_terrain':
		$result = $field_value;
		break;
		case 'comment':
		$result = $field_value;
		break;
		}
		break;
		case '#__resultsdb_runners':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'modified_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'urn':
		$result = $field_value;
		break;
		case 'firstname':
		$result = $field_value;
		break;
		case 'surname':
		$result = $field_value;
		break;
		case 'gender':
		$result = $field_value;
		break;
		case 'claim':
		$result = $field_value;
		break;
		case 'dob':
		$result = $field_value;
		break;
		case 'membership':
		$result = $field_value;
		break;
		case 'alias':
		$result = $field_value;
		break;
		case 'comment':
		$result = $field_value;
		break;
		}
		break;
		case '#__resultsdb_results':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'modified_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'raceid':
		$result = self::loadValueFromExternalTable('#__resultsdb_races', 'id', 'description', $field_value);
		break;
		case 'date':
		$result = $field_value;
		break;
		case 'runner':
		$result = self::loadValueFromExternalTable('#__resultsdb_runners', 'id', '', $field_value);
		break;
		case 'position':
		$result = $field_value;
		break;
		case 'time':
		$result = $field_value;
		break;
		case 'agegrade':
		$result = $field_value;
		break;
		case 'pb':
		$result = $field_value;
		break;
		case 'agecat':
		$result = $field_value;
		break;
		case 'catposition':
		$result = $field_value;
		break;
		case 'comment':
		$result = $field_value;
		break;
		}
		break;
		}

		return $result;
	}

	/**
	 * Returns the translatable name of the element
	 *
	 * @param   string .................. $table_name table name
	 * @param   string                   $field   Field name
	 *
	 * @return string Translatable name.
	 */
	public static function renderTranslatableHeader($table_name, $field)
	{
		return Text::_(
			'MOD_RESULTSDB_HEADER_FIELD_' . str_replace('#__', '', strtoupper($table_name)) . '_' . strtoupper($field)
		);
	}

	/**
	 * Checks if an element should appear in the table/item view
	 *
	 * @param   string $field name of the field
	 *
	 * @return boolean True if it should appear, false otherwise
	 */
	public static function shouldAppear($field)
	{
		$noHeaderFields = array('checked_out_time', 'checked_out', 'ordering', 'state');

		return !in_array($field, $noHeaderFields);
	}

	

    /**
     * Method to get a value from a external table
     * @param string $source_table Source table name
     * @param string $key_field Source key field 
     * @param string $value_field Source value field
     * @param mixed  $key_value Value for the key field
     * @return mixed The value in the external table or null if it wasn't found
     */
    private static function loadValueFromExternalTable($source_table, $key_field, $value_field, $key_value) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
                ->select($db->quoteName($value_field))
                ->from($source_table)
                ->where($db->quoteName($key_field) . ' = ' . $db->quote($key_value));


        $db->setQuery($query);
        return $db->loadResult();
    }
}
