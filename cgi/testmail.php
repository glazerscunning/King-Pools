<?php
//if "email" variable is filled out, send email
  if (isset($_REQUEST['email']))  {
  
  //Email information
  $admin_email = "aaroncunningham@mailinator.com";
  $email = $_REQUEST['email'];
  $subject = $_REQUEST['subject'];
  $comment = $_REQUEST['comment'];
  
  //send email
  $success = @mail($admin_email, "$subject", $comment, "From:" . $email);
  $error = error_get_last();
  echo "Error: [" . print_r($error) . "]";
  //Email response
  echo "Thank you for contacting us!";
  }
  
  //if "email" variable is not filled out, display the form
  else  {
?>
<form action="/cgi/testmail.php" method="post"><input type="hidden" name="subject" value="Form Submission" /><br/><p>First Name:<input type="text" name="FirstName" /></p><br/><p>Last Name:<input type="text" name="LastName" /></p><br/><p>E-Mail:<input type="text" name="email" /></p><br/><p>Comments:<textarea name="comments" cols="40" rows="10"><br/>Type comments here.</textarea></p><input type="submit" name="submit" value="submit"/><br/></form>

<?php
  }
?>