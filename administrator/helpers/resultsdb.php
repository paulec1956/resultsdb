<?php

/**
 * @version    CVS: 1.1.35
 * @package    Com_Resultsdb
 * @author     Paul Crean <pecrean@gmail.com>
 * @copyright  2020 Paul Crean
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;

/**
 * Resultsdb helper.
 *
 * @since  1.6
 */
class ResultsdbHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_RESULTSDB_TITLE_RACES'),
			'index.php?option=com_resultsdb&view=races',
			$vName == 'races'
		);

JHtmlSidebar::addEntry(
			JText::_('COM_RESULTSDB_TITLE_RUNNERS'),
			'index.php?option=com_resultsdb&view=runners',
			$vName == 'runners'
		);

JHtmlSidebar::addEntry(
			JText::_('COM_RESULTSDB_TITLE_RESULTS'),
			'index.php?option=com_resultsdb&view=results',
			$vName == 'results'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_RESULTSDB_TITLE_MONTHLYRESULTS'),
			'index.php?option=com_resultsdb&view=monthlyresults',
			$vName == 'monthlyresults'
		);
	}

	/**
	 * Gets the files attached to an item
	 *
	 * @param   int     $pk     The item's id
	 *
	 * @param   string  $table  The table's name
	 *
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
	 */
	public static function getFiles($pk, $table, $field)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($field)
			->from($table)
			->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    JObject
	 *
	 * @since    1.6
	 */
	public static function getActions()
	{
		$user   = Factory::getUser();
		$result = new JObject;

		$assetName = 'com_resultsdb';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}

