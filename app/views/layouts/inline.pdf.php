<?php
$filename = $content['pdf'];
header("Content-type: application/pdf");
echo $this->Pdf->Output("$filename", 'I');
?>
