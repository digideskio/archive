<?php 

$this->title($collection->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
		<?=$this->html->link('Collections','/collections'); ?>
		<span class="divider">/</span>
	</li>

	<li>
		<?=$this->html->link($collection->title,'/collections/view/'.$collection->slug); ?>
		<span class="divider">/</span>
	</li>
	
	<li class="active">
		Package
	</li>

	</ul>

</div>

<div class="well">

<p>Your package for <strong><?=$collection->title ?></strong> is now available!</p>

<?=$this->html->link("Download Package", $package_url, array('class' => 'btn btn-success')); ?>

</div>

