<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark shadow">
    <a class="navbar-brand" href="{{ custom_url('/') }}">
        {{ config('app.name', 'MCQ Module') }}
    </a>
		<!-- Toggler/collapsibe Button -->
  	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
  	</button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
		<ul class="navbar-nav mr-auto">
    		<li class="nav-item active">
      			<a class="nav-link" href="@if(session('UserType') == 'teacher'){{ custom_url('teacher') }} @elseif(session('UserType') == 'student') {{custom_url('student')}} @endif">Home</a>
    		</li>
        @if(session('UserType') == 'teacher')
            <li class="nav-item">
                <a class="nav-link" href="{{ custom_url('teacher/allques') }}">Questions</a>
            </li>
        @endif
  	</ul>
    </div>
    @if (Auth::check())
      @if (session()->has('event'))
        <button class="btn btn-danger my-2 my-sm-0" type="button" data-toggle="modal" data-target="#EventLogout" data-whatever="@fat">Logout</button>
      @else
        <a href="{{ custom_url('logout') }}"><button class="btn btn-danger my-2 my-sm-0" type="submit">Logout</button></a>
      @endif
    @endif
</nav>
