<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla view library
jimport ( 'joomla.application.component.view' );

// require_once JPATH_COMPONENT . '/views/reservables/view.html.php';

/**
 * HTML View class for the Easy Reservation Component
 */
class EasyReservationViewNewReservation extends JViewLegacy {

	public $reservables;
	public $input_data;
	public $occupations;
	
	// Overwriting JView display method
	function display($tpl = null) {
		
		$this->reservables = $this->get('Reservables');
		$jinput = JFactory::getApplication()->input;

		if ($jinput->get('cancel',null) == '1' || JFactory::getUser()->id == 0) {
			return $this->back();
		}

		$model = $this->getModel();
				
		if ($jinput->get('submit',null ) == '1') {
			JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
			if ($model->newReservation() == true) {
				return $this->back();
			}
		} 

		$this->input_data = $model->getInputData();
		
		// check for errors
		if (count ( $errors = $this->get ( 'Errors' ) )) {
			JLog::dd ( implode ( '<br />', $errors ), JLog::WARNING, 'jerror' );
			print_r($errors);
			return false;
		}

		// Display the view
		parent::display ( $tpl );
	}
	
	private function back() {
		JFactory::getApplication()->redirect( JRoute::_('index.php'));
		return true;
	}
}