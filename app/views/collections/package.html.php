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

<p>Your package for <strong><?=$collection->title ?></strong> will be available at:</p>

<code><?=$packages_path ?>/<?=$package ?></code>

</div>