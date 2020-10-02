<?php

/**
 * @version    CVS: 1.1.41
 * @package    Com_Resultsdb
 * @author     Paul Crean <pecrean@gmail.com>
 * @copyright  2020 Paul Crean
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * changed date column - no longer FK
 * Changed 300920 to add where state = 1 to query in get_items to exclude trashed records
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Resultsdb records.
 *
 * @since  1.6
 */
class ResultsdbModelMonthlyresults extends \Joomla\CMS\MVC\Model\ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
    public $month;
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				
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
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app  = Factory::getApplication();
            
		$list = $app->getUserState($this->context . '.list');

		$ordering  = isset($list['filter_order'])     ? $list['filter_order']     : null;
		$direction = isset($list['filter_order_Dir']) ? $list['filter_order_Dir'] : null;
		if(empty($ordering)){
		$ordering = $app->getUserStateFromRequest($this->context . '.filter_order', 'filter_order', $app->get('filter_order'));
		if (!in_array($ordering, $this->filter_fields))
		{
		$ordering = 'date';
		}
		$this->setState('list.ordering', $ordering);
		}
		if(empty($direction))
		{
		$direction = $app->getUserStateFromRequest($this->context . '.filter_order_Dir', 'filter_order_Dir', $app->get('filter_order_Dir'));
		if (!in_array(strtoupper($direction), array('ASC', 'DESC', '')))
		{
		$direction = 'DESC';
		}
		$this->setState('list.direction', $direction);
		}

		$list['limit']     = $app->getUserStateFromRequest($this->context . '.list.limit', 'limit', $app->get('list_limit'), 'uint');
		$list['start']     = $app->input->getInt('start', 0);
		$list['ordering']  = $ordering;
		$list['direction'] = $direction;

		$app->setUserState($this->context . '.list', $list);
		$app->input->set('list', null);
           
            
        // List state information.

        parent::populateState($ordering, $direction);

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
	    
	    // Join over the users for the checked out user.
	    $query->select('uc.name AS uEditor');
	    $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
	    
	    // Join over the created by field 'created_by'
	    $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
	    
	    // Join over the created by field 'modified_by'
	    $query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');
	    // Join over the foreign key 'raceid'
	    $query->select('CONCAT(`#__resultsdb_races_3445521`.`description`, \' \', `#__resultsdb_races_3445521`.`additional_info`) AS races_fk_value_3445521');
	    $query->join('LEFT', '#__resultsdb_races AS #__resultsdb_races_3445521 ON #__resultsdb_races_3445521.`id` = a.`raceid`');
	    // Join over the foreign key 'date'
	    $query->select('`#__resultsdb_races_3447765`.`date` AS races_fk_value_3447765');
	    $query->join('LEFT', '#__resultsdb_races AS #__resultsdb_races_3447765 ON #__resultsdb_races_3447765.`id` = a.`date`');
	    // Join over the foreign key 'runner'
	    $query->select('CONCAT(`#__resultsdb_runners_3445522`.`firstname`, \' \', `#__resultsdb_runners_3445522`.`surname`) AS runners_fk_value_3445522');
	    $query->join('LEFT', '#__resultsdb_runners AS #__resultsdb_runners_3445522 ON #__resultsdb_runners_3445522.`id` = a.`runner`');
	    
	    if (!Factory::getUser()->authorise('core.edit', 'com_resultsdb'))
	    {
	        $query->where('a.state = 1');
	    }
	    else
	    {
	        $query->where('(a.state IN (0, 1))');
	    }
	    $month='2016-06';
	    //$query->where($query->quotename('#__resultsdb_races_3447765.date') . ' LIKE ' . $query->quote('2016-06%') );
	    // Filter by search in title
	    $search = $this->getState('filter.search');
	    
	    if (!empty($search))
	    {
	        //JFactory::getApplication()->enqueueMessage(JText::_($search), 'search');
	        if (stripos($search, 'id:') === 0)
	        {
	            $query->where('a.id = ' . (int) substr($search, 3));
	        }
	        else
	        {
	            $search = $db->Quote('%' . $db->escape($search, true) . '%');
	            $query->where('(#__resultsdb_races_3445521.description LIKE ' . $search . '  OR #__resultsdb_races_3447765.date LIKE ' . $search . '  OR CONCAT(`#__resultsdb_runners_3445522`.`firstname`, \' \', `#__resultsdb_runners_3445522`.`surname`) LIKE ' . $search . ' )');
	        }
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
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
	    //$items = parent::getItems();
	    //JFactory::getApplication()->enqueueMessage(JText::_('Call getListQuery'), 'error');
	    $search = $this->getState('filter.search');
	    if (empty($search)) // set default month
	    {
	        $search='2020-09';
	    }
	    if(!preg_match("/\d{4}-\d{2}/", $search, $match)) {
	        JFactory::getApplication()->enqueueMessage(JText::_('Please enter seach month in format yyyy-mm'), 'search error');
	    }
	    $month=$search;
	    //JFactory::getApplication()->enqueueMessage(JText::_($search), 'search');
	    $db    = JFactory::getDbo();
	    $query = $db->getQuery(true);
	    //$query=$this->getListQuery();
	    //$month='2019-11';
	    //$year=2019;
	    $query->select(
	        $this->getState(
	            'list.select', 'DISTINCT a.*'
	            )
	        );
	    $query->from('`#__resultsdb_results` AS a');
	    // Join over the foreign key 'raceid'
	    $query->select('CONCAT(`#__resultsdb_races_3445521`.`description`, \' \', `#__resultsdb_races_3445521`.`additional_info`) AS races_fk_value_3445521');
	    $query->join('LEFT', '#__resultsdb_races AS #__resultsdb_races_3445521 ON #__resultsdb_races_3445521.`id` = a.`raceid`');
	    // Join over the foreign key 'date'
	    //$query->select('`#__resultsdb_races_3445521`.`date`');
	    //$query->join('LEFT', '#__resultsdb_races AS #__resultsdb_races_3447765 ON #__resultsdb_races_3447765.`id` = a.`date`');
	    // Join over the foreign key 'runner'
	    $query->select('CONCAT(`#__resultsdb_runners_3445522`.`firstname`, \' \', `#__resultsdb_runners_3445522`.`surname`) AS runners_fk_value_3445522');
	    $query->join('LEFT', '#__resultsdb_runners AS #__resultsdb_runners_3445522 ON #__resultsdb_runners_3445522.`id` = a.`runner`');
		$query->where('a.state = 1'); // added 300920
	    $query->where ('`a`.`date`' . 'LIKE ' . $query->quote($month . "%"));
	    
	    //->from($db->quotename(MONTH('#__resultsdb_results')))
	    //->limit($db->quote('100'));
	    
	    //$db    = JFactory::getDbo();
	    //$query = $db->getQuery(true);
		//JFactory::getApplication()->enqueueMessage(JText::_($query), 'monthly results query');
	    $db->setQuery($query);
	    $items=$db->loadObjectList();
	    $res=count ($items);
	    $msg = "number of items read" . $res;
	    //JFactory::getApplication()->enqueueMessage(JText::_($msg), 'info');
	    
		
		foreach ($items as $item)
		{
		    //var_dump($item);
			if (isset($item->raceid))
			{

				$values    = explode(',', $item->raceid);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('CONCAT(`#__resultsdb_races_3445521`.`description`, \' \', `#__resultsdb_races_3445521`.`additional_info`) AS `fk_value`')
						->from($db->quoteName('#__resultsdb_races', '#__resultsdb_races_3445521'))
						->where($db->quoteName('#__resultsdb_races_3445521.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->fk_value;
					}
				}

				$item->raceid = !empty($textValue) ? implode(', ', $textValue) : $item->raceid;
			}


			if (isset($item->date))
			{

				$values    = explode(',', $item->date);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__resultsdb_races_3447765`.`date`')
						->from($db->quoteName('#__resultsdb_races', '#__resultsdb_races_3447765'))
						->where($db->quoteName('#__resultsdb_races_3447765.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->date;
					}
				}

				$item->date = !empty($textValue) ? implode(', ', $textValue) : $item->date;
			}


			if (isset($item->runner))
			{

				$values    = explode(',', $item->runner);
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

				$item->runner = !empty($textValue) ? implode(', ', $textValue) : $item->runner;
				
			}

		}

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = Factory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(Text::_("COM_RESULTSDB_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? Factory::getDate($date)->format("Y-m-d") : null;
	}
	public function filter ($month)
	{
	    $this->month=$month;
	}
	
}
