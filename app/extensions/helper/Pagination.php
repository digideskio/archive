<?php

namespace app\extensions\helper;

use \lithium\template\helper\Html;

class Pagination extends \lithium\template\Helper {

	public function pager($controller, $action, $page, $total, $limit, $parameters = array()) {

		$pages = ceil($total / $limit);

		$http_query = http_build_query($parameters);

		$html = '<div class="pagination">';
		$html .= '<ul>';

		if ($page > 1) {
			$prev = $page - 1;
			$html .= '<li>' . Html::link('«', "/$controller/$action/$prev?$http_query", array('title' => 'Previous Page')) . '</li>';
		}

		if ($page != 1 && $page > 5) {
			$html .= '<li>' . Html::link('First', "/$controller/$action/1?$http_query") . '</li>'; 
		}
		
		$count = 0;
		for ($p = $page - 4; $p <= $pages && $count < 9; $p++) {
			if($p > 0) {
				$active = $p == $page ? 'active' : '';
				$title = $p == $page ? $p . ' / ' . $pages : $p;
				$html .= "<li class='$active'>" . Html::link($title, "/$controller/$action/$p?$http_query") . '</li>';
				$count++;
			}
		}

		if ($page != $pages && $page < $pages - 4) {
			$html .= '<li>' . Html::link('Last', "/$controller/$action/$pages?$http_query") . '</li>';
		}

		if ($total > ($limit * $page)) {
			$next = $page + 1;
			$html .= '<li>' . Html::link('»', "/$controller/$action/$next?$http_query", array('title' => 'Next Page')) . '</li>'; 
		}

		$html .= '</ul>';
		$html .= '</div>';

		return $html;

	}

}

?>
