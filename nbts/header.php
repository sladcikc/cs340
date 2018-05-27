<!-- Header for webpages -->
<header>
		NOT BEAT THE STREAK 
		<?php 
			if(!empty($_SESSION['username'])){
				echo "<button id='logout' > <a href='logout.php' >Logout</a></button>";
			} ?>
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
