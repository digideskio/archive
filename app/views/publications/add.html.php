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


<ul class="nav nav-tabs">
	<li>
		<?=$this->html->link('Index','/publications'); ?>
	</li>

	<span class="action">
		<a class="btn btn-inverse disabled" href="#"><i class="icon-plus-sign icon-white"></i> Add a Publication</a>
	</span>
	
</ul>

<div class="well">
<?=$this->form->create($publication); ?>
	<legend>Publication Info</legend>
	
	 <span class="help-block">Is the publication an interview?</span>
	
	<label class="checkbox">
    <?=$this->form->checkbox('interview');?> Interview
    </label>
	
	<?=$this->form->field('author');?>
	<?=$this->form->field('title');?>
	<?=$this->form->field('publisher');?>
	<?=$this->form->field('earliest_date');?>
	<?=$this->form->field('latest_date');?>
	<?=$this->form->field('pages');?>
	<?=$this->form->field('url', array('label' => 'Website'));?>
	<?=$this->form->field('subject');?>
	<?=$this->form->field('remarks', array('type' => 'textarea'));?>
	<?=$this->form->field('language');?>
	<?=$this->form->field('location');?>
	<?=$this->form->field('location_code');?>
	<?=$this->form->field('publication_number', array('label' => 'Publication ID'));?>
	<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
	<?=$this->html->link('Cancel','/publications', array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>
