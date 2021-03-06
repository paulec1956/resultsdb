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

jimport('joomla.application.component.view');

use \Joomla\CMS\Language\Text;

/**
 * View class for a list of Resultsdb.
 *
 * @since  1.6
 */
class ResultsdbViewMonthlyresults extends \Joomla\CMS\MVC\View\HtmlView
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
        
        

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		ResultsdbHelper::addSubmenu('monthlyresults');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = ResultsdbHelper::getActions();

		JToolBarHelper::title(Text::_('COM_RESULTSDB_TITLE_MONTHLYRESULTS'), 'monthlyresults.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/monthlyresult';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('monthlyresult.add', 'JTOOLBAR_NEW');

				if (isset($this->items[0]))
				{
					JToolbarHelper::custom('monthlyresults.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
				}
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('monthlyresult.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('monthlyresults.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('monthlyresults.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'monthlyresults.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('monthlyresults.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('monthlyresults.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'monthlyresults.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('monthlyresults.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_resultsdb');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_resultsdb&view=monthlyresults');
	}

	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
			'a.`raceid`' => JText::_('COM_RESULTSDB_RESULTS_RACEID'),
			'a.`date`' => JText::_('COM_RESULTSDB_RESULTS_DATE'),
			'a.`runner`' => JText::_('COM_RESULTSDB_RESULTS_RUNNER'),
			'a.`position`' => JText::_('COM_RESULTSDB_RESULTS_POSITION'),
			'a.`time`' => JText::_('COM_RESULTSDB_RESULTS_TIME'),
			'a.`pb`' => JText::_('COM_RESULTSDB_RESULTS_PB'),
			'a.`agecat`' => JText::_('COM_RESULTSDB_RESULTS_AGECAT'),
			'a.`catposition`' => JText::_('COM_RESULTSDB_RESULTS_CATPOSITION'),
			'a.`comment`' => JText::_('COM_RESULTSDB_RESULTS_COMMENT'),
		);
	}

    /**
     * Check if state is set
     *
     * @param   mixed  $state  State
     *
     * @return bool
     */
    public function getState($state)
    {
        return isset($this->state->{$state}) ? $this->state->{$state} : false;
    }
}
