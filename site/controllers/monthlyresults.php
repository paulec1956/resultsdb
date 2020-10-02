<?php
/**
 * @version    CVS: 1.1.25
 * @package    Com_Resultsdb
 * @author     Paul Crean <pecrean@gmail.com>
 * @copyright  2020 Paul Crean
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Monthlyresults list controller class.
 *
 * @since  1.6
 */
class ResultsdbControllerMonthlyresults extends ResultsdbController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional
	 * @param   array   $config  Configuration array for model. Optional
	 *
	 * @return object	The model
	 *
	 * @since	1.6
	 */
    
    public function filter()
    {
        $input=JFactory::getApplication()->input;
        $month=$input->get('month','202001', 'string' );
        JFactory::getApplication()->enqueueMessage(JText::_('month value $month'), 'error');
        //$model=&getModel('Monthlyresults', 'ResultsdbModel');
        //$model->filter ($month);
        
    }
	public function &getModel($name = 'Monthlyresults', $prefix = 'ResultsdbModel', $config = array())
	{
	    $input=JFactory::getApplication()->input;
	    $month=$input->get('month','202001', 'string' );
	    JFactory::getApplication()->enqueueMessage(JText::_('month value $month'), 'error');
	    $model = parent::getModel($name, $prefix, array('ignore_request' => true));
	    $model->filter ($month);
		return $model;
	}
}
