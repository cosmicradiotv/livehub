<template>
	<div class="switcher">
		<div class="switcher-overlay" @click="close"></div>
		<div class="switcher-content">
			<h2 class="title is-2">{{ brand }}</h2>
			<div class="title is-3">
				{{ pluralizeShows(showsByState.live.length) }} live right now!
			</div>
			<show v-for="show in showsByState.live" :key="show.id" :show="show" @click.native="close" />
		</div>
		<button class="switcher-close is-large" aria-label="close" @click="close"></button>
	</div>
</template>

<script>
	import Show from './switcher/Show'

	export default {
		components: {
			Show
		},
		computed: {
			showsByState() {
				const res = {
					live: [],
					next: []
				}

				this.shows.forEach(show => {
					let isLive = false
					show.streams.forEach(stream => {
						if (stream.state === 'live') {
							isLive = true
						}
					})

					res[isLive ? 'live' : 'next'].push(show)
				})

				return res
			}
		},
		methods: {
			close() {
				this.$emit('close')
			},
			pluralizeShows(count) {
				switch (count) {
					case 0:
						return 'No shows are'
					case 1:
						return '1 show is'
					default:
						return count + ' shows are'
				}
			}
		},
		props: {
			brand: String,
			shows: Array
		}
	}
</script>
