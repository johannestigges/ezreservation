<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

JHtml::calendar ( date('Y-m-d',strtotime($this->input_data['start_date'])), 'start_date', 'start_date', '%d.%m.%Y' );
$user = JFactory::getUser();

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
	
	<?php 
	if ($user->authorise('core.admin')) { 
		echo '<label>' . JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE); 
		echo '<select name="reservation_type">';
		echo '<option value="1"> ' . JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE1) . '</option>';
		echo '<option value="2"> ' . JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE2) . '</option>';
		echo '<option value="3"> ' . JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE3) . '</option>';
		echo '<option value="4"> ' . JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE4) . '</option>';
		echo '</select>'; 
		echo '</label>'; 
	} else {
		echo '<input type="hidden" name="reservation_type" value="1" />';
	}
	?>
	
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
		$i = 2;
		if ($user->authorise('core.admin')) {
			$i = 12;
		}
		foreach ( range ( 1, $i ) as $duration ) {
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
	<?php echo JHtml::_('form.token');?>
	</form>
