<?php
/*
MailChimp REST Api V3 exposes methods that allows you to add, manage members of a specific MailChimp list. 
The following code snippet help you to add a new subscriber to MailChimp List.
*/
class Newsletter_Plugin {
	public function __construct(){ 
	
	} 
	
	function subscribe($user_id, $user_email){
		//get api_key and list id from admin settings
		$api_key = "add-api-key";
		$list_id = "add-list-id";
		
		require_once('MailChimp.php');
		$mailChimp = new MailChimp($api_key);
		
		$result = $mailChimp->post("lists/$list_id/members", [
			'email_address' => $user_email,
			'status'=> 'subscribed'
		]);

		if ($mailChimp->success()) {
		    echo "success";
		} else {
		   echo "fail";
		}
	}
}

?>
