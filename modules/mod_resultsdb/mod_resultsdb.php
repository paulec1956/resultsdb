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
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Helper\ModuleHelper;

// Include the syndicate functions only once
JLoader::register('ModResultsdbHelper', dirname(__FILE__) . '/helper.php');

$doc = Factory::getDocument();

/* */
$doc->addStyleSheet(URI::base() . 'media/mod_resultsdb/css/style.css');

/* */
$doc->addScript(URI::base() . 'media/mod_resultsdb/js/script.js');

require ModuleHelper::getLayoutPath('mod_resultsdb', $params->get('content_type', 'blank'));
