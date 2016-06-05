<?php
class ZoomAPI{
	
	private $api_url = 'https://api.zoom.us/v1/';
	/*Function to send HTTP POST Requests*/
	/*Used by every function below to make HTTP POST call*/
	function sendRequest($calledFunction, $data){
		/*Creates the endpoint URL*/
		$request_url = $this->api_url.$calledFunction;

		$api_key = get_option('zoom_api_key');
		$api_secret = get_option('zoom_api_secret');

		/*Adds the Key, Secret, & Datatype to the passed array*/
		$data['api_key'] = $api_key;
		$data['api_secret'] = $api_secret;
		$data['data_type'] = 'JSON';

		$postFields = http_build_query($data);

		/*Check to see queried fields*/
		/*Used for troubleshooting/debugging*/
		//echo $postFields;

		/*Preparing Query...*/
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_URL, $request_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		$response = curl_exec($ch);

		/*Check for any errors*/
		// $errorMessage = curl_exec($ch);
		// return json_encode($errorMessage);
		//curl_clost($ch);

		/*Will print back the response from the call*/
		/*Used for troubleshooting/debugging		*/
		//echo $request_url;
		//var_dump($data);
		//var_dump($response);
		if(!$response){
			return false;
		}
		/*Return the data in JSON format*/
		return $response;
	}
	/*Functions for management of users*/

	function createAUser(){		
		$createAUserArray = array();
		$createAUserArray['email'] = $_POST['email'];
		$createAUserArray['type'] = $_POST['type'];
		$createAUserArray['first_name'] = $_POST['first_name'];
		$createAUserArray['last_name'] = $_POST['last_name'];
		$createAUserArray['dept'] = $_POST['dept'];
		return $this->sendRequest('user/create', $createAUserArray);
	}   

	function autoCreateAUser(){
	  $autoCreateAUserArray = array();
	  $autoCreateAUserArray['email'] = $_POST['userEmail'];
	  $autoCreateAUserArray['type'] = $_POST['userType'];
	  $autoCreateAUserArray['password'] = $_POST['userPassword'];
	  return $this->sendRequest('user/autocreate', $autoCreateAUserArray);
	}

	function custCreateAUser(){
	  $custCreateAUserArray = array();
	  $custCreateAUserArray['email'] = $_POST['userEmail'];
	  $custCreateAUserArray['type'] = $_POST['userType'];
	  return $this->sendRequest('user/custcreate', $custCreateAUserArray);
	}  

	function deleteAUser(){
	  $deleteAUserArray = array();
	  $deleteAUserArray['id'] = $_POST['userId'];
	  return $this->sendRequest('user/delete', $deleteAUserArray);
	}     

	function listUsers(){
	  $listUsersArray = array();
	  return $this->sendRequest('user/list', $listUsersArray);
	}   

	function listPendingUsers(){
	  $listPendingUsersArray = array();
	  return $this->sendRequest('user/pending', $listPendingUsersArray);
	}    

	function getUserInfo(){
	  $getUserInfoArray = array();
	  $getUserInfoArray['id'] = $_POST['userId'];
	  return $this->sendRequest('user/get',$getUserInfoArray);
	}   

	function getUserInfoByEmail(){
	  $getUserInfoByEmailArray = array();
	  $getUserInfoByEmailArray['email'] = $_POST['userEmail'];
	  $getUserInfoByEmailArray['login_type'] = $_POST['userLoginType'];
	  return $this->sendRequest('user/getbyemail',$getUserInfoByEmailArray);
	}  

	function updateUserInfo(){
	  $updateUserInfoArray = array();
	  $updateUserInfoArray['id'] = $_POST['userId'];
	  return $this->sendRequest('user/update',$updateUserInfoArray);
	}  

	function updateUserPassword(){
	  $updateUserPasswordArray = array();
	  $updateUserPasswordArray['id'] = $_POST['userId'];
	  $updateUserPasswordArray['password'] = $_POST['userNewPassword'];
	  return $this->sendRequest('user/updatepassword', $updateUserPasswordArray);
	}      

	function setUserAssistant(){
	  $setUserAssistantArray = array();
	  $setUserAssistantArray['id'] = $_POST['userId'];
	  $setUserAssistantArray['host_email'] = $_POST['userEmail'];
	  $setUserAssistantArray['assistant_email'] = $_POST['assistantEmail'];
	  return $this->sendRequest('user/assistant/set', $setUserAssistantArray);
	}     

	function deleteUserAssistant(){
	  $deleteUserAssistantArray = array();
	  $deleteUserAssistantArray['id'] = $_POST['userId'];
	  $deleteUserAssistantArray['host_email'] = $_POST['userEmail'];
	  $deleteUserAssistantArray['assistant_email'] = $_POST['assistantEmail'];
	  return $this->sendRequest('user/assistant/delete',$deleteUserAssistantArray);
	}   

	function revokeSSOToken(){
	  $revokeSSOTokenArray = array();
	  $revokeSSOTokenArray['id'] = $_POST['userId'];
	  $revokeSSOTokenArray['email'] = $_POST['userEmail'];
	  return $this->sendRequest('user/revoketoken', $revokeSSOTokenArray);
	}      

	function deleteUserPermanently(){
	  $deleteUserPermanentlyArray = array();
	  $deleteUserPermanentlyArray['id'] = $_POST['userId'];
	  $deleteUserPermanentlyArray['email'] = $_POST['userEmail'];
	  return $this->sendRequest('user/permanentdelete', $deleteUserPermanentlyArray);
	}               

	/*Functions for management of meetings*/
	function createAMeeting(){
	  $createAMeetingArray = array();
	  $createAMeetingArray['host_id'] = $_POST['userId'];
	  $createAMeetingArray['topic'] = $_POST['meetingTopic'];
	  $createAMeetingArray['type'] = $_POST['meetingType'];
	  $createAMeetingArray['start_time'] = $_POST['start_date'].$_POST['start_time'];
	  $createAMeetingArray['timezone'] = $_POST['timezone'];
	  $createAMeetingArray['password'] = $_POST['zoom_password_field'] ? $_POST['zoom_password_field'] : false;
	  $createAMeetingArray['duration'] = $_POST['duration'];
	  $createAMeetingArray['option_jbh'] = $_POST['join_before_host'] ? $_POST['join_before_host'] : false;
	  $createAMeetingArray['option_participants_video'] = $_POST['option_participants_video'] ? $_POST['join_before_host'] : false;
	  return $this->sendRequest('meeting/create', $createAMeetingArray);
	}

	/*ADD MEETING FUNCTION FOR FRONTEND**/
	function createFronendMeeting(){
	  $createAMeetingArray = array();
	  $createAMeetingArray['host_id'] = $_POST['userId'];
	  $createAMeetingArray['topic'] = $_POST['course_title'];
	  $createAMeetingArray['type'] = 2;
	  $createAMeetingArray['start_time'] = $_POST['start_date'].'T'.$_POST['start_time'].'Z';
	  $createAMeetingArray['timezone'] = $_POST['timezone'];
	  $createAMeetingArray['duration'] = $_POST['meeting_duration'];
	  $createAMeetingArray['option_jbh'] = $_POST['join_before_host'];
	  $createAMeetingArray['option_participants_video'] = $_POST['participant_video'];
	  return $this->sendRequest('meeting/create', $createAMeetingArray);
	}

	function deleteAMeeting(){
	  $deleteAMeetingArray = array();
	  $deleteAMeetingArray['id'] = $_POST['meeting_id'];
	  $deleteAMeetingArray['host_id'] = $_POST['host_id'];
	  return $this->sendRequest('meeting/delete', $deleteAMeetingArray);
	}

	function listMeetings(){
	  $listMeetingsArray = array();
	  $listMeetingsArray['host_id'] = $_POST['userId'];
	  //$listMeetingsArray['host_id'] = get_option('zoom_user_id');
	  return $this->sendRequest('meeting/list',$listMeetingsArray);
	}

	function listMeetingsCustom($userid) {
		$listMeetingsArray = array();
	  $listMeetingsArray['host_id'] = $userid;
	  return $this->sendRequest('meeting/list',$listMeetingsArray);
	}

	function getMeetingInfo($id, $host_id) {
    $getMeetingInfoArray = array();
	  $getMeetingInfoArray['id'] = $id;
	  $getMeetingInfoArray['host_id'] = $host_id;
	  return $this->sendRequest('meeting/get', $getMeetingInfoArray);
	}

	function updateMeetingInfo(){
		if(empty($_POST['start_date']) || empty($_POST['start_time'])) {
			$start_date = $_POST['start_date_hidden'].$_POST['start_time_hidden'];
		} else {
			$start_date = $_POST['start_date'].$_POST['start_time'];
		}
	  $updateMeetingInfoArray = array();
	  $updateMeetingInfoArray['id'] = $_POST['meetingId'];
	  $updateMeetingInfoArray['host_id'] = $_POST['userId'];
	  $updateMeetingInfoArray['topic'] = $_POST['meetingTopic'];
	  $updateMeetingInfoArray['type'] = $_POST['meetingType'];
	  $updateMeetingInfoArray['start_time'] = $start_date;
	  $updateMeetingInfoArray['timezone'] = $_POST['timezone'];
	  $updateMeetingInfoArray['password'] = $_POST['zoom_password_field'];
	  $updateMeetingInfoArray['duration'] = $_POST['duration'];
	  $updateMeetingInfoArray['option_jbh'] = $_POST['join_before_host'];
	  $updateMeetingInfoArray['option_participants_video'] = $_POST['option_participants_video'];
	  return $this->sendRequest('meeting/update', $updateMeetingInfoArray);
	}

	function endAMeeting(){
    $endAMeetingArray = array();
	  $endAMeetingArray['id'] = $_POST['meetingId'];
	  $endAMeetingArray['host_id'] = $_POST['userId'];
	  return $this->sendRequest('meeting/end', $endAMeetingArray);
	}

	function listRecording(){
    $listRecordingArray = array();
	  $listRecordingArray['host_id'] = $_POST['userId'];
	  return $this->sendRequest('recording/list', $listRecordingArray);
	}


	/*Functions for management of reports*/
	function getDailyReport($month_int, $year){
	  $getDailyReportArray = array();
	  $getDailyReportArray['year'] = $year;
	  $getDailyReportArray['month'] = $month_int;
	  return $this->sendRequest('report/getdailyreport', $getDailyReportArray);
	}

	function getAccountReport($zoom_account_from, $zoom_account_to){
	  $getAccountReportArray = array();
	  $getAccountReportArray['from'] = $zoom_account_from;
	  $getAccountReportArray['to'] = $zoom_account_to;
	  return $this->sendRequest('report/getaccountreport', $getAccountReportArray);
	}

	function getUserReport(){
	  $getUserReportArray = array();
	  $getUserReportArray['user_id'] = $_POST['userId'];
	  $getUserReportArray['from'] = $_POST['from'];
	  $getUserReportArray['to'] = $_POST['to'];
	  return $this->sendRequest('report/getuserreport', $getUserReportArray);
	}


	/*Functions for management of webinars*/
	function createAWebinar(){
	  $createAWebinarArray = array();
	  $createAWebinarArray['host_id'] = $_POST['userId'];
	  $createAWebinarArray['topic'] = $_POST['topic'];
	  return $this->sendRequest('webinar/create',$createAWebinarArray);
	}

	function deleteAWebinar(){
	  $deleteAWebinarArray = array();
	  $deleteAWebinarArray['id'] = $_POST['webinarId'];
	  $deleteAWebinarArray['host_id'] = $_POST['userId'];
	  return $this->sendRequest('webinar/delete',$deleteAWebinarArray);
	}

	function listWebinars($userid){
	  $listWebinarsArray = array();
	  $listWebinarsArray['host_id'] = $userid;
	  return $this->sendRequest('webinar/list',$listWebinarsArray);
	}

	function getWebinarInfo(){
	  $getWebinarInfoArray = array();
	  $getWebinarInfoArray['id'] = $_POST['webinarId'];
	  $getWebinarInfoArray['host_id'] = $_POST['userId'];
	  return $this->sendRequest('webinar/get',$getWebinarInfoArray);
	}

	function updateWebinarInfo(){
	  $updateWebinarInfoArray = array();
	  $updateWebinarInfoArray['id'] = $_POST['webinarId'];
	  $updateWebinarInfoArray['host_id'] = $_POST['userId'];
	  return $this->sendRequest('webinar/update',$updateWebinarInfoArray);
	}

	function endAWebinar(){
	  $endAWebinarArray = array();
	  $endAWebinarArray['id'] = $_POST['webinarId'];
	  $endAWebinarArray['host_id'] = $_POST['userId'];
	  return $this->sendRequest('webinar/end',$endAWebinarArray);
	}

	//For Zoom Dashboard
	function dashboard_get_meetings() {
		$dashboardGetMeetings = array();
		$dashboardGetMeetings['type'] = "2";
		$dashboardGetMeetings['from'] = "2015-08-02";
		$dashboardGetMeetings['to'] = "2015-09-02";
		return $this->sendRequest('metrics/meetings',$dashboardGetMeetings);
	}
}
