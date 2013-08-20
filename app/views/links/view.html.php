<?php 

$title = $link->title ?: "Link";

$this->title($title);

$authority_can_edit = $this->authority->canEdit();

?>


<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Links','/Links'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($title,'/links/view/'.$link->id); ?>
	</li>

	</ul>

</div>

<div class="actions">

	<ul class="nav nav-tabs">
		<li class="active">
			<?=$this->html->link('View','/links/view/'.$link->id); ?>
		</li>

		<?php if($authority_can_edit): ?>
		<li>
			<?=$this->html->link('Edit','/links/edit/'.$link->id); ?>
		</li>
		<?php endif; ?>
	</ul>

</div>

<div class="alert alert-info">

<h2><?=$link->title ?></h2>

<h4><?=$this->html->link($link->url, $link->url); ?></h4><br/>

<p><?=$link->description ?></p>

<p>
<small style="font-size: smaller;">
	Added <?=$link->date_created ?>
</small>
</p>


</div>

	<?php 
		$has_works = sizeof($works) > 0 ? true : false; 
		$has_exhibitions = sizeof($exhibitions) > 0 ? true : false; 
		$has_publications = sizeof($publications) > 0 ? true : false; 
	?>

	<?php if ($has_works): ?>

		<?=$this->partial->works(array('works' => $works, 'showBar' => true)); ?>

	<?php endif; ?>

	<?php if ($has_exhibitions): ?>

		<?=$this->partial->exhibitions(array('exhibitions' => $exhibitions, 'showBar' => true)); ?>

	<?php endif; ?>

	<?php if ($has_publications): ?>

		<?=$this->partial->publications(array('publications' => $publications, 'showBar' => true)); ?>

	<?php endif; ?>
