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

jimport('joomla.application.component.controller');

use \Joomla\CMS\Factory;

/**
 * Class ResultsdbController
 *
 * @since  1.6
 */
class ResultsdbController extends \Joomla\CMS\MVC\Controller\BaseController
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean $cachable  If true, the view output will be cached
	 * @param   mixed   $urlparams An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController   This object to support chaining.
	 *
	 * @since    1.5
     * @throws Exception
	 */
	public function display($cachable = false, $urlparams = false)
	{
        $app  = Factory::getApplication();
        $view = $app->input->getCmd('view', 'races');
		$app->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
}
