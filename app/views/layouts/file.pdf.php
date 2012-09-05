<?php
$filename = $content['filename'];
echo $this->Pdf->Output("$filename.pdf", 'F');
?>
