document.addEventListener('DOMContentLoaded', function() {
    // Fetch data for stats
    async function fetchStats() {
        try {
            const response = await fetch('/api/stats');
            const data = await response.json();
            
            // Updating the stats data
            const stats = document.querySelectorAll('.stat-card');
            stats[0].querySelector('p').textContent = data.jobPosts;  // Service Posts 
            stats[0].querySelector('.growth').textContent = data.jobPostsGrowth;

            stats[1].querySelector('p').textContent = data.totalArtists;  // Total Artist
            stats[1].querySelector('.growth').textContent = data.totalArtistsGrowth;

            stats[2].querySelector('p').textContent = data.noOfCustomer;  // Total No of Customer
            stats[2].querySelector('.growth').textContent = data.noOfCustomerGrowth;

            stats[3].querySelector('p').textContent = data.noOfUsers;  // Total No of Users
            stats[3].querySelector('.growth').textContent = data.noOfUsersGrowth;
        } catch (error) {
            console.error('Error fetching stats:', error);
        }
    }

    // Fetch data for the chart
    async function fetchChartData() {
        try {
            const response = await fetch('/api/chart-data');
            const data = await response.json();

            const ctx = document.getElementById('responseChart').getContext('2d');
            const responseChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Total Artists', 'Total Customers', 'Total Users'],
                    datasets: [{
                        label: 'Application Users',
                        data: [data.totalArtists, data.totalCustomers, data.totalUsers],  // Use correct keys from API
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.6)',  // appending-response - Blue
                            'rgba(75, 192, 192, 0.6)',  // accepted - Green
                            'rgba(255, 99, 132, 0.6)'   // rejected - Red
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });

            // Update the legend dynamically
            document.querySelector('.TotalArtists').textContent = `Total Artists: ${data.totalArtists}`;
            document.querySelector('.TotalCustomers').textContent = `Total Customers: ${data.totalCustomers}`;
            document.querySelector('.TotalUsers').textContent = `Total Users: ${data.totalUsers}`;
        } catch (error) {
            console.error('Error fetching chart data:', error);
        }
    }

   /*  // Fetch data for recent job posts
    async function fetchRecentJobs() {
        try {
            const response = await fetch('/api/recent-jobs');
            const data = await response.json();

            const servicePostsTableBody = document.querySelector('.service-posts tbody');
            servicePostsTableBody.innerHTML = ''; // Clear existing rows

            data.forEach(job => {
                const row = `
                    <tr>
                        <td>${job.title}</td>
                        <td>${job.category}</td>
                        <td>${job.requests}</td>
                        <td class="${job.status === 'Active' ? 'active' : 'inactive'}">${job.status}</td>
                    </tr>
                `;
                servicePostsTableBody.insertAdjacentHTML('beforeend', row);
            });
        } catch (error) {
            console.error('Error fetching recent job posts:', error);
        }
    } */

    // Initialize fetching of data
    function init() {
        fetchStats();
        fetchChartData();
        /* fetchRecentJobs(); */
    }

    // Call init to fetch all data when the DOM is fully loaded
    init();
});
