
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.events = new Vue();
// for flash messaging to be able to say something like:
// flash('my new message');
window.flash = function (message) {
    window.events.$emit('flash', message);
};

// window.Vue.prototype.myStore = {
//     showLeftNavMenu: false
// };

//window.Event = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('flash', require('./components/Flash.vue'));

Vue.component('left-nav-menu', require('./components/LeftNavMenu.vue'));
// Vue.component('nav-menu-icon', require('./components/NavMenuIcon.vue'));

Vue.component('main-activities-table', require('./components/activities/MainActivitiesTable.vue'));
Vue.component('row-new-activity', require('./components/activities/RowNewActivity.vue'));
Vue.component('row-edit-activity', require('./components/activities/RowEditActivity.vue'));

Vue.component('main-domains-table', require('./components/domains/MainDomainsTable.vue'));
Vue.component('row-new-domain', require('./components/domains/RowNewDomain.vue'));
Vue.component('row-edit-domain', require('./components/domains/RowEditDomain.vue'));

Vue.component('locations-wrapper', require('./components/locations/LocationsWrapper.vue'));

Vue.component('main-individual-activity-attendance', require('./components/individual-activity-attendance/MainIndividualActivityAttendance.vue'));
Vue.component('individual-activity-attendance-records', require('./components/individual-activity-attendance/IndividualAttendanceRecords.vue'))
Vue.component('row-option-delete-individual-activity', require('./components/individual-activity-attendance/RowOptionDeleteIndividualActivity.vue'))

Vue.component('main-residents-table', require('./components/residents/MainResidentsTable.vue'));
Vue.component('row-new-resident', require('./components/residents/RowNewResident.vue'));
Vue.component('row-edit-resident', require('./components/residents/RowEditResident.vue'));

Vue.component('schedule-and-activities-list', require('./components/schedule-activities-draggable/ScheduleAndActivitiesList.vue'));

Vue.component('scheduled-activities-and-residents-list', require('./components/attendance-taking/ScheduledActivitiesAndResidentsList.vue'));
Vue.component('attendance-button', require('./components/attendance-taking/AttendanceButton.vue'));

Vue.component('reports-wrapper', require('./components/reports/ReportsWrapper.vue'));

export const bus = new Vue();

const app = new Vue({
    el: '#app'
});