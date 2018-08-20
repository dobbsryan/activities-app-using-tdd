<template>
    <div class="flex flex-col items-center my-6">
        <div class="w-full lg:w-1/2 p-4 mb-4 bg-white sm:rounded">
            <table class="w-full">
                <tr>
                    <td width="25%">Date</td>
                    <td width="65%">
                        <input type="date"
                               v-model="initDate"
                               class="p-1"
                               @change="updateRecords"
                        >
                    </td>
                    <td width="10%"></td>
                </tr>
                <tr>
                    <td>Resident</td>
                    <td>
                        <div class="inline-block relative w-full">
                            <select
                                class="appearance-none w-full h-full border-none pl-4 py-2 pr-8"
                                v-model="selectedResident"
                                @change="showAddButton"
                            >
                                <option disabled value="">Choose</option>
                                <option
                                    v-for="(resident, index) in sortedResidents"
                                    :value="resident.id"
                                >
                                    {{ resident.first_name }} {{ resident.last_name }}
                                </option>
                            </select>
                            <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Activity</td>
                    <td>
                        <div class="inline-block relative w-full mr-4">
                            <select
                                class="appearance-none w-full h-full border-none pl-4 py-2 pr-8"
                                v-model="selectedActivity"
                                @change="showAddButton"
                            >
                                <option disabled value="">Choose</option>
                                <option
                                    v-for="(activity, index) in sortedActivities"
                                    :value="activity.id"
                                >
                                    {{ activity.name }}
                                </option>
                            </select>
                            <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="w-6 h-6 m-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                @click="saveToDB"
                                class="cursor-pointer"
                                v-if="residentAndActivitySelected"
                            >
                                <path d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/>
                            </svg>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <individual-activity-attendance-records
            :prop-date="initDate"
        >
        </individual-activity-attendance-records>
        <!-- <div class="w-full lg:w-1/2 px-4 mb-4">
            <div class="bg-white rounded py-4">
                <div class="px-4 pb-4 border-b">Attended</div>
                <div class="p-4">...drop attended activities here...</div>
            </div>
        </div> -->
    </div>
</template>

<script>
    import { bus } from '../../app';

    export default {
        props: [
            'date',
            'propActivities',
            'propResidents',
       ],
        
        data() {
            return {
                initDate: this.date,
                activities: this.propActivities,
                residents: this.propResidents,

                selectedResident: '',
                selectedActivity: '',

                residentAndActivitySelected: false

                // initTimesAndScheduledActivities: this.mapOverTimesAndScheduledValuesFromBackend(this.timesAndScheduledActivities),
                // editable: true, // switch to turn on off editability
                // isDragging: false,
                // delayedDragging: false,
            }
        },

        methods: {
            showAddButton() {
                if (this.selectedResident && this.selectedActivity) {
                    this.residentAndActivitySelected = true;
                }
            },
            saveToDB() {
                // clearing the add button since it's not correct to create the same record twice anyway;
                // also, given their may be a lag, this will prevent multi-clicks;
                // and if it fails to save, oh well.
                this.residentAndActivitySelected = false;

                axios.post('/individual-activity-attendance/store', {
                    resident_id: this.selectedResident,
                    activity_id: this.selectedActivity,
                    date: this.initDate,
                })
                .then((response) => {
                    //console.log(response.data);
                    bus.$emit('addedIndividualAttendanceRecord');

                    // and going ahead and clearing these too
                    this.selectedResident = '';
                    this.selectedActivity = '';
                })
                .catch((response) => {
                    console.log(error);
                    window.flash('There was a problem saving.');
                });
            },
            updateRecords() {

                // "form" reset
                this.selectedResident = '';
                this.selectedActivity = '';
                this.residentAndActivitySelected = false;
                // probably an anti-patern but I think not using Vuex is anti-pattern;
                // so just trying get this to work;
                // the :prop-date=initDate is not updating in time for 
                // this event to get the updated date value from
                // within IndividualAttendanceRecords component
                // for rerender of attendance data based on
                // updated date ocurring in this component;
                // :. forcing it in here...
                bus.$emit('changedDate', this.initDate);

            }
        },

        computed: {
            // alphabetical order
            sortedResidents: function() {
                function compare(a, b) {
                    if (a.first_name < b.first_name)
                        return -1;
                    if (a.first_name > b.first_name)
                        return 1;
                    return 0;
                }

                return this.residents.sort(compare);
            },
            sortedActivities: function() {
                function compare(a, b) {
                    if (a.name < b.name)
                        return -1;
                    if (a.name > b.name)
                        return 1;
                    return 0;
                }

                return this.activities.sort(compare);
            },
        },

        // created() {
        //     alert(this.date);
        // }
    }
</script>
