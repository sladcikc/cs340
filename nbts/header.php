<!-- Header for webpages -->
<header>
		NOT BEAT THE STREAK
</header>
<nav>
	<ul>
	<?php
	foreach ($content as $page => $location){
		echo "<li><a href='$location' ".($page==$currentpage?" class='active'":"").">".$page."</a></li>";
	}
	?>
	</ul>
</nav>
