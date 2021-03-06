<?php

/**
 * @version    CVS: 1.1.35
 * @package    Com_Resultsdb
 * @author     Paul Crean <pecrean@gmail.com>
 * @copyright  2020 Paul Crean
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Resultsdb records.
 *
 * @since  1.6
 */
class ResultsdbModelResults extends \Joomla\CMS\MVC\Model\ListModel
{
    
        
/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.`id`',
				'ordering', 'a.`ordering`',
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'raceid', 'a.`raceid`',
				'date', 'a.`date`',
				'runner', 'a.`runner`',
				'position', 'a.`position`',
				'time', 'a.`time`',
				'agegrade', 'a.`agegrade`',
				'pb', 'a.`pb`',
				'agecat', 'a.`agecat`',
				'catposition', 'a.`catposition`',
				'comment', 'a.`comment`',
			);
		}

		parent::__construct($config);
	}

    
        
    
        

        
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
        // List state information.
        parent::populateState('date', 'DESC');

        $context = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $context);

        // Split context into component and optional section
        $parts = FieldsHelper::extract($context);

        if ($parts)
        {
            $this->setState('filter.component', $parts[0]);
            $this->setState('filter.section', $parts[1]);
        }
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

                
                    return parent::getStoreId($id);
                
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__resultsdb_results` AS a');
                
		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');
		// Join over the foreign key 'raceid'
		$query->select('`#__resultsdb_races_3445521`.`description` AS races_fk_value_3445521');
		$query->join('LEFT', '#__resultsdb_races AS #__resultsdb_races_3445521 ON #__resultsdb_races_3445521.`id` = a.`raceid`');
		// Join over the foreign key 'runner'
		$query->select('CONCAT(`#__resultsdb_runners_3445522`.`firstname`, \' \', `#__resultsdb_runners_3445522`.`surname`) AS runners_fk_value_3445522');
		$query->join('LEFT', '#__resultsdb_runners AS #__resultsdb_runners_3445522 ON #__resultsdb_runners_3445522.`id` = a.`runner`');
                

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif (empty($published))
		{
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('(#__resultsdb_races_3445521.description LIKE ' . $search . '  OR  a.date LIKE ' . $search . '  OR CONCAT(`#__resultsdb_runners_3445522`.`firstname`, \' \', `#__resultsdb_runners_3445522`.`surname`) LIKE ' . $search . ' )');
			}
		}
                

		// Filtering runner
		$filter_runner = $this->state->get("filter.runner");

		if ($filter_runner !== null && !empty($filter_runner))
		{
			$query->where("FIND_IN_SET('" . $db->escape($filter_runner) . "',a.runner)");
		}
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'date');
		$orderDirn = $this->state->get('list.direction', 'DESC');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
                
		foreach ($items as $oneItem)
		{

			if (isset($oneItem->raceid))
			{
				$values    = explode(',', $oneItem->raceid);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__resultsdb_races_3445521`.`description`')
						->from($db->quoteName('#__resultsdb_races', '#__resultsdb_races_3445521'))
						->where($db->quoteName('#__resultsdb_races_3445521.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->description;
					}
				}

				$oneItem->raceid = !empty($textValue) ? implode(', ', $textValue) : $oneItem->raceid;
			}

			if (isset($oneItem->runner))
			{
				$values    = explode(',', $oneItem->runner);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('CONCAT(`#__resultsdb_runners_3445522`.`firstname`, \' \', `#__resultsdb_runners_3445522`.`surname`) AS `fk_value`')
						->from($db->quoteName('#__resultsdb_runners', '#__resultsdb_runners_3445522'))
						->where($db->quoteName('#__resultsdb_runners_3445522.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->fk_value;
					}
				}

				$oneItem->runner = !empty($textValue) ? implode(', ', $textValue) : $oneItem->runner;
			}
		}

		return $items;
	}
}
