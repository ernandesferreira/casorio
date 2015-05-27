<?php

class ffAdminScreenFavicon extends ffAdminScreen implements ffIAdminScreen {
	public function getMenu() {
		$menu = $this->_getMenuObject();
		$menu->pageTitle = 'Favicon';
		$menu->menuTitle = 'Favicon';
		$menu->type = ffMenu::TYPE_SUB_LEVEL;
		$menu->capability = 'manage_options';
		$menu->parentSlug='themes.php';
		return $menu;
	}
}
