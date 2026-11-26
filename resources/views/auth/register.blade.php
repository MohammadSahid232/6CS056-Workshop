<x-layouts.app title="Register">
    <h1>Register</h1>

    <form action="{{ route('register.submit') }}" method="POST">
        @csrf

        <x-form-input name="name" label="Name" required autofocus maxlength="255" />
        <x-form-input name="email" label="Email" type="email" required maxlength="255" />

        <div>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required minlength="8">
            @error('password')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <br>

        <div>
            <label for="password_confirmation">Confirm Password</label><br>
            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
        </div>
        <br>

        <div>
            <button type="submit">Register</button>
            <a href="{{ route('login') }}">Already have an account? Login</a>
        </div>
    </form>
</x-layouts.app>
