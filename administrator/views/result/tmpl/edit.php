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

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;


HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('behavior.keepalive');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_resultsdb/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	js('input:hidden.raceid').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('raceidhidden')){
			js('#jform_raceid option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_raceid").trigger("liszt:updated");
	js('input:hidden.runner').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('runnerhidden')){
			js('#jform_runner option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_runner").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'result.cancel') {
			Joomla.submitform(task, document.getElementById('result-form'));
		}
		else {
			
			if (task != 'result.cancel' && document.formvalidator.isValid(document.id('result-form'))) {
				
				Joomla.submitform(task, document.getElementById('result-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_resultsdb&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="result-form" class="form-validate form-horizontal">

	
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
	<?php echo $this->form->renderField('created_by'); ?>
	<?php echo $this->form->renderField('modified_by'); ?>
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'result')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'result', JText::_('COM_RESULTSDB_TAB_RESULT', true)); ?>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_RESULTSDB_FIELDSET_RESULT'); ?></legend>
				<?php echo $this->form->renderField('raceid'); ?>
				<?php
				foreach((array)$this->item->raceid as $value)
				{
					if(!is_array($value))
					{
						echo '<input type="hidden" class="raceid" name="jform[raceidhidden]['.$value.']" value="'.$value.'" />';
					}
				}
				?>
				<?php echo $this->form->renderField('date'); ?>
				<?php echo $this->form->renderField('runner'); ?>
				<?php
				foreach((array)$this->item->runner as $value)
				{
					if(!is_array($value))
					{
						echo '<input type="hidden" class="runner" name="jform[runnerhidden]['.$value.']" value="'.$value.'" />';
					}
				}
				?>
				<?php echo $this->form->renderField('position'); ?>
				<?php echo $this->form->renderField('time'); ?>
				<?php echo $this->form->renderField('agegrade'); ?>
				<?php echo $this->form->renderField('pb'); ?>
				<?php echo $this->form->renderField('agecat'); ?>
				<?php echo $this->form->renderField('catposition'); ?>
				<?php echo $this->form->renderField('comment'); ?>
				<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
				<?php endif; ?>
			</fieldset>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>

</form>
