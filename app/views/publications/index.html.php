<?php 

$classification = isset($options['classification']) ? $options['classification'] : NULL;
$type = isset($options['type']) ? $options['type'] : NULL;

$this->title('Publications');

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

		<li <?php if (!$classification) { echo 'class="active"'; } ?>>
			<?=$this->html->link('Index','/publications'); ?>
		</li>

		<li class="dropdown <?php if ($classification) { echo 'active'; } ?>">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">Filter <b class="caret"></b></a>
			<ul class="dropdown-menu">
		<?php foreach($pub_classifications as $pc): ?>
			<li <?php if ($pc == $classification) { echo 'class="active"'; } ?>>
				<?=$this->html->link($pc,'/publications?classification='.$pc); ?> 
			</li>
		<?php endforeach; ?>
			<li class="divider"></li>
		<?php foreach($pub_types as $pt): ?>
			<li <?php if ($pt == $type) { echo 'class="active"'; } ?>>
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
		<li>
			<?=$this->html->link('Search','/publications/search'); ?>
		</li>

	</ul>
	<div class="btn-toolbar">
		<?php if($authority_can_edit): ?>

				<a class="btn btn-inverse" href="/publications/add"><i class="icon-plus-sign icon-white"></i> Add Publication</a>
		
		<?php endif; ?>

	</div>
<div>

<?php if($total == 0 && !$classification && !$type): ?>

	<div class="alert alert-danger">There are no Publications in the Archive.</div>

	<?php if($authority_can_edit): ?>

		<div class="alert alert-success">You can add the first Publication by clicking the <strong><?=$this->html->link('Add a Publication','/publications/add/'); ?></strong> button.</div>

	<?php endif; ?>

<?php endif; ?>

<?php if($total > 0): ?>

<?=$this->partial->publications(compact('publications')); ?>

<?=$this->pagination->pager('publications', 'pages', $page, $total, $limit, array('classification' => $classification)); ?>

<?php endif; ?>
