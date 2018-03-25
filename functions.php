<?php
	session_start();
    date_default_timezone_set('Asia/Kolkata');
	$link=mysqli_connect("localhost", "root", "", "twitter");
	if(mysqli_connect_errno()){
		print_r(mysqli_connect_error());
		exit();
	}
	if (@($_GET['function'] == "logout")) {
        
        session_unset();
        
    }
     function time_since($since) {
        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'min'),
            array(1 , 'sec')
        );

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
        return $print;
    }

    function displaytweets($type){
    	global $link;
           $delete=0;
        if ($type == 'public') {
            
            $whereClause = "";
                
        } else if ($type == 'isFollowing') {
            
            $query = @("SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id']));
            $result = mysqli_query($link, $query);
            
            $whereClause = "";
            
            while (@($row = mysqli_fetch_assoc($result))) {
                
                if ($whereClause == "") $whereClause = "WHERE";
                else $whereClause.= " OR";
                $whereClause.= " userid = ".$row['isFollowing'];
                
                
            }
             if($whereClause == ""){
                if(@($_SESSION['id']==0)){
                      echo "<h3>Please Login To see Your TimeLine...</h3>";
                 }
                 else {
                    echo "<h3>Please Follow People So that Their Tweets can be displayed in your TimeLine</h3>";
                 }
                 echo "<br><h4>Here are some recent tweets</h4>";
                 
             }
            
        } else if ($type == 'yourtweets') {
            
           $whereClause = "WHERE userid = ". mysqli_real_escape_string($link, $_SESSION['id']);
           $delete=1;
            
        } else if ($type == 'search') {
            
            echo '<p>Showing search results for "'.mysqli_real_escape_string($link, $_GET['q']).'":</p>';
            
           $whereClause = "WHERE tweet LIKE '%". mysqli_real_escape_string($link, $_GET['q'])."%'";
            
        }
        else if (is_numeric($type)) {
            
            $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $type)." LIMIT 1";
                $userQueryResult = mysqli_query($link, $userQuery);
                $user = mysqli_fetch_assoc($userQueryResult);
            
            echo "<h2>".mysqli_real_escape_string($link, $user['email'])."'s Tweets</h2>";
            
            $whereClause = "WHERE userid = ". mysqli_real_escape_string($link, $type);
            
            
        }
    	  $query="SELECT * FROM tweets ".$whereClause." ORDER BY `date` DESC LIMIT 15";

    	$result=mysqli_query($link, $query);

    	if(mysqli_num_rows($result)==0){
    		echo "There are no tweets to display";
    	}
    	else{
    		while ($row = mysqli_fetch_assoc($result)) {
                
                $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $row['userid'])." LIMIT 1";
                $userQueryResult = mysqli_query($link, $userQuery);
                $user = mysqli_fetch_assoc($userQueryResult);
                echo "<div class='tweet'><p><a href='?page=publicprofiles&userid=".$user['id']."'>".$user['email']."</a> <span class='time'>".time_since(time() - strtotime($row['date']))." ago</span>:</p>";
                $output = preg_replace( "/#([^\s]+)/", "<a href=\"?page=search&q=%23$1\">#$1</a>", $row['tweet'] );
    			echo "<p>".$output."</p>";
                
                echo "<p><a class='toggleFollow' data-userId='".$row['userid']."'>";
                
               $isFollowingQuery = @("SELECT * FROM isfollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($link, $row['userid'])." LIMIT 1");
            $isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);
            if(@($row['userid']==$_SESSION['id'])){
                 ;
             }
            else if (@(mysqli_num_rows($isFollowingQueryResult) > 0)) {
                
                echo "Unfollow";
                
            } else {
                
                echo "Follow";
                
            }
                
                echo "</a>";
                if($delete){
                  echo '<a href='.'?page=delete&tweet='.$row["id"].'>Delete</a>'; 
                }
                echo "</p></div>";
                
            }
    }
}
    function postTweet(){
    	if(@($_SESSION['id']>0)){
    		echo "<div id='tweetSuccess' class='alert alert-success'>Your Tweet was posted successfully</div><div id='tweetFail' class='alert alert-danger'></div><div id='tweetDelete' class='alert alert-success'>Your Tweet has been Deleted Successfully</div><div class='form-inline'><textarea name='posttweet' id='tweetContent' class='form-control' style='width:90%;' placeholder='Post New Tweet..'></textarea><button id='postTweetButton' class='btn btn-primary-outline'>Post</button></div>";
    	}
    }
    function searchTweets(){
    	echo '<form class="row">
                    <input type="hidden" name="page" value="search"/>
					<div class="col-md-12">
						<input type="text" name="q" style="width :100%" class="form-control" placeholder="Search tweets"/>
					</div>
					<div class="col-md-4">
						<input type="submit" class="btn btn-sm btn-primary" value="Search"/>
    				</div>
    		</form>';
    }
    function displayUsers() {
        
        global $link;
        
        $query = "SELECT * FROM users ";
        
        $result = mysqli_query($link, $query);
            
        while ($row = mysqli_fetch_assoc($result)) {
            
            echo "<p><a href='?page=publicprofiles&userid=".$row['id']."'>".$row['email']."</a></p>";
            
        }
        
        
        
    }
    function delete($str){
         global $link;
         $query="DELETE FROM `tweets` WHERE id=".$str;
         mysqli_query($link, $query);
    }

?>