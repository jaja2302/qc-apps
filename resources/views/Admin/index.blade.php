<x-layout.app>


    <div class="login-box">


        {{-- <h2>Login</h2> --}}
        <div class="logo-srs">
            <img src="{{ asset('img/Logo-SSS.png') }}" style="height: 100%;width:50%">
        </div>
        <div class="text-center mt-4">
            <p class="text-secondary text-center" style="margin:0 0 30px;
        font-style: normal;
        font-size: 14px;
        font-family: Arial, Helvetica, sans-serif;
        font-weight: 600 ; color: #1e1e1f">
                Silakan masukkan Nama atau Email dan Password yang ada miliki untuk mengakses portal <span style="color: #4CAF50">QC Apps SRS</span>
            </p>
        </div>

        <form class="text-center mt-4" action="{{ route('login') }}" method="post">
            @csrf
            <div class="user-box">
                <input type="text" name="email_or_nama_lengkap" required id="email_or_nama_lengkap" autofocus value="{{old('email_or_nama_lengkap')}}">
                <label for="email_or_nama_lengkap">Email / Nama</label>
                @error('error')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="user-box">
                <input type="password" name="password" id="password" required>
                <label for="password">Password</label>
                @error('error')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="logo-srs">
                <input type="submit" class="tombol" id="loginButton" value="Submit" style="height: 100%;width:100%;
                background-color: #013C5E;
                font-size: 20px;
                color:  #ffffff;
                font-style: normal;
        font-family: Arial, Helvetica, sans-serif;
        font-weight: 600 ;">
            </div>
            <div class="logo-srs">
                <img src="{{ asset('img/LOGO-SRS.png') }}" style="height: 80%;width:15%">
            </div>
        </form>

    </div>

</x-layout.app>