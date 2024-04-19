<?--ajax.php-->
<!DOCTYPE html>
<html>
<head>
    <title>Async PHP Call</title>
    <script>
        function fetchData() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("data").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "administration_choose_products.php", true);
            xhttp.send();
        }
    </script>
</head>
<body onload="fetchData()">
    <div id="data"></div>
    <?php 
$myText1 = " Change Quantity \/";
$myText2 = " Update all products \/";
$myText3 = " Add New Rescuer \/";
$myText4 = " Log Out \/";
$myText5 = " Make New Announcement \/";
$myText6 = " History Announcements \/";

        include "delete_id.html";

echo "<p style=\"font-size: 25pt;\">$myText1</p>";

        include "change_quantity.html";

echo "<p style=\"font-size: 25pt;\">$myText3</p>";

        include "insert_rescuer.html";

echo "<p style=\"font-size: 25pt;\">$myText2</p>";

        include "update.html";

echo "<p style=\"font-size: 25pt;\">$myText5</p>";

        include "difficult.php";

echo "<p style=\"font-size: 25pt;\">$myText6</p>";

        include "show_announcements.html";

echo "<p style=\"font-size: 25pt;\">$myText4</p>";

        include "log_out.html";
        
    ?>
</body>
</html>