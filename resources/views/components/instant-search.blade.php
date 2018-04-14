<ais-index
  app-id="{{ config('services.algolia.id') }}"
  api-key="{{ config('services.algolia.secret') }}" 
  index-name="{{ $index }}"
>
  <div class="input-group">
  <ais-search-box>
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text" id="search-addon">
          <i class="fa fa-search"></i>
        </span>
      </div>
      <ais-input
        placeholder="Search {{ $index }}"
        :class-names="{'ais-input': 'form-control'}"
      />

      <span class="input-group-btn">
        <ais-clear :class-names="{'ais-clear': 'btn btn-default'}">
          <span aria-hidden="true">x</span>
        </ais-clear>
      </span>
    </div>
  </ais-search-box>

  </div>
  <ais-results>
    <template slot-scope="{ result }">
      <div class="list-group list-group-flush">
        <a :href="result.link" class="list-group-item list-group-item-action">
          <ais-highlight :result="result" attribute-name="name"></ais-highlight>
        </a>
      </div>
    </template>
  </ais-results>

  <ais-no-results/>

  <ais-powered-by />

  <ais-stats/>
</ais-index>