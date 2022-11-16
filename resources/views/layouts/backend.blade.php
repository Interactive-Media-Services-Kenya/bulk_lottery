<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ env('APP_NAME') }} Admin Dashboard</title>

    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main/app-dark.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/png">

    <link rel="stylesheet" href="{{ asset('assets/css/shared/iconly.css') }}">
    @yield('styles')
</head>

<body>
    <div id="app">
        @include('layouts.partials.sidebar')
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            @if ($errors->count() > 0)
                @foreach ($errors as $error)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $error->message }}
                    </div>
                @endforeach
            @endif
            @if (\Session::has('success'))
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <ul>
                        <li>{!! \Session::get('success') !!}</li>
                    </ul>
                </div>
            @endif
            @if (\Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        <li>{!! \Session::get('error') !!}</li>
                    </ul>
                </div>
            @endif
            @yield('content')

            @include('layouts.partials.footer')
        </div>
    </div>
    @include('layouts.partials.scripts')
    @yield('scripts')
</body>

</html>
