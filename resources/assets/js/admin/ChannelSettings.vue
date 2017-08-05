<script>
	export default {
		methods: {
			async getSettings(event) {
				const value = event.target.value
				const el = this.$refs.channelServiceSettings

				if (value && el) {
					el.innerHTML = '<div class="alert-box info">Loading settings...</div>'
				}
				const res = await this.$http.get(`/admin/channel/settings/${value}`, {
					responseType: 'text'
				})
				el.innerHTML = res.data
			}
		}
	}
</script>
