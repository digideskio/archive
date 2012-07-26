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
	<?=$this->html->link('Cancel','/publications/view/' . $publication->slug, array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>
