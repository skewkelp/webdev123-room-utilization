document.addEventListener('DOMContentLoaded', function() {
    // Fetch user data when the page loads
    fetch('../ajax/get_users.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tableBody = document.querySelector('#userTable tbody');
                // Clear existing table content
                tableBody.innerHTML = '';
                
                // Add each user to the table
                data.data.forEach(user => {
                    tableBody.innerHTML += `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.first_name}</td>
                            <td>${user.last_name}</td>
                            <td>${user.username}</td>
                            <td>${user.role}</td>
                            <td>${user.status}</td>
                        </tr>
                    `;
                });
            } else {
                console.error('Error fetching user data:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
});
