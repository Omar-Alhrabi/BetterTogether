<!-- resources/views/admin/partials/sidebar.blade.php -->
<div class="sidebar bg-dark text-white" style="width: 250px; min-height: 100vh;">
    <div class="p-3">
        <h4 class="text-center mb-4">Administrator Control Panel</h4>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Home
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center" href="{{ route('users.index') }}">
                    <i class="fas fa-users me-2"></i>User Management
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center" href="{{ route('activities.index') }}">
                    <i class="fas fa-calendar-alt me-2"></i>Activities Management
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center" href="{{ route('posts.index') }}">
                    <i class="fas fa-file-alt me-2"></i> post Management
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center" href="{{ route('comments.index') }}">
                    <i class="fas fa-comments me-2"></i> Comments management
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center" href="{{ route('donations.index') }}">
                    <i class="fas fa-hand-holding-usd me-2"></i> Donation Management
                </a>
            </li>
        </ul>

        <hr class="my-4">
        
        <div class="d-flex justify-content-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>