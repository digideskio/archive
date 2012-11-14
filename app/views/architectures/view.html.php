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
	<?=$this->html->link($architecture->title,'/architectures/view/'.$architecture->slug); ?>
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">View</a>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/architectures/edit/'.$architecture->slug); ?></li>
	
	<?php endif; ?>

</ul>

<div class="row">
	<div class="span6">
	
		<ul class="thumbnails">
			
				<?php
					$num_thumbs = sizeof($architecture_documents);
					$span = $num_thumbs > 1 ? 'span3' : 'span6';
					$size = $num_thumbs > 1 ? 'thumb' : 'small';
				?>
		
			<?php foreach($architecture_documents as $ad): ?>
			
				<li class="<?=$span?>">
					<a href="/documents/view/<?=$ad->document->slug?>" class="thumbnail">
						<img src="/files/<?=$ad->document->view(array('action' => $size)); ?>" alt="<?=$ad->document->title ?>">
					</a>
				</li>
			
			<?php endforeach; ?>
			
			<?php if(sizeof($architecture_documents) == 0): ?>
				<li class="<?=$span?>">
				<div class="thumbnail">
				<span class="label label-warning">No Image</span>
				</div>
				</li>
			<?php endif; ?>
		
		</ul>
		
	</div>
	
	<div class="span4">
	
		<div class="alert alert-block">
    	<p>
    		<?php echo $architecture->caption(); ?>
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
			</tbody>
		
		</table>

		<?php
			$hasDocuments = sizeof($architecture_documents) > 0;
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
						
			
						<?php foreach($architecture_documents as $ad): ?>
				
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
