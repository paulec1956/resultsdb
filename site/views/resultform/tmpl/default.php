<?php
/**
 * @version    CVS: 1.1.35e
 * @package    Com_Resultsdb
 * @author     Paul Crean <pecrean@gmail.com>
 * @copyright  2020 Paul Crean
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * 07/07/20 - changed order of fields
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = Factory::getLanguage();
$lang->load('com_resultsdb', JPATH_SITE);
$doc = Factory::getDocument();
$doc->addScript(Uri::base() . '/media/com_resultsdb/js/form.js');

$user    = Factory::getUser();
$canEdit = ResultsdbHelpersResultsdb::canUserEdit($this->item, $user);


?>

<div class="result-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(Text::_('COM_RESULTSDB_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo Text::sprintf('COM_RESULTSDB_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo Text::_('COM_RESULTSDB_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>

		<form id="form-result"
			  action="<?php echo Route::_('index.php?option=com_resultsdb&task=result.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
			
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>
	<?php echo $this->form->renderField('raceid'); ?>

	<?php foreach((array)$this->item->raceid as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="raceid" name="jform[raceidhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
	<?php echo $this->form->renderField('date'); ?>

	<?php echo $this->form->renderField('runner'); ?>

	<?php foreach((array)$this->item->runner as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="runner" name="jform[runnerhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
	<?php echo $this->form->renderField('position'); ?>

	<?php echo $this->form->renderField('time'); ?>

	<?php echo $this->form->renderField('agecat'); ?>

	<?php echo $this->form->renderField('catposition'); ?>
	
	<?php echo $this->form->renderField('pb'); ?>

	<?php echo $this->form->renderField('agegrade'); ?>
	
	<?php echo $this->form->renderField('comment'); ?>

			<div class="control-group">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php echo Text::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn"
					   href="<?php echo Route::_('index.php?option=com_resultsdb&task=resultform.cancel'); ?>"
					   title="<?php echo Text::_('JCANCEL'); ?>">
						<?php echo Text::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_resultsdb"/>
			<input type="hidden" name="task"
				   value="resultform.save"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
