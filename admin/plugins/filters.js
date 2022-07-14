import Vue from 'vue'

Vue.filter('limit', (str, limit = 150) => {
	if(str != null && str.length > limit) {
		return str.slice(0, limit) + '...'
	}
	return str;
})