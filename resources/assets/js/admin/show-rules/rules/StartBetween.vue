<template>
	<td>
		<div class="checklist">
			Day:
			<label title="Monday"><input type="checkbox" v-model="rule.days" :value="1"/>Mon</label>
			<label title="Tueday"><input type="checkbox" v-model="rule.days" :value="2"/>Tue</label>
			<label title="Wednesday"><input type="checkbox" v-model="rule.days" :value="3"/>Wed</label>
			<label title="Thursday"><input type="checkbox" v-model="rule.days" :value="4"/>Thu</label>
			<label title="Friday"><input type="checkbox" v-model="rule.days" :value="5"/>Fri</label>
			<label title="Saturday"><input type="checkbox" v-model="rule.days" :value="6"/>Sat</label>
			<label title="Sunday"><input type="checkbox" v-model="rule.days" :value="0"/>Sun</label>
		</div>
		<div class="row collapse">
			<div class="small-5 columns">
				<input type="time"
					v-model="rule.start"
					class="small-12"
					placeholder="HH:MM(:SS)"
					pattern="^([01][0-9]|2[0-3]):([0-5][0-9])(?::([0-5][0-9]))?$"
					required />
			</div>
			<div class="small-2 columns text-center">
				and
			</div>
			<div class="small-5 columns">
				<input type="time"
					v-model="rule.end"
					class="small-12"
					placeholder="HH:MM(:SS)"
					pattern="^([01][0-9]|2[0-3]):([0-5][0-9])(?::([0-5][0-9]))?$"
					required />
			</div>
		</div>
	</td>
</template>


<script>
	export default {
		computed: {

		},
		defaults() {
			return {
				days: [],
				start: '',
				end: ''
			};
		},
		props: {
			rule: Object
		}
	}
</script>
