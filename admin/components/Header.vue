<template lang="pug">
	div.container-fluid.header
		div.row.h-100.d-flex.flex-row.flex-wrap.align-items-stretch
			div.col-12.d-flex.flex-row.flex-wrap.align-items-center
				div.col
				b-dropdown(variant="light" no-caret)
					<template #button-content>
						div.user
							img.user_avatar(src="/img/avatar.png" alt="")
							span.username Olivier Isselé
					</template>
					b-dropdown-item(href="#") Configuration
					b-dropdown-divider
					b-dropdown-item(href="#") Se déconnecter
				</b-dropdown>
				
</template>

<style lang="scss" scoped>
	.header {
		border-bottom: 1px solid rgb(220, 220, 220);
		height: 62px;
	}
	.user {
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		align-items: center;
		//min-width: 250px;
		&_avatar {
			width: 42px;
			height: 42px;
			object-fit: cover;
			border-radius: 100%;
		}
		&name {
			padding-left: 0.8rem;
		}
	}
</style>