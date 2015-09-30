<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_easyreservation
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
JFormHelper::loadFieldClass('list');
 
/**
 * EasyReservation Form Field class for the EasyReservation component
 *
 * @since  0.0.1
 */
class JFormFieldEasyReservation extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var         string
	 */
	protected $type = 'EasyReservation';
 
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  array  An array of JHtml options.
	 */
	protected function getOptions()
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,name');
		$query->from('#__reservable');
		$db->setQuery((string) $query);
		$messages = $db->loadObjectList();
		$options  = array();
 
		if ($messages)
		{
			foreach ($messages as $message)
			{
				$options[] = JHtml::_('select.option', $message->id, $message->greeting);
			}
		}
 
		$options = array_merge(parent::getOptions(), $options);
 
		return $options;
	}
}