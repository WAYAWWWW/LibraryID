// ===== User Notification System =====

let notifications = [];
let unreadCount = 0;

/**
 * Initialize notification system for user
 */
function initUserNotifications() {
    // Load initial notifications
    loadUserNotifications();
    
    // Setup event listeners
    setupNotificationEvents();
    
    // Check for overdue loans and show immediate notification
    checkOverdueLoans();
    
    // Auto-refresh every 30 seconds
    setInterval(loadUserNotifications, 30000);
    
    // Auto-refresh page every 5 minutes for fresh data
    setInterval(() => {
        if (!document.hidden) {
            window.location.reload();
        }
    }, 300000);
}

/**
 * Load user notifications from backend
 */
function loadUserNotifications() {
    if (notificationRoutes.get) {
        fetchUserNotifications();
    } else {
        // Fallback to demo data
        loadDemoUserNotifications();
    }
}

/**
 * Fetch notifications from backend API
 */
function fetchUserNotifications() {
    fetch(notificationRoutes.get, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        updateUserNotificationUI(data);
    })
    .catch(error => {
        console.error('Error fetching notifications:', error);
        loadDemoUserNotifications();
    });
}

/**
 * Update UI with notification data
 */
function updateUserNotificationUI(data) {
    const badge = document.getElementById('notificationBadge');
    
    if (data.notifications && data.notifications.length > 0) {
        notifications = data.notifications;
        unreadCount = data.unread_count || data.notifications.filter(n => !n.is_read).length;
    } else {
        notifications = [];
        unreadCount = 0;
    }
    
    // Update badge
    updateNotificationBadge();
    
    // Render notification list
    renderUserNotifications();
}

/**
 * Update notification badge
 */
function updateNotificationBadge() {
    const badge = document.getElementById('notificationBadge');
    if (!badge) return;
    
    if (unreadCount > 0) {
        badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
        badge.style.display = 'flex';
        badge.classList.add('active');
    } else {
        badge.style.display = 'none';
        badge.classList.remove('active');
    }
}

/**
 * Render user notifications in popup
 */
function renderUserNotifications() {
    const container = document.getElementById('notificationItems');
    const emptyState = document.getElementById('emptyNotification');
    
    if (!container || !emptyState) return;
    
    if (notifications.length === 0) {
        container.innerHTML = '';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    container.innerHTML = '';
    
    // Sort notifications: unread first, then by date
    const sortedNotifications = [...notifications].sort((a, b) => {
        if (a.is_read !== b.is_read) {
            return a.is_read ? 1 : -1;
        }
        return new Date(b.created_at) - new Date(a.created_at);
    });
    
    sortedNotifications.forEach(notification => {
        const item = createUserNotificationItem(notification);
        container.appendChild(item);
    });
}

/**
 * Create a user notification item element
 */
function createUserNotificationItem(notification) {
    const item = document.createElement('div');
    item.className = 'notification-item user-notification-item ' + (notification.is_read ? '' : 'unread');
    
    // Determine notification type and styling
    const { icon, status, title, message } = getUserNotificationDetails(notification);
    
    // Format date
    const createdDate = new Date(notification.created_at);
    const timeAgo = getTimeAgo(createdDate);
    
    item.innerHTML = `
        <div class="notification-item-left">
            <div class="notification-icon-wrapper ${status}">
                <i class="fas fa-${icon}"></i>
            </div>
        </div>
        <div class="notification-item-content">
            <div class="notification-item-title">${title}</div>
            <div class="notification-item-message">${message}</div>
            <div class="notification-item-meta">
                <i class="far fa-clock"></i> ${timeAgo}
                ${notification.days_overdue ? `<strong>• Terlambat ${notification.days_overdue} hari</strong>` : ''}
            </div>
        </div>
        <div class="notification-item-action">
            <a href="${getActionUrl(notification)}" class="btn-return-book">
                ${getActionText(notification)}
            </a>
        </div>
    `;
    
    return item;
}

/**
 * Get notification details based on type
 */
function getUserNotificationDetails(notification) {
    if (notification.type === 'overdue') {
        return {
            icon: 'exclamation-triangle',
            status: 'critical',
            title: 'Buku Terlambat',
            message: `"${notification.book_title}" sudah lewat ${notification.days_overdue} hari dari batas pengembalian`
        };
    } else if (notification.type === 'pending') {
        return {
            icon: 'clock',
            status: 'warning',
            title: 'Menunggu Konfirmasi',
            message: `Permintaan pinjam "${notification.book_title}" menunggu persetujuan petugas`
        };
    } else if (notification.type === 'approved') {
        return {
            icon: 'check-circle',
            status: 'success',
            title: 'Disetujui!',
            message: `Permintaan pinjam "${notification.book_title}" telah disetujui. Ambil buku di perpustakaan.`
        };
    } else if (notification.type === 'due_soon') {
        return {
            icon: 'calendar-alt',
            status: 'warning',
            title: 'Jatuh Tempo',
            message: `"${notification.book_title}" akan jatuh tempo dalam ${notification.days_left} hari`
        };
    } else {
        return {
            icon: 'info-circle',
            status: 'info',
            title: notification.title || 'Pemberitahuan',
            message: notification.message || 'Ada pembaruan pada peminjaman Anda'
        };
    }
}

/**
 * Get action URL based on notification type
 */
function getActionUrl(notification) {
    if (notification.type === 'overdue' || notification.type === 'due_soon') {
        return notificationRoutes.loansHistory + '?filter=active';
    } else if (notification.type === 'pending' || notification.type === 'approved') {
        return notificationRoutes.loansHistory + '?filter=' + notification.type;
    }
    return notificationRoutes.loansHistory;
}

/**
 * Get action text based on notification type
 */
function getActionText(notification) {
    if (notification.type === 'overdue') {
        return 'Kembalikan';
    } else if (notification.type === 'pending') {
        return 'Lihat';
    } else if (notification.type === 'approved') {
        return 'Detail';
    }
    return 'Lihat';
}

/**
 * Get time ago string
 */
function getTimeAgo(date) {
    const seconds = Math.floor((new Date() - date) / 1000);
    
    if (seconds < 60) return 'baru saja';
    
    const minutes = Math.floor(seconds / 60);
    if (minutes < 60) return `${minutes} menit lalu`;
    
    const hours = Math.floor(minutes / 60);
    if (hours < 24) return `${hours} jam lalu`;
    
    const days = Math.floor(hours / 24);
    if (days < 7) return `${days} hari lalu`;
    
    return date.toLocaleDateString('id-ID');
}

/**
 * Setup event listeners
 */
function setupNotificationEvents() {
    // Close popup when clicking outside
    window.addEventListener('click', function(event) {
        const popup = document.getElementById('notificationPopup');
        if (popup && event.target === popup) {
            closeNotificationPopup();
        }
    });
    
    // Close with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeNotificationPopup();
        }
    });
}

/**
 * Show notification popup
 */
function showNotificationPopup(event) {
    if (event) event.preventDefault();
    
    const popup = document.getElementById('notificationPopup');
    if (!popup) return;
    
    popup.style.display = 'flex';
    
    // Mark as read when opening
    if (unreadCount > 0) {
        markNotificationsAsRead();
    }
}

/**
 * Close notification popup
 */
function closeNotificationPopup() {
    const popup = document.getElementById('notificationPopup');
    if (popup) {
        popup.style.display = 'none';
    }
}

/**
 * Mark notifications as read
 */
function markNotificationsAsRead() {
    if (!notificationRoutes.markRead || !notificationRoutes.csrfToken) return;
    
    fetch(notificationRoutes.markRead, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': notificationRoutes.csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(() => {
        unreadCount = 0;
        updateNotificationBadge();
        
        // Update UI to remove unread styling
        document.querySelectorAll('.notification-item.unread').forEach(item => {
            item.classList.remove('unread');
        });
    })
    .catch(error => console.error('Error marking notifications as read:', error));
}

/**
 * Check for overdue loans and show immediate notification
 */
function checkOverdueLoans() {
    if (userLoansData && userLoansData.overdueCount > 0) {
        // Show immediate notification
        setTimeout(() => {
            showOverdueNotification();
        }, 1000);
    }
}

/**
 * Show overdue notification alert
 */
function showOverdueNotification() {
    // Create alert element
    const alert = document.createElement('div');
    alert.className = 'overdue-alert';
    alert.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        background: linear-gradient(135deg, #ff4757 0%, #ff3838 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(255, 71, 87, 0.3);
        z-index: 1000;
        max-width: 350px;
        animation: slideInRight 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    `;
    
    alert.innerHTML = `
        <i class="fas fa-exclamation-triangle" style="font-size: 20px;"></i>
        <div>
            <strong>Peringatan!</strong><br>
            Anda memiliki ${userLoansData.overdueCount} buku terlambat dikembalikan.
        </div>
        <button onclick="this.parentElement.remove()" style="background:none; border:none; color:white; cursor:pointer; margin-left:auto;">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(alert);
    
    // Auto remove after 10 seconds
    setTimeout(() => {
        if (alert.parentElement) {
            alert.remove();
        }
    }, 10000);
}

/**
 * Load demo notifications for testing
 */
function loadDemoUserNotifications() {
    const demoData = {
        unread_count: 3,
        notifications: [
            {
                id: 1,
                type: 'overdue',
                book_title: 'Surat untuk Senja',
                days_overdue: 5,
                is_read: false,
                created_at: new Date(Date.now() - 3600000).toISOString() // 1 hour ago
            },
            {
                id: 2,
                type: 'due_soon',
                book_title: 'Informatika Kelas X',
                days_left: 2,
                is_read: false,
                created_at: new Date(Date.now() - 7200000).toISOString() // 2 hours ago
            },
            {
                id: 3,
                type: 'approved',
                book_title: 'Matematika Diskrit',
                is_read: false,
                created_at: new Date(Date.now() - 86400000).toISOString() // 1 day ago
            },
            {
                id: 4,
                type: 'pending',
                book_title: 'Kalkulus Dasar',
                is_read: true,
                created_at: new Date(Date.now() - 172800000).toISOString() // 2 days ago
            }
        ]
    };
    
    updateUserNotificationUI(demoData);
}

// Initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initUserNotifications);
} else {
    initUserNotifications();
}

// Make functions available globally
window.showNotificationPopup = showNotificationPopup;
window.closeNotificationPopup = closeNotificationPopup;