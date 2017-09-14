<template>
	 <div class="content-wrapper">
		<div class="live-container iframe-container">
			<iframe id="live-frame" :src="videoURL" frameborder="0" allowfullscreen></iframe>
		</div>
		<div class="chat-container iframe-container">
			<iframe id="live-frame" :src="chatURL" frameborder="0"></iframe>
		</div>
	</div>
</template>

<script>
	export default {
		computed: {
			shows() {
				if (this.$parent.shows) {
					return this.$parent.shows
				}
				return []
			},
			matchedShow() {
				if (!this.$route.params.show) {
					return
				}
				return this.shows.find(show => show.slug === this.$route.params.show)
			},
			matchedStream() {
				if (!this.matchedShow) {
					return
				}
				if (this.$route.params.channel_id) {
					const stream = this.matchedShow.streams.find(stream => stream.channel_id === this.$route.params.channel_id)
					if (stream) {
						return stream
					}
				}
				return this.matchedShow.streams[0]
			},
			chatURL() {
				if (!this.matchedStream) {
					return 'about:blank'
				}
				return this.matchedStream.chat_url
			},
			videoURL() {
				if (!this.matchedStream) {
					return 'about:blank'
				}
				return this.matchedStream.video_url
			}
		}
	}
</script>
