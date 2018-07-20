@extends('layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">{{__('Dashboard')}}</div>
                    <div class="card-body">
                        <p>{{ __("You're currently have :count enrollement(s)", ['count' => $enrollmentsCount]) }}
                        @includeIf($enrollmentsCount > 0, 'home.latest-enrollments', [
                            'workouts' => $workouts,
                            'diets' => $diets
                        ])

                        <current-workout-progress :user="user"></current-workout-progress>

                        <!-- Next workout suggestion? -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</home>
@endsection
