 <div class="card">
     <div class="card-header">
         <h4 class="card-title">
             <a href="{{ $workout->link  }}">{{ $workout->title  }}</a>
             <span class="pull-right">
                 <i class="fa fa-barbell">{{ $workout->type  }}</i>
             </span>
         </h4>

         <img class="img-responsive" src="{{ $workout->banner_url  }}"/>
     </div>

     <div class="card-body pl-2">
         <i class="fa fa-plus">{{ $workout->level  }}</i>
         <p class="card-text lead">{{ $workout->summary  }}</p>
     </div>
 </div>
