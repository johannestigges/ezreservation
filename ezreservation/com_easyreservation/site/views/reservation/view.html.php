<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * HTML Contact View class for the Contact component
 *
 * @since  1.5
 */
class EasyReservationViewReservation extends JViewLegacy
{
	/**
	 * The item model state
	 *
	 * @var    \Joomla\Registry\Registry
	 * @since  1.6
	 */
	protected $state;

	/**
	 * The form object for the contact item
	 *
	 * @var    JForm
	 * @since  1.6
	 */
	protected $form;

	/**
	 * The item object details
	 *
	 * @var    JObject
	 * @since  1.6
	 */
	protected $item;

	/**
	 * The page to return to on sumission
	 *
	 * @var         string
	 * @since       1.6
	 * @deprecated  4.0  Variable not used
	 */
	protected $return_page;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display($tpl = null)
	{
		$app        = JFactory::getApplication();
		$user       = JFactory::getUser();
		$state      = $this->get('State');
		$item       = $this->get('Item');
		$this->form = $this->get('Form');

		// Get the parameters
		$params = JComponentHelper::getParams('com_easyreservation');

		if ($item)
		{
			// If we found an item, merge the item parameters
			$params->merge($item->params);
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));

			return false;
		}

		// Check if access is not public
		$groups = $user->getAuthorisedViewLevels();

		$return = '';

		if ((!in_array($item->access, $groups)) || (!in_array($item->category_access, $groups)))
		{
			JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return;
		}



		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->contact  = &$item;
		$this->params   = &$params;
		$this->return   = &$return;
		$this->state    = &$state;
		$this->item     = &$item;
		$this->user     = &$user;
		$this->contacts = &$contacts;

		$item->tags = new JHelperTags;
		$item->tags->getItemTags('com_easyreservation.reservation', $this->item->id);

		$model = $this->getModel();
		$model->hit();
		$this->_prepareDocument();

		return parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 */
	protected function _prepareDocument()
	{
		$app     = JFactory::getApplication();
		$menus   = $app->getMenu();
		$pathway = $app->getPathway();
		$title   = null;
	
		$this->params->def('page_heading', JText::_('COM_CONTACT_DEFAULT_PAGE_TITLE'));

		$title = $this->params->get('page_title', '');

		$id = (int) @$menu->query['id'];

		// If the menu item does not concern this contact
		if ($menu && ($menu->query['option'] != 'com_contact' || $menu->query['view'] != 'contact' || $id != $this->item->id))
		{

			// If this is not a single contact menu item, set the page title to the contact title
			if ($this->item->name)
			{
				$title = $this->item->name;
			}
			$path = array(array('title' => $this->contact->name, 'link' => ''));
			$category = JCategories::getInstance('Contact')->get($this->contact->catid);

			while ($category && ($menu->query['option'] != 'com_contact' || $menu->query['view'] == 'contact' || $id != $category->id) && $category->id > 1)
			{
				$path[] = array('title' => $category->title, 'link' => ContactHelperRoute::getCategoryRoute($this->contact->catid));
				$category = $category->getParent();
			}

			$path = array_reverse($path);

			foreach ($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}
		}

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		if (empty($title))
		{
			$title = $this->item->name;
		}
		$this->document->setTitle($title);

		if ($this->item->metadesc)
		{
			$this->document->setDescription($this->item->metadesc);
		}
		elseif ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->item->metakey)
		{
			$this->document->setMetadata('keywords', $this->item->metakey);
		}
		elseif ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

		$mdata = $this->item->metadata->toArray();

		foreach ($mdata as $k => $v)
		{
			if ($v)
			{
				$this->document->setMetadata($k, $v);
			}
		}
	}
}
