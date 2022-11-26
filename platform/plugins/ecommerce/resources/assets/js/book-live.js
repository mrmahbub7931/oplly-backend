import BookLiveAvailabilityComponent from './components/BookLiveAvailability.vue'
import Vue from 'vue';
import VueMeetingSelector from 'vue-meeting-selector';

Vue.component('book-live', BookLiveAvailabilityComponent);
Vue.component('availability', VueMeetingSelector);

/**
 * This let us access the `__` method for localization in VueJS templates
 * ({{ __('key') }})
 */
Vue.prototype.__ = key => {
    return _.get(window.trans, key, key);
};

new Vue({
    el: '#book-live',
});
