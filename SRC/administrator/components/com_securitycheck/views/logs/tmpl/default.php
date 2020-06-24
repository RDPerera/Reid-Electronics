<?php 

/*
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); 

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Router\Route as JRoute;

$description_array = array(JHtml::_('select.option','TAGS_STRIPPED',JText::_('COM_SECURITYCHECK_TAGS_STRIPPED')),
			JHtml::_('select.option','DUPLICATE_BACKSLASHES',JText::_('COM_SECURITYCHECK_DUPLICATE_BACKSLASHES')),
			JHtml::_('select.option','LINE_COMMENTS',JText::_('COM_SECURITYCHECK_LINE_COMMENTS')),
			JHtml::_('select.option','SQL_PATTERN',JText::_('COM_SECURITYCHECK_SQL_PATTERN')),
			JHtml::_('select.option','IF_STATEMENT',JText::_('COM_SECURITYCHECK_IF_STATEMENT')),
			JHtml::_('select.option','INTEGERS',JText::_('COM_SECURITYCHECK_INTEGERS')),
			JHtml::_('select.option','BACKSLASHES_ADDED',JText::_('COM_SECURITYCHECK_BACKSLASHES_ADDED')),
			JHtml::_('select.option','LFI',JText::_('COM_SECURITYCHECK_LFI')),
			JHtml::_('select.option','IP_BLOCKED',JText::_('COM_SECURITYCHECK_IP_BLOCKED')),
			JHtml::_('select.option','IP_PERMITTED',JText::_('COM_SECURITYCHECK_IP_PERMITTED')),
			JHtml::_('select.option','FORBIDDEN_WORDS',JText::_('COM_SECURITYCHECK_FORBIDDEN_WORDS')),
			JHtml::_('select.option','SESSION_PROTECTION',JText::_('COM_SECURITYCHECK_SESSION_PROTECTION')));
	
$type_array = array(JHtml::_('select.option','XSS',JText::_('COM_SECURITYCHECK_TITLE_XSS')),
			JHtml::_('select.option','XSS_BASE64',JText::_('COM_SECURITYCHECK_TITLE_XSS_BASE64')),
			JHtml::_('select.option','SQL_INJECTION',JText::_('COM_SECURITYCHECK_TITLE_SQL_INJECTION')),
			JHtml::_('select.option','SQL_INJECTION_BASE64',JText::_('COM_SECURITYCHECK_TITLE_SQL_INJECTION_BASE64')),
			JHtml::_('select.option','LFI',JText::_('COM_SECURITYCHECK_TITLE_LFI')),
			JHtml::_('select.option','LFI_BASE64',JText::_('COM_SECURITYCHECK_TITLE_LFI_BASE64')),
			JHtml::_('select.option','IP_PERMITTED',JText::_('COM_SECURITYCHECK_TITLE_IP_PERMITTED')),
			JHtml::_('select.option','IP_BLOCKED',JText::_('COM_SECURITYCHECK_TITLE_IP_BLOCKED')),
			JHtml::_('select.option','SECOND_LEVEL',JText::_('COM_SECURITYCHECK_TITLE_SECOND_LEVEL')),
			JHtml::_('select.option','USER_AGENT_MODIFICATION',JText::_('COM_SECURITYCHECK_TITLE_USER_AGENT_MODIFICATION')),
			JHtml::_('select.option','REFERER_MODIFICATION',JText::_('COM_SECURITYCHECK_TITLE_REFERER_MODIFICATION')),
			JHtml::_('select.option','SESSION_PROTECTION',JText::_('COM_SECURITYCHECK_TITLE_SESSION_PROTECTION')),
			JHtml::_('select.option','SPAM_PROTECTION', JText::_('COM_SECURITYCHECK_SPAM_PROTECTION')));
			
$leido_array = array(JHtml::_('select.option',0,JText::_('COM_SECURITYCHECK_LOG_NOT_READ')),
			JHtml::_('select.option',1,JText::_('COM_SECURITYCHECK_LOG_READ')));

if ( version_compare(JVERSION, '3.20', 'lt') )
{	
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.modal');
	JHTML::_( 'behavior.framework', true );
}

// Add style declaration
$media_url = "media/com_securitycheck/stylesheets/cpanelui.css";
JHTML::stylesheet($media_url);

$bootstrap_css = "media/com_securitycheck/stylesheets/bootstrap.min.css";
JHTML::stylesheet($bootstrap_css);
?>

<form action="<?php echo JRoute::_('index.php?option=com_securitycheck&view=logs');?>" method="post" name="adminForm" id="adminForm">

<div id="filter-bar" class="btn-toolbar">
	<div class="filter-search btn-group pull-left">
		<input type="text" name="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
	</div>
	<div class="btn-group pull-left">
		<button class="btn tip" type="submit" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
		<button class="btn tip" type="button" onclick="document.getElementById('filter_search').value='';this.form.submit();" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
	</div>
	<div class="filter-search btn-group pull-left hidden-phone">
		<?php echo JHTML::_('calendar', $this->getModel()->getState('datefrom',''), 'datefrom', 'datefrom', '%Y-%m-%d', array('onchange'=>'document.adminForm.submit();', 'class' => 'input-small')); ?>	
	</div>
	<div class="filter-search btn-group pull-left hidden-phone">
		<?php echo JHTML::_('calendar', $this->getModel()->getState('dateto',''), 'dateto', 'dateto', '%Y-%m-%d', array('onchange'=>'document.adminForm.submit();', 'class' => 'input-small')); ?>
	</div>
	<div class="btn-group pull-left">
		<select name="filter_description" class="custom-select" onchange="this.form.submit()">
			<option value=""><?php echo JText::_('COM_SECURITYCHECK_SELECT_DESCRIPTION');?></option>
			<?php echo JHtml::_('select.options', $description_array, 'value', 'text', $this->state->get('filter.description'));?>
		</select>
	</div>
	<div class="btn-group pull-left">
		<select name="filter_type" class="custom-select" onchange="this.form.submit()">
			<option value=""><?php echo JText::_('COM_SECURITYCHECK_TYPE_DESCRIPTION');?></option>
			<?php echo JHtml::_('select.options', $type_array, 'value', 'text', $this->state->get('filter.type'));?>
		</select>
	</div>
	<div class="btn-group pull-left">
		<select name="filter_leido" class="custom-select" onchange="this.form.submit()">
			<option value=""><?php echo JText::_('COM_SECURITYCHECK_MARKED_DESCRIPTION');?></option>
			<?php echo JHtml::_('select.options', $leido_array, 'value', 'text', $this->state->get('filter.leido'));?>
		</select>
	</div>
</div>

<div class="clearfix"> </div>

<div>
	<span class="badge" style="background-color: #C68C51; padding: 10px 10px 10px 10px; float:right;"><?php echo JText::_('COM_SECURITYCHECK_LIST_LOGS');?></span>
</div>
	
	<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th class="logs" align="center">
				<?php echo JText::_( "Ip" ); ?>
			</th>
			<th class="logs" align="center">
				<?php echo JText::_( 'COM_SECURITYCHECK_LOG_TIME' ); ?>
			</th>
			<th class="logs" align="center">
				<?php echo JText::_( 'COM_SECURITYCHECK_LOG_DESCRIPTION' ); ?>
			</th>
			<th class="logs" align="center">
				<?php echo JText::_( 'COM_SECURITYCHECK_LOG_URI' ); ?>
			</th>
			<th class="logs" align="center">
				<?php echo JText::_( 'COM_SECURITYCHECK_TYPE_COMPONENT' ); ?>
			</th>
			<th class="logs" align="center">
				<?php echo JText::_( 'COM_SECURITYCHECK_LOG_TYPE' ); ?>
			</th>
			<th class="logs" align="center">
				<?php echo JText::_( 'COM_SECURITYCHECK_LOG_READ' ); ?>
			</th>
			<th class="logs" align="center">
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
			</th>
		</tr>
	</thead>
<?php
$k = 0;
foreach ($this->log_details as &$row) {
?>
<tr>
	<td align="center">
			<?php 
				$ip_sanitized = filter_var($row->ip, FILTER_SANITIZE_STRING);
			echo $ip_sanitized; ?>	
	</td>
	<td align="center">
			<?php echo $row->time; ?>	
	</td>		
	<td align="center">
			<?php $title = JText::_( 'COM_SECURITYCHECK_ORIGINAL_STRING' ); ?>
			<?php $original_string_sanitized = filter_var($row->original_string, FILTER_SANITIZE_STRING); ?>			
			<?php if (strlen($original_string_sanitized)<=60){
						$tip = $original_string_sanitized;
					} else {
						$tip = substr($original_string_sanitized,0,50) .' ' .JText::_( 'COM_SECURITYCHECK_TRUNCATED' );
					}
			?>
			<?php $tag_description_sanitized = filter_var($row->tag_description, FILTER_SANITIZE_STRING); ?>
			<?php echo JText::_( 'COM_SECURITYCHECK_' .$tag_description_sanitized ); ?>
			<?php $description_sanitized = filter_var($row->original_string, FILTER_SANITIZE_STRING); ?>
			<?php echo JText::_( ':' . substr($description_sanitized,0,50) ); ?>
			<?php echo JHtml::tooltip($tip,$title,'tooltip.png','','',false); ?>
	</td>	
	<td align="center">
			<?php 
				$uri_sanitized = filter_var($row->uri, FILTER_SANITIZE_STRING);
			echo substr(($uri_sanitized),0,60); ?>	
	</td>
	<td align="center">
			<?php $component_sanitized = filter_var($row->component, FILTER_SANITIZE_STRING);
			echo substr(($component_sanitized),0,20);	?>				
	</td>
	<td align="center">
		<?php 
			$type_sanitized = filter_var($row->type, FILTER_SANITIZE_STRING);
			$type = $type_sanitized;			
			if ( $type == 'XSS' ){
				echo ('<img src="../media/com_securitycheck/images/xss.png" title="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'" alt="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'">');
			}else if ( $type == 'XSS_BASE64' ){
				echo ('<img src="../media/com_securitycheck/images/xss_base64.png" title="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'" alt="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'">');
			}else if ( $type == 'SQL_INJECTION' ){
				echo ('<img src="../media/com_securitycheck/images/sql_injection.png" title="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'" alt="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'">');
			}else if ( $type == 'SQL_INJECTION_BASE64' ){
				echo ('<img src="../media/com_securitycheck/images/sql_injection_base64.png" title="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'" alt="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'">');
			}else if ( $type == 'LFI' ){
				echo ('<img src="../media/com_securitycheck/images/local_file_inclusion.png" title="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'" alt="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'">');
			}else if ( $type == 'LFI_BASE64' ){
				echo ('<img src="../media/com_securitycheck/images/local_file_inclusion_base64.png" title="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'" alt="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'">');
			}else if ( $type == 'IP_PERMITTED' ){
				echo ('<img src="../media/com_securitycheck/images/permitted.png" title="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'" alt="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'">');
			}else if ( $type == 'IP_BLOCKED' ){
				echo ('<img src="../media/com_securitycheck/images/blocked.png" title="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'" alt="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'">');
			}else if ( $type == 'SECOND_LEVEL' ){
				echo ('<img src="../media/com_securitycheck/images/second_level.png" title="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'" alt="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'">');
			}else if ( $type == 'USER_AGENT_MODIFICATION' ){
				echo ('<img src="../media/com_securitycheck/images/http.png" title="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'" alt="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'">');
			}else if ( $type == 'REFERER_MODIFICATION' ){
				echo ('<img src="../media/com_securitycheck/images/http.png" title="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'" alt="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'">');
			}else if ( $type == 'SESSION_PROTECTION' ){
				echo ('<img src="../media/com_securitycheck/images/session_protection.png" title="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'" alt="' . JText::_( 'COM_SECURITYCHECK_TITLE_' .$row->type ) .'">');
			}else if ( $type == 'SPAM_PROTECTION' ){
				echo ('<img src="../media/com_securitycheck/images/spam_protection.png" title="' . JText::_( 'COM_SECURITYCHECKPRO_TITLE_' .$row->type ) .'" alt="' . JText::_( 'COM_SECURITYCHECKPRO_TITLE_' .$row->type ) .'">');
			}
		?>
	</td>
	<td align="center">
		<?php 
			$marked = $row->marked;			
			if ( $marked == 1 ){
				echo ('<img src="../media/com_securitycheck/images/read.png" title="' . JText::_( 'COM_SECURITYCHECK_LOG_NO_READ_CHANGE' ) .'" alt="' . JText::_( 'COM_SECURITYCHECK_LOG_NO_READ_CHANGE' ) .'">');
			} else {
				echo ('<img src="../media/com_securitycheck/images/no_read.png" title="' . JText::_( 'COM_SECURITYCHECK_LOG_READ_CHANGE' ) .'" alt="' . JText::_( 'COM_SECURITYCHECK_LOG_READ_CHANGE' ) .'">');
			}
		?>
	</td>
	<td align="center">
			<?php echo JHtml::_('grid.id', $k, $row->id); ?>
	</td>
</tr>
<?php
$k = $k+1;
}
?>

</table>

<div>
	<?php echo $this->pagination->getListFooter(); ?></td>
</div>

<div class="clearfix"> </div>

<input type="hidden" name="option" value="com_securitycheck" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="1" />
<input type="hidden" name="controller" value="securitycheck" />
</form>