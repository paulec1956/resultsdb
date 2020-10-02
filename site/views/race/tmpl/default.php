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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_resultsdb');

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_resultsdb'))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_RESULTSDB_FORM_LBL_RACE_DESCRIPTION'); ?></th>
			<td><?php echo $this->item->description; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_RESULTSDB_FORM_LBL_RACE_ADDITIONAL_INFO'); ?></th>
			<td><?php echo $this->item->additional_info; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_RESULTSDB_FORM_LBL_RACE_DATE'); ?></th>
			<td><?php echo $this->item->date; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_RESULTSDB_FORM_LBL_RACE_RUNNERS'); ?></th>
			<td><?php echo $this->item->runners; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_RESULTSDB_FORM_LBL_RACE_DISTANCE'); ?></th>
			<td><?php echo $this->item->distance; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_RESULTSDB_FORM_LBL_RACE_ASCENT'); ?></th>
			<td><?php echo $this->item->ascent; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_RESULTSDB_FORM_LBL_RACE_RACE_TERRAIN'); ?></th>
			<td><?php echo $this->item->race_terrain; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_RESULTSDB_FORM_LBL_RACE_COMMENT'); ?></th>
			<td><?php echo $this->item->comment; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_resultsdb&task=race.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_RESULTSDB_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_resultsdb.race.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_RESULTSDB_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_RESULTSDB_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_RESULTSDB_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_resultsdb&task=race.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_RESULTSDB_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>