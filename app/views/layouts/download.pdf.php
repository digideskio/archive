<?php
$filename = $content['filename'];
header("Content-type: application/pdf");
echo $this->Pdf->Output("$filename.pdf", 'D');
?>
