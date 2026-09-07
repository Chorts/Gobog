@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<h1>Dashboard Admin</h1>
<p>Selamat datang, {{ auth()->user()->nama }}!</p>
<ul>
    <li><a href="{{ route('admin.gobog.index') }}">Kelola Koin Gobog</a></li>
    <li><a href="{{ route('admin.distribusi.index') }}">Distribusi Koin (Rupiah &rarr; Gobog)</a></li>
    <li><a href="{{ route('admin.retur.index') }}">Retur Koin (Gobog &rarr; Rupiah)</a></li>
    <li><a href="{{ route('admin.laporan.index') }}">Laporan Penjualan</a></li>
</ul>
@endsection
