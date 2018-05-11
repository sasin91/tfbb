<form method="GET" @submit.prevent>
	<div class="form-group">
		<div class="input-group">
 			<input 
 				v-model="searchForm.query"
 			  	name="search"
 			  	type="search" 
 			  	class="form-control" 
 			  	placeholder="{{ __('Search for diets...') }}" 
 			  	aria-label="{{ __('Search for diets...') }}" 
 			  	aria-describedby="submit-search"
 			  	@keyup.esc="clearSearch"
			>
		</div>
	</div>
</form>