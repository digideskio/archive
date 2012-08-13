<?php

$this->title($publication->title);

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
	<?=$this->html->link('Publications','/publications'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($publication->title,'/publications/view/'.$publication->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/publications/view/'.$publication->slug); ?></li>
	<li class="active">
		<a href="#">
			Edit
		</a>
	</li>
</ul>


<div class="well">
<?=$this->form->create($publication); ?>
	<legend>Info</legend>
	
	 <span class="help-block">Is the publication an interview?</span>
	
	<label class="checkbox">
    <?=$this->form->checkbox('interview');?> Interview
    </label>
    
	<?=$this->form->field('author');?>
	<?=$this->form->field('title');?>
	<?=$this->form->field('publisher');?>
	<?=$this->form->field('earliest_date', array('value' => $publication->start_date()));?>
	<?=$this->form->field('latest_date', array('value' => $publication->end_date()));?>
	<?=$this->form->field('pages');?>
	<?=$this->form->field('url', array('label' => 'Website'));?>
	<?=$this->form->field('subject');?>
	<?=$this->form->field('remarks', array('type' => 'textarea'));?>
	<?=$this->form->field('language');?>
	<?=$this->form->field('location');?>
	<?=$this->form->field('location_code');?>
	<?=$this->form->field('publication_number', array('label' => 'Publication ID'));?>
	<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
	<?=$this->html->link('Cancel','/publications/view/' . $publication->slug, array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>


		
<div class="well">

	<legend>Edit</legend>

	<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
		<i class="icon-white icon-trash"></i> Delete Publication
	</a>

</div>



<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Publication</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$publication->title; ?></strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Publication from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($publication, array('url' => "/publications/delete/$publication->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
