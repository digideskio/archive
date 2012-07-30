<?php 

$this->title('Add a Project');

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
	<?=$this->html->link('Architecture','/architectures'); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		Add a Project
	</li>

	</ul>

</div>

<div class="well">
<?=$this->form->create($architecture); ?>
    <?=$this->form->field('title');?>
    <?=$this->form->field('client');?>
    <?=$this->form->field('project_lead');?>
	<?=$this->form->field('remarks');?>
    <?=$this->form->field('earliest_date', array('label' => 'Design Date'));?>
    <?=$this->form->field('latest_date', array('label' => 'Completion Date'));?>
    <?=$this->form->field('status', array('label' => 'Project Status'));?>
    <?=$this->form->field('location');?>
    <?=$this->form->field('city');?>
    <?=$this->form->field('country');?>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/architectures', array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>
