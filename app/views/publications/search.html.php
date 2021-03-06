<?php

$this->title('Publications');

$conditions_list = array(
	'' => 'Search by...',
	'Archives.name' => 'Title',
	'author' => 'Author',
	'publisher' => 'Publisher',
	'editor' => 'Editor',
	'earliest_date' => 'Year',
	'subject' => 'Subject',
	'language' => 'Language',
	'storage_location' => 'Storage Location',
	'storage_number' => 'Storage Number',
	'publication_number' => 'Publication Number',
);

$authority_can_edit = $this->authority->canEdit();

?>

<div id="location" class="row-fluid">

	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Publications','/publications'); ?>
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">

		<li>
			<?=$this->html->link('Index','/publications'); ?>
		</li>

		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">Filter <b class="caret"></b></a>
			<ul class="dropdown-menu">
		<?php foreach($pub_classifications as $pc): ?>
			<li>
				<?=$this->html->link($pc,'/publications?classification='.$pc); ?>
			</li>
		<?php endforeach; ?>
			<li class="divider"></li>
		<?php foreach($pub_types as $pt): ?>
			<li>
				<?=$this->html->link($pt,'/publications?type='.$pt); ?>
			</li>
		<?php endforeach; ?>
			</ul>
		</li>

		<li>
			<?=$this->html->link('Languages','/publications/languages'); ?>
		</li>
		<li>
			<?=$this->html->link('Subjects','/publications/subjects'); ?>
		</li>
		<li>
			<?=$this->html->link('History','/publications/histories'); ?>
		</li>
		<li class="active">
			<?=$this->html->link('Search','/publications/search'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

				<a class="btn btn-inverse" href="/publications/add"><i class="icon-plus-sign icon-white"></i> Add Publication</a>

		<?php endif; ?>

	</div>
<div>

<div class="well">

	<?=$this->form->create(null, array('class' => 'form-inline', 'action' => 'search', 'method' => 'get')); ?>
		<legend>Search Publications</legend>

		<input type="text" name="query" value="<?=$query?>" placeholder="Search…" autocomplete="off">

			<?=$this->form->select('condition', $conditions_list, array('label' => '', 'value' => $condition)); ?>

		<?=$this->form->submit('Submit', array('class' => 'btn btn-inverse')); ?>

	<?=$this->form->end(); ?>

</div>

<?php if($total > 0): ?>

<div id="search-results">

<?=$this->partial->publications(compact('publications')); ?>

</div>

<?=$this->pagination->pager('publications', 'search', $page, $total, $limit, array('condition' => $condition, 'query' => $query, 'limit' => $limit)); ?>

	<?php $condition_class = $condition ? ".info-$condition" : ''; //if we are searching a particular field, only highlight the term in the correct table column ?>

	<?php
		if ($condition == 'Archives.name') {
			$condition_class = '.info-title';
		}
	?>

	<script>

		$(document).ready(function() {

			$("#search-results .table <?=$condition_class?>, #search-results article <?=$condition_class?>").highlight("<?=$query?>");

		 });

	</script>

<?php endif; ?>
