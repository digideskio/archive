<table class="table table-striped table-bordered">

<tbody>

<?php foreach ($links as $link): ?>

<tr <?php if (isset($saved) && $link->id == $saved) { echo 'class="success"'; } ?>>
	<td>
		<?php $title = $link->title ?: $link->url; ?>

		<p class="lead" style="margin-bottom: 0">
			<?=$this->html->link($title, $this->url(array('Links::view', 'id' => $link->id))); ?>

			<small style="font-size: smaller;">
				<?php $date = date('Y-m-d', strtotime($link->date_created)); ?>
					<?=$date ?>
			</small>

				<?php if (isset($saved) && $link->id == $saved): ?>
					<span class="label">Saved</span>
				<?php endif; ?>

		</p>

		<p>
			<strong>
				<?=$this->html->link($link->url) ?>
			</strong>
		</p>
	
		<?php if (!empty($link->description)): ?>
		<blockquote><?=$link->description ?></blockquote>
		<?php endif; ?>

	</td>
</tr>

<?php endforeach; ?>

</tbody>

</table>
