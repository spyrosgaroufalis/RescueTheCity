<?php
include "security_cityzen.php";
?>

<?php
//difficult.php
// Connect to the database
$conn = mysqli_connect("localhost","root","","help_city1");

$products = array(); // Define $products as an empty array

if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    // Retrieve product names from the database
    $sql = "SELECT `name` FROM `product` WHERE `name` not like'';";
    $result = $conn->query($sql);

    // Store product names in an array
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row["name"];
        }
    }
}

$conn->close();
?>



<?php 
$myText1 = " Change Quantity \/";
$myText2 = " History of Giving Help \/";
$myText3 = " Make New Aplication \/";
$myText4 = " Log Out \/";
$myText5 = " My Applications \/";
$myText6 = " History Announcements by Administrator \/";

echo "<p style=\"font-size: 25pt;\">$myText6</p>";
include "show_announcements_resid.html";

echo "<p style=\"font-size: 25pt;\">$myText5</p>";
include "show_aplications.html";

echo "<p style=\"font-size: 25pt;\">$myText3</p>";
 ?>

<button id="selectProducts">Select Products That Are Needed</button>
    <div id="productList" style="display: none;">
        <?php foreach($products as $product): ?>
            <input type="radio" name="selectedProducts" value="<?php echo $product; ?>"><?php echo $product; ?><br>
        <?php endforeach; ?>
        <p>Please insert the number of people that are with you:</p>
        <textarea id="numPeople" oninput="checkInput()"></textarea><br>
        <p>Please insert the number of products that are needed:</p>
        <input type="text" id="numProducts" oninput="checkInput()"><br> <!-- New input field for number of products -->
        <button id="submitSelection" disabled>Publish Announcement</button>
    </div>


 <?php

echo "<p style=\"font-size: 25pt;\">$myText2</p>";
include "History_of_help.html";


echo "<p style=\"font-size: 25pt;\">MAP</p>";


        ?>
    <div id="map" style="height: 400px;"></div>
<?php


include "log_out.html";
?>


<!-- Include Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

<!-- Include Leaflet Search plugin JavaScript -->
<script src="https://unpkg.com/leaflet-search"></script>
<!-- Include Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>



<script>


var map = L.map('map').fitWorld();
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    let initialMarkerPosition;
// Declare LocationMarker variable in the global scope
let LocationMarker;



    // Function to handle location found
function onLocationFound(e) {
    const radius = e.accuracy / 2;
    const userLocation = e.latlng;

    // Zoom the map to the user's current location
    map.setView(userLocation, 15);

    // Optionally, you can add a marker at the user's location
    L.marker(userLocation).addTo(map)
        .bindPopup("You are here").openPopup();
}

// Event listener for location found
map.on('locationfound', onLocationFound);

// Event listener for location error
map.on('locationerror', function (e) {
    console.error(e.message);
    alert("Location access denied.");
});

// Request location access
map.locate({ setView: true, maxZoom: 16 });


function fetchAndAddVehicleMarkers() {
        // Fetch data from PHP script and add markers
        fetch('show_vehicles_citizen.php')
          .then(response => response.json())
          .then(data => {
            addVehicleMarkers(data);
          })
          .catch(error => {
              console.error('Error fetching data:', error);
          });
        }

// Call the function to fetch data and add markers when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    fetchAndAddVehicleMarkers();
});

 /// Function to add markers for announcements
 function addVehicleMarkers(data) {
    data.forEach(row => {
        
        
  
        const marker = L.marker([parseFloat(row.latitude), parseFloat(row.longitude)]);
        
        // Define popup content with all table info
        let popupContent = `<br><b>Διασώστης:</b><br><b>Όνοματεπώνυμο:</b> ${row.name}<br><b>Περιεχόμενα:</b> ${row.cargo}<br><b>Κατάσταση:</b> ${row.status}`;

        


        marker.bindPopup(popupContent);

         // Add the marker to the map
         marker.addTo(map);
       
    });
  }







    function checkInput() {
        var numPeople = document.getElementById('numPeople').value;
        var numProducts = document.getElementById('numProducts').value; // Get the value of number of products

    // Check if both inputs are valid numbers and not empty
    if (!isNaN(numPeople) && !isNaN(numProducts) && numPeople.trim() !== '' && numProducts.trim() !== '') {
        document.getElementById('submitSelection').disabled = false;
    } else {
        document.getElementById('submitSelection').disabled = true;
    }
    }

    document.getElementById('selectProducts').addEventListener('click', function() {
        document.getElementById('productList').style.display = 'block';
    });

    document.getElementById('submitSelection').addEventListener('click', function() {
    getLocation();
});
    function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                // Store latitude and longitude or use them directly
                console.log('Latitude:', latitude);
                console.log('Longitude:', longitude);
                
                // Get other form data
                var selectedProduct = document.querySelector('input[name="selectedProducts"]:checked');
                var selectedProductName = selectedProduct ? selectedProduct.value : null;
                var numPeople = document.getElementById('numPeople').value;
                var numProducts = document.getElementById('numProducts').value;
                var currentDate = new Date().toISOString().split('T')[0]; // Get the current date in YYYY-MM-DD format
                
                console.log("Selected Product: ", selectedProductName);
                console.log("Number of People: ", numPeople);
                console.log("Number of numProducts: ", numProducts);
                console.log("Current Date: ", currentDate);

                // Send data to the server to store in the database
                sendDataToServer(selectedProductName, numPeople, currentDate, latitude, longitude, numProducts);
            },
            error => {
                console.error('Error getting location:', error.message);
            }
        );
    } else {
        console.error('Geolocation is not supported by this browser.');
    }
}

function sendDataToServer(selectedProductName, numPeople, currentDate, latitude, longitude, numProducts) {
    // Send data to the server (using AJAX)
    fetch('save_product_wanted.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            productName: selectedProductName,
            numberOfPeople: numPeople,
            currentDate: currentDate,
            latitude: latitude,
            longitude: longitude,
            numberOfProducts: numProducts, 
        }),
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Log success message or error message from the server
        alert('Data storage successful');
    })
    .catch(error => {
        console.error('Error sending data to server:', error);
        alert('Data storage failed');
    });

    // Hide the product list
    document.getElementById('productList').style.display = 'none';
}

</script>
