<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla view library
jimport ( 'joomla.application.component.view' );

/**
 * HTML View class for the Easy Reservation Component
 */
class EasyReservationViewReservables extends JViewLegacy {
	
	protected $reservables;
	
	// Overwriting JView display method
	function display($tpl = null) {
		$this->msg = '<h1>' . JText::_('COM_EASYRESERVATION_RESERVABLES').'</h1>';
		
		foreach ( $this->get ( 'Reservables' ) as $reservable ) {
			$this->msg .= ('<br />' . $reservable->id . ' ' . $reservable->name);
		}
		
		
		// check for errors
		if (count ( $errors = $this->get ( 'Errors' ) )) {
			JLog::dd ( implode ( '<br />', $errors ), JLog::WARNING, 'jerror' );
			return false;
		}
		
		// Display the view
		parent::display ( $tpl );
	}
	
	/**
	 * liest alle reservables über das Model ein
	 */
	private function getReservables() {
		$this->reservables = $this->get('Reservables');
	}
	
	/**
	 * liefert ein Reservable zur ID
	 * @param unknown $id
	 * @return unknown
	 */
	public function reservable($id) {
		if (!isset($this->reservables)) {
			$this->getReservables();
		}
		foreach ($this->reservables as $reservable) {
			if ($reservable->id == $id) {
				return $reservable;
			}
		}
	} 
}