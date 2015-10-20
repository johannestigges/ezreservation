<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

$csspath = JUri::base () . 'components/com_easyreservation/css/occupation.css';
$document = JFactory::getDocument ();
$document->addStyleSheet ( $csspath );
?>

<form
action="<?php echo JRoute::_('index.php?option=com_easyreservation&view=occupation'); ?>"
	method="POST" id="occupationform">
	<h1><?php echo JText::_(COM_EASYRESERVATION_OCCUPATION);?>

	<input type="hidden" name="occupation_date" value="<?php echo $this->occupation_date; ?>" />
	<button id="decrement_date" name="decrement_date" value='1'>-</button>
	<button id="increment_date" name="increment_date" value='1'>+</button>
	</h1>
</form>

<?php echo $this->msg; ?>
