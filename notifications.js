// Function to load notifications
function loadNotifications() {
    fetch('get_notifications.php')
        .then(response => response.json())
        .then(data => {
            const notificationList = document.getElementById('notificationList');
            const notificationCount = document.getElementById('notificationCount');
            
            // Update notification count
            const unreadCount = data.filter(n => !n.read_status).length;
            notificationCount.textContent = unreadCount;
            notificationCount.style.display = unreadCount > 0 ? 'block' : 'none';
            
            // Clear existing notifications
            notificationList.innerHTML = '';
            
            if (data.length === 0) {
                notificationList.innerHTML = '<div class="dropdown-item text-center">No notifications</div>';
                return;
            }
            
            // Add notifications to the dropdown 
            data.forEach(notification => {
                const item = document.createElement('a');
                item.href = '#';
                item.className = `dropdown-item ${notification.read_status ? '' : 'unread'}`;
                item.onclick = (e) => {
                    e.preventDefault();
                    handleNotificationClick(notification);
                };
                
                item.innerHTML = `
                    <div class="notification-item">
                        <div class="notification-icon">
                            ${getNotificationIcon(notification.type)}
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">${notification.title}</div>
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-time">
                                ${formatTimeAgo(new Date(notification.created_at))}
                            </div>
                        </div>
                    </div>
                `;
                
                notificationList.appendChild(item);
            });
        })
        .catch(error => console.error('Error loading notifications:', error));
}

// Function to handle notification click 
async function handleNotificationClick(notification) {
    try {
        // Mark notification as read
        const readResponse = await fetch('mark_notification_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notification_id: notification.notification_id })  // It passes the noficicationID
        });

        if (!readResponse.ok) {
            throw new Error('Failed to mark notification as read');
        }

        // If it's a claim notification, show the claim review modal
        if (notification.type === 'claim' && notification.related_claim_id) {
            showClaimReviewModal(notification.related_claim_id);
        }
    } catch (error) {
        console.error('Error handling notification:', error);
        showNotification('Error processing notification', 'error');
    }
}

// Function to show claim review modal
async function showClaimReviewModal(claimId) {
    try {
        const response = await fetch('get_claim_details.php?claim_id=' + claimId);
        if (!response.ok) {
            throw new Error('Failed to fetch claim details');
        }

        const data = await response.json();
        if (!data.success) {
            throw new Error(data.message || 'Failed to load claim details');
        }

        // Populate and show the claim review modal
        document.getElementById('claimDescription').textContent = data.description;
        document.getElementById('claimerInfo').innerHTML = `
            <strong>Name:</strong> ${data.name}<br>
            <strong>Email:</strong> ${data.email}<br>
            <strong>Contact:</strong> ${data.contact}<br>
            <strong>Item:</strong> ${data.item_name}<br>
            <strong>Claim Date:</strong> ${new Date(data.claim_date).toLocaleString()}
        `;
        
        // Set up accept/reject buttons
        document.getElementById('acceptClaimBtn').onclick = () => respondToClaim(claimId, 'accept');
        document.getElementById('rejectClaimBtn').onclick = () => respondToClaim(claimId, 'reject');
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('claimReviewModal'));
        modal.show();
    } catch (error) {
        console.error('Error loading claim details:', error);
        showNotification('Error loading claim details', 'error');
    }
}

// Function to respond to claim
async function respondToClaim(claimId, action) {
    try {
        const response = await fetch('process_claim_response.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                claim_id: claimId,
                action: action
            })
        });

        const data = await response.json();
        if (data.success) {
            // Hide modal
            bootstrap.Modal.getInstance(document.getElementById('claimReviewModal')).hide();
            
            // Show success message
            showNotification(
                action === 'accept' ? 'Claim accepted successfully!' : 'Claim rejected successfully!',
                'success'
            );
            
            // Reload notifications
            loadNotifications();
        } else {
            showNotification(data.message || 'Error processing response', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    }
}

// Helper function to format time ago
function formatTimeAgo(date) {
    const seconds = Math.floor((new Date() - date) / 1000);
    
    let interval = Math.floor(seconds / 31536000);
    if (interval > 1) return interval + ' years ago';
    if (interval === 1) return 'a year ago';
    
    interval = Math.floor(seconds / 2592000);
    if (interval > 1) return interval + ' months ago';
    if (interval === 1) return 'a month ago';
    
    interval = Math.floor(seconds / 86400);
    if (interval > 1) return interval + ' days ago';
    if (interval === 1) return 'yesterday';
    
    interval = Math.floor(seconds / 3600);
    if (interval > 1) return interval + ' hours ago';
    if (interval === 1) return 'an hour ago';
    
    interval = Math.floor(seconds / 60);
    if (interval > 1) return interval + ' minutes ago';
    if (interval === 1) return 'a minute ago';
    
    return 'just now';
}

// Helper function to get notification icon
function getNotificationIcon(type) {
    switch (type) {
        case 'claim':
            return '<i class="fas fa-hand-paper text-warning"></i>';
        case 'accept':
            return '<i class="fas fa-check-circle text-success"></i>';
        case 'reject':
            return '<i class="fas fa-times-circle text-danger"></i>';
        default:
            return '<i class="fas fa-bell text-primary"></i>';
    }
}

// Function to show notifications
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} position-fixed`;
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Initialize notifications when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    // Check for new notifications every 30 seconds
    setInterval(loadNotifications, 30000);
});