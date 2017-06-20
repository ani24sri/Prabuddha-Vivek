<?php

//Retrieve form data. 
//GET - user submitted data using AJAX
//POST - in case user does not support javascript, we'll use POST instead
$name = ($_GET['name']) ? $_GET['name'] : $_POST['name'];
$email = ($_GET['email']) ?$_GET['email'] : $_POST['email'];
$phone = ($_GET['phone']) ?$_GET['phone'] : $_POST['phone'];
$comment = ($_GET['comment']) ?$_GET['comment'] : $_POST['comment'];

//flag to indicate which method it uses. If POST set it to 1

if ($_POST) $post=1;

//Simple server side validation for POST data, of course, you should validate the email
if (!$name) $errors[count($errors)] = 'Please enter your name.';
if (!$email) $errors[count($errors)] = 'Please enter your email.'; 
if (!$phone) $errors[count($errors)] = 'Please enter your phone number.'; 
if (!$comment) $errors[count($errors)] = 'Please enter your message.'; 

//if the errors array is empty, send the mail
if (!$errors) {

	//recipient - replace your email here
	$to = 'echevalglobal@gmail.com';	
	//sender - from the form
	$from = $name . ' <' . $email . '>';
	
	//subject and the html message
	$subject = 'Message via Prabuddha vivek from ' . $name;	
	$message = 'Name: ' . $name . '<br/><br/>
		       Email: ' . $email . '<br/><br/>		
		       Phone Number: ' . $phone . '<br/><br/>
               Message :' . nl2br($comment).'<br/>';
    //response mail data
    $res = $email;
    $resub = 'Thanks for your Time';
    $resmsg = '<p> <b>Thank You '. $name .'</b>
                <br/>
                We appreciate your interest in our trust and shall get back to you as
                soon as possible
                </p>' ;
    $resfrm = $to;
	//send the mail
	$result = sendmail($to, $subject, $message, $from);
	$response = replymail($res,$resub,$resmsg,$resfrm);
	//if POST was used, display the message straight away
	if ($_POST) {
		if ($result && $response) echo 'Thank you! We have received your message.';
		else echo 'Sorry, unexpected error. Please try again later';
		
	//else if GET was used, return the boolean value so that 
	//ajax script can react accordingly
	//1 means success, 0 means failed
	} else {
		echo $result;	
	}

//if the errors array has values
} else {
	//display the errors message
	for ($i=0; $i<count($errors); $i++) echo $errors[$i] . '<br/>';
	echo '<a href="index.html">Back</a>';
	exit;
}


//Simple mail function with HTML header
function sendmail($to, $subject, $message, $from) {
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	$headers .= 'From: ' . $from . "\r\n";
	
	$result = mail($to,$subject,$message,$headers);
	
	if ($result) return 1;
	else return 0;
}
function replymail($res, $resub, $resmsg, $resfrm) {
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	$headers .= 'From: ' . $resfrm . "\r\n";
	
	$result = mail($res, $resub, $resmsg,$headers);
	
	if ($result) return 1;
	else return 0;
}

?>