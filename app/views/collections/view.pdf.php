<?php 

$options = $content['options'];

$collection = $content['collection'];
$title = $collection->title;

$collections_works = $content['collections_works'];

$pdf =& $this->Pdf;
$this->Pdf->setCustomLayout(array(
	'header'=>function() use($pdf, $collection){
		list($r, $g, $b) = array(200,200,200);
		$pdf->SetFillColor($r, $g, $b); 
		$pdf->SetTextColor(0 , 0, 0);
		$pdf->Cell(0,15, $collection->title, 0,1,'C', 1);
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

$this->Pdf->AddPage('L', 'A4');
$this->Pdf->SetTextColor(0, 0, 0);
//$this->Pdf->SetFont($textfont,'B',20); 

$html = <<<EOD

	<style>
		table {
			font-size: 12pt;
		}
	</style>
EOD;

$html .= <<<EOD

<table cellpadding="5" cellspacing="1" style="width:100%;">

<tbody>

EOD;

foreach( $collections_works as $cw) {

$work = $cw->work;
$years = $work->years();
$caption = $work->caption();
$annotation = $work->annotation;
$notes = $work->notes();

if(isset($options['view']) && $options['view'] == 'images') {

	$documents = $work->documents();

	foreach($documents as $doc) {

	$thumbnail = $doc->thumbnail(array('hash' => true));

	$img_path = 'files/small/'.$thumbnail;
	$thumb_img = '<img width="100" src="'.$img_path.'" />';

	
	$dpi = 300;
	$width = number_format($doc->width * 2.54 / $dpi, 2);
	$height = number_format($doc->height * 2.54 / $dpi, 2);


$html .= <<<EOD

	<tr style="page-break-inside: avoid;">
		<td style="width:150px">
			$thumb_img	
		</td>
		<td>
			<p style="color:#08C"><strong>$caption</strong></p>
		</td>
		<td style="width:380px; font-family:monospaced;">
			$doc->width × $doc->height px<br/>
			$width × $height cm @ $dpi dpi
		</td>
	</tr>
EOD;

	}

}

if(!isset($options['view']) || $options['view'] == 'artwork') {

$thumbnail = $work->preview(array('hash' => true));

if( $thumbnail ) {
	$img_path = 'files/thumb/'.$thumbnail;
	$thumb_img = '<img width="100" height="100" src="'.$img_path.'" />';
}

$html .= <<<EOD

	<tr style="page-break-inside: avoid;">
		<td style="width:150px">
			$thumb_img	
		</td>
		<td>
			<p style="color:#08C"><strong>$caption</strong></p>
			<p style="color: #888888"><small>$annotation</small></p>
		</td>
		<td style="width:380px">
			<p><small>$notes</small></p>
		</td>
	</tr>

EOD;


}

}
$html .= "</tbody></table>";

$this->Pdf->writeHTML($html, true, false, true, false, '');

?>
