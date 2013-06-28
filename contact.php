<?php
if(isset($_POST['email'])) {
     
    // EDIT THE 2 LINES BELOW AS REQUIRED
    $email_to = "kuyan@poly.edu";
    $email_subject = "Faculty EIR Inquiry";
     
     
     
    $first_name = $_POST['name']; // required
    $last_name = $_POST['lastname']; // required
    $email_from = $_POST['email']; // required
    $telephone = $_POST['phone']; // required
    $comments = $_POST['comment']; // required
	
    $email_message = "Form details below.\n\n";
     
    function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }
     
    $email_message .= "First Name: ".clean_string($first_name)."\n";
    $email_message .= "Last Name: ".clean_string($last_name)."\n";
    $email_message .= "Email: ".clean_string($email_from)."\n";
    $email_message .= "Phone Number: ".clean_string($telephone)."\n";
    $email_message .= "Comments: ".clean_string($comments)."\n";
     
     
// create email headers
$headers = 'From: '.$email_from."\r\n".
'Reply-To: '.$email_from."\r\n" .
'X-Mailer: PHP/' . phpversion();
@mail($email_to, $email_subject, $email_message, $headers);  
?>
 
<!-- include your own success html here -->
<h4 class="form_thanks">Thank you for contacting us. We will be in touch with you very soon.</h4> 
 
<?php
}
?>