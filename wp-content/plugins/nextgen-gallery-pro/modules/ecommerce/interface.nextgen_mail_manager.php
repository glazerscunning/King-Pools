<?php

interface I_Nextgen_Mail_Manager
{
	function create_content($type = null);
	
	function send_mail($content, $receiver, $subject = null, $sender = null);
}
