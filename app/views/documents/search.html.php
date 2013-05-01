<?php 

$this->title('Search Documents');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Documents','/documents'); ?>
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
			<?=$this->html->link('Index','/documents'); ?>
		</li>
		<li class="active">
			<?=$this->html->link('Search ','/documents/search'); ?>
		</li>

	</ul>

	<div class="btn-toolbar">
		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

				<a class="btn btn-inverse" href="/documents/add"><i class="icon-plus-sign icon-white"></i> Add a Document</a>
		
		<?php endif; ?>

	</div>
</div>

<div class="well">

	<?=$this->form->create(null, array('class' => 'form-inline', 'action' => 'search')); ?>
		<legend>Search Documents</legend>

		<input type="text" name="query" value="<?=$query?>" placeholder="Search…" autocomplete="off">

		<?php $selected = 'selected="selected"'; ?>

		<select name="conditions">
			<option value='title'>Title</option>
			<option value='year' <?php if ($condition == 'year') { echo $selected; } ?>>Year</option>
			<option value='repository' <?php if ($condition == 'repository') { echo $selected; } ?>>Image Repository</option>
			<option value='credit' <?php if ($condition == 'credit') { echo $selected; } ?>>Photo Credit</option>
			<option value='remarks' <?php if ($condition == 'remarks') { echo $selected; } ?>>Remarks</option>
		</select>

		<?=$this->form->submit('Submit', array('class' => 'btn btn-inverse')); ?>

	<?=$this->form->end(); ?>
	
</div>

<?php if($total > 0): ?>

<?=$this->partial->documents(compact('documents')); ?>

<div class="pagination">
    <ul>
	<?php $query = $condition ? "?conditions=$condition&query=$query" : ''; ?>
    <?php if($page > 1):?>
	 <?php $prev = $page - 1; ?>
    <li><?=$this->html->link('«', "/documents/search/$prev$query");?></li> 
    <?php endif;?> 
        <li class="active"><a href=""><?=$page ?> / <?= ceil($total / $limit); ?></a></li>
     <?php if($total > ($limit * $page)):?>
	 <?php $next = $page + 1; ?>
     <li><?=$this->html->link('»', "/documents/search/$next$query");?></li>
     <?php endif;?> 
    </ul>
</div>

<?php endif; ?>
