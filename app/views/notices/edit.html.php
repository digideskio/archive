
<?php 

$this->title('Edit Notice');

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
	<?=$this->html->link('Notices','/Notices'); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Edit
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<?=$this->html->link('Index','/notices'); ?>
		</li>

	</ul>

	<div class="btn-toolbar">
			<a class="btn btn-inverse" href="/notices/add"><i class="icon-plus-sign icon-white"></i> Write a Notice</a>
	</div>
</div>

<div class="well">
<?=$this->form->create($notice); ?>
	<legend>Notice</legend>
    <?=$this->form->field('subject');?>
    <?=$this->form->field('body', array('type' => 'textarea'));?>
    <?=$this->form->hidden('path', array('value' => 'home'));?>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/notices', array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>


<div class="well">

	<legend>Edit</legend>

	<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
		<i class="icon-white icon-trash"></i> Delete Notice
	</a>

</div>


<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Notice</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong>this notice</strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Notice from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($notice, array('url' => "/notices/delete/$notice->id", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
