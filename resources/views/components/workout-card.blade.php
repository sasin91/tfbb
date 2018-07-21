 <div class="card">
     <div class="card-header">
         <h4 class="card-title">
             {{ $workout->title  }}
             <span class="pull-right">
                 {{ $workout->type }}
             </span>
         </h4>

         <img class="img-fluid" src="{{ $workout->banner_url  }}"/>
     </div>

     <div class="card-body pl-2">
         <i class="fa fa-plus">{{ $workout->level  }}</i>
         <p class="card-text lead">{{ $workout->summary  }}</p>
     </div>

     <div class="card-footer">
        <a href="{{ $workout->link }}">
            <i class="fa fa-link"></i>
            {{ __('Go to workout') }}
        </a>

        @can('addLog', $workout)
            <!-- Modal? Use Vue + Transition? -->
        @endcan
     </div>
 </div>
