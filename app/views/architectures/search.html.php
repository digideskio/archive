<?php 

$this->title('Search Architecture');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Architecture','/architectures'); ?>
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
			<?=$this->html->link('Index','/architectures'); ?>
		</li>
		<li class="active">
			<?=$this->html->link('Search','/architectures/search'); ?>
		</li>

	</ul>

	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

			<a class="btn btn-inverse" href="/architectures/add"><i class="icon-plus-sign icon-white"></i> Add a Project</a>
		
		<?php endif; ?>

	</div>
<div>

<div class="well">

	<?=$this->form->create(null, array('class' => 'form-inline')); ?>
		<legend>Search Architecture</legend>

		<input type="text" name="query" value="<?=$query?>" placeholder="Search…">

		<?php $selected = 'selected="selected"'; ?>

		<select name="conditions">
			<option value='title'>Title</option>
			<option value='client' <?php if ($condition == 'client') { echo $selected; } ?>>Client</option>
			<option value='project_lead' <?php if ($condition == 'project_lead') { echo $selected; } ?>>Project Lead</option>
			<option value='year' <?php if ($condition == 'year') { echo $selected; } ?>>Year</option>
			<option value='status' <?php if ($condition == 'status') { echo $selected; } ?>>Status</option>
			<option value='location' <?php if ($condition == 'location') { echo $selected; } ?>>Location</option>
			<option value='city' <?php if ($condition == 'city') { echo $selected; } ?>>City</option>
			<option value='country' <?php if ($condition == 'country') { echo $selected; } ?>>Country</option>
			<option value='remarks' <?php if ($condition == 'remarks') { echo $selected; } ?>>Remarks</option>
		</select>

		<?=$this->form->submit('Submit', array('class' => 'btn btn-inverse')); ?>

	<?=$this->form->end(); ?>
	
</div>

<table class="table table-bordered">

<thead>
	<tr>
		<th>Image</th>
		<th>Title</th>
		<th>Year</th>
		<th>Notes</th>
	</tr>
</thead>
		
<tbody>

<?php foreach($architectures as $architecture): ?>

<tr>
	
	<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
		<?php $document = $architecture->documents('first'); if($document->id) { ?>	
			<a href="/architectures/view/<?=$architecture->slug?>">
			<img width="125" height="125" src="/files/<?=$document->view(); ?>" />
			</a>
		<?php } else { ?>
			<span class="label label-warning">No Image</span>
		<?php } ?>
	</td>
    <td><?=$this->html->link($architecture->title,'/architectures/view/'.$architecture->slug); ?></td>
    <td><?=$architecture->years(); ?></td>
    <td><?php echo $architecture->caption(); ?></td>
</tr>
    
<?php endforeach; ?>
    
</tbody>
</table>