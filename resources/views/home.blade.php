@extends('layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container-fluid">
        <!-- Application Dashboard -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="well">
                        @includeWhen(
                        $enrollmentsCount > 0, 
                        'home.latest-workouts', 
                        [
                            'workouts' => $workouts,
                            // 'diets' => $diets
                        ])
                </div>
            </div>
        </div>
    </div>
</home>
@endsection
