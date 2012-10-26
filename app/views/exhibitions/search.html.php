<?php 

$this->title('Search Exhibitions');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Exhibitions','/exhibitions'); ?>
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
			<?=$this->html->link('Index','/exhibitions'); ?>
		</li>
		<li class="active">
			<?=$this->html->link('Search','/exhibitions/search'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

				<a class="btn btn-inverse" href="/exhibitions/add"><i class="icon-plus-sign icon-white"></i> Add an Exhibition</a>
		
		<?php endif; ?>
	</div>
</div>

<div class="well">

	<?=$this->form->create(null, array('class' => 'form-inline')); ?>
		<legend>Search Exhibitions</legend>

		<input type="text" name="query" value="<?=$query?>" placeholder="Search…">

		<?php $selected = 'selected="selected"'; ?>

		<select name="conditions">
			<option value='title'>Title</option>
			<option value='venue' <?php if ($condition == 'venue') { echo $selected; } ?>>Venue</option>
			<option value='city' <?php if ($condition == 'city') { echo $selected; } ?>>City</option>
			<option value='country' <?php if ($condition == 'country') { echo $selected; } ?>>Country</option>
			<option value='curator' <?php if ($condition == 'curator') { echo $selected; } ?>>Curator</option>
			<option value='year' <?php if ($condition == 'year') { echo $selected; } ?>>Opening Year</option>
			<option value='remarks' <?php if ($condition == 'remarks') { echo $selected; } ?>>Remarks</option>
		</select>

		<?=$this->form->submit('Submit', array('class' => 'btn btn-inverse')); ?>

	<?=$this->form->end(); ?>
	
</div>

<?php foreach($exhibitions as $exhibition): ?>
<article>
	<div class="alert">
	<h1><?=$this->html->link($exhibition->title,'/exhibitions/view/'.$exhibition->slug); ?></h1>
	
	<?php 
		date_default_timezone_set('UTC');
		
		$location = $exhibition->location();
		$dates = $exhibition->dates();
		$curator = $exhibition->curator;
	?>
	
	<?php if($location) echo "<p>$location</p>"; ?>
	<?php if($dates) echo "<p>$dates</p>"; ?>
	<?php if($curator) echo "<p>$curator, Curator</p>"; ?>
	
	<?php 
	
		$has_works = isset($exhibition->exhibitions_works[0]->id) ? true : false;
	
		if ($has_works) echo '<span class="badge badge-info">' . sizeof($exhibition->exhibitions_works) . '</span>';
		
	?>
	
	<span class="badge"><?=$exhibition->type ?> Show</span>
	
	</div>
</article>
<?php endforeach; ?>
