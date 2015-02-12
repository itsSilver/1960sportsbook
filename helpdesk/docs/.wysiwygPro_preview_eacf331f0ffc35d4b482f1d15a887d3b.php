<?php
if ($_GET['randomId'] != "yjv2eH_NMeLKeJwuMBMJb38TLJIer54kPP4bh8ygWZV8vVjqrKsmv7AjwYT9GSKl") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
