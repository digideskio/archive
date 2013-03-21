<?php 

$this->title($architecture->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Architecture','/architectures'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($architecture->title,'/architectures/view/'.$architecture->archive->slug); ?>
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">View</a>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/architectures/edit/'.$architecture->archive->slug); ?></li>
	
	<?php endif; ?>

	<li>
		<?=$this->html->link('History', $this->url(array('Architectures::history', 'slug' => $architecture->archive->slug))); ?>
	</li>

</ul>

<div class="row">
	<div class="span6">
	
		<?=$this->partial->archives_documents(array('archives_documents' => $archives_documents)); ?>

	</div>
	
	<div class="span4">

		<?php if($architecture->annotation): ?>
		
		<div class="popover" style="display:block; position: static; margin-bottom:18px; width:100%">
		<div class="popover-inner">
			<div class="popover-title">
			<strong><?=$architecture->title ?></strong>
			</div>
			<div class="popover-content">
			<p><?=$architecture->annotation ?></p>
			</div>
		</div>
		</div>
		
		<?php endif; ?>
	
		<div class="alert alert-block">
    	<p>
    		<?=$this->architecture->caption($architecture->archive, $architecture); ?>
    	</p>
		</div>
	
		<table class="table">
			<thead>
				<tr>
					<th><i class="icon-road"></i></th>
					<th class="meta"></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><i class="icon-user"></i></td>
					<td class="meta">Client</td>
					<td><?=$architecture->client ?></td>
				</tr>
				<tr>
					<td><i class="icon-hand-right"></i></td>
					<td class="meta">Project Lead</td>
					<td><?=$architecture->project_lead ?></td>
				</tr>
				<tr>
					<td><i class="icon-certificate"></i></td>
					<td class="meta">Consultants</td>
					<td><?=$architecture->consultants ?></td>
				</tr>
				<tr>
					<td><i class="icon-wrench"></i></td>
					<td class="meta">Materials</td>
					<td><?=$architecture->materials ?></td>
				</tr>
				<tr>
					<td><i class="icon-th-large"></i></td>
					<td class="meta">Area</td>
					<td><?=$architecture->dimensions(); ?> </td>
				</tr>
			</tbody>
		
		</table>

		<?php
			$hasDocuments = sizeof($archives_documents) > 0;
		?>

		<?php if ($hasDocuments): ?>

			<table class="table">
				<thead>
					<tr>
						<th><i class="icon-random"></i></th>
						<th class="meta"></th>
						<th></th>
					</tr>
				</thead>
				<tbody>

					<?php if ($hasDocuments) : ?>
					<tr>
						<td><i class="icon-folder-open"></i></td>
						<td class="meta">Documents</td>
						<td>
							<ul class="unstyled" style="margin-bottom:0">
						
			
						<?php foreach($archives_documents as $ad): ?>
				
								<li><a href="/documents/view/<?=$ad->document->slug?>">
									<strong><?=$ad->document->slug?>.<?=$ad->format->extension?></strong>
								</a></li>
				
						<?php endforeach; ?>
						</ul>
						<td>
					</tr>
					<?php endif; ?>
					
				</tbody>
			</table>
		<?php endif; ?>
	
	</div>
</div>
