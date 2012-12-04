<?php $c = strtolower($this->request()->params['controller']); ?>

<div id="sidebar"  class="span2">
<div class="row">
	<div class="span2 affix">
	<div class="well" style="padding: 8px 0;">
		<ul class="nav nav-list">
			<li <?php if ($c == 'works') echo 'class="active"'; ?> >
				<a href="/works"><i class="icon-picture"></i> Artwork</a></li>
			<li <?php if ($c == 'architectures') echo 'class="active"'; ?> >
				<a href="/architectures"><i class="icon-road"></i> Architecture</a></li>
			<li <?php if ($c == 'exhibitions') echo 'class="active"'; ?> >
				<a href="/exhibitions"><i class="icon-eye-open"></i> Exhibitions</a></li>
			<li <?php if ($c == 'publications') echo 'class="active"'; ?> >
				<a href="/publications"><i class="icon-book"></i> Publications</a></li>
			<li class="divider"></li>
			<li <?php if ($c == 'collections') echo 'class="active"'; ?> >
				<a href="/albums"><i class="icon-briefcase"></i> Albums</a></li>
			<li <?php if ($c == 'documents') echo 'class="active"'; ?> >
				<a href="/documents"><i class="icon-hdd"></i> Documents</a></li>
			<li <?php if ($c == 'links') echo 'class="active"'; ?> >
				<a href="/links"><i class="icon-bookmark"></i> Links</a></li>
			<li class="divider"></li>
			<li <?php if ($c == 'users') echo 'class="active"'; ?> >
				<a href="/users"><i class="icon-user"></i> Users</a></li>
			<li <?php if ($c == 'notices') echo 'class="active"'; ?> >
				<a href="/notices"><i class="icon-bell"></i> Notices</a></li>
			<li <?php if ($c == 'metrics') echo 'class="active"'; ?> >
				<a href="/metrics"><i class="icon-signal"></i> Metrics</a></li>
		</ul>
	</div>
	</div>
</div>
<div class="row">
	<div class="span2">
	<p> </p>
	</div>
</div>
</div>
