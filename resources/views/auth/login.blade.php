@extends('layouts.app')

@section('content')
    <h1>Login</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label>Email</label>
            <input type="email" name="email" required autofocus />
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" required />
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
@endsection
