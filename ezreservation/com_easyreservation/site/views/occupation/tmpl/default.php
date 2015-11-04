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

	<input type="hidden" name="start_date" value="<?php echo $this->start_date; ?>" />
	<button id="decrement_week" name="decrement_week" value="1"
			title="<?php echo JText::_(COM_EASYRESERVATION_OCCUPATION_DECREMENT_WEEK); ?>"><<</button>
	<button id="decrement_date" name="decrement_date" value="1"
			title="<?php echo JText::_(COM_EASYRESERVATION_OCCUPATION_DECREMENT_DAY); ?>"><</button>
	<button id="actual_date"    name="actual_date" value="1"
			title="<?php echo JText::_(COM_EASYRESERVATION_OCCUPATION_ACTUAL); ?>">+</button>
	<button id="increment_date" name="increment_date" value="1"
			title="<?php echo JText::_(COM_EASYRESERVATION_OCCUPATION_INCREMENT_DAY); ?>">></button>
	<button id="increment_week" name="increment_week" value="1"
			title="<?php echo JText::_(COM_EASYRESERVATION_OCCUPATION_INCREMENT_WEEK); ?>">>></button>
	</h1>
</form>
<?php echo $this->msg; ?>
