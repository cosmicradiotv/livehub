<template>
	<td>
		<input type="text" v-model="rule.rule" class="no-margin" required />
	</td>
</template>


<script>
	export default {
		defaults() {
			return {
				rule: ''
			};
		},
		props: {
			rule: Object
		}
	}
</script>
