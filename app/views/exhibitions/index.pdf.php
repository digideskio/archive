<?php

$title = 'Archive Exhibitions ' . date('Y-m-d');

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
		/*$footertext = sprintf('Copyright © %d. All rights reserved.', date('Y')); */
		$footertext = sprintf('Page ' . $pdf->PageNo());
		$pdf->SetY(-20); 
		$pdf->SetTextColor(0, 0, 0); 
		$pdf->SetFont(PDF_FONT_NAME_MAIN,'', 8); 
		$pdf->Cell(0,8, $footertext,'T',1,'C');
	}
));
$this->Pdf->setMargins(10,30,10);
$this->Pdf->setFooterMargin(20);
$this->Pdf->SetAuthor('Archive');
$this->Pdf->SetAutoPageBreak(true, 20);

$this->Pdf->AddPage('P', 'A4');
$this->Pdf->SetTextColor(0, 0, 0);

$html = <<<EOD

	<style>
		table {
			font-size: 12pt;
		}

		.meta {
			color: gray;
		}

		td {
			border-top: 1px solid gray;
		}

		h3 {
			font-weight: normal;
			margin-bottom: 0;
		}

		h3 .title {
			font-weight: bold;
		}

		.titles {
			width: 70%;
		}
	
		.dates {
			text-align: right;
			font-family: monospace;
			width: 30%;
		}

		.Solo-show {
			font-weight: bold;
		}
	</style>
EOD;

$html .= <<<EOD

<table cellpadding="5" cellspacing="1" style="width:100%;">

<tbody>

EOD;

foreach ( $exhibitions as $exhibition ) {

	$exhibition_title = $exhibition->title;
	$exhibition_venue = $exhibition->venue;
	$exhibition_type = $exhibition->archive->type;
	$city = $exhibition->city;
	$country = $exhibition->country;
	$location = implode(', ', array_filter(array($city, $country)));
	$dates = $exhibition->archive->dates();


$html .= <<<EOD

	 <tr style="page-break-inside: avoid;">
	 	<td class="titles">
			<h3><span class="title">$exhibition_title</span> $exhibition_venue <small class="meta">$location</small></h3>
		</td>
		<td class="dates $exhibition_type-show">
			<small>$dates</small><br/>
			<small>$exhibition_type Show</small>
		</td>
	</tr>

EOD;

}

$html .= "</tbody></table>";

$this->Pdf->writeHTML($html, true, false, true, false, '');

?>
