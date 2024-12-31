var map = L.map("map").setView([47.1563, 27.5843], 14);

L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 19,
  attribution:
    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(map);

var marker = null;

function onMapClick(e) {
  if (marker) {
    map.removeLayer(marker); // Remove the existing marker if it exists
  }
  marker = L.marker(e.latlng).addTo(map);
  document.getElementById('latitude').value = e.latlng.lat; // Update latitude input field
  document.getElementById('longitude').value = e.latlng.lng; // Update longitude input field
}

map.on('click', onMapClick);