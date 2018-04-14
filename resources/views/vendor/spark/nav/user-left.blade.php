<!-- Left Side Of Navbar -->
<li class="nav-item">
	<a @click="$bus.$emit('search-modal.toggle')" class="notification-pill mx-auto mb-3 mb-md-0 mr-md-0 ml-md-auto">
        <i class="fa fa-search"></i>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ url('boards') }}">{{__('Boards')}}</a>
</li>

<li class="nav-item">
	<a class="nav-link" href="{{ url('workouts') }}">{{__('Workouts')}}</a>	
</li>

<li class="nav-item">
	<a class="nav-link" href="{{ url('workout-of-the-month') }}">{{__('Workout of the Month')}}</a>
</li>

<li class="nav-item">
	<a class="nav-link" href="{{ url('profiles') }}">{{__('Members')}}</a>
</li>

<li class="nav-item">
	<a class="nav-link" href="{{ url('recordings') }}">{{__('Recordings')}}</a>
</li>