<aside class="sidebar">
    <div class="logo">
        <h1>Coffouria</h1>
    </div>
    <ul class="nav-menu">
        <li><a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">📊 DASHBOARD</a></li>
        <li><a href="{{ route('products.index') }}" class="{{ request()->is('products*') ? 'active' : '' }}">📦 PRODUK</a></li>
        <li><a href="{{ route('reports.index') }}" class="{{ request()->is('reports*') ? 'active' : '' }}">💰 LAPORAN</a></li>
    </ul>
</aside>

<style>
.sidebar {
    width: 280px;
    background: #b68f82;
    padding: 30px 20px;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    overflow-y: auto;
    z-index: 1000;
}

.logo {
    text-align: center;
    margin-bottom: 40px;
    padding: 20px 0;
    border-bottom: 2px solid rgba(255,255,255,0.3);
}

.logo h1 {
    color: #f5e8c0;
    font-size: 28px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.nav-menu {
    list-style: none;
}

.nav-menu li {
    margin-bottom: 15px;
}

.nav-menu a {
    display: block;
    padding: 15px 20px;
    color: #f5e8c0;
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 16px;
    border: 2px solid transparent;
}

.nav-menu a:hover,
.nav-menu a.active {
    background: #f5e8c0;
    color: #b68f82;
    border-color: #f5e8c0;
    transform: translateX(10px);
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        position: relative;
        width: 100%;
        height: auto;
    }
    
    .main-content {
        margin-left: 0;
    }
}
</style>