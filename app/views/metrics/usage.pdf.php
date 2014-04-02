<?php

$pdf =& $this->Pdf;
$this->Pdf->setCustomLayout(array(
	'header'=>function() use($pdf, $title){
		list($r, $g, $b) = array(200,200,200);
		$pdf->SetFillColor($r, $g, $b);
		$pdf->SetTextColor(0 , 0, 0);
		$pdf->Cell(0,15, $title, 0,1,'C', 1);
		$pdf->Ln();
	},
	'footer'=>function() use($pdf){
		$footertext = sprintf('Copyright © %d. All rights reserved.', date('Y'));
		$pdf->SetY(-20);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont(PDF_FONT_NAME_MAIN,'', 8);
		//$pdf->Cell(0,8, $footertext,'T',1,'C');
	}
));
$this->Pdf->setMargins(10,30,10);
$this->Pdf->SetAuthor('Archive');
$this->Pdf->SetAutoPageBreak(true);

$this->Pdf->AddPage('P', 'A4');
$this->Pdf->SetTextColor(0, 0, 0);

$dates_all_time = $dates['all_time'];
$dates_month = $dates['month'];
$dates_week = $dates['week'];
$dates_now = $dates['now'];

$intervals_month = $intervals['month'];
$intervals_week = $intervals['week'];

$contributors_total = $contributors['total'];
$works_total = $works['total'];
$exhibitions_total = $exhibitions['total'];
$publications_total = $publications['total'];
$documents_total = $documents['total'];

$contributors_month = $contributors['month'];
$works_month = $works['month'];
$exhibitions_month = $exhibitions['month'];
$publications_month = $publications['month'];
$documents_month = $documents['month'];

$contributors_week = $contributors['week'];
$works_week = $works['week'];
$exhibitions_week = $exhibitions['week'];
$publications_week = $publications['week'];
$documents_week = $documents['week'];

$html = <<<EOD
	<style>
		table {
			font-size: 12pt;
			border-left: 1px solid gray;
			border-top: 1px solid gray;
		}

		td {
			border-bottom: 1px solid gray;
			border-right: 1px solid gray;
		}

		.head {
			text-align: center;
		}

		.meta {
			font-size: 10pt;
			color: gray;
		}

		.stat {
			text-align: right;
		}
	</style>

	<table cellpadding="10" cellspacing="1" style="width:100%;">

	<thead>
		<tr>
			<td class="head"><strong>Metrics</strong></td>
			<td class="head"><strong>All Time</strong><br/><small>$dates_all_time &ndash; $dates_now</small></td>
			<td class="head"><strong>Last Month</strong><br/><small>$dates_month &ndash; $dates_now</small></td>
			<td class="head"><strong>Last Week</strong> <br/><small>$dates_week &ndash; $dates_now</small></td>
		</tr>
	</thead>
	<tbody>
				<tr>
					<td class="meta">
						REPORTING PERIOD
					</td>
					<td class="stat">
						$total_days days
					</td>
					<td class="stat">
						$intervals_month days
					</td>
					<td class="stat">
						$intervals_week days
					</td>
				</tr>
				<tr>
					<td class="meta">
						CONTRIBUTORS
					</td>
					<td class="stat">
						$contributors_total
					</td>
					<td class="stat">
						$contributors_month
					</td>
					<td class="stat">
						$contributors_week
					</td>
				</tr>
				<tr>
					<td class="meta">
						ARTWORKS
					</td>
					<td class="stat">
						$works_total
					</td>
					<td class="stat">
						$works_month
					</td>
					<td class="stat">
						$works_week
					</td>
				</tr>
				<tr>
					<td class="meta">
						EXHIBITIONS
					</td>
					<td class="stat">
						$exhibitions_total
					</td>
					<td class="stat">
					   $exhibitions_month
					</td>
					<td class="stat">
					   $exhibitions_week
					</td>
				</tr>
				<tr>
					<td class="meta">
						PUBLICATIONS
					</td>
					<td class="stat">
						$publications_total
					</td>
					<td class="stat">
					    $publications_month
					</td>
					<td class="stat">
					    $publications_week
					</td>
				</tr>
				<tr>
					<td class="meta">
						UPLOADS
					</td>
					<td class="stat">
						$documents_total
					</td>
					<td class="stat">
						$documents_month
					</td>
					<td class="stat">
						$documents_week
					</td>
				</tr>
				<tr>
					<td colspan="4"></td>
				</tr>
				<tr>
					<td class="meta">NAME</td>
					<td class="meta" colspan="3">EDITS</td>
				</tr>
EOD;

$contributions_month = array();
$contributions_week = array();

foreach ($contributions['month'] as $cm) {
	$username = $cm['username'];
	$records = $cm['records'];
	$contributions_month[$username] = $records;
}

foreach ($contributions['week'] as $cw) {
	$username = $cw['username'];
	$records = $cw['records'];
	$contributions_week[$username] = $records;
}

foreach ($contributions['total'] as $contribution):

$contribution_name = $contribution['name'];
$contribution_username = $contribution['username'];
$contribution_records = $contribution['records'];

$contribution_month_records = isset($contributions_month[$contribution_username]) ? $contributions_month[$contribution_username] : '';
$contribution_week_records = isset($contributions_week[$contribution_username]) ? $contributions_week[$contribution_username] : '';

$html .= <<<EOD

	<tr>
		<td>$contribution_name</td>
		<td class="stat">$contribution_records</td>
		<td class="stat">$contribution_month_records</td>
		<td class="stat">$contribution_week_records</td>
	</tr>

EOD;

endforeach;

$html .= <<<EOD
	</tbody>
	</table>

EOD;

$this->Pdf->writeHTML($html, true, false, true, false, '');

?>
