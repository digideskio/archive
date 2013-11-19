<?php

$options = $content['options'];
$works = $content['works'];
$parent = $content['parent'];
$artists = $content['artists'];
$inventory = $content['inventory'];
$pdf = $content['pdf'];

$title = "";

$pdf =& $this->Pdf;
$this->Pdf->setCustomLayout(array(
	'header'=>function() use($pdf, $title){
		list($r, $g, $b) = array(255,255,255);
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
//$this->Pdf->SetFont($textfont,'B',20); 

$html = <<<EOD

	<style>
		table {
			font-size: 10pt;
		}

		.image-artwork {
			height: 100px;
		}

		.row-artwork {
			page-break-inside: avoid;
		}

		tr {
			margin-bottom: 12px;
		}

	</style>
EOD;

if (!empty($artists)) {
	$artist_list = implode(', ', $artists);

$html .= <<<EOD

	<h3>$artist_list</h3>

EOD;

}

if (!empty($parent->name)) {

	$heading = $this->escape($parent->name);

$html .= <<<EOD

	<h3>$heading</h3>

EOD;

	$dates = $this->escape($parent->dates());

	if (!empty($dates)) {

$html .= <<<EOD

	<p>$dates</p>

EOD;

	}
}

$html .= <<<EOD

	<table>
		<tbody>

EOD;

foreach ($works as $work) {

$html .= <<<EOD

EOD;

	$document = $work->documents('first');
	if (!empty($document) && !empty($document->id)) {

		$thumbnail = $document->file(array('size' => 'small'));
		$img_path = $options['path'] . '/' . $thumbnail;
		$thumb_img = '<img class="image-artwork" src="'.$img_path.'" />';

$html .= <<<EOD
	
	<tr>
		<td>
	
		<p>$thumb_img</p>

		</td>

	</tr>

EOD;

	}

	$work_artist = $this->escape($work->artist);

	//Don't repeat the artist name if there is only one artist
	$artist = sizeof($artists) > 1 ? $work_artist : NULL;

	$name = $this->escape($work->archive->name);
	$years = $this->escape($work->archive->years());

	$title = !empty($years) ? "$name, $years" : $name;

	$materials = $this->escape($work->materials);
	$dimensions = $this->escape($work->dimensions());

	$price = '';

	if (!empty($inventory)) {
		$sell_price = $work->attribute('sell_price');
		$currency = $this->escape($work->attribute('sell_price_per'));
		$price = $sell_price ? $currency . ' ' . $sell_price : NULL;
	}

	$caption = implode(', ', array_filter(array($artist, $title, $materials, $dimensions)));

$html .= <<<EOD

	<tr>
		<td width="70%">
			<p>$caption</p>
			<p>&nbsp;</p>
		</td>
		<td width="30%">
			<p>$price</p>
		</td>
	</tr>
		

EOD;

}

$html .= <<<EOD

		</tbody>
	</table>


EOD;
$this->Pdf->writeHTML($html, true, false, true, false, '');

?>
