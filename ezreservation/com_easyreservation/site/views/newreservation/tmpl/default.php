<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

JHtml::calendar ( date('Y-m-d',strtotime($this->input_data['start_date'])), 'start_date', 'start_date', '%d.%m.%Y' );

echo $this->msg;
?>

<h1><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION)?></h1>

<form action="<?php echo JRoute::_('index.php?option=com_easyreservation&view=newreservation'); ?>" 
	method="post" name="createReservationForm" id="createReservationForm">
	
	<label><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_RESERVABLE);?> 
		<select	name="id_reservable">
		<?php
		foreach ( $this->reservables as $reservable ) {
			if ($reservable->id == $this->input_data['id_reservable']) {
				echo "<option value=\"" . $reservable->id . "\" selected>" . $reservable->name . "</option>";
			} else {
				echo "<option value=\"" . $reservable->id . "\">" . $reservable->name . "</option>";
			}
		}
		?>
		</select> 
	</label> 
	
	<label><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_DATE);?>  
		<input type="text" id="start_date" name="start_date" 
				value="<?php echo $this->input_data['start_date']; ?>" maxlength="5">
	</label>
	 
	<label><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TIME);?>
		<select name="start_time">
		<?php
		foreach ( range ( 7, 23 ) as $time ) {
			if ($time == $this->input_data['start_time']) {
				echo "<option selected>$time:00</option>";
			} else {
				echo "<option>$time:00</option>";
			}
		}
		?>
		</select>
	</label>
	
	<label><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_DURATION);?>  
		<select name="duration">
		<?php 
		foreach ( range ( 1, 2 ) as $duration ) {
			if ($duration == $this->input_data['duration']) {
				echo "<option selected>$duration</option>";
			} else {
				echo "<option>$duration</option>";
			}
		}
		?>
		</select>
	</label>
	
	<button type="submit" name="submit" value="1"><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION_SUBMIT);?></button>
	<button type="submit" name="cancel" value="1"><?php echo JText::_(COM_EASYRESERVATION_CANCEL);?> </button>
</form>
