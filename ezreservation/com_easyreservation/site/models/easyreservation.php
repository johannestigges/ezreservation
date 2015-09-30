<?php 

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class EasyReservationModelEasyReservation extends JModelItem
{
	protected $messages;
	
	public function getTable($type = 'EasyReservation',$prefix='EasyReservationTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getMsg($id = 1) {
		if (!isset($this->mesage)) {
			$jinput = JFactory::getApplication()->input;
			$id = $jinput->get('id',1,'INT');
			
			$table = $this->getTable();
			$table->load($id);
			$this->messages[$id] = $table->greeting;
		}
		return $this->messages[$id];
	}
}
?>