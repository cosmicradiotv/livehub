<template>
	<div class="row">
		<div class="large-12 columns">
			<table class="small-12">
				<thead>
				<tr>
					<th class="small-4">Type</th>
					<th class="small-7">Rules</th>
					<th class="small-1"></th>
				</tr>
				</thead>
				<tbody>
					<rule v-for="(rule, i) in rules"
						:key="i"
						:value="rule"
						@remove="remove(i)" />
				</tbody>
				<tfoot>
					<tr>
						<th colspan="3">
							<a @click="add">+ Add Rule</a>
						</th>
					</tr>
				</tfoot>
			</table>
			<textarea name="rules" :value="stringified" class="hide"></textarea>
		</div>
	</div>
</template>

<script>
	import Rule from './Rule'

	export default {
		components: {
			Rule
		},
		computed: {
			stringified: {
				get() {
					return JSON.stringify(this.rules)
				},
				set(value) {
					this.rules = JSON.parse(value)
				}
			}
		},
		data() {
			return {
				rules: []
			}
		},
		created() {
			this.rules = this.value
		},
		methods: {
			add() {
				this.rules.push({
					type: null
				})
			},
			remove(i) {
				console.log('remove i')
				this.rules.splice(i, 1)
			}
		},
		props: {
			value: Array
		}
	}
</script>

