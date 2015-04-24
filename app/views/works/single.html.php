<?php

use li3_filesystem\extensions\storage\FileSystem;

$this->title($work->archive->name);

?>

<meta charset="UTF-8">

<script>
    document.title = "<?=$work->archive->name ?>";
</script>

<style>

@media all {

html, body {
    text-align: center;
}

.logo {
    margin-top: 60px;
    margin-bottom: 80px;
}

.image {
    height: 360;
}

.caption {
    line-height: 2em;
    font-weight: bold;
}

.caption em {
    font-style: normal;
}

.footer {
    display: block;
    position: absolute;
    bottom: 50;
    width: 100%;
    text-align: center;
    font-size: 10px;
    color: #888;
}

}

</style>

<div class="logo">
</div>

<?php

if ($document && $document->id) :

    $file_name = $document->title . '.' .$document->format->extension;
    $filename = $document->file();
    $file_data = base64_encode(FileSystem::read('documents', $filename));
    $file_mime = $document->format->mime_type;
    $img_url = $this->url(array("Files::small", 'slug' => $document->slug, 'file' => $file_name));
?>

    <p>
    <img class="image" src="data:<?=$file_mime ?>;base64,<?php echo $file_data; ?>" title="<?=$file_name ?>"/>
    </p>

<?php endif; ?>

<div class="caption">
<p>
<?=$this->artwork->caption($work, array('materials' => true, 'separator' => '<br/>', 'terminator' => '')); ?>
</p>
</div>

<div class="footer">
<?=$host ?>
</div>
