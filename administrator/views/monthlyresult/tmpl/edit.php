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
		if (task == 'monthlyresult.cancel') {
			Joomla.submitform(task, document.getElementById('monthlyresult-form'));
		}
		else {
			
			if (task != 'monthlyresult.cancel' && document.formvalidator.isValid(document.id('monthlyresult-form'))) {
				
				Joomla.submitform(task, document.getElementById('monthlyresult-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_resultsdb&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="monthlyresult-form" class="form-validate form-horizontal">

	

	
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>

</form>
