<?php
$menus = !empty($context_menu['menus']) ? $context_menu['menus'] : null;
if (!empty($menus)) {
	$menu = '<div class="btn-toolbar pull-right clearfix">';
	foreach ($menus as $menugroup) {
		if (!empty($menugroup['items'])) {
			$menu .= '<div class="btn-group">';
			foreach ($menugroup['items'] as $item) {
				$menu .= str_replace('<a', '<a class="btn"', $item);
				//$menu .= '<button class="btn">' . $item . '</button>';
			}
			$menu .= '</div>';
		}
	}
	$menu .= '</div>';
}
echo !empty($menu) ? $menu : null;
echo '<div class="clearfix"></div><hr />';
