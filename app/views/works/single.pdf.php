<?php

$options = $content['options'];
$works = $content['works'];
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
			font-size: 12pt;
		}

		.image-artwork {
			height: 400px;
		}

	</style>
EOD;

$html .= <<<EOD


EOD;

foreach ($works as $work) {

	$document = $work->documents('first');
	if (!empty($document) && !empty($document->id)) {

		$thumbnail = $document->file(array('size' => 'small'));
		$img_path = $options['path'] . '/' . $thumbnail;
		$thumb_img = '<img class="image-artwork" src="'.$img_path.'" />';

$html .= <<<EOD
	
	<p>$thumb_img</p>

EOD;

	}

	$artist = $this->escape($work->artist);
	$name = $this->escape($work->archive->name);
	$years = $this->escape($work->archive->years());

	$title = !empty($years) ? "$name, $years" : $name;

	$materials = $this->escape($work->materials);
	$dimensions = $this->escape($work->dimensions());

	$price = '';

	if (!empty($inventory)) {
		$sell_price = (float) $work->attribute('sell_price');
		$currency = $this->escape($work->attribute('sell_price_per'));
		$price = $sell_price ? $currency . ' ' . number_format($sell_price) : NULL;
	}

	$caption = implode('<br/>', array_filter(array($artist, $title, $materials, $dimensions, $price)));

$html .= <<<EOD

	<p>$caption</p>

EOD;

}

$this->Pdf->writeHTML($html, true, false, true, false, '');

?>
