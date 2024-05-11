<?php
include "security.php";
?>

<!-- Include Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
<!-- SCRIPT GRAFIMATOS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<?--administrator_start_page.php-->
<!DOCTYPE html>
<html>
<head>
    <title>Async PHP Call</title>

    // <!-- Link to your external CSS file -->
    // <link rel="stylesheet" href="styles.css">

    <!-- Include Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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


        
        // Function to fetch data for the chart with custom date range
function fetchChartData(startDate, endDate) {
  fetch(`chart_data.php?start_date=${startDate}&end_date=${endDate}`)
      .then(response => response.json())
      .then(data => {
          // Process the data and create the chart
          createChart(data);
      })
      .catch(error => {
          console.error('Error fetching chart data:', error);
      });
}

function createChart(data) {
    var ctx = document.getElementById('statistics-chart').getContext('2d');
    
    // Check if a chart instance already exists
    if (window.myChart) {
        // Destroy the existing chart instance
        window.myChart.destroy();
    }

    window.myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(entry => entry.date),
            datasets: [
                {
                    label: 'Expected Announcements',
                    data: data.map(entry => entry.announcementsExpected),
                    borderColor: 'blue',
                    borderWidth: 1,
                    fill: false
                },
                {
                    label: 'Expected Help Offerings',
                    data: data.map(entry => entry.helpOfferingsExpected),
                    borderColor: 'green',
                    borderWidth: 1,
                    fill: false
                },
                {
                    label: 'Not Expected Announcements',
                    data: data.map(entry => entry.announcementsNotExpected),
                    borderColor: 'red',
                    borderWidth: 1,
                    fill: false
                },
                {
                    label: 'Not Expected Help Offerings',
                    data: data.map(entry => entry.helpOfferingsNotExpected),
                    borderColor: 'orange',
                    borderWidth: 1,
                    fill: false
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}


      // Function to handle the button click event
function handleDateRangeSelection() {
    // Get the values of start and end dates
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    // Fetch data for the selected date range
    fetchChartData(startDate, endDate);
}


    // Call the function to fetch and create the chart when the DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Get current date in YYYY-MM-DD format
        const currentDate = new Date().toISOString().split('T')[0];
        // Start date for the chart (1/1/2024)
        const startDate = '2024-01-01';
        // Fetch data for the default date range
        fetchChartData(startDate, currentDate);
    });

        
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
$myText8 = " Update all products from the internet \/";

        include "delete_id.html";

echo "<p style=\"font-size: 25pt;\">$myText1</p>";

        include "change_quantity.html";

echo "<p style=\"font-size: 25pt;\">$myText3</p>";

        include "insert_rescuer.html";

echo "<p style=\"font-size: 25pt;\">$myText2</p>";

        include "update.html";
        
echo "<p style=\"font-size: 25pt;\">$myText8</p>";

        include "update_internet.html";

echo "<p style=\"font-size: 25pt;\">$myText5</p>";

        include "difficult.php";

echo "<p style=\"font-size: 25pt;\">$myText6</p>";


        include "show_announcements_from_citizens.php";

        

echo "<p style=\"font-size:25pt;\">map and statistics</p>";

?>

    <div id="map" style="height: 400px;"></div>

    
    
      <div id="statistics-chart-container">
      <h2>Στατιστικά Εξυπηρέτησης</h2>

      <!-- Date range selection -->
    <label for="start-date">Start Date:</label>
    <input type="date" id="start-date" name="start-date">
    <label for="end-date">End Date:</label>
    <input type="date" id="end-date" name="end-date">
    <button onclick="handleDateRangeSelection()">Fetch Data</button>
      
       <canvas id="statistics-chart" width="800" height="400"></canvas>
    </div>

    <?php

    echo "<p style=\"font-size: 25pt;\">$myText4</p>";

    include "log_out.html";
    
?>



</body>



<!-- Include Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

<!-- Include Leaflet Search plugin JavaScript -->
<script src="https://unpkg.com/leaflet-search"></script>



<script>

// window.addEventListener('scroll', function() {
//     // Calculate the percentage of how much the user has scrolled
//     const scrolled = window.scrollY / (document.documentElement.scrollHeight - window.innerHeight);
//     // Interpolate between white (#ffffff) and light blue (#add8e6)
//     const interpolatedColor = interpolateColors('#ffffff', '#add8e6', scrolled);
//     // Set the background color
//     document.body.style.background = interpolatedColor;
// });

// function interpolateColors(color1, color2, factor) {
//     if (factor === 0) return color1;
//     if (factor === 1) return color2;

//     const result = color1.split('').map((char, i) => {
//         const val1 = parseInt(char, 16);
//         const val2 = parseInt(color2[i], 16);
//         const delta = val2 - val1;
//         return Math.round(val1 + delta * factor).toString(16);
//     });
//     return `#${result.join('')}`;
// }



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

  // Define a variable to store the initial marker position
let initialMarkerPosition;

// Function to handle location found
function onLocationFound(e) {
    const radius = e.accuracy / 2;
    const locationMarker = L.marker(e.latlng, { draggable: false }).addTo(map);
    locationMarker.bindPopup("<b>Your location</b>").openPopup();

    // const locationCircle = L.circle(e.latlng, radius).addTo(map);
    // markersLayer.addLayer(locationMarker);

   




    // // Fetch vehicle data from the database (using AJAX)
    // fetch('get_vehicle_data.php')
    // .then(response => response.json())
    // .then(data => {
    //     data.forEach(vehicle => {
    //         const vehicleMarker = L.marker([vehicle.latitude, vehicle.longitude]).addTo(map);
    //         const popupContent = `
               
    //             <b>Name:</b> ${vehicle.name}<br>
    //             <b>Cargo:</b> ${vehicle.cargo}<br>
    //             <b>Status:</b> ${vehicle.status}<br>
    //         `;
    //         vehicleMarker.bindPopup(popupContent);
    //     });
    // })
    // .catch(error => {
    //     console.error('Error fetching vehicle data:', error);
    // });

     // Retrieve base marker position from the database (using AJAX)
    fetch('get_base_marker_position.php')
    .then(response => response.json())
    .then(data => {
        if (data.latitude && data.longitude) {
            const baseMarkerPosition = L.latLng(data.latitude, data.longitude);
            const randomMarker = L.marker(baseMarkerPosition, { draggable: true }).addTo(map);
            randomMarker.bindPopup("<b>BASE</b>");
            
            const polyline = L.polyline([e.latlng, baseMarkerPosition], { color: 'blue' }).addTo(map);

            
            randomMarker.on('drag', function (event) {
                const newLatLng = event.target.getLatLng();
                polyline.setLatLngs([e.latlng, newLatLng]);
            });

            // Store initial marker position
            initialMarkerPosition = randomMarker.getLatLng();

            // Add event listener for when the marker is moved
            randomMarker.on('moveend', function (event) {
                confirmMarkerMovement(randomMarker);
            });
        } else {
            console.error('Failed to fetch base marker position from the database');
        }
    })
    .catch(error => {
        console.error('Error fetching base marker position:', error);
    });

   
}

// Function to save marker position to the database
function saveMarkerPositionToDatabase(marker) {
    const position = marker.getLatLng();
    const data = { latitude: position.lat, longitude: position.lng };

    console.log('Data to be sent to save_base_marker_position.php:', data); // Print the data for debugging


    fetch('save_base_marker_position.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to save base marker position');
        }
        console.log('Base marker position saved successfully.', data); // Print success message
    })
    .catch(error => {
        console.error('Error saving base marker position:', error);
    });
}


// Function to confirm marker movement
function confirmMarkerMovement(marker) {
    if (confirm("Are you sure you want to change the base location?")) {
        // User clicked "Yes"
        console.log("Base location changed.");
        saveMarkerPositionToDatabase(marker);
    } else {
        // User clicked "No"
        fetch('get_base_marker_position.php')
        .then(response => response.json())
        .then(data => {
            if (data.latitude && data.longitude) {
                const baseMarkerPosition = L.latLng(data.latitude, data.longitude);
                marker.setLatLng(baseMarkerPosition);
                marker.openPopup();

                // Retrieve the polyline from the map
                let polyline;
                map.eachLayer(function(layer) {
                    if (layer instanceof L.Polyline) {
                        polyline = layer;
                    }
                });

                // Update the polyline's coordinates
                if (polyline) {
                    const initialMarkerPosition = polyline.getLatLngs()[0];
                    polyline.setLatLngs([initialMarkerPosition, baseMarkerPosition]);
                } else {
                    console.error('Polyline not found on the map');
                }
            } else {
                console.error('Failed to fetch base marker position from the database');
            }
        })
        .catch(error => {
            console.error('Error fetching base marker position:', error);
        });
    }
}

    
 

// Fetch data from PHP script and add markers
function fetchAndAddMarkers() {
    fetch('get_help_offering_data_admin.php')
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
    fetch('get_help_offering_data_admin.php')
    .then(response => response.json())
    .then(data => {
        addHelpOfferingMarkers2(data);
    })
    .catch(error => {
        console.error('Error fetching help offering data:', error);
    });
}


function fetchAndAddReqMarkers() {
    // Fetch data from PHP script and add markers
    fetch('show_announcements_admin_wanted.php')
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
        fetch('show_active_vehicles.php')
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
            fetch('show_inactive_vehicles.php')
              .then(response => response.json())
              .then(data => {
                addVehicleMarkers2(data);
              })
              .catch(error => {
                  console.error('Error fetching data:', error);
              });
            }


    function fetchAndAddReqMarkers2() {
        // Fetch data from PHP script and add markers
        fetch('show_announcements_admin_wanted.php')
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




// Function to add markers for help offerings
function addHelpOfferingMarkers(data) {
    data.forEach(item => {
        let customIcon = YellowIcon; // Default color is red
        if (item.accepted === 'received') {
           return;
        }
        else if (item.accepted === 'expected') {
            customIcon = YellowIcon; // Green marker for status "expected"
        }
        else if (item.accepted === 'waiting') {
            customIcon = PurpleIcon; // Red marker for status "not_expected"
        }

        const marker = L.marker([parseFloat(item.latitude), parseFloat(item.longitude)], { icon: customIcon });
        
        // Define popup content with all table info
        let popupContent = `<br><b>ΠΡΟΣΦΟΡΑ:</b><br><b>Όνοματεπώνυμο:</b> ${item.username}<br><b>Τηλέφωνο:</b> ${item.phone_num}<br><b>Ημερομηνία καταχώρησης:</b> ${item.date}<br><b>Όνομα προιόντος:</b> ${item.product_name}<br><b>Απαιτούμενο νούμερο:</b> ${item.product_num}`;

        
            if (item.accepted === 'received') {
                    popupContent += `<br><b>Ημερομηνία ανάληψης από όχημα:</b> ${item.date_of_comp}`;
                    popupContent += `<br><b>Όνομα Διασώστη:</b> ${item.rescuer_name}`;

            }

        marker.bindPopup(popupContent);
        marker.addTo(offersLayer); // Add marker to the offers layer group
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

/// Function to add markers for announcements
function addAnnouncementMarkers(data) {
    data.forEach(row => {
        let markerIcon = redIcon; // Default color is red
        if (row.accepted === 'expected') {
            markerIcon = redIcon; // Green marker for status "expected"
        } else if (row.accepted === 'not_expected') {
           return;
        }
        else if (row.accepted === 'waiting') {
          markerIcon = goldIcon; // Red marker for status "not_expected"
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
               marker.addTo(requestsLayer);
              })
               
  
            .catch(error => {
                console.error('Error fetching phone number:', error);
            });
    });
  }

  /// Function to add markers for announcements
function addAnnouncementMarkers2(data) {
    data.forEach(row => {
        let markerIcon = redIcon; // Default color is red
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


 









/* SCRIPT GRAFIMATOS */







</script>
</html>
