<?php

$this->title($document->title);

$this->form->config(
    array( 
        'templates' => array( 
            'error' => '<div class="help-inline">{:content}</div>' 
        )
    )
); 

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Documents','/documents'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($document->title,'/documents/view/'.$document->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/documents/view/'.$document->slug); ?></li>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
</ul>

<div class="row">

	<div class="span5">

		<div class="well">
		<?=$this->form->create($document); ?>
			<legend>Info</legend>
			
			<?=$this->form->field('title', array('autocomplete' => 'off'));?>
			<?=$this->form->field('file_date', array('autocomplete' => 'off', 'disabled' => 'disabled'));?>
			<?=$this->form->field('repository', array('label' => 'Image Repository', 'autocomplete' => 'off'));?>
			<?=$this->form->field('credit', array('label' => 'Photo Credit', 'autocomplete' => 'off'));?>
			<?=$this->form->field('remarks', array('type' => 'textarea'));?>
			
			<label class="checkbox">
			<?=$this->form->checkbox('published');?> <strong>Approved for Publication</strong>
			</label><br/>

			<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
			<?=$this->html->link('Cancel','/documents/view/' . $document->slug, array('class' => 'btn')); ?>
		<?=$this->form->end(); ?>
		</div>


		<div class="well">

			<legend>Edit</legend>

			<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
				<i class="icon-white icon-trash"></i> Delete Document
			</a>

		</div>

	</div>

	<div class="span5">

		<div class="well">
			<legend>Albums</legend>
			<table class="table">
			
				<?php foreach($albums as $album): ?>
				<?php $archive_doc = $album->archives_documents->first(); ?> 
					<tr>
						<td>
							<?=$this->html->link($album->title, $this->url(array('Albums::view', 'slug' => $album->archive->slug))); ?>
						</td>
						<td align="right" style="text-align:right">
				<?=$this->form->create($archive_doc, array('url' => $this->url(array('ArchivesDocuments::delete', 'id' => $archive_doc->id)), 'method' => 'post')); ?>
				<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
				<?=$this->form->end(); ?>
						</td>
					</tr>
				
				<?php endforeach; ?>
				
				<?php if(sizeof($other_albums) > 0): ?>
				
				<tr>
					<td></td>
					<td align="right" style="text-align:right">
						<a data-toggle="modal" href="#albumModal" class="btn btn-mini btn-inverse">Add an Album</a>
					</td>
				</tr>
				
				<?php endif; ?>
				
				</table>
			
		</div>

	</div>

</div>

<div class="modal fade hide" id="albumModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Add this Document to an Album</h3>
		</div>
		<div class="modal-body">
			<table class="table"><tbody>
			<?php foreach($other_albums as $oc): ?>
				<tr>
					<td>
						<strong>
							<?=$this->html->link($oc->title, $this->url(array('Albums::view', 'slug' => $oc->archive->slug))); ?>
						</strong><br/>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($oc, array('url' => $this->url(array('ArchivesDocuments::add')), 'method' => 'post')); ?>
			<input type="hidden" name="document_id" value="<?=$document->id ?>" />
			<input type="hidden" name="archive_id" value="<?=$oc->archive->id ?>" />
			<?=$this->form->submit('Add', array('class' => 'btn btn-mini btn-success')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody></table>
			</div>
			<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
	</div>
</div>

<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Document</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$document->title; ?></strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will erase this Document from the archive. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($document, array('url' => "/documents/delete/$document->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
