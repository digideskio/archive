<?php $check = lithium\security\Auth::check('default'); $username = $check['username']; ?>
<?php $host = $this->request()->env('HTTP_HOST'); ?>
<?php 
	$request_query = $this->request()->query;
	$query = isset($request_query['query']) ? $request_query['query'] : '';
?>

<div class="navbar navbar-fixed-top navbar-inverse" >
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="/home"><?=$host ?></a>

			<div class="nav-collapse">

				<ul class="nav pull-right">
					<li>
					<?=$this->html->link($username, $this->url(array('Users::view', 'username' => $username))); ?>
					</li>
					<li>
					<a href="/logout">Logout</a>
					</li>
				</ul>

				<form class="navbar-search pull-right form-search" action="/search" method="get">
					<input type="text" class="search-query span3" placeholder="Search" value="<?=$query ?>" name="query" autocomplete="off">
				</form>
			</div>
		</div>
	</div>
</div>
