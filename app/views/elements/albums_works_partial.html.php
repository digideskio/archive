<table class="table table-bordered">

<thead>
	<tr>
		<th>Year</th>
		<th>Image</th>
		<th>Notes</th>
	</tr>
</thead>
		
<tbody>

<?php foreach($works as $work): ?>

<tr>
	<td class="meta"><?=$work->archive->years(); ?> </td>
	<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
	
		<?php 
			$document = $work->documents('first');
		
			if($document->id) {
				$thumbnail = $document->view();
				$work_slug = $work->archive->slug;
				echo "<a href='/works/view/$work_slug'>";
				echo "<img width='125' height='125' src='/files/$thumbnail' />";
				echo "</a>";
			} else {
				echo '<span class="label">No Published Images</span>';
			}
		
		?>
	
	</td>
    <td>
		<h5><?=$this->html->link($work->title,'/works/view/'.$work->archive->slug); ?></h5>
		<p><small><?=$this->artwork->caption($work->archive, $work); ?></small></p>
		<blockquote class="pull-right"><?=$work->annotation ?></blockquote>
</tr>
    
<?php endforeach; ?>

</tbody>
