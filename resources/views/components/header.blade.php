<header class="header">
    <h2>@yield('title', 'COFFOURIA')</h2>
    <div class="user-menu">
        <div class="profile-menu">
            <div class="profile-pic">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <span>{{ auth()->user()->name }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">🚪 LOGOUT</button>
        </form>
    </div>
</header>

<style>
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px 0;
    border-bottom: 3px solid #b68f82;
    position: sticky;
    top: 0;
    background: #f5e8c0;
    z-index: 100;
    padding: 20px 30px;
    margin: -30px -30px 30px -30px;
    backdrop-filter: blur(10px);
}

.header h2 {
    color: #b68f82;
    font-size: 32px;
    font-weight: bold;
}

.user-menu {
    display: flex;
    align-items: center;
    gap: 20px;
}

.profile-menu {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    padding: 10px 15px;
    background: #b68f82;
    border-radius: 25px;
    color: #f5e8c0;
}

.profile-pic {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #f5e8c0;
    color: #b68f82;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
}

.logout-btn {
    background: #8B4513;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 25px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s ease;
}

.logout-btn:hover {
    background: #654321;
}
</style>