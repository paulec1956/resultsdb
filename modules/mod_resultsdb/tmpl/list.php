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
$elements = ModResultsdbHelper::getList($params);

$tableField = explode(':', $params->get('field'));
$table_name = !empty($tableField[0]) ? $tableField[0] : '';
$field_name = !empty($tableField[1]) ? $tableField[1] : '';
?>

<?php if (!empty($elements)) : ?>
	<table class="table">
		<?php foreach ($elements as $element) : ?>
			<tr>
				<th><?php echo ModResultsdbHelper::renderTranslatableHeader($table_name, $field_name); ?></th>
				<td><?php echo ModResultsdbHelper::renderElement(
						$table_name, $params->get('field'), $element->{$field_name}
					); ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif;
