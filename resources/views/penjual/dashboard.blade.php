@extends('layouts.app')
@section('title', 'Dashboard Penjual')
@section('content')
<h1>Dashboard Penjual</h1>
<p>Selamat datang, {{ auth()->user()->nama }}!</p>
@if(auth()->user()->tenan)
    <p>Lapak/Tenan: <strong>{{ auth()->user()->tenan->nama }}</strong></p>
@endif
<ul>
    <li><a href="{{ route('penjual.scan.index') }}">Scan &amp; Cek Keaslian Koin Gobog</a></li>
</ul>
@endsection
