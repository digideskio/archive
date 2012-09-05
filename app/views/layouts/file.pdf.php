<?php
$filename = $content['pdf'];
echo $this->Pdf->Output("$filename", 'F');
?>
