<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.partials.head')
    <style>
        /* Tambahan CSS untuk memastikan tidak ada jarak berlebih */
        .main-content {
            flex: 1;
            transition: all 0.3s;
            width: 100%;
            overflow-x: hidden;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .content-wrapper {
            padding: 0;
            width: 100%;
        }

        /* Memastikan sidebar dan konten utama align dengan benar */
        .d-flex {
            display: flex;
            width: 100%;
            position: relative;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .content-wrapper {
                padding: 0;
            }
        }
    </style>
</head>
<body class="bg-light">

@include('layouts.partials.header')

<div class="d-flex">
    {{-- SIDEBAR --}}
    @auth
        @if(auth()->user()->role == 'admin_dinas')
            @include('layouts.partials.sidebars.admin-sidebar')
        @elseif(auth()->user()->role == 'operator_sekolah')
            @include('layouts.partials.sidebars.operator-sidebar')
        @elseif(auth()->user()->role == 'korwil')
            @include('layouts.partials.sidebars.korwil-sidebar')
        @endif
    @endauth

    {{-- MAIN CONTENT --}}
    <div class="main-content">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
</div>

@include('layouts.partials.scripts')

</body>
</html>
