<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>

    <!-- CSS -->
    <link href="{{ mix(Spark::usesRightToLeftTheme() ? 'css/app-rtl.css' : 'css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @yield('scripts', '')

    <!-- Global Spark Object -->
    <script>
        window.Spark = <?php echo json_encode(array_merge(
            Spark::scriptVariables(), [
                'algolia' => [
                    'id' => config('services.algolia.app_id'),
                    'secret' => config('services.algolia.search')
                ]
            ]
        )); ?>;
    </script>
</head>
<body>
    <div id="spark-app" v-cloak>
        <!-- Navigation -->
        @if (Auth::check())
            @include('spark::nav.user')
        @else
            @include('spark::nav.guest')
        @endif

        <a href="{{ url('/boards') }}">
            <img 
                src="{{ asset('img/leehayward-members-header1.jpg') }}" 
                class="mt-2 mx-auto d-block img-fluid rounded" 
            />
        </a>
        <hr class="divider"></hr>

        <!-- Main Content -->
        <main class="py-4">
            @if(session('status'))
                <div class="alert alert-info" role="alert">
                  {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Application Level Modals -->
        @if (Auth::check())
            @include('spark::modals.notifications')
            @include('spark::modals.support')
            @include('spark::modals.session-expired')
            <photo-modal></photo-modal>
            <search-modal></search-modal>
        @endif
    </div>

    <!-- JavaScript -->
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="/js/sweetalert.min.js"></script>

    <script>
        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })    
    </script>
</body>
</html>
