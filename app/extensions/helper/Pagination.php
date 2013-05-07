<?php

namespace app\extensions\helper;

use \lithium\template\helper\Html;

class Pagination extends \lithium\template\Helper {

	public function pager($controller, $action, $page, $total, $limit, $query = array()) {

		$http_query = http_build_query($query);

		$html = '<div class="pagination">';
		$html .= '<ul>';

		if ($page > 1) {
			$prev = $page - 1;
			$html .= '<li>' . Html::link('«', "/$controller/$action/$prev?$http_query");
		}
		
		$html .= '<li class="active"><a href="">' . $page . ' / ' . ceil($total / $limit) . '</a></li>';

		if ($total > ($limit * $page)) {
			$next = $page + 1;
			$html .= '<li>' . Html::link('»', "/$controller/$action/$next?$http_query"); 
		}

		$html .= '</ul>';
		$html .= '</div>';

		return $html;

	}

}

?>
