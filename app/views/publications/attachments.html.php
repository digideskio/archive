<?php

$this->title($publication->title);

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Publications','/publications'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($publication->title,'/publications/view/'.$publication->archive->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Attachments
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li>
		<?=$this->html->link('View', $this->url(array('Publications::view', 'slug' => $publication->archive->slug))); ?>
	</li>
	<li>
		<?=$this->html->link('Edit', $this->url(array('Publications::edit', 'slug' => $publication->archive->slug))); ?>
	</li>
	<li class="active">
		<a href="#">
			Attachments
		</a>
	</li>
	<li>
		<?=$this->html->link('History', $this->url(array('Publications::history', 'slug' => $publication->archive->slug))); ?>
	</li>
</ul>


<div class="row">

	<div class="span5">

	<?=$this->partial->archives_documents_edit(array(
		'model' => $publication,
		'archives_documents' => $archives_documents,
	)); ?>		

	</div>
	
	<div class="span5">

	<?=$this->partial->archives_links_edit(array(
		'model' => $publication,
		'junctions' => $publication_links,
	)); ?>		

	</div>
</div>


