<?php

$this->title("Add a Publication");

$this->form->config(
    array( 
        'templates' => array( 
            'error' => '<div class="help-inline">{:content}</div>'
        )
    )
); 

$this->form->config(array('templates' => array(
    
)));

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Publications','/publications'); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Add
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<?=$this->html->link('Index','/publications'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
			<a class="btn btn-inverse disabled" href="#"><i class="icon-plus-sign icon-white"></i> Add a Publication</a>
	</div>
</div>

<div class="well">
<?=$this->form->create($publication); ?>
	<legend>Publication Info</legend>

	<?php $pub_classes_list = array_merge(array('' => 'Choose one...'), $pub_classes_list); ?>

	<?=$this->form->label('classification', 'Category'); ?>
	<?=$this->form->select('classification', $pub_classes_list); ?>
	
	<span class="help-block">Is the publication an interview?</span>
	
	<label class="checkbox">
    <?=$this->form->checkbox('type', array('value' => 'Interview'));?> Interview
    </label>
	
	<?=$this->form->field('author');?>
	<?=$this->form->field('title');?>
	<?=$this->form->field('editor');?>
	<?=$this->form->field('publisher');?>
	<?=$this->form->field('earliest_date');?>
	<?=$this->form->field('latest_date');?>
	<?=$this->form->field('pages');?>
	<?=$this->form->field('url', array('label' => 'Website'));?>
	<?=$this->form->field('subject');?>
	<?=$this->form->field('remarks', array('type' => 'textarea'));?>
	<?=$this->form->field('language');?>
	<?=$this->form->field('storage_location');?>
	<?=$this->form->field('storage_number');?>
	<?=$this->form->field('publication_number', array('label' => 'Publication ID'));?>
	<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
	<?=$this->html->link('Cancel','/publications', array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>
