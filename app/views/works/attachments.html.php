<?php

$this->title($work->archive->name);

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($work->archive->name,'/works/view/'.$work->archive->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Attachments
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/works/view/'.$work->archive->slug); ?></li>
	<li><?=$this->html->link('Edit','/works/edit/'.$work->archive->slug); ?></li>
	<li class="active">
		<a href="#">
			Attachments
		</a>
	</li>
	<li><?=$this->html->link('History','/works/history/'.$work->archive->slug); ?></li>
</ul>

<div class="row">

	<div class="span5">
		<?=$this->partial->archives_documents_edit(array(
			'model' => $work,
			'archives_documents' => $archives_documents,
		)); ?>		

		<?=$this->partial->archives_links_edit(array(
			'archive' => $work->archive,
			'archives_links' => $archives_links,
		)); ?>		

	</div>

	<div class="span5">

	<?=$this->partial->albums_archives_edit(array(
		'archive' => $work->archive,
		'component_type' => 'albums_works',
		'albums' => $albums,
		'other_albums' => $other_albums,
	)); ?>
	
	<?=$this->partial->exhibitions_archives_edit(array(
		'archive' => $work->archive,
		'component_type' => 'exhibitions_works',
		'exhibitions' => $exhibitions,
		'other_exhibitions' => $other_exhibitions,
	)); ?>

	</div>

</div>

<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Artwork</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$work->archive->name; ?></strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Artwork from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?php $slug = $work->archive->slug; ?>
			<?=$this->form->create($work, array('url' => "/works/delete/$slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
