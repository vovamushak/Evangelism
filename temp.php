<?php
// Replace 'YOUR_API_KEY' with your actual OpenWeatherMap API key
$apiKey = 'AIzaSyD7qN-YI4B690-nEs3bus5EhE5DErQ4EAA';
$country = 'United States';  // Replace with the desired country name

// URL for the OpenWeatherMap API endpoint
$apiUrl = "http://api.openweathermap.org/data/2.5/weather?q=$country&appid=$apiKey";

// Initialize cURL session
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL session and get the response
$response = curl_exec($ch);

// Check if the request was successful
if ($response) {
    // Decode the JSON response
    $data = json_decode($response, true);

    // Check if the country was found
    if ($data['cod'] == 200) {
        // Get the city name from the response
        $city = $data['name'];

        // Output the result
        echo "City in $country: $city";
    } else {
        // Output an error message
        echo "Error: {$data['message']}";
    }
} else {
    // Output an error message
    echo "Error fetching data from the API.";
}

// Close cURL session
curl_close($ch);
?>