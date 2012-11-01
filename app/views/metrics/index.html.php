<?php 

$this->title('Metrics');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Metrics','/metrics'); ?>
	</li>

	</ul>

</div>

<div class="hero-unit">

<h1>The Archive holds:</h1>

<hr/>

<!--<p><strong><?=$collections ?></strong> collections comprising <strong><?=$collections_works ?></strong> artworks.</p>-->

<p><strong><?=$works ?></strong> artworks and <strong><?=$architectures ?></strong> architecture projects.</p>

<p><strong><?=$exhibitions ?></strong> exhibitions which featured <strong><?=$exhibitions_works ?></strong> artworks, including <strong><?=$solo_shows ?></strong> solo shows and <strong><?=$group_shows ?></strong> group shows.</p>

<p><strong><?=$publications ?></strong> publications with <strong><?=$publications_documents ?></strong> attachments.</p>

<p>The total number of uploaded files is  <strong><?=$documents ?></strong>.</p>


</div>
