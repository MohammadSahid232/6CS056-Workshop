<x-layouts.app title="Login">
    <h1>Login</h1>

    <form action="{{ route('login.submit') }}" method="POST">
        @csrf

        <x-form-input name="email" label="Email" type="email" required autofocus />

        <div>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required>
            @error('password')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <br>

        <div>
            <button type="submit">Login</button>
            <a href="{{ route('register') }}">Need an account? Register</a>
        </div>
    </form>
</x-layouts.app>
