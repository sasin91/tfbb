<h2 class="text-center">{{ __('Latest workouts') }}</h2>

@foreach ($workouts as $enrollment)
    @include ('components.progress-bar', ['progress' => $enrollment->progress])
    
    @include ('components.workout-card', ['workout' => $enrollment->enrollable])
@endforeach
