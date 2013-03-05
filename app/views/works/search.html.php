<?php

$this->title('Search Artwork');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		Search
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<a href="/works">Index</a>
		</li>

		<li>
			<?=$this->html->link('History','/works/histories'); ?>
		</li>

		<li class="active">
			<?=$this->html->link('Search','/works/search'); ?>
		</li>

	</ul>
	
	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

			<a class="btn btn-inverse" href="/works/add/"><i class="icon-plus-sign icon-white"></i> Add Artwork</a>
		
		<?php endif; ?>
	</div>
</div>

<div class="well">

	<?=$this->form->create(null, array('class' => 'form-inline')); ?>
		<legend>Search Artwork</legend>

		<input type="text" name="query" value="<?=$query?>" placeholder="Search…">

		<?php $selected = 'selected="selected"'; ?>

		<select name="conditions">
			<option value='title'>Title</option>
			<option value='artist' <?php if ($condition == 'artist') { echo $selected; } ?>>Artist</option>
			<option value='classification' <?php if ($condition == 'classification') { echo $selected; } ?>>Classification</option>
			<option value='year' <?php if ($condition == 'year') { echo $selected; } ?>>Year</option>
			<option value='materials' <?php if ($condition == 'materials') { echo $selected; } ?>>Materials</option>
			<option value='remarks' <?php if ($condition == 'remarks') { echo $selected; } ?>>Remarks</option>
			<option value='creation_number' <?php if ($condition == 'creation_number') { echo $selected; } ?>>Artwork ID</option>
			<option value='annotation' <?php if ($condition == 'annotation') { echo $selected; } ?>>Annotation</option>
		</select>

		<?=$this->form->submit('Submit', array('class' => 'btn btn-inverse')); ?>

	<?=$this->form->end(); ?>
	
</div>

<?=$this->partial->works(compact('works')); ?>
