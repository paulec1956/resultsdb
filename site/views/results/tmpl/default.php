<?php
/**
 * @version    CVS: 1.1.45
 * @package    Com_Resultsdb
 * @author     Paul Crean <pecrean@gmail.com>
 * @copyright  2020 Paul Crean
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
  * PB display added 250121
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
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">
<p style="text-align: center;">
  <span style="font-size: 18pt;">
    <strong>
      <span style="font-family: helvetica; ">Full list of Historical Club Results:</span>
	</strong>
</span>
</p>
<p> Use the Search Tools to filter by name and Search box to look for races</p>
	<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
        <div class="table-responsive">
	<table class="table " id="resultList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>

			<?php endif; ?>

							<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_RESULTSDB_RESULTS_RACEID', 'a.raceid', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_RESULTSDB_RESULTS_DATE', 'a.date', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_RESULTSDB_RESULTS_RUNNER', 'a.runner', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_RESULTSDB_RESULTS_POSITION', 'a.position', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_RESULTSDB_RESULTS_TIME', 'a.time', $listDirn, $listOrder); ?>
				</th>
        <th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_RESULTSDB_RESULTS_PB', 'a.pb', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_RESULTSDB_RESULTS_AGECAT', 'a.agecat', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_RESULTSDB_RESULTS_CATPOSITION', 'a.catposition', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_RESULTSDB_RESULTS_COMMENT', 'a.comment', $listDirn, $listOrder); ?>
				</th>


							<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo JText::_('COM_RESULTSDB_RESULTS_ACTIONS'); ?>
				</th>
				<?php endif; ?>

		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_resultsdb'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_resultsdb')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

			<tr class="row<?php echo $i % 2; ?>">

				<?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>

				<?php endif; ?>

								<td>

					<?php echo $item->raceid; ?>
				</td>
				<td>

					<?php
					$date = $item->date;
					echo $date > 0 ? JHtml::_('date', $date, JText::_('DATE_FORMAT_LC4')) : '-';
					?>				</td>
				<td>

					<?php echo $item->runner; ?>
				</td>
				<td>

					<?php echo $item->position; ?>
				</td>
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'results.', $canCheckin); ?>
				<?php endif; ?>
				<a href="<?php echo JRoute::_('index.php?option=com_resultsdb&view=result&id='.(int) $item->id); ?>">
				<?php echo $this->escape($item->time); ?></a>
				</td>
        <td>

					<?php echo $item->pb; ?>
				</td>
				<td>

					<?php echo $item->agecat; ?>
				</td>
				<td>

					<?php echo $item->catposition; ?>
				</td>
				<td>

					<?php echo $item->comment; ?>
				</td>


								<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_resultsdb&task=result.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_resultsdb&task=resultform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
						<?php endif; ?>
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
        </div>
	<?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_resultsdb&task=resultform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_RESULTSDB_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

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
