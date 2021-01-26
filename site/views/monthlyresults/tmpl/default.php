<?php
/**
 * @version    CVS: 1.1.45
 * @package    Com_Resultsdb
 * @author     Paul Crean <pecrean@gmail.com>
 * @copyright  2020 Paul Crean
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * 13/06/20:  copied from Results view template file to start with.
 * 02/07/20 v1.1.31 change column headings for handicap races
 * * 07/07/20 fixed issue with final race in month
 * 23/09/20  Added Hobble to handicap races change in column headings
 * 25/01/21 PB added to view
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_resultsdb') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'resultform.xml');
$canEdit    = $user->authorise('core.edit', 'com_resultsdb') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'resultform.xml');
$canCheckin = $user->authorise('core.manage', 'com_resultsdb');
$canChange  = $user->authorise('core.edit.state', 'com_resultsdb');
$canDelete  = $user->authorise('core.delete', 'com_resultsdb');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_resultsdb/css/list.css');
//JFactory::getApplication()->enqueueMessage(JText::_('Layout file' ), 'started');
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">
<p style="text-align: center;">
  <span style="font-size: 18pt;">
    <strong>
      <span style="font-family: helvetica; ">Results for one Month: </span>
	</strong>
 </span>
</p>
<p>
  <span style="font-size: 14pt;">
     <span style="font-family: helvetica; ">Enter a month and year in the search box (yyyy-mm) OR </span>
 </span>
</p>
<p>
  <span style="font-size: 14pt;">
     <span style="font-family: helvetica; ">Click Search Tools and select the year and month you want to see in the dropdowns</span>
 </span>
</p>
<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>

		<?php
		$res=count ($this->$items);
		$msg = "View number of items read" . $res;
		//echo $msg;
		//JFactory::getApplication()->enqueueMessage(JText::_($msg), 'Layout database calls');
		$thisrace= " ";
		$numresult=0;?>

		<?php
		foreach ($this->items as $i => $item)
		{  // Read all results as objects  - $item
		    //var_dump($item);
			 $canEdit = $user->authorise('core.edit', 'com_resultsdb'); ?>
			 <?php if ($thisrace != $item->raceid) // Is this a new race?
            {
				//JFactory::getApplication()->enqueueMessage(JText::_($item->raceid), 'New race');
                if ($thisrace != " ")
                { // last race results all captured and ready to display

                    display_race_results ($results);
                }
                // initialise for next race
                $results=[]; // setup empty array for the results in the race
                $numresult=0; //reset result counter for array in race
                $thisrace = $item-> raceid; // Store current race name
            }

        $results [$numresult++] = get_object_vars($item); // convert object to array and add to results array
		}
		// display last race results
		display_race_results ($results);

	function display_race_results ($results)
	{
	    $thisrace= $results[0]['raceid'];
	    $date=date_create ($results[0]['date'], timezone_open("Europe/Oslo"));
	    $thisdate=date_format($date, "l jS F Y");
	    $thismonth=date_format($date, "F Y");
		$racename= $thisdate . " - " . $thisrace;
		$i=0;
		if (preg_match ("/HANDICAP/", strtoupper("$thisrace"), $matches) OR
		preg_match ("/HOBBLE/", strtoupper("$thisrace"), $matches))
            $handicap=true;
		?>
	  <div class="table-responsive">
	    <h3><?php echo $racename; // display race title and date ?>
        </h3>
	   <table class="table " id="resultList">
        <tbody>
            <th>
            <?php echo JText::_('COM_RESULTSDB_RESULTS_RUNNER'); ?>
			</th>
			<th class=''>
			<?php echo JText::_('COM_RESULTSDB_RESULTS_POSITION'); ?>
			</th>
			<th class=''>
			<?php  echo JText::_('COM_RESULTSDB_RESULTS_TIME'); ?>
			</th>
      <th class=''>
			<?php  echo JText::_('COM_RESULTSDB_RESULTS_PB'); ?>
			</th>
			<th class=''>
			<?php if ($handicap)
			    echo JText::_('Delay');
			else
			    echo JText::_('COM_RESULTSDB_RESULTS_AGECAT'); ?>
			</th>
			<th class=''>
			<?php if ($handicap)
			    echo JText::_('Actual Time');
			else
			    echo JText::_('COM_RESULTSDB_RESULTS_CATPOSITION'); ?>
			</th>
			<th class=''>
			<?php echo JText::_('COM_RESULTSDB_RESULTS_COMMENT'); ?>
    		</th>

		<?php foreach ($results as $result)
		{ ?>
			<tr class="row<?php echo $i++ % 2; ?>">
			<td>
				<?php echo $result['runner']; ?>
			</td>
			<td>
				<?php echo $result['position']; ?>
			</td>
			<td>
				<?php echo $result['time']; ?>
			</td>
      <td>
				<?php echo $result['pb']; ?>
			</td>
			<td>
				<?php echo $result['agecat']; ?>
			</td>
			<td>
				<?php echo $result['catposition']; ?>
			</td>
			<td>
				<?php echo $result['comment']; ?>
			</td>
		</tr>

 <?php  } // end foreach ?>
     </tbody>
     </table>
     </div>

<?php } //end of function display race results ?>

<?php if($canDelete) : ?>
<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {

		if (!confirm("<?php echo Text::_('COM_RESULTSDB_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; ?>
