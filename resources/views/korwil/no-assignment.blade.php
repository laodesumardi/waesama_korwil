@extends('layouts.app')

@section('content')
<div class="no-assignment">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center py-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                    </div>
                    <h4 class="mb-3">Data Korwil Belum Tersedia</h4>
                    <p class="text-muted mb-4">
                        Data koordinator wilayah Anda belum lengkap.<br>
                        Silakan hubungi administrator untuk melengkapi data.
                    </p>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="btn btn-warning">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
