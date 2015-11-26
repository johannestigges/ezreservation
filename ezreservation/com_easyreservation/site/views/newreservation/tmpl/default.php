<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// add css
$csspath = JUri::base () . 'components/com_easyreservation/css/newreservation.css';
$document = JFactory::getDocument ();
$document->addStyleSheet ( $csspath );

$user = JFactory::getUser();

JHtml::calendar ( date('Y-m-d',strtotime($this->input_data['start_date'])), 'start_date', 'start_date', '%d.%m.%Y' );

if ($user->authorise('core.admin')) {
	JHtml::calendar ( date('Y-m-d',strtotime($this->input_data['end_day'])), 'end_day', 'end_day', '%d.%m.%Y' );
}

function option($value, $msg, $selectedValue) {
	if ($value == $selectedValue) {
		$selected = 'selected';
	}
	if (isset($value)) {
		$v = "value='$value'";
	}
	echo "<option $v $selected>$msg</option>\n";
}

echo $this->msg;
?>

<h1><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION)?></h1>

<form action="<?php echo JRoute::_('index.php?option=com_easyreservation&view=newreservation'); ?>" 
	method="post" name="createReservationForm" id="createReservationForm">
	
	<label><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_RESERVABLE);?> 
		<select	name="id_reservable">
		<?php
		foreach ( $this->reservables as $reservable ) {
			option($reservable->id,$reservable->name,$this->input_data['id_reservable']);
		}
		?>
		</select> 
	</label> 
	
	<?php 
	if ($user->authorise('core.admin')) { 
		echo '<label>' . JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE); 
		echo '<select name="reservation_type">';
		option(1, JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE1), $this->input_data['reservation_type']);
		option(2, JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE2), $this->input_data['reservation_type']);
		option(3, JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE3), $this->input_data['reservation_type']);
		option(4, JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TYPE4), $this->input_data['reservation_type']);
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
		$max_duration = 2;
		if ($user->authorise('core.admin')) {
			$max_duration = 12;
		}
		foreach ( range ( 1, $max_duration ) as $duration ) {
			if ($duration == $this->input_data['duration']) {
				echo "<option selected>$duration</option>";
			} else {
				echo "<option>$duration</option>";
			}
		}
		?>
		</select>
	</label>

	
	<?php if ($user->authorise('core.admin')) { ?>
	<label><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_TEXT);?>  
		<input type="text" id="reservation_name" name="reservation_name" 
				value="<?php echo $this->input_data['reservation_name']; ?>" maxlength="50">
	</label>
		
	<label><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION_LABEL_ENDDAY);?>  
		<input type="text" id="end_day" name="end_day" 
				value="<?php echo $this->input_data['end_day']; ?>" maxlength="5">
	</label>

	<?php 
	$occupations = $this->get('Occupations');
	if (count($occupations) > 1) {
		echo '<h3>' . JText::_(COM_EASYRESERVATION_NEW_RESERVATION_WEEKLY_OCCUPATIONS) . "</h3>\n";
		echo '<table>';
		foreach ($occupations as $occupation) {
			echo '<tr>';
			echo '<td>'. date('d.m.Y', $occupation['start_time']) . '</td>';
			echo '<td>'. date('H:i', $occupation['start_time']) . '</td>';
			echo '<td>-</td>';
			echo '<td>'. date('H:i', $occupation['end_time']). '</td>';
			echo '<td>' . (1 == $occupation['occupied'] ? '<div class="occupied">'. JText::_(COM_EASYRESERVATION_NEW_RESERVATION_OCCUPIED) .'</div>' : '&nbsp;') . '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
	?>
	<?php } ?>
	
	<button type="submit" name="submit" value="1"><?php echo JText::_(COM_EASYRESERVATION_NEW_RESERVATION_SUBMIT);?></button>
	<button type="submit" name="cancel" value="1"><?php echo JText::_(COM_EASYRESERVATION_CANCEL);?> </button>
	<?php echo JHtml::_('form.token');?>
	</form>
