<template>
	<div v-show="spin">
		<i class="fa fa-spinner" :class="{ 'fa-spin': spin }"></i>
		<slot></slot>
	</div>
</template>

<script>
	export default {
		props: { 
			spin: Boolean,
		}
	}
</script>