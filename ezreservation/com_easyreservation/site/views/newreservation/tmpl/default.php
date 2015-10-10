<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

JHtml::calendar ( $this->date->format('Y-m-d'), 'start_date', 'start_date', '%d.%m.%Y' );

echo $this->msg;
?>
<h1><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION)?></h1>

<form action="<?php echo JRoute::_('index.php?option=com_easyreservation&view=newreservation'); ?>" 
	method="post" name="createReservationForm" id="createReservationForm">
	
	<label><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_RESERVABLE);?> 
	<select	name="id_reservable">
	<?php
	foreach ( $this->reservables as $reservable ) {
		echo "<option value=\"" . $reservable->id . "\">" . $reservable->name . "</option>";
	}
	?>
	</select> 
	</label> 
	
	<label><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_DATE);?>  
	<input type="text" id="start_date" name="start_date" 
			value="<?php echo $this->date->format('d.m.Y'); ?>" maxlength="5">
	</label>
	 
	<label><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TIME);?>
	<select name="start_time">
	<?php
	foreach ( range ( 7, 23 ) as $time ) {
		echo "<option>$time:00</option>";
	}
	?>
	</select>
	</label>
	
	<label><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_DURATION);?>  
	<select name="duration">
			<option selected>1</option>
			<option>2</option>
	</select>
	</label>
	
	<button type="submit" name="submit" value="1"><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION_SUBMIT);?> </button>
	<button type="submit" name="cancel" value="1"><?php echo JText::_(COM_EASYRESERVATION_CANCEL);?> </button>
</form>


