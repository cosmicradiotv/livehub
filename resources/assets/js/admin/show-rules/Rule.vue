<template>
	<tr>
		<td>
			<select v-model="value.type" class="no-margin">
				<option selected disabled :value="null">Select a rule</option>
				<option value="title">Title Regex Matches</option>
				<option value="startBetween">Start Time Between</option>
			</select>
		</td>
		<component :is="ruleComponent" :rule="value"></component>
		<td>
			<button class="button alert small no-margin" @click="$emit('remove')" type="button">x</button>
		</td>
	</tr>
</template>

<script>
	import RuleStartBetween from './rules/StartBetween'
	import RuleTitle from './rules/Title'

	const defaultValues = {
		null() {
			return {}
		},
		startBetween: RuleStartBetween.defaults,
		title: RuleTitle.defaults
	}

	export default {
		components: {
			RuleNull: {
				render(h) {
					return h('td', 'No settings available')
				},
				defaults() {
					return {}
				},
				props: {
					rule: Object
				}
			},
			RuleStartBetween,
			RuleTitle
		},
		computed: {
			ruleComponent() {
				return 'rule-' + this.value.type;
			}
		},
		props: {
			value: Object
		},
		watch: {
			'value.type'(type) {
				// remove old values
				Object.keys(this.value).forEach(key => {
					if (key !== 'type') {
						this.$delete(this.value, key)
					}
				})
				// add new
				const defaults = defaultValues[type]()
				Object.keys(defaults).forEach(key => {
					if (key !== 'type') {
						this.$set(this.value, key, defaults[key])
					}
				})
			}
		}
	}
</script>
