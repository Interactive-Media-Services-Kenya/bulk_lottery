<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP - Verification</title>
    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/png">
</head>

<body>
    <div id="auth">

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="{{ route('home') }}"><img src="{{ asset('assets/images/logo/logo.svg') }}"
                                alt="Logo"></a>
                    </div>
                    <h1 class="auth-title">Enter OTP Code</h1>
                    <p class="auth-subtitle mb-5">Enter OTP 4-digit Code sent to your phone number.</p>
                    <p class="text-center text-success">We sent code to your phone :
                        {{ substr(auth()->user()->phone, 0, 5) . '*****' . substr(auth()->user()->phone, -2) }}
                    </p>

                    @if ($message = Session::get('success'))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success alert-block">
                                    <strong>{{ $message }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($message = Session::get('error'))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-block">
                                    <strong>{{ $message }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('otp.post') }}">
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="number" name="code" class="form-control form-control-xl"
                                placeholder="OTP CODE">
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Submit</button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-4">
                        <a href="{{ route('otp.resend') }}">
                            <button class="btn btn-warning btn-block btn-sm shadow-lg mt-5">Resend Code?</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">

                </div>
            </div>
        </div>

    </div>
</body>

</html>
