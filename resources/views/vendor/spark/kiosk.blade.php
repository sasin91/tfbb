@extends('spark::layouts.app')

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.4.6/mousetrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
@endsection

@section('content')
<spark-kiosk :user="user" inline-template>
    <div class="spark-screen container">
        <div class="row">
            <!-- Tabs -->
            <div class="col-md-3 spark-settings-tabs">
                <aside>
                    <h3 class="nav-heading ">
                        {{__('Kiosk')}}
                    </h3>
                    <ul class="nav flex-column mb-4 ">
                        <li class="nav-item ">
                            <a class="nav-link" href="#announcements" aria-controls="announcements" role="tab" data-toggle="tab">
                                <i class="fa fa-users"></i>
                                {{__('Announcements')}}
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="#metrics" aria-controls="metrics" role="tab" data-toggle="tab">
                                <i class="fa fa-chart-line"></i>

                                {{__('Metrics')}}
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="#users" aria-controls="users" role="tab" data-toggle="tab">
                                <i class="fa fa-lock"></i>
                                {{__('Users')}}
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="#boards" aria-controls="boards" role="tab" data-toggle="tab">
                                <i class="fa fa-comment"></i>
                                {{__('Boards')}}
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="#workouts" aria-controls="workouts" role="tab" data-toggle="tab">
                                <i class="fa fa-comment"></i>
                                {{__('Workouts')}}
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="#recordings" aria-controls="recordings" role="tab" data-toggle="tab">
                                <i class="fas fa-video"></i>
                                {{__('Recordings')}}
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="#offers" aria-controls="offers" role="tab" data-toggle="tab">
                                <i class="fas fa-money-bill-alt"></i>
                                {{__('Offers')}}
                            </a>
                        </li>
                    </ul>
                </aside>
            </div>

            <!-- Tab cards -->
            <div class="col-md-9">
                <div class="tab-content">
                    <!-- Announcements -->
                    <div role="tabcard" class="tab-pane active" id="announcements">
                        @include('spark::kiosk.announcements')
                    </div>

                    <!-- Metrics -->
                    <div role="tabcard" class="tab-pane" id="metrics">
                        @include('spark::kiosk.metrics')
                    </div>

                    <!-- User Management -->
                    <div role="tabcard" class="tab-pane" id="users">
                        @include('spark::kiosk.users')
                    </div>

                    <!-- Board Management -->
                    <div role="tabcard" class="tab-pane" id="boards">
                        @include('kiosk.boards')
                    </div>

                    <!-- Workout Management -->
                    <div role="tabcard" class="tab-pane" id="workouts">
                        @include('kiosk.workouts')
                    </div>

                    <!-- Recording Management -->
                    <div role="tabcard" class="tab-pane" id="recordings">
                        @include('kiosk.recordings')
                    </div>

                    <!-- Offer Management -->
                    <div role="tabcard" class="tab-pane" id="offers">
                        @include('kiosk.offers')
                    </div>
                </div>
            </div>
        </div>
    </div>
</spark-kiosk>
@endsection
