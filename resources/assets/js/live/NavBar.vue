<template>
	<nav class="navbar is-primary" role="navigation" aria-label="main navigation">
		<div class="navbar-brand">
			<slot name="brand"></slot>
			<div class="navbar-burger burger" @click="isOpen = !isOpen">
				<span></span>
				<span></span>
				<span></span>
			</div>
		</div>
		<div class="navbar-menu" :class="{'is-active': isOpen}">
			<slot></slot>
		</div>
	</nav>
</template>

<script>
	export default {
		data() {
			return {
				isOpen: false
			}
		}
	}
</script>

