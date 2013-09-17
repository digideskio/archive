<?php

$this->title('Packages');

if($this->authority->timezone()) {
	$tz = new DateTimeZone($this->authority->timezone());
}

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
		<?=$this->html->link('Albums', $this->url(array('Albums::index'))); ?>
		<span class="divider">/</span>
	</li>

	<li class="active">
		Packages
	</li>

	</ul>

</div>

<div class="actions">
	<ul class="nav nav-tabs">
		<li>
			<?=$this->html->link('Index',$this->url(array('Albums::index'))); ?>
		</li>
		<li class="active">
			<?=$this->html->link('Packages',$this->url(array('Albums::packages'))); ?>
		</li>
	</ul>
</div>

<?=$this->partial->packages(compact('packages')); ?>
