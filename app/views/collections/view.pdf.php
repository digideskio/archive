<?php 

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
		$pdf->Cell(0,8, $footertext,'T',1,'C');
	}
));
$this->Pdf->setMargins(10,30,10);
$this->Pdf->SetAuthor('Archive');
$this->Pdf->SetAutoPageBreak(true);

$this->Pdf->AddPage();
$this->Pdf->SetTextColor(0, 0, 0);
//$this->Pdf->SetFont($textfont,'B',20); 

$html = "<table class='table table-bordered'>";

$html .= <<<EOD

<thead>
	<tr>
		<th>Year</th>
		<th>Image</th>
		<th>Notes</th>
	</tr>
</thead>

EOD;

$html .= "</table";

$this->Pdf->writeHTML($html);
?>
