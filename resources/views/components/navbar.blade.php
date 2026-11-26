<nav>
    <a href="{{ url('/') }}"><strong>Training Institute</strong></a>
    @auth
        | <a href="{{ route('courses.index') }}">Courses</a>
        | <a href="{{ route('students.index') }}">Students</a>
        | <strong>Logged in as {{ auth()->user()->name }}</strong>
        | <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit">Log Out</button>
        </form>
    @endauth

    @guest
        | <a href="{{ route('register') }}">Register</a>
        | <a href="{{ route('login') }}">Login</a>
    @endguest
</nav>
<hr>
