<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceffouria | Sign Up</title>
    <style>
        /* CSS ini disalin langsung dari login.blade.php untuk konsistensi tampilan */
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Times New Roman', serif;
            overflow: hidden;
            color: #E0E0E0;
        }
        .bg-container {
            /* Pastikan gambar latar belakang (kopi) dan logo sudah ada di public/images/ */
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
            background: rgba(0, 0, 0, 0.2); 
        }
        .login-card {
            padding: 40px;
            text-align: center;
            z-index: 10;
            width: 350px;
            max-width: 90%;
            position: relative;
        }
        .logo-container {
            margin-bottom: 0px; 
            padding-bottom: 0px; 
        }
        .logo-container img {
            width: 200px;
            height: auto;
            display: block; 
            margin: 0 auto; 
            transform: translateY(-50px);
        }
        .sub-heading {
            font-size: 1.5em;
            color: #F5F5F5;
            /* Di sini kita ganti tulisan di tengah menjadi REGISTER */
            margin-top: -20px; 
            margin-bottom: 30px; 
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
        /* Tombol Register (Submit) - Kita gunakan gaya gelap */
        .btn-register-submit { 
            background-color: #5d4037;
            color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .btn-register-submit:hover {
            background-color: #4e342e;
        }
        /* Tombol Kembali ke Login (Link) - Kita gunakan gaya terang */
        .btn-login-link { 
            background-color: #A1887F;
            color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .btn-login-link:hover {
            background-color: #8D6E63;
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

            <div class="sub-heading">REGISTER</div> <form method="POST" action="{{ route('signup.post') }}">
                @csrf
                
                <div class="form-group">
                    <label for="username">USERNAME</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus placeholder="Nama Pengguna">
                    @error('username')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">EMAIL</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="hello@reallygreatsite">
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
                    <button type="submit" class="btn btn-register-submit">Sign Up</button>
                    <a href="{{ route('login') }}" class="btn btn-login-link">Login</a>
                </div>
            </form>

            <img src="{{ asset('images/kopi1.png') }}" alt="Biji Kopi Dekorasi Kiri" class="coffee-beans beans-left">
            <img src="{{ asset('images/kopi1.png') }}" alt="Biji Kopi Dekorasi Kanan" class="coffee-beans beans-right">

        </div>
    </div>
</body>
</html>