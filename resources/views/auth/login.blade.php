<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceffouria | Login</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Times New Roman', serif;
            overflow: hidden;
            color: #E0E0E0;
        }
        .bg-container {
            background-image: url('{{ asset('images/bg.png') }}');
            background-size: cover;
            background-position: center;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
        }
        .login-card {
            padding: 40px;
            text-align: center;
            z-index: 10;
            width: 350px;
            max-width: 90%;
            position: relative;
            /* Tambahkan margin-top negatif jika perlu untuk menggeser ke atas */
            /* margin-top: -50px; /* Contoh: geser seluruh card ke atas */
        }
        /* --- Kustomisasi Logo (Judul) --- */
        .logo-container {
            /* Menghilangkan margin-bottom agar logo menempel ke sub-heading */
            margin-bottom: 0px; 
            padding-bottom: 0px; /* Pastikan tidak ada padding */
        }
        .logo-container img {
            width: 200px; /* Ukuran logo disesuaikan agar tidak terlalu besar */
            height: auto;
            display: block; /* Agar img bisa diatur margin otomatis */
            margin: 0 auto; /* Tengah secara horizontal */
        }
        .sub-heading {
            font-size: 1.5em;
            color: #F5F5F5;
            /* Mengatur margin-top negatif agar tulisan "Sign Up" naik mendekati logo */
            margin-top: -10px; /* Sesuaikan nilai ini */
            margin-bottom: 30px; /* Jarak bawah ke input tetap */
            font-weight: lighter;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        .form-group label {
            display: block;
            font-size: 0.8em;
            color: #F5F5F5;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }
        .form-group input {
            width: 100%;
            padding: 8px 0;
            border: none;
            border-bottom: 1px solid #F5F5F5;
            background: transparent;
            box-sizing: border-box;
            font-size: 1em;
            color: #FFFFFF;
            outline: none;
            text-align: left;
        }
        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .form-group input:focus {
            border-bottom: 2px solid #FFC107;
        }
        .btn-group {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            gap: 20px;
        }
        .btn {
            padding: 10px 30px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            line-height: 20px;
        }
        .btn-login-submit {
            background-color: #A1887F;
            color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .btn-login-submit:hover {
            background-color: #8D6E63;
        }
        .btn-signup-link {
            background-color: #5d4037;
            color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .btn-signup-link:hover {
            background-color: #4e342e;
        }
        .error-message {
            color: #FFC107;
            font-size: 0.8em;
            margin-top: 5px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.9);
        }
        .coffee-beans {
            position: absolute;
            bottom: -150px;
            width: 300px;
            height: auto;
            /* Tambahkan z-index jika biji kopi menutupi form pada kondisi tertentu */
            z-index: 5; 
        }
        .beans-left {
            left: -400px;
            transform: rotate(-10deg);
        }
        .beans-right {
            right: -400px;
            transform: scaleX(-1) rotate(10deg);
        }
        
        @media (max-width: 600px) {
            .beans-left, .beans-right {
                display: none;
            }
            .login-card {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-container">
        <div class="overlay"></div>
        <div class="login-card">
            
            <div class="logo-container">
                <img src="{{ asset('images/logo .png') }}" alt="Ceffouria Logo">
            </div> 

            <div class="sub-heading">Sign Up</div> 

            @if(session('success'))
                <p style="color: #FFC107; margin-bottom: 20px; font-size: 0.9em;">{{ session('success') }}</p>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email">USERNAME</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="hello@reallygreatsite">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <input type="password" id="password" name="password" required placeholder="********">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="btn-group">
                    <a href="{{ route('signup') }}" class="btn btn-signup-link">Sign Up</a>
                    <button type="submit" class="btn btn-login-submit">Login</button>
                </div>
            </form>

            <img src="{{ asset('images/kopi1.png') }}" alt="Biji Kopi Dekorasi Kiri" class="coffee-beans beans-left">
            <img src="{{ asset('images/kopi1.png') }}" alt="Biji Kopi Dekorasi Kanan" class="coffee-beans beans-right">

        </div>
    </div>
</body>
</html>