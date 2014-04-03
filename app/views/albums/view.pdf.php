<?php

$options = $content['options'];

$album = $content['album'];
$title = $album->archive->name;

$works = $content['works'];
$publications = $content['publications'];
$documents = $content['documents'];

$pdf =& $this->Pdf;
$this->Pdf->setCustomLayout(array(
	'header'=>function() use($pdf, $album){
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

foreach( $works as $work) {

$caption = $this->artwork->caption($work);
$notes = $options['view'] === 'notes' ? $this->artwork->notes($work) : '';

$work_documents = $work->documents('all');

	foreach ($work_documents as $doc) {

		$thumbnail = $doc->file(array('size' => 'thumb'));

		$img_path = $options['path'] . '/' . $thumbnail;
		$thumb_img = '<img width="100" src="'.$img_path.'" />';

		$resolution = $doc->resolution();
		$size = $doc->size();

		$credit = "Photo &copy; " . $doc->year() . " " .  $doc->credit;

		$remarks = $doc->remarks;

$html .= <<<EOD

	<tr style="page-break-inside: avoid;">
		<td style="width:150px">
			$thumb_img
		</td>
		<td>
			<p style="color:#08C"><strong>$caption</strong></p>
            <p style="font-size: 0.8em; font-family:kozminproregular">$notes</p>
		</td>
		<td style="width:380px; font-family:monospace; font-size:0.8em;">
			$resolution<br/>
			$size<br/>
			$credit<br/>
			<span style="color: #888888">$remarks</span>
		</td>
	</tr>
EOD;

	}

}

foreach ($documents as $doc) {

		// If the Document belongs to an artwork, we assume that it is preferable
		// to look up that artwork and derive a caption from it, instead of
		// simply using the document's title.
		$doc_work = \app\models\Works::first('all', array(
			'with' => array('Archives', 'ArchivesDocuments'),
			'conditions' => array('ArchivesDocuments.document_id' => $doc->id),
			'order' => 'earliest_date DESC'
		));

		$caption = $doc_work ? $this->artwork->caption($doc_work) : $doc->title;
		$remarks = $doc->remarks;

		if ($doc->published) {

			$thumbnail = $doc->file(array('size' => 'thumb'));

			$img_path = $options['path'] . '/' . $thumbnail;
			$thumb_img = '<img width="100" src="'.$img_path.'" />';

			$resolution = $doc->resolution();
			$size = $doc->size();

			$credit = "Photo &copy; " . $doc->year() . " " .  $doc->credit;

		} else {
			$thumb_img = "Private Image";
			$resolution = "";
			$size = "";
		}

$html .= <<<EOD

	<tr style="page-break-inside: avoid;">
		<td style="width:150px">
			$thumb_img
		</td>
		<td>
			<p style="color:#08C"><strong>$caption</strong></p>
			<p style="color: #888888"><small>$remarks</small></p>
		</td>
		<td style="width:380px; font-family:monospace; font-size:0.8em;">
			$resolution<br/>
			$size<br/>
			$credit
		</td>
	</tr>
EOD;

}

if (sizeof($publications) > 0) {

$html .= <<<EOD
	<tr style="page-break-inside: avoid;">
		<td style="width:150px">
		</td>
		<td>
			<p><strong>Related Publications:</strong></p>
		</td>
		<td style="width:380px; font-family:monospace; font-size:0.8em;">
		</td>
	</tr>

EOD;

}

foreach ($publications as $pub) {

	$pub_title = $pub->archive->name;
	$pub_byline = $pub->byline();
	$pub_publisher = $pub->publisher;
	$pub_dates = $pub->archive->dates();

$html .= <<<EOD
	<tr style="page-break-inside: avoid;">
		<td style="width:150px">
		</td>
		<td>
			<p style="color:#08C"><strong>$pub_title</strong></p>
		</td>
		<td style="width:380px; font-family:monospace; font-size:0.8em;">
			<strong>$pub_publisher</strong><br/>
			$pub_byline<br/>
			$pub_dates
		</td>
	</tr>

EOD;

}

$html .= "</tbody></table>";

$this->Pdf->writeHTML($html, true, false, true, false, '');

?>
