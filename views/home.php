<div class="container maincontainer">
	<div class="row">
		<div class="col-lg-8 col-md-8 border" >
		<?php postTweet()?>
			<h2 class="header">RECENT TWEETS</h2>

			<?php displaytweets('public');?>
		</div>
		<div class="col-lg-4 col-md-4">
			<?php searchTweets();?>
		</div>
	</div>
</div>
