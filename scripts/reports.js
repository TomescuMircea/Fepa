function initializeMap(markers) {
  if (markers.length === 0) {
      var map = L.map("map").setView([47.151726, 27.587914], 14);
      L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
          maxZoom: 19,
          attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      }).addTo(map);
  }
  else {
      var map = L.map("map").setView([markers[0].latitude, markers[0].longitude], 14);
      L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
          maxZoom: 19,
          attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      }).addTo(map);

      markers.forEach(function (marker) {
          var markerInstance = L.marker([marker.latitude, marker.longitude]).addTo(map);
          markerInstance.bindPopup(`<b>Report id: ${marker.reportId}</b><br><a href = "https://localhost/tw-project/index.php/reports/${marker.reportId}">See details<a>`);
      });
  }
}

// Fetch the markers
fetch('http://localhost/tw-project/index.php/coordinates')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.json();
    })
    .then(markers => {
        // Initialize the map with the fetched markers
        initializeMap(markers);
    })
    .catch(error => console.error('Error fetching marker data:', error));

