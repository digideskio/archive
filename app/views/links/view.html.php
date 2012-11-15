<?php 

$title = $link->title ?: "Link";

$this->title($title);

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

		<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
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

<p>Added <?=$link->date_created ?></p>

</div>
