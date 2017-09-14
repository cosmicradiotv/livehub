<template>
	<div class="container" v-if="!loading">
		<div class="header">
			<nav-bar>
				<div class="navbar-item" slot="brand">{{ brand }}</div>
				<div class="navbar-start">
					<a class="navbar-item" @click="showSwitcher(true)">
						<span class="tag is-info">{{ liveStreamsCount }}</span>&nbsp;Streams Live
					</a>
				</div>
			</nav-bar>
		</div>
		<router-view />
		<switcher v-if="switcherOpen" :brand="brand" :shows="shows" @close="showSwitcher(false)" />
	</div>
	<div v-else class="loading-screen">
		<div class="loading-spinner"></div>
		<div class="loading-message">
			Loading Streams...
		</div>
	</div>
</template>

<script>
	import NavBar from './NavBar'
	import Switcher from './Switcher'

	export default {
		components: {
			NavBar,
			Switcher
		},
		computed: {
			liveStreamsCount() {
				return this.streams.filter(stream => stream.state === 'live').length;
			},
			shows() {
				const result = []
				const shows = {}

				this.streams.forEach(stream => {
					const show_id = stream.show_id
					if (!shows[show_id]) {
						const show_data = stream.show.data
						shows[show_id] = Object.assign({ streams: [] }, show_data)
						result.push(shows[show_id])
					}
					const show = shows[show_id]

					show.streams.push(stream)
				})

				return result
			}
		},
		data() {
			return {
				brand: 'Live',
				loading: true,
				streams: [],
				switcherOpen: true
			}
		},
		created() {
			this.getConfig()
		},
		methods: {
			async getConfig() {
				this.loading = true
				const res = await this.$http.get('/live/config')

				const config = res.data
				this.brand = config.brand
				this.streams = config.streams.data
				this.loading = false
			},
			showSwitcher(value) {
				this.switcherOpen = value
			}
		}
	}
</script>
