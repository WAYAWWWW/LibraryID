<!-- ===== User Sidebar ===== -->
<aside class="user-sidebar" id="userSidebar">
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <img src="{{ asset('images/logo.png') }}" alt="LibraryID Logo">
            <span class="sidebar-title">
                <span class="menu-text">LibraryID</span>
                <span class="menu-text">Member Area</span>
            </span>
        </a>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                <i class="fas fa-house"></i>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.*') ? 'active' : '' }}" data-tooltip="Buku">
                <i class="fas fa-book"></i>
                <span class="menu-text">Buku</span>
            </a>
        </li>
        <li>
            <a href="{{ route('loans.history') }}" class="{{ request()->routeIs('loans.*') ? 'active' : '' }}" data-tooltip="Peminjaman">
                <i class="fas fa-history"></i>
                <span class="menu-text">Peminjaman</span>
            </a>
        </li>
        <li>
            <a href="{{ route('wishlist.index') }}" class="sidebar-highlight {{ request()->routeIs('wishlist.*') ? 'active' : '' }}" data-tooltip="Wishlist">
                <i class="fas fa-heart"></i>
                <span class="menu-text">Wishlist</span>
            </a>
        </li>
        @if(auth()->user()->isAdmin())
            <li>
                <a href="{{ route('admin.dashboard') }}" data-tooltip="Admin Panel">
                    <i class="fas fa-cog"></i>
                    <span class="menu-text">Admin Panel</span>
                </a>
            </li>
        @endif
    </ul>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="{{ auth()->user()->name }}">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>

        <div class="sidebar-actions">
            <button class="sidebar-action" type="button" onclick="showNotificationPopup(event)" data-tooltip="Notifikasi">
                <span><i class="fas fa-bell"></i><span class="menu-text">Notifikasi</span></span>
                <span class="sidebar-notification-badge" id="notificationBadge">0</span>
            </button>
            <a href="{{ route('profile') }}" class="sidebar-action" data-tooltip="Profil">
                <span><i class="fas fa-user-circle"></i><span class="menu-text">Profil</span></span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-action logout" data-tooltip="Logout">
                    <span><i class="fas fa-sign-out-alt"></i><span class="menu-text">Logout</span></span>
                </button>
            </form>
        </div>
    </div>
</aside>
