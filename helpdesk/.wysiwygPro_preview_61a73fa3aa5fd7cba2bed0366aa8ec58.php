<?php
if ($_GET['randomId'] != "Ubu3wBhAGBe7s4kSComHbAdintEBG4Q18VDZmO097EY0AqyCZoaWE2Z53v0f4OR8") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
