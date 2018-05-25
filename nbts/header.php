<!-- Header for webpages -->
<header>
		NOT BEAT THE STREAK - <em>Welcome <span id="username"><?php echo $username;?></span>!</em>
</header>
<nav>
	<ul>
	<?php
	foreach ($content as $page => $location){
		echo "<li><a href='$location?user=".$user."' ".($page==$currentpage?" class='active'":"").">".$page."</a></li>";
	}
	?>
	</ul>
</nav>
