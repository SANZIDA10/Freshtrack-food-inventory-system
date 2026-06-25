<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FreshTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">FreshTrack</a>
        <div class="navbar-nav flex-row gap-3 me-auto ms-4">
            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Home</a>
            <a class="nav-link {{ request()->is('inventory') ? 'active' : '' }}" href="/inventory">Inventory</a>
            <a class="nav-link {{ request()->is('categories') ? 'active' : '' }}" href="/categories">Categories</a>
            <a class="nav-link {{ request()->is('reports') ? 'active' : '' }}" href="/reports">Reports</a>
        </div>
        <form class="d-flex" role="search" action="/inventory" method="get">
            <input name="q" value="{{ request('q') }}" class="form-control form-control-sm me-2" type="search" placeholder="Search products or categories" aria-label="Search">
            <button class="btn btn-outline-light btn-sm" type="submit">Search</button>
        </form>
    </div>
</nav>

<!-- PAGE CONTENT -->
<div class="container mt-4">
    @yield('content')
</div>

<!-- FOOTER -->
<footer class="bg-dark text-white text-center p-3 mt-5">
    © 2026 FreshTrack System
</footer>

</body>
</html>
