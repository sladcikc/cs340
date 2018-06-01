<!-- Header for webpages -->
<header>
		NOT BEAT THE STREAK
		<?php
			if(!empty($_SESSION['username'])){
				echo "<button id='logout' > <a onclick=window.location.assign('http://web.engr.oregonstate.edu/~sladcikc/CS340/cs340/cs340/nbts/logout.php')>Logout</a></button>";
			} ?>
</header>
<nav>
	<ul>
	<?php
	if(!empty($_SESSION['username'])){
		foreach ($content as $page => $location){
			echo "<li><a href='$location' ".($page==$currentpage?" class='active'":"").">".$page."</a></li>";
		}
	}
	?>
	</ul>
</nav>
