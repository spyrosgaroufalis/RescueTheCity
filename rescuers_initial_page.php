<?php
include "security_rescuer.php";
?>

<?php

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
   // var_dump($_SESSION);
   
    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "help_city1");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the latitude and longitude of the rescuer's vehicle
    $sqlVehiclePosition = "SELECT latitude, longitude FROM rescuer_vehicle WHERE name = '$username'";
    $resultVehiclePosition = mysqli_query($conn, $sqlVehiclePosition);
    $rowVehiclePosition = mysqli_fetch_assoc($resultVehiclePosition);
    $vehicleLatitude = $rowVehiclePosition['latitude'];
    $vehicleLongitude = $rowVehiclePosition['longitude'];

    // Fetch the latitude and longitude of the base position
    $sqlBasePosition = "SELECT latitude, longitude FROM base_marker_position WHERE id = 1";
    $resultBasePosition = mysqli_query($conn, $sqlBasePosition);
    $rowBasePosition = mysqli_fetch_assoc($resultBasePosition);
    $baseLatitude = $rowBasePosition['latitude'];
    $baseLongitude = $rowBasePosition['longitude'];

    // Calculate the distance between the vehicle and the base
    $distance = calculateDistance1($vehicleLatitude, $vehicleLongitude, $baseLatitude, $baseLongitude);

    // Check if the vehicle is within 100 meters of the base
    $showButton = ($distance <= 0.1); // 100 meters in kilometers

    // Fetch all products from the database
    $sqlProducts = "SELECT `name` FROM `product` WHERE `name` != ''";
    $resultProducts = mysqli_query($conn, $sqlProducts);


    // Close database connection
    mysqli_close($conn);
}else {
    echo "Error: Required username not set.";
}
?>

<?php
// Function to calculate distance between two points using Haversine formula
function calculateDistance1($lat1, $lon1, $lat2, $lon2) {
    $R = 6371; // Radius of the Earth in kilometers
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $d = $R * $c; // Distance in kilometers
    return $d;
}
?>




<!-- Include Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>



    <?php 
//resident.annaouncements.php
$myText1 = " Change Quantity \/";
$myText2 = " People willing of Giving Help \/";
$myText3 = " Make New Aplication \/";
$myText4 = " Log Out \/";
$myText5 = " Citizens call for help Applications \/";
$myText6 = " History Announcements by Administrator \/";
$myText7 = " Current Action Panel \/";
$myText8 = " Base is nearby! \/";

  

echo "<p style=\"font-size: 25pt;\">$myText6</p>";


        include "show_announcements.html";

echo "<p style=\"font-size: 25pt;\">$myText5</p>";

        include "show_aplications_rescuers.html";

echo "<p style=\"font-size: 25pt;\">$myText2</p>";

        include "History_of_help_rescuers.html";


// Display the button if the condition is met
if ($showButton) {
    echo "<p style=\"font-size: 25pt;\">Base is Nearby!</p>";
    
}




        ?>

<?php if ($showButton): ?>

    <!-- Form for selecting products -->
    <form id="productForm">
    <?php
// Loop through each product and create checkboxes and input fields
while ($rowProduct = mysqli_fetch_assoc($resultProducts)) {
    $productName = $rowProduct['name'];
    // Sanitize the product name
    $sanitizedProductName = preg_replace("/[^a-zA-Z0-9]+/", "_", $productName);
    echo '<label>';
    echo '<input type="checkbox" name="selectedProducts[]" value="' . $sanitizedProductName . '">' . $productName;
    echo '</label>';
    echo '<input type="number" name="' . $sanitizedProductName . '" placeholder="Quantity"><br>';
}
?>
    </form>

   




    <!-- Container for the button -->
    <div id="buttonContainer"></div>
    <div id="buttonContainer2"></div>


<script>
        // Function to create the button and bind it to AddToCargo function
        function createButton(username) {
            const buttonContainer = document.getElementById('buttonContainer');
            const button = document.createElement('button');
            button.textContent = 'Φόρτωση';
            button.style.fontSize = '24px';
            button.onclick = function() {
                AddToCargo(username);
            };
            buttonContainer.appendChild(button);
        }
        // Function to create the button and bind it to AddToCargo function
        function createButton2(username) {
            const buttonContainer = document.getElementById('buttonContainer2');
            const button = document.createElement('button');
            button.textContent = 'Εκφόρτωση';
            button.style.fontSize = '24px';
            button.onclick = function() {
                DeleteFromCargo(username);
            };
            buttonContainer.appendChild(button);
        }

        // Function to handle adding products to cargo
        function AddToCargo(username) {
            // Get the form element
            const form = document.getElementById('productForm');

            // Create an object to store the selected products and quantities
            const selectedProducts = {};

            // Loop through each checkbox and input field in the form
            form.querySelectorAll('input[type=checkbox]').forEach(checkbox => {
                if (checkbox.checked) {
                    // Get the product name
                    const productName = checkbox.value;
                    // Get the quantity input field associated with the product
                    const quantityInput = form.querySelector('input[name=' + productName + ']');
                    // Get the quantity value
                    const quantity = quantityInput.value;
                    // Add the product and quantity to the selectedProducts object
                    selectedProducts[productName] = quantity;
                }
            });

            // Send request to update status in the database
            console.log("Rescuer:", username);
            console.log("Selected products:", selectedProducts);

            // Send request to update status in the database
        fetch('AddToCargo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                rescuer_name: username,
                selected_products: selectedProducts
            }),
            })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Log success message or error message from the server
        })
        .catch(error => {
            console.error('Error updating status:', error);
        });

        }
        

        // Call the function to create the button
        createButton('<?php echo urlencode($username); ?>');


        // Function to handle adding products to cargo
        function DeleteFromCargo(username) {
            // Get the form element
            const form = document.getElementById('productForm');

            // Create an object to store the selected products and quantities
            const selectedProducts = {};

            // Loop through each checkbox and input field in the form
            form.querySelectorAll('input[type=checkbox]').forEach(checkbox => {
                if (checkbox.checked) {
                    // Get the product name
                    const productName = checkbox.value;
                    // Get the quantity input field associated with the product
                    const quantityInput = form.querySelector('input[name=' + productName + ']');
                    // Get the quantity value
                    const quantity = quantityInput.value;
                    // Add the product and quantity to the selectedProducts object
                    selectedProducts[productName] = quantity;
                }
            });

            // Send request to update status in the database
            console.log("Rescuer:", username);
            console.log("Selected products:", selectedProducts);

            // Send request to update status in the database
        fetch('DeleteFromCargo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                rescuer_name: username,
                selected_products: selectedProducts
            }),
            })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Log success message or error message from the server
        })
        .catch(error => {
            console.error('Error updating status:', error);
        });

        }

         // Call the function to create the button
         createButton2('<?php echo urlencode($username); ?>');


    </script>

<?php endif; ?>


<?php

echo "<p style=\"font-size: 25pt;\">MAP</p>";


        ?>
    <div id="map" style="height: 400px;"></div>
<?php

//echo "<p style=\"font-size: 25pt;\">$myText4</p>";
echo "<p style=\"font-size: 25pt;\">$myText7</p>";
// Call the create_panel.php script here
include "create_panel.php";

?>
        <!-- add the panel here -->

<?php
        include "log_out.html";
        
    ?>


<!-- Include Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

<!-- Include Leaflet Search plugin JavaScript -->
<script src="https://unpkg.com/leaflet-search"></script>


<script>




    
    var map = L.map('map').fitWorld();
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var markersLayer = L.layerGroup().addTo(map);

    // Create an instance of L.Control.Search after the plugin script has been loaded
    var controlSearch = new L.Control.Search({
        position: "topright",
        layer: markersLayer,
        propertyName: "title",
        initial: false,
        zoom: 20,
        marker: false
    });
    map.addControl(controlSearch);
    



    let initialMarkerPosition;
// Declare LocationMarker variable in the global scope
let LocationMarker;

    // Function to handle location found
    function onLocationFound(e) {
        const radius = e.accuracy / 2;
      // Fetch vehicle data from the database (using AJAX)
   // Function to handle location found

  
    // Fetch vehicle data from the database (using AJAX)
    fetch('get_vehicle_data.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(vehicle => {
                if (vehicle.name === "<?php echo $username; ?>") { // Check if the vehicle's name matches the rescuer's username
                    LocationMarker = L.marker([vehicle.latitude, vehicle.longitude], { draggable: true }).addTo(map);

                    const popupContent = `
                        <b>Name:</b> ${vehicle.name}<br>
                        <b>Cargo:</b> ${vehicle.cargo}<br>
                        <b>Status:</b> ${vehicle.status}<br>
                    `;
                    LocationMarker.bindPopup(popupContent);

                    // Zoom the map to the location of the vehicle
                    map.setView([vehicle.latitude, vehicle.longitude], 15);

                    // Store initial marker position
                    let initialMarkerPosition = LocationMarker.getLatLng();

                    // Event listener for when the marker is moved
                    LocationMarker.on('moveend', function (event) {
                        confirmMarkerMovement(LocationMarker, initialMarkerPosition);
                    });
                }
            });
        })
        .catch(error => {
            console.error('Error fetching vehicle data:', error);
        });


       

         // Retrieve base marker position from the database (using AJAX)
    fetch('get_base_marker_position.php')
    .then(response => response.json())
    .then(data => {
        if (data.latitude && data.longitude) {
            const initialMarkerPosition = L.latLng(data.latitude, data.longitude);
            const randomMarker = L.marker(initialMarkerPosition, { draggable: false }).addTo(map);
            randomMarker.bindPopup("<b>BASE</b>");
            
           
            
          
        } else {
            console.error('Failed to fetch base marker position from the database');
        }
    })
    .catch(error => {
        console.error('Error fetching base marker position:', error);
    });


    
    }




   // Function to display a confirmation popup when the marker is moved
function confirmMarkerMovement(marker, initialPosition) {
    if (confirm("Are you sure you want to change the marker position?")) {
        // User clicked "Yes"
        console.log("Marker position changed.");
        saveMarkerPositionToDatabase(marker);
    } else {
        // User clicked "No"
        fetch('get_vehicle_marker_position.php')
         .then(response => response.json())
         .then(data => {
             if (data.latitude && data.longitude) {
                 const initialMarkerPosition = L.latLng(data.latitude, data.longitude);
                 marker.setLatLng(initialMarkerPosition);
                 marker.openPopup();
             } else {
                 console.error('Failed to fetch base marker position from the database');
             }
         })
         .catch(error => {
             console.error('Error fetching base marker position:', error);
         });
    }
}

function saveMarkerPositionToDatabase(marker) {
    const latitude = marker.getLatLng().lat;
    const longitude = marker.getLatLng().lng;
    const data = { latitude: latitude, longitude: longitude };

    // Send marker position data to the server (using AJAX)
    fetch('save_marker_position.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Log success message or error message from the server
    })
    .catch(error => {
        console.error('Error saving marker position:', error);
    });
}




// Fetch data from PHP script and add markers
function fetchAndAddMarkers() {
    const rescuerName = "<?php echo $username; ?>";
    fetch('get_help_offering_data.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({rescuer_name: rescuerName }), // Include rescuer's name in the request body
})
    .then(response => response.json())
    .then(data => {
        addHelpOfferingMarkers(data);
    })
    .catch(error => {
        console.error('Error fetching help offering data:', error);
    });
}
// Fetch data from PHP script and add markers
function fetchAndAddMarkers2() {
    const rescuerName = "<?php echo $username; ?>";
    fetch('get_help_offering_data.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({rescuer_name: rescuerName }), // Include rescuer's name in the request body
})
    .then(response => response.json())
    .then(data => {
        addHelpOfferingMarkers2(data);
    })
    .catch(error => {
        console.error('Error fetching help offering data:', error);
    });
}


function fetchAndAddReqMarkers() {
    // Get the rescuer's name
const rescuerName = "<?php echo $username; ?>";
        // Fetch data from PHP script and add markers
        fetch('show_announcements_wanted.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({rescuer_name: rescuerName }), // Include rescuer's name in the request body
})
          .then(response => response.json())
          .then(data => {
              addAnnouncementMarkers(data);
          })
          .catch(error => {
              console.error('Error fetching data:', error);
          });
    }

    function fetchAndAddVehicleMarkers() {
        // Fetch data from PHP script and add markers
        fetch('show_active_vehicles_rescuer.php')
          .then(response => response.json())
          .then(data => {
            addVehicleMarkers(data);
          })
          .catch(error => {
              console.error('Error fetching data:', error);
          });
        }

        function fetchAndAddVehicleMarkers2() {
            // Fetch data from PHP script and add markers
            fetch('show_inactive_vehicles_rescuer.php')
              .then(response => response.json())
              .then(data => {
                addVehicleMarkers2(data);
              })
              .catch(error => {
                  console.error('Error fetching data:', error);
              });
            }


    function fetchAndAddReqMarkers2() {

        // Get the rescuer's name
const rescuerName = "<?php echo $username; ?>";
        // Fetch data from PHP script and add markers
        fetch('show_announcements_wanted.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({rescuer_name: rescuerName }), // Include rescuer's name in the request body
})
          .then(response => response.json())
          .then(data => {
              addAnnouncementMarkers2(data);
          })
          .catch(error => {
              console.error('Error fetching data:', error);
          });
        }






    
// Call the function to fetch data and add markers when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    fetchAndAddMarkers();
    fetchAndAddMarkers2();
    fetchAndAddReqMarkers();
    fetchAndAddReqMarkers2();
    fetchAndAddVehicleMarkers();
    fetchAndAddVehicleMarkers2();
});

// Toggle the visibility of the offers layer group based on user interaction
function toggleOffersLayer() {
    if (map.hasLayer(offersLayer)) {
        map.removeLayer(offersLayer);
    } else {
        map.addLayer(offersLayer);
    }
}



// Toggle the visibility of the offers layer group based on user interaction
function toggleReqLayer() {
    if (map.hasLayer(requestsLayer)) {
        map.removeLayer(requestsLayer);
    } else {
        map.addLayer(requestsLayer);
       
    }
}

// Toggle the visibility of the offers layer group based on user interaction
function toggleReqLayer2() {
    if (map.hasLayer(requestsLayer2)) {
        map.removeLayer(requestsLayer2);
    } else {
        map.addLayer(requestsLayer2);
       
    }
}



// Toggle the visibility of the offers layer group based on user interaction
function toggleOffersLayer2() {
    if (map.hasLayer(offersLayer2)) {
        map.removeLayer(offersLayer2);
    } else {
        map.addLayer(offersLayer2);
    }
}

// Toggle the visibility of the offers layer group based on user interaction
function toggleVehicleLayer() {
    if (map.hasLayer(vehiclesWithTasksLayer)) {
        map.removeLayer(vehiclesWithTasksLayer);
    } else {
        map.addLayer(vehiclesWithTasksLayer);
    }
}

// Toggle the visibility of the offers layer group based on user interaction
function toggleVehicleLayer2() {
    if (map.hasLayer(vehiclesWithoutTasksLayer)) {
        map.removeLayer(vehiclesWithoutTasksLayer);
    } else {
        map.addLayer(vehiclesWithoutTasksLayer);
    }
}
    
    // Function to handle location error
    function onLocationError(e) {
        alert("Location error: " + e.message);
    }

    // Request location permission and locate user
    map.on('locationfound', onLocationFound);
    map.on('locationerror', onLocationError);
    map.locate({ setView: true, maxZoom: 16, enableHighAccuracy: true });

    function commitUserInput(lat, lng) {
        const userInput = document.getElementById(`userInput-${lat}-${lng}`).value;
        console.log(`User input for marker at ${lat}, ${lng}: ${userInput}`);
        
        }


        

// Create layer groups
const requestsLayer = L.layerGroup();
const requestsLayer2 = L.layerGroup();
const offersLayer = L.layerGroup();
const offersLayer2 = L.layerGroup();
const vehiclesWithTasksLayer = L.layerGroup();
const vehiclesWithoutTasksLayer = L.layerGroup();
const linesLayer = L.layerGroup();
const linesLayer2 = L.layerGroup();



// Define a green marker icon
const greenIcon = L.icon({
  iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});

// Define a red marker icon
const redIcon = L.icon({
  iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});

// Define a red marker icon
const goldIcon = L.icon({
    iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-gold.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});




/// Function to add markers for announcements
function addAnnouncementMarkers(data) {
    data.forEach(row => {
        let markerIcon = redIcon; // Default color is red
        if (row.accepted === 'not_expected') {
           return;
            // markerIcon = greenIcon;
        }

        if (row.accepted === 'expected') {
            markerIcon = redIcon; // Green marker for status "expected"
        } else if (row.accepted === 'waiting') {
       
         markerIcon = goldIcon; // Red marker for status "not_expected"
        }
        

        // Fetch phone number from the "user" table based on the username
        fetch(`get_phone_number.php?username=${row.username}`)
            .then(response => response.text())
            .then(phoneNumber => {
                const marker = L.marker([row.lat_cit, row.lon_cit], { icon: markerIcon });
                let popupContent = `<br><b>ΑΙΤΗΜΑ:</b><br><b>Username:</b> ${row.username}<br><b>Phone Number:</b> ${phoneNumber}<br><b>Register Date:</b> ${row.have_seen_date}<br><b>Name:</b> ${row.name}<br><b>Number:</b> ${row.numProducts}<br><b>Date of Completion:</b> ${row.date_of_comp}`;
                // Add button based on accepted status
                if (row.accepted === 'expected') {
                    popupContent += `<br><button onclick="changeStatus('${row.id}')">Get on it</button>`;
                }
                if (row.accepted === 'not_expected') {
                   
                    popupContent += `<br><b>Username:</b> ${row.rescuer_name}`;

                }
                marker.bindPopup(popupContent);
            
            // Add marker to helpOfferingsLayer
            requestsLayer.addLayer(marker);
        })

                
            .catch(error => {
                console.error('Error fetching phone number:', error);
            });
    });
}

  /// Function to add markers for announcements
  function addAnnouncementMarkers2(data) {
    data.forEach(row => {
        let markerIcon = greenIcon;; // Default color is red
        if (row.accepted === 'expected') {
            return;
        } else if (row.accepted === 'not_expected') {
            markerIcon = greenIcon; // Red marker for status "not_expected"
        }
        else if (row.accepted === 'waiting') {
         return;
      }
  
        // Fetch phone number from the "user" table based on the username
        fetch(`get_phone_number.php?username=${row.username}`)
            .then(response => response.text())
            .then(phoneNumber => {
                const marker = L.marker([row.lat_cit, row.lon_cit], { icon: markerIcon });
                let popupContent = `<br><b>ΑΙΤΗΜΑ:</b><br><b>Username:</b> ${row.username}<br><b>Phone Number:</b> ${phoneNumber}<br><b>Register Date:</b> ${row.have_seen_date}<br><b>Name:</b> ${row.name}<br><b>Number:</b> ${row.numProducts}<br><b>Date of Completion:</b> ${row.date_of_comp}`;
                // Add button based on accepted status
                if (row.accepted === 'not_expected') {
                  popupContent += `<br><b>Rescuer Username:</b> ${row.rescuer_name}`;
  
              }
                
                marker.bindPopup(popupContent);
              
               // Add marker to helpOfferingsLayer
               marker.addTo(requestsLayer2);
              })
               
  
            .catch(error => {
                console.error('Error fetching phone number:', error);
            });
    });
  }


   /// Function to add markers for announcements
function addVehicleMarkers(data) {
    data.forEach(row => {
        let markerIcon = goldIcon; // Default color is red
        
  
        const marker = L.marker([parseFloat(row.latitude), parseFloat(row.longitude)], { icon: markerIcon });
        
        // Define popup content with all table info
        let popupContent = `<br><b>Διασώστης:</b><br><b>Όνοματεπώνυμο:</b> ${row.name}<br><b>Περιεχόμενα:</b> ${row.cargo}<br><b>Κατάσταση:</b> ${row.status}`;

        


        marker.bindPopup(popupContent);
        marker.addTo(vehiclesWithTasksLayer); // Add marker to the offers layer group

    });
  }

   /// Function to add markers for announcements
function addVehicleMarkers2(data) {
    data.forEach(row => {
        
        
  
        const marker = L.marker([parseFloat(row.latitude), parseFloat(row.longitude)]);
        
        // Define popup content with all table info
        let popupContent = `<br><b>Διασώστης:</b><br><b>Όνοματεπώνυμο:</b> ${row.name}<br><b>Περιεχόμενα:</b> ${row.cargo}<br><b>Κατάσταση:</b> ${row.status}`;

        


        marker.bindPopup(popupContent);
        marker.addTo(vehiclesWithoutTasksLayer); // Add marker to the offers layer group

    });
  }







// Define a global variable to track the number of markers taken
let markersTaken = 0;

// Function to change status from expected to waiting
function changeStatus(id) {


    // Get the rescuer's name
    const rescuerName = "<?php echo $username; ?>";

    // Send request to update status in the database
    fetch('update_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id, rescuer_name: rescuerName }), // Include rescuer's name in the request body
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Log success message or error message from the server
        // Close all popups
        map.closePopup();
        // Refresh the markers after status change
        refreshMarkers();
        // // Draw polyline from marker to location marker
        // drawPolyline(id);

        // Increment the markers taken counter
    // markersTaken++;
    // console.log(markersTaken);
    })
    .catch(error => {
        console.error('Error updating status:', error);

    });
    

    // Send request to update status in the database
    fetch('update_availability.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id, rescuer_name: rescuerName }), // Include rescuer's name in the request body
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Log success message or error message from the server

    })
    .catch(error => {
        console.error('Error updating availability:', error);
    });


}





// Function to refresh markers after status change
function refreshMarkers() {
    // Clear existing markers
    markersLayer.clearLayers();
     // Get the rescuer's name
const rescuerName = "<?php echo $username; ?>";
        // Fetch data from PHP script and add markers
        fetch('show_announcements_wanted.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({rescuer_name: rescuerName }), // Include rescuer's name in the request body
})
          .then(response => response.json())
          .then(data => {
              addAnnouncementMarkers(data);
              addAnnouncementMarkers2(data);
          })
          .catch(error => {
              console.error('Error fetching data:', error);
          });
}










// Function to add markers for help offerings
function addHelpOfferingMarkers(data) {
    data.forEach(item => {
       
        if (item.accepted === 'received') {
            return;
            // customIcon = greenIcon;
        
        }
        else if (item.accepted === 'expected') {
            customIcon = YellowIcon; // Green marker for status "expected"
        }
        else if (item.accepted === 'waiting') {
            customIcon = PurpleIcon; // Red marker for status "not_expected"
        }
         
        
        // Add marker for each latitude and longitude pair
        const marker = L.marker([parseFloat(item.latitude), parseFloat(item.longitude)], { icon: customIcon });

        
       


        // Define popup content with all table info
        let popupContent = `<br><b>ΠΡΟΣΦΟΡΑ:</b><br><b>Username:</b> ${item.username}<br><b>Phone Number:</b> ${item.phone_num}<br><b>Register Date:</b> ${item.date}<br><b>Product Name:</b> ${item.product_name}<br><b>Number:</b> ${item.product_num}`;

        if (item.accepted === 'expected') {
                    popupContent += `<br><button onclick="changeStatusOffer('${item.id}')">Get onto it</button>`;
                }
                

        // Bind popup to marker
        marker.bindPopup(popupContent);

        // Add marker to helpOfferingsLayer
        offersLayer.addLayer(marker);
    });
}

// Function to add markers for help offerings
function addHelpOfferingMarkers2(data) {
    data.forEach(item => {
        let customIcon = YellowIcon; // Default color is red
        if (item.accepted === 'received') {
            customIcon = GreenIcon; // Green marker for status "expected"
        }
        else if (item.accepted === 'expected') {
           return;
        }
        else if (item.accepted === 'waiting') {
            return;
        }

        const marker = L.marker([parseFloat(item.latitude), parseFloat(item.longitude)], { icon: customIcon });
        
        // Define popup content with all table info
        let popupContent = `<br><b>ΠΡΟΣΦΟΡΑ:</b><br><b>Όνοματεπώνυμο:</b> ${item.username}<br><b>Τηλέφωνο:</b> ${item.phone_num}<br><b>Ημερομηνία καταχώρησης:</b> ${item.date}<br><b>Όνομα προιόντος:</b> ${item.product_name}<br><b>Απαιτούμενο νούμερο:</b> ${item.product_num}`;

        
            if (item.accepted === 'received') {
                    popupContent += `<br><b>Ημερομηνία ανάληψης από όχημα:</b> ${item.date_of_comp}`;
                    popupContent += `<br><b>Όνομα Διασώστη:</b> ${item.rescuer_name}`;

            }

        marker.bindPopup(popupContent);
        marker.addTo(offersLayer2); // Add marker to the offers layer group
    });
}



     


// Function to change status from expected to waiting
function changeStatusOffer(id) {
    // Check if the maximum number of markers has been taken
    // if (markersTaken >= 4) {
    //     alert("You have already taken the maximum number of markers.");
    //     return;
    // }

    

    // Get the rescuer's name
    const rescuerName = "<?php echo $username; ?>";

    // Send request to update status in the database
    fetch('update_status_offer.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id, rescuer_name: rescuerName }), // Include rescuer's name in the request body
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Log success message or error message from the server
        // Close all popups
        map.closePopup();
        // Refresh the markers after status change
        refreshMarkersOffer();

        // Increment the markers taken counter
    // markersTaken++;
    // console.log(markersTaken);
        
    })
    .catch(error => {
        console.error('Error updating status:', error);
    });


    // Send request to update status in the database
    fetch('update_availability.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id, rescuer_name: rescuerName }), // Include rescuer's name in the request body
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Log success message or error message from the server

    })
    .catch(error => {
        console.error('Error updating availability:', error);
    });

}



// Function to refresh markers after status change
function refreshMarkersOffer() {
    const rescuerName = "<?php echo $username; ?>";

    // Clear existing markers
    markersLayer.clearLayers();
    // Fetch data from PHP script and add markers
    fetch('show_offerings.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({rescuer_name: rescuerName }), // Include rescuer's name in the request body
})
        .then(response => response.json())
        .then(data => {
            addHelpOfferingMarkers(data);
            addHelpOfferingMarkers2(data);
            
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            
        });
}



        

// Add layers to the map
const layerControl = L.control.layers(null, {
  'Αιτήματα, αιτήματα ενεργά': requestsLayer,
  'Αιτήματα ολοκληρωμένα': requestsLayer2,
  'Προσφορές, προσφορές ενεργές': offersLayer,
  'Προσφορές ολοκληρωμένες': offersLayer2,
  'Οχήματα με Tasks': vehiclesWithTasksLayer,
  'Οχήματα χωρίς Tasks': vehiclesWithoutTasksLayer,
  'Ευθείες Γραμμές Ενεργών Task': linesLayer,
  'Ευθείες Γραμμές Ολοκληρωμένων Task': linesLayer2,
}).addTo(map);

// Fetch polyline data from the server
fetch('fetch_polylines.php')
    .then(response => response.json())
    .then(data => {
        // Loop through the polyline data
        data.forEach(polyline => {
            // Extract polyline coordinates and other info
            const startLat = polyline.polyline_start_lat;
            const startLon = polyline.polyline_start_lon;
            const endLat = polyline.polyline_end_lat;
            const endLon = polyline.polyline_end_lon;

            // Create polyline coordinates array
            const polylineCoords = [
                [startLat, startLon],
                [endLat, endLon]
            ];

            // Create polyline object
            const polylineObj = L.polyline(polylineCoords).addTo(linesLayer);

            // Add popup with info
            const popupContent = `<b>Start Info:</b> `;
            polylineObj.bindPopup(popupContent);
        });
    })
    .catch(error => {
        console.error('Error fetching polyline data:', error);
    });

    // Fetch polyline data from the server
fetch('fetch_polylines2.php')
.then(response => response.json())
.then(data => {
    // Loop through the polyline data
    data.forEach(polyline => {
        // Extract polyline coordinates and other info
        const startLat = polyline.polyline_start_lat;
        const startLon = polyline.polyline_start_lon;
        const endLat = polyline.polyline_end_lat;
        const endLon = polyline.polyline_end_lon;

        // Create polyline coordinates array
        const polylineCoords = [
            [startLat, startLon],
            [endLat, endLon]
        ];

        // Create polyline object
        const polylineObj = L.polyline(polylineCoords).addTo(linesLayer2);

        // Add popup with info
        const popupContent = `<b>Start Info:</b> `;
        polylineObj.bindPopup(popupContent);
    });
})
.catch(error => {
    console.error('Error fetching polyline data:', error);
});
    
// Add example markers and lines

// Define a green marker icon
const YellowIcon = L.icon({
    iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});
// Define a green marker icon
const GreenIcon = L.icon({
    iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});

// Define a red marker icon
const PurpleIcon = L.icon({
    iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-violet.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});




// // Function to fetch and add markers when 'Αιτήματα' layer is selected
// function onRequestsLayerAdd() {
//     fetch('show_announcements_wanted.php')
//         .then(response => response.json())
//         .then(data => {
//             addAnnouncementMarkers(data);
//         })
//         .catch(error => {
//             console.error('Error fetching data:', error);
//         });
// }

// // Function to remove markers when 'Αιτήματα' layer is deselected
// function onRequestsLayerRemove() {
//     requestsLayer.clearLayers();
// }

// // Add event listeners for layer control changes
// map.on('overlayadd', function(eventLayer) {
//     if (eventLayer.name === 'Αιτήματα') {
//         onRequestsLayerAdd();
//     }
// });

// map.on('overlayremove', function(eventLayer) {
//     if (eventLayer.name === 'Αιτήματα') {
//         onRequestsLayerRemove();
//     }
// });

// // Function to fetch and add markers when 'Προσφορές' layer is selected
// function onOffersLayerAdd() {
//     fetch('show_offerings.php')
//         .then(response => response.json())
//         .then(data => {
//             addHelpOfferingMarkers(data);
//         })
//         .catch(error => {
//             console.error('Error fetching data:', error);
//         });
// }

// // Function to remove markers when 'Προσφορές' layer is deselected
// function onOffersLayerRemove() {
//     offersLayer.clearLayers();
// }

// // Add event listeners for layer control changes
// map.on('overlayadd', function(eventLayer) {
//     if (eventLayer.name === 'Προσφορές') {
//         onOffersLayerAdd();
//     }
// });

// map.on('overlayremove', function(eventLayer) {
//     if (eventLayer.name === 'Προσφορές') {
//         onOffersLayerRemove();
//     }
// });








</script>

