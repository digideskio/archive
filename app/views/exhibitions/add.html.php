<?php 

$this->title('Add an Exhibition');

$this->form->config(
    array( 
        'templates' => array( 
            'error' => '<div class="help-inline">{:content}</div>' 
        )
    )
); 

$title_data = json_encode($titles);

$venue_data = json_encode($venues);

$city_data = json_encode($cities);

$country_data = json_encode($countries);

?>

<div id="location" class="row-fluid">

	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
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
			<?=$this->html->link('Index','/exhibitions'); ?>
		</li>
		<li>
			<?=$this->html->link('Venues','/exhibitions/venues'); ?>
		</li>
		<li>
			<?=$this->html->link('History','/exhibitions/histories'); ?>
		</li>
		<li>
			<?=$this->html->link('Search','/exhibitions/search'); ?>
		</li>
		
	</ul>
	<div class="btn-toolbar">
			<a class="btn btn-inverse disabled" href="#"><i class="icon-plus-sign icon-white"></i> Add an Exhibition</a>
	</div>
</div>

<div class="well">
<?=$this->form->create($exhibition); ?>
	<legend>Exhibition Info</legend>
    <?=$this->form->field('title', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $title_data));?>
    <?=$this->form->field('curator', array('autocomplete' => 'off'));?>
    <?=$this->form->field('venue', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $venue_data));?>
    <?=$this->form->field('city', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $city_data));?>
    <?=$this->form->field('country', array('autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $country_data));?>
	<?=$this->form->field('earliest_date', array('autocomplete' => 'off', 'label' => 'Opening Date'));?>
	<?=$this->form->field('latest_date', array('autocomplete' => 'off', 'label' => 'Closing Date'));?>
    <?=$this->form->label('Show Type');?>
    <select name="type">
    	<option value="Solo">Solo</option>
    	<option value="Group">Group</option>
    </select>
    <?=$this->form->field('remarks', array('type' => 'textarea'));?>
    <?=$this->form->field('url', array('autocomplete' => 'off', 'label' => 'URL'));?>
    <?=$this->form->submit('Save', array('class' => 'btn btn-inverse')); ?>
    <?=$this->html->link('Cancel','/exhibitions', array('class' => 'btn')); ?>
<?=$this->form->end(); ?>
</div>
