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

</div>

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
						<img src="/files/<?=$size?>/<?=$ad->document->slug?>.jpeg" alt="<?=$ad->document->title ?>">
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
	
	</div>
</div>
