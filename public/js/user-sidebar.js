(function () {
    const body = document.body;
    const expandBtn = document.querySelector('[data-sidebar-expand]');

    function lockSidebarOpen() {
        body.classList.add('sidebar-expanded');
        body.classList.add('sidebar-open');
    }

    lockSidebarOpen();

    if (expandBtn) {
        expandBtn.addEventListener('click', (event) => {
            event.preventDefault();
        });
    }

    window.toggleUserSidebar = lockSidebarOpen;
    window.closeUserSidebar = lockSidebarOpen;

    if (typeof window.showNotificationPopup !== 'function') {
        window.showNotificationPopup = function (event) {
            if (event) event.preventDefault();
            const popup = document.getElementById('notificationPopup');
            if (popup) {
                popup.style.display = 'flex';
                return;
            }
            if (window.notificationRoutes && window.notificationRoutes.loansHistory) {
                window.location.href = window.notificationRoutes.loansHistory;
                return;
            }
            window.location.href = '/loans/history';
        };
    }

    if (typeof window.closeNotificationPopup !== 'function') {
        window.closeNotificationPopup = function () {
            const popup = document.getElementById('notificationPopup');
            if (popup) {
                popup.style.display = 'none';
            }
        };
    }

    function getConfirmMessage(form, submitter) {
        if (!form || form.tagName !== 'FORM') return null;

        const explicit = form.getAttribute('data-confirm');
        if (explicit) return explicit;

        if (form.getAttribute('onsubmit')) return null;
        if (submitter && submitter.getAttribute && submitter.getAttribute('onclick') && submitter.getAttribute('onclick').includes('confirm')) {
            return null;
        }

        const action = (form.getAttribute('action') || '').toLowerCase();
        const methodInput = form.querySelector('input[name="_method"]');
        const method = methodInput ? methodInput.value.toUpperCase() : (form.getAttribute('method') || 'GET').toUpperCase();

        const submitterClass = submitter && submitter.classList ? submitter.classList : null;
        const isLogout =
            action.includes('logout') ||
            (submitterClass && (submitterClass.contains('btn-logout') || submitterClass.contains('logout')));

        if (isLogout) {
            return 'Yakin ingin logout?';
        }

        const isDelete =
            method === 'DELETE' ||
            action.includes('delete') ||
            action.includes('destroy') ||
            (submitterClass && (submitterClass.contains('btn-delete') || submitterClass.contains('delete-btn')));

        if (isDelete) {
            return 'Yakin ingin menghapus data ini?';
        }

        return null;
    }

    document.addEventListener('submit', (event) => {
        const form = event.target;
        const submitter = event.submitter || document.activeElement;
        const message = getConfirmMessage(form, submitter);

        if (!message) return;

        if (!window.confirm(message)) {
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    });
})();
