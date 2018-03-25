<div class="container maincontainer">
	<div class="row">
		<div class="col-lg-8 col-md-8 border" >
		<?php if (@($_GET['userid'])) { ?>
      
      	<?php displayTweets($_GET['userid']); ?>
      
      	<?php } else { ?> 
        
        <h2>Active Users</h2>
        
        	<?php displayUsers(); ?>
      
      <?php } ?>
		</div>
		<div class="col-lg-4 col-md-4">
			<?php searchTweets();?>
		</div>
	</div>
</div>
