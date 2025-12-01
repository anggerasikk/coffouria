<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceffouria - @yield('title')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5e8c0;
            min-height: 100vh;
            color: #5a4a42;
            overflow-x: hidden;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 30px;
            background: #f5e8c0;
            margin-left: 280px; /* Match sidebar width */
        }

        /* Content Area */
        .content-area {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(182, 143, 130, 0.2);
            border: 2px solid #b68f82;
            min-height: calc(100vh - 200px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Component -->
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header Component -->
            @include('components.header')

            <!-- Content -->
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>