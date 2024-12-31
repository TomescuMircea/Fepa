// import Chart from '/tw-project/node_modules/chart.js/auto';

async function fetchDataPieChart() {
    try {
        const response = await fetch(window.location.origin + '/tw-project/index.php/stats/pieChartData', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        // console.log('Response:', response.body.json);
        const data = await response.json();

        // console.log('Data:', data);
        createPieChart(data);
    } catch (error) {
        console.error('Error:', error);
    }
}

function createPieChart(data) {
    const config = {
        type: 'pie',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'values',
                data: data.values,
                backgroundColor: data.colors,
                hoverOffset: 4
            }],
        },
        options: {
            radius: '80%'
        }
    };

    const ctx = document.getElementById('myPieChart').getContext('2d');
    new Chart(ctx, config);
}

async function fetchDataBarChart() {
    try {
        const response = await fetch(window.location.origin + '/tw-project/index.php/stats/barChartData', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        // console.log('Response:', response.body.json);
        const data = await response.json();

        // console.log('Data:', data);
        createBarChart(data);
    } catch (error) {
        console.error('Error:', error);
    }
}

function createBarChart(data) {
    const config = {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Number of missing animals by areas',
                data: data.values,
                backgroundColor: data.colors,
                borderColor: data.colors,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };
    const ctx = document.getElementById('myBarChart').getContext('2d');
    new Chart(ctx, config);
}

async function fetchDataPolarAreaChart() {
    try {
        const response = await fetch(window.location.origin + '/tw-project/index.php/stats/barPolarAreaData', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        // console.log('Response:', response.body.json);
        const data = await response.json();

        // console.log('Data:', data);
        createPolarAreaChart(data);
    } catch (error) {
        console.error('Error:', error);
    }
}

function createPolarAreaChart(data) {
    console.log('Data:', data);
    const config = {
        type: 'polarArea',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'values',
                data: data.values,
                backgroundColor: data.colors
            }]
        },
        option: {}
    };
    const ctx = document.getElementById('myPolarAreaChart').getContext('2d');
    new Chart(ctx, config);
}

async function fetchDataBarHorizontalChart() {
    try {
        const response = await fetch(window.location.origin + '/tw-project/index.php/stats/barChartHorizontalData', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        // console.log('Response:', response.body.json);
        const data = await response.json();

        // console.log('Data:', data);
        createBarChartHorizontal(data);
    } catch (error) {
        console.error('Error:', error);
    }
}

function createBarChartHorizontal(data) {
    const config = {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Number of missing dogs by areas',
                data: data.values,
                backgroundColor: data.colors,
                borderColor: data.colors,
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };
    const ctx = document.getElementById('myBarChartHorizontal').getContext('2d');
    new Chart(ctx, config);
}

async function fetchDataChartsSequentially() {
    await fetchDataPieChart();
    await fetchDataBarChart();
    await fetchDataPolarAreaChart();
    await fetchDataBarHorizontalChart();
}

window.onload = fetchDataChartsSequentially;