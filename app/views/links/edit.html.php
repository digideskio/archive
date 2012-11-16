<?php 

$title = $link->title ?: "Link";

$this->title('Edit Link');

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
	<?=$this->html->link('Links','/links'); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
	<?=$this->html->link($title,'/links/view/'.$link->id); ?>
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
			<?=$this->html->link('View','/links/view/'.$link->id); ?>
		</li>

		<li class="active">
			<?=$this->html->link('Edit','/links/edit/'.$link->id); ?>
		</li>
	</ul>

</div>
<div class="well">
<?=$this->form->create($link); ?>
	<legend>Link</legend>
    <?=$this->form->field('title');?>
    <?=$this->form->field('url', array('label' => 'URL'));?>
    <?=$this->form->field('description', array('type' => 'textarea'));?>

	<?=$this->form->hidden('redirect', array('value' => $redirect)); ?>

    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>

	<?php $cancel = $redirect ?: '/links' ?>

    <?=$this->html->link('Cancel',$cancel, array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>


<div class="well">

	<legend>Edit</legend>

	<a class="btn btn-danger" data-toggle="modal" href="#deleteModal">
		<i class="icon-white icon-trash"></i> Delete Link
	</a>

</div>


<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Link</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong>this link</strong>?</p>
			
			<p>By selecting <code>Delete</code>, you will remove this Link from the listings. Are you sure you want to continue?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($link, array('url' => "/links/delete/$link->id", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
