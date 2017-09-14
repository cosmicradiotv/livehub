<template>
	<router-link class="show" :to="{ name: 'live', params: {show: show.slug}}">
		<h4 class="title is-4">{{ show.name }}</h4>
	</router-link>
</template>

<script>
	export default {
		props: {
			show: Object
		}
	}
</script>
