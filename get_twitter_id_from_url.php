<?php

The following PHP code snippet extracts the twitter username from twitter URL using regular expressions. 
For example, if you pass the following url, it will output ‘panay_georgiou‘.

//www.twitter.com/panay_georgiou

if ( !function_exists( 'get_twitter_id_from_url' ) ){
	function get_twitter_id_from_url($url)
	{	
  	  if (preg_match("/^https?:\/\/(www\.)?twitter\.com\/(#!\/)?(?<name>[^\/]+)(\/\w+)*$/", $url, $regs)) {
  	    return $regs['name'];
  	  }
  	  return false;	  
  }
}

?>
