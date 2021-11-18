<h1>Admin Dashboard</h1>
<p>Hi {{ auth()->user()->getFullName() }}</p>

<br>
<br>
<br>

<form action="{{ route('auth.logout') }}" method="post">
    @csrf
    <button type="submit">Uitloggen</button>
</form>
