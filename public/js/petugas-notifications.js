// ===== Petugas Notification System =====

let unreadNotifications = 0;

/**
 * Fetch notifikasi petugas dari backend
 */
function fetchPetugasNotifications() {
    fetch(notificationRoutes.get, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        updatePetugasNotificationUI(data);
    })
    .catch(error => {
        console.error('Error fetching notifications:', error);
        // Fallback: tampilkan notifikasi dummy jika backend belum ready
        loadDemoPetugasNotifications();
    });
}

/**
 * Update UI notifikasi petugas
 */
function updatePetugasNotificationUI(data) {
    const badge = document.getElementById('notificationBadge');
    const notificationItems = document.getElementById('notificationItems');
    const emptyState = document.getElementById('emptyState');
    
    unreadNotifications = data.unread_count || 0;
    
    // Update badge
    if (unreadNotifications > 0) {
        badge.textContent = unreadNotifications;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
    
    // Update notification list
    if (data.notifications && data.notifications.length > 0) {
        emptyState.style.display = 'none';
        notificationItems.innerHTML = '';
        
        data.notifications.forEach(notification => {
            const item = createPetugasNotificationItem(notification);
            notificationItems.appendChild(item);
        });
    } else {
        emptyState.style.display = 'block';
        notificationItems.innerHTML = '';
    }
}

/**
 * Membuat elemen notifikasi petugas
 */
function createPetugasNotificationItem(notification) {
    const item = document.createElement('div');
    item.className = 'notification-item ' + (notification.is_read ? '' : 'unread');
    
    let icon = '📋';
    let statusClass = 'info';
    let actionText = 'Lihat';
    let actionUrl = notificationRoutes.petugasDashboard;
    
    // Tentukan icon berdasarkan tipe notifikasi
    if (notification.type === 'pending') {
        icon = '⏳';
        statusClass = 'warning';
        actionText = 'Setujui';
    } else if (notification.type === 'overdue') {
        icon = '⚠️';
        statusClass = notification.days_overdue > 7 ? 'critical' : 'warning';
        actionText = 'Proses';
    }
    
    // Format tanggal
    const createdDate = new Date(notification.created_at).toLocaleDateString('id-ID');
    
    item.innerHTML = `
        <div class="notification-item-left">
            <div class="notification-icon ${statusClass}">
                <i class="icon-status">${icon}</i>
            </div>
        </div>
        <div class="notification-item-content">
            <div class="notification-item-title">${notification.user_name}</div>
            <div class="notification-item-message">
                ${notification.message}
            </div>
            <div class="notification-item-meta">
                Buku: <strong>${notification.book_title}</strong> • ${createdDate}
            </div>
        </div>
        <div class="notification-item-action">
            <a href="${actionUrl}" class="btn-return-book">${actionText}</a>
        </div>
    `;
    
    return item;
}

/**
 * Demo notifikasi petugas (untuk testing)
 */
function loadDemoPetugasNotifications() {
    const demoData = {
        unread_count: 3,
        notifications: [
            {
                id: 1,
                type: 'pending',
                user_name: 'Andi Wijaya',
                book_title: 'Surat untuk Senja',
                message: 'Meminta konfirmasi peminjaman',
                is_read: false,
                created_at: new Date().toISOString()
            },
            {
                id: 2,
                type: 'overdue',
                user_name: 'Budi Santoso',
                book_title: 'Informatika Kelas X',
                message: 'Terlambat 5 hari mengembalikan buku',
                days_overdue: 5,
                is_read: false,
                created_at: new Date().toISOString()
            },
            {
                id: 3,
                type: 'pending',
                user_name: 'Citra Dewi',
                book_title: 'Matematika Diskrit',
                message: 'Meminta konfirmasi peminjaman',
                is_read: false,
                created_at: new Date().toISOString()
            }
        ]
    };
    updatePetugasNotificationUI(demoData);
}

/**
 * Tutup popup notifikasi petugas
 */
function closePetugasNotificationPopup() {
    const popup = document.getElementById('notificationPopup');
    if (popup) {
        popup.style.display = 'none';
    }
    // Mark notifications as read
    markPetugasNotificationsAsRead();
}

/**
 * Buka popup notifikasi petugas
 */
function showNotificationPopup(event) {
    event.preventDefault();
    const popup = document.getElementById('notificationPopup');
    if (popup) {
        popup.style.display = 'block';
    }
}

/**
 * Mark notifikasi petugas sebagai sudah dibaca
 */
function markPetugasNotificationsAsRead() {
    fetch(notificationRoutes.markRead, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': notificationRoutes.csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .catch(error => console.error('Error marking notifications as read:', error));
}

/**
 * Initialize notifikasi petugas
 */
function initPetugasNotifications() {
    // Tutup popup ketika klik di luar
    window.addEventListener('click', function(event) {
        const popup = document.getElementById('notificationPopup');
        if (popup && event.target === popup) {
            closePetugasNotificationPopup();
        }
    });

    // Load notifikasi saat halaman dimuat
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            fetchPetugasNotifications();
            // Refresh notifikasi setiap 15 detik (lebih cepat dari user karena urgent)
            setInterval(fetchPetugasNotifications, 15000);
        });
    } else {
        fetchPetugasNotifications();
        // Refresh notifikasi setiap 15 detik
        setInterval(fetchPetugasNotifications, 15000);
    }
}

// Initialize saat script dimuat
initPetugasNotifications();
