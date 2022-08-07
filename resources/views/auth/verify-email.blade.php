@extends('layouts.app', ['title' => 'Verifikasi Email'])

@section('content')
<div class="col-md-5">
    <div class="card" style="border: none;">
        <div class="card-header bg-primary text-center text-white">
            <h2>{{ auth()->user()->name }}</h2>
        </div>
        <div class="p-3">
            <div class="card-body">
                {{-- Notifikasi kirim ulang email verifikasi berhasil  --}}
                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success text-center">Tautan verifikasi email baru telah dikirim ke email Anda!</div>
                @endif
                <div class="text-center mb-5">
                    <h3>Silahkan verifikasi e-mail address kamu makasiii :) </h3>
                    <p>Kamu harus memverifikasi alamat email kamu untuk mengakses halaman ini.</p>
                </div>
                <form method="POST" action="{{ route('verification.send') }}" class="text-center">
                    @csrf
                    <h5>Belum Dapat Email? Klik Tombol Dibawah</h5>
                    <button type="submit" class="btn btn-primary">Mengirim ulang email verifikasi</button>
                </form>
            </div>
            {{-- Opsional: Tautan ini agar pengguna dapat menghapus cache browser --}}
            <p class="mt-3 mb-0 text-center">
                <small>Ada masalah dengan proses verifikasi atau salah memasukkan email?
                    <br>Silakan mendaftar dengan
                    <a href="/register-retry">lain</a>
                    email address.
                </small>
            </p>
        </div>
    </div>
</div>
@endsection
