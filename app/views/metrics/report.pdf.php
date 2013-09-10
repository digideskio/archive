<?php 

$title = 'Archive Report ' . $dates['now'];

$auth = $this->authority->auth();

if($auth->timezone_id) {
	$tz = new DateTimeZone($auth->timezone_id);
}

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
$this->Pdf->SetFont('cid0cs');

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
			<td class="head"><strong>Updates</strong></td>
		</tr>
	</thead>
	<tbody>
EOD;

if ($updates->count()):
	foreach ($updates as $update):
		$subject = $update->subject;
		$body = $update->body;
		$date = $update->date_created;

$html .= <<<EOD

	<tr>
		<td>
				<strong>$subject</strong> &mdash; $body
				<small class="meta">$date</small>
		</td>
	</tr>
	
EOD;

	endforeach;
endif;

$html .= <<<EOD
	</tbody>
	</table>
	<br/>
EOD;

$html .= <<<EOD

EOD;

if ($archives->count()):

	$last_controller = '';

	foreach ($archives as $archive):
		$start_date_time = new DateTime($archive->date_modified);

		if (isset($tz)) {
			$start_date_time->setTimeZone($tz);
		}

		$start_date_display = $start_date_time->format("Y-m-d");

		$name = $archive->name;
		$classification = $archive->classification;
		$user = $archive->user->name;

		$controller = \lithium\util\Inflector::humanize($archive->controller);

		if ($controller != $last_controller):

			if ($last_controller != ''):

$html .= <<<EOD
	</tbody>
	</table>
	<br/>

EOD;
			endif;
	
$html .= <<<EOD
	<table cellpadding="10" cellspacing="1" style="width:100%;">

	<thead>
		<tr>
			<td colspan="2" class="head"><strong>$controller</strong></td>
		</tr>
	</thead>
	<tbody>

EOD;
		$last_controller = $controller;

		endif;

$html .= <<<EOD

	<tr>
		<td style="width: 70%">
				<strong>$name</strong>
				<span class="meta">$classification</span>
		</td>
		<td style="width: 30%">
			<small>$start_date_display</small><br/>
			<small><strong>$user</strong></small>
		</td>
	</tr>
	
EOD;

	endforeach;
endif;

$html .= <<<EOD
	</tbody>
	</table>
EOD;

$this->Pdf->writeHTML($html, true, false, true, false, '');

?>
