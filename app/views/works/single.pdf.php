<?php

$options = $content['options'];
$works = $content['works'];
$inventory = $content['inventory'];
$pdf = $content['pdf'];

$title = $options['title'];

$pdf =& $this->Pdf;
$this->Pdf->setCustomLayout(array(
	'header'=>function() use($pdf, $title){
		list($r, $g, $b) = array(255,255,255);
		$pdf->SetFillColor($r, $g, $b);
		$pdf->SetTextColor(0 , 136, 204);
		$pdf->Cell(0,15, $title, 0,1,'L', 1);
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

		.following-page {
			page-break-before: always;
		}

		.image-artwork {
			height: 400px;
		}

	</style>
EOD;

$html .= <<<EOD


EOD;

$page_class = "first-page";

foreach ($works as $work) {

	$artwork_helper = new \app\extensions\helper\Artwork();
	$caption = $artwork_helper->caption($work, array('materials' => true, 'separator' => '<br/>'));

	$price = '';

	if (!empty($inventory)) {
		$sell_price = $work->attribute('sell_price');
		$currency = $this->escape($work->attribute('sell_price_per'));
		$price = $sell_price ? $currency . ' ' . $sell_price : NULL;
	}

	$caption = implode('<br/>', array_filter(array($caption, $price)));

    $doc_preview_conditions = array(
        'or' => array(
            array('Formats.mime_type' => 'application/pdf'),
            array('Formats.mime_type' => array('LIKE' => 'image/%'))
        )
    );

    $documents = $work->documents('all', $doc_preview_conditions);
    $output_doc_count = 0;

    foreach ($documents as $document) {

		$thumbnail = $document->file(array('size' => 'small'));
		$img_path = $options['path'] . '/' . $thumbnail;

        if (file_exists($img_path)) {
		    $thumb_img = '<img class="image-artwork" src="'.$img_path.'" />';

$html .= <<<EOD

	<div class="$page_class page-artwork">
	<p>$thumb_img</p>

	<p>$caption</p>
	</div>

EOD;

    	$page_class = 'following-page';
        $output_doc_count++;
        }
    }

    // Output just the caption if no documents were printed
    if($output_doc_count === 0) {

$html .= <<<EOD
	<div class="$page_class page-artwork">
    <p><em>No Preview Available</em></p>
	<p>$caption</p>
	</div>
EOD;
    	$page_class = 'following-page';

    }
}

$this->Pdf->writeHTML($html, true, false, true, false, '');

?>
