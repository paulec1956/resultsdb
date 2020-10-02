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

use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Factory;
use Joomla\CMS\Categories\Categories;

/**
 * Class ResultsdbRouter
 *
 */
class ResultsdbRouter extends RouterView
{
	private $noIDs;
	public function __construct($app = null, $menu = null)
	{
		$params = Factory::getApplication()->getParams('com_resultsdb');
		$this->noIDs = (bool) $params->get('sef_ids');
		
		$races = new RouterViewConfiguration('races');
		$this->registerView($races);
			$race = new RouterViewConfiguration('race');
			$race->setKey('id')->setParent($races);
			$this->registerView($race);
			$raceform = new RouterViewConfiguration('raceform');
			$raceform->setKey('id');
			$this->registerView($raceform);$runners = new RouterViewConfiguration('runners');
		$this->registerView($runners);$results = new RouterViewConfiguration('results');
		$this->registerView($results);
			$result = new RouterViewConfiguration('result');
			$result->setKey('id')->setParent($results);
			$this->registerView($result);
			$resultform = new RouterViewConfiguration('resultform');
			$resultform->setKey('id');
			$this->registerView($resultform);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));

		if ($params->get('sef_advanced', 0))
		{
			$this->attachRule(new StandardRules($this));
			$this->attachRule(new NomenuRules($this));
		}
		else
		{
			JLoader::register('ResultsdbRulesLegacy', __DIR__ . '/helpers/legacyrouter.php');
			JLoader::register('ResultsdbHelpersResultsdb', __DIR__ . '/helpers/resultsdb.php');
			$this->attachRule(new ResultsdbRulesLegacy($this));
		}
	}


	
		/**
		 * Method to get the segment(s) for an race
		 *
		 * @param   string  $id     ID of the race to retrieve the segments for
		 * @param   array   $query  The request that is built right now
		 *
		 * @return  array|string  The segments of this item
		 */
		public function getRaceSegment($id, $query)
		{
			return array((int) $id => $id);
		}
			/**
			 * Method to get the segment(s) for an raceform
			 *
			 * @param   string  $id     ID of the raceform to retrieve the segments for
			 * @param   array   $query  The request that is built right now
			 *
			 * @return  array|string  The segments of this item
			 */
			public function getRaceformSegment($id, $query)
			{
				return $this->getRaceSegment($id, $query);
			}
		/**
		 * Method to get the segment(s) for an result
		 *
		 * @param   string  $id     ID of the result to retrieve the segments for
		 * @param   array   $query  The request that is built right now
		 *
		 * @return  array|string  The segments of this item
		 */
		public function getResultSegment($id, $query)
		{
			return array((int) $id => $id);
		}
			/**
			 * Method to get the segment(s) for an resultform
			 *
			 * @param   string  $id     ID of the resultform to retrieve the segments for
			 * @param   array   $query  The request that is built right now
			 *
			 * @return  array|string  The segments of this item
			 */
			public function getResultformSegment($id, $query)
			{
				return $this->getResultSegment($id, $query);
			}

	
		/**
		 * Method to get the segment(s) for an race
		 *
		 * @param   string  $segment  Segment of the race to retrieve the ID for
		 * @param   array   $query    The request that is parsed right now
		 *
		 * @return  mixed   The id of this item or false
		 */
		public function getRaceId($segment, $query)
		{
			return (int) $segment;
		}
			/**
			 * Method to get the segment(s) for an raceform
			 *
			 * @param   string  $segment  Segment of the raceform to retrieve the ID for
			 * @param   array   $query    The request that is parsed right now
			 *
			 * @return  mixed   The id of this item or false
			 */
			public function getRaceformId($segment, $query)
			{
				return $this->getRaceId($segment, $query);
			}
		/**
		 * Method to get the segment(s) for an result
		 *
		 * @param   string  $segment  Segment of the result to retrieve the ID for
		 * @param   array   $query    The request that is parsed right now
		 *
		 * @return  mixed   The id of this item or false
		 */
		public function getResultId($segment, $query)
		{
			return (int) $segment;
		}
			/**
			 * Method to get the segment(s) for an resultform
			 *
			 * @param   string  $segment  Segment of the resultform to retrieve the ID for
			 * @param   array   $query    The request that is parsed right now
			 *
			 * @return  mixed   The id of this item or false
			 */
			public function getResultformId($segment, $query)
			{
				return $this->getResultId($segment, $query);
			}
}
