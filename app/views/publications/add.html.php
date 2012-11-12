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
	
	<?=$this->form->label('type', 'Category'); ?>
	<select name="type">
		<option value=''>Choose one...</option>
		<?php foreach($publications_types as $pt): ?>
		<option value="<?=$pt ?>" <?php if ($publication->type == $pt) { echo 'selected="selected"'; } ?>>
			<?=$pt ?>
		</option>
		<?php endforeach; ?>
	</select>
	 <span class="help-block">Is the publication an interview?</span>
	
	<label class="checkbox">
    <?=$this->form->checkbox('interview');?> Interview
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
	<?=$this->form->field('location');?>
	<?=$this->form->field('location_code');?>
	<?=$this->form->field('publication_number', array('label' => 'Publication ID'));?>
	<?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
	<?=$this->html->link('Cancel','/publications', array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>
