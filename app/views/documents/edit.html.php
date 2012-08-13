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


<div class="well">
<?=$this->form->create($document); ?>
	<legend>Info</legend>
	
	<?=$this->form->field('title');?>
	<?=$this->form->field('slug', array('label' => 'Permalink', 'disabled' => 'disabled'));?>
	<?=$this->form->field('file_date');?>
	<?=$this->form->field('repository', array('label' => 'Image Repository'));?>
	<?=$this->form->field('credit', array('label' => 'Photo Credit'));?>
	<?=$this->form->field('remarks', array('type' => 'textarea'));?>
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
