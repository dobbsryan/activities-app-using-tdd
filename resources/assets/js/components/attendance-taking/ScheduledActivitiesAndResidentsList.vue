<template>
    <div class="flex my-6">
        <div class="w-1/2 px-4">
            <div class="flex items-center bg-white rounded p-4 mb-4">
                <div class="mr-4">
                    Date
                </div>
                <!-- <input type="date" v-model="initDate"> -->
                <input type="date"
                       v-model="initDate"
                       @change="changeDateForSchedulingActivitiesAndRetrieveAttendanceRecords"
                       class="p-1"
                >
            </div>
            <div class="bg-white rounded pt-4">
                <div class="flex border-b-2">                
                    <div class="px-4 pb-4">Time</div>
                    <div class="pl-6 pr-4 pb-4">Activity</div>
                </div>
                <div class="flex py-2">
                    <div>
                        <div class="py-2 px-4 h-8 \\bg-red text-right" v-for="(immutableProp, index) in timesAndScheduledActivities">
                            {{ immutableProp.time }}
                        </div>
                    </div>
                    <div class="w-full">
                        <div v-for="(timeAndScheduledActivity, index) in initTimesAndScheduledActivities"
                            class="py-2 px-4 h-8 mr-4"
                            :class="{'bg-grey-lighter rounded-sm border-b cursor-pointer': selectedActivityDetails.activity_id === timeAndScheduledActivity.activity_id}"
                            :key="index"
                            @click="changeActiveActivityToNewlySelectedAndRetrieveAttendanceRecords(timeAndScheduledActivity.activity_id, index)"
                        >
                            <div class="w-full cursor-pointer">
                                {{ timeAndScheduledActivity.activity }}
                            </div>
                            <!-- <div
                                class="w-4 h-4 m-2"
                                v-show="selectedActivityDetails.activity_id === timeAndScheduledActivity.activity_id"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    class="cursor-pointer">
                                    <path d="M.2 10a11 11 0 0 1 19.6 0A11 11 0 0 1 .2 10zm9.8 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/>
                                </svg>
                            </div> -->
                        </div>
                    </div>
                </div>
              </div>  
        </div>
        <div class="w-1/2 px-4">
            <div class="bg-white rounded pt-4">
                <div class="border-b-2 px-4 pb-4">{{ selectedActivityDetails.activity }}</div>
                <!-- <div class="border-b-2 px-4 pb-4">Residents</div> -->
                    <div class="pt-2 pb-4">
                        <div v-for="(resident, index) in initResidents"
                            class="py-2 px-4"
                            :key="resident.id"
                            :data-id="resident.id"
                        >
                            <attendance-button
                                class="text-grey-darkest py-4 px-4 mb-2 bg-grey-lighter hover:bg-grey-light border-b rounded text-dark-muted cursor-pointer"
                                :resident="resident"
                                :activityDetails="selectedActivityDetails"
                                :scheduledActivityId="scheduledActivityId"
                            ></attendance-button>
                            <!-- <div>
                                {{ activity.activity }} 
                            </div> -->
                       </div>
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            // primary/default build on page load
            'date',
            'timesAndScheduledActivities',
            // secondary; loads with axios upon created() below
            'residents',
       ],
        
        data() {
            return {
                initDate: this.date,
                initResidents: this.residents,
                initTimesAndScheduledActivities: this.timesAndScheduledActivities,

                selectedActivityDetails: [],
                isActive: true,

                scheduledActivityId: '',
            }
        },

        methods: {
            changeActiveActivityToNewlySelectedAndRetrieveAttendanceRecords(activity_id, index) {

                //console.log(activity_id, index);
                //console.log(this.selectedActivityDetails);
                //console.log(this.selectedActivityDetails.activity);

                // get list of all times and activities
                const allActivitiesData = this.initTimesAndScheduledActivities; // right?
                // const allActivitiesData = this.timesAndScheduledActivities; // and should not be using this, correct?

                //console.log(allActivitiesData[index]);
                const newlySelectedRow = allActivitiesData[index];
                //console.log(newlySelectedRow);

                // only if selected row has an activity and is not empty
                if ( newlySelectedRow.activity !== '' ) {

                    // get the attendance records for that activiey
                    this.retrieveAttendanceRecords(newlySelectedRow);

                    // go ahead and update the highlighted row and
                    // related current values
                    this.selectedActivityDetails = newlySelectedRow;
                    
                }
            },
            changeDateForSchedulingActivitiesAndRetrieveAttendanceRecords() {

                axios
                    .get('/schedule-activities-already-scheduled-list/' + this.initDate)
                    .then((response) => {

                        let fromBackend = response.data.timesAndScheduledActivities;
                        
                        // // apples to apples front and backend data comparison
                        //console.log(fromBackend);
                        // console.log(this.initTimesAndScheduledActivities);
                        
                        // update frontend array with all values for updated date from backend
                        this.initTimesAndScheduledActivities = fromBackend;
 
                        // set the first scheduled activity as active
                        //this.setFirstActivityScheduledToActive(fromBackend);

                        // reset the index for first activity highlighted
                        //this.updateFirstActivityAutoSelectedOnChange();


                        const allActivitiesData = this.initTimesAndScheduledActivities;
                        //console.log(allActivitiesData);
                        for (let i = 0; i < allActivitiesData.length; i++) {
                            // looking for first scheduled activity in sequential order of array
                            if (allActivitiesData[i].activity_id) {

                                this.retrieveAttendanceRecords(allActivitiesData[i]);

                                return this.selectedActivityDetails = allActivitiesData[i];
                            }
                        }
                        // if no schedule activities exist for date
                        // wipe view related values
                        //console.log('residents: ', this.initResidents);
                        for (let i = 0; i < this.initResidents.length; i++) {
                            // for each, wipe the attendanceRecords
                            this.initResidents[i].attendance_records = [];
                        }
                        // and wipe the previously selected activity details
                        // console.log(this.selectedActivityDetails);
                        return this.selectedActivityDetails = []; // if you want to return; currently nothing following

                        // flash success message ?
                    })
                    .catch((error) => {
                        console.log(error);
                        //alert(error);
                    });

            },
            setFirstActivityScheduledToActive(fromBackend) {
                const allActivitiesData = fromBackend;
                // const allActivitiesData = this.timesAndScheduledActivities;
                for (let i = 0; i < allActivitiesData.length; i++) {
                    // looking for first scheduled activity in sequential order of array
                    if (allActivitiesData[i].activity_id) {
                        //console.log(allActivitiesData[i]);
                        return this.selectedActivityDetails = allActivitiesData[i];
                        //break;
                    }
                }
                // if no schedule activities exist for date
                return this.selectedActivityDetails = [];
            },
            retrieveAttendanceRecords(activeActivityDetails) {
                axios
                    .post('/residents-and-attendance-records', {
                        activeActivityDetails: activeActivityDetails
                    })
                    .then((response) => {

                        //console.log(response.data);

                        // replace residents with updated resident list
                        // that includes attendance records
                        this.initResidents = response.data.residents;
                        this.scheduledActivityId = response.data.scheduledActivityId;

                        // success message
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        created() {

            // when component first loads, we're going to set the active_id on the
            // selectedActivityDetails.activity_id property; it's referenced by 
            // the attendance button;
            // we're going to default to the first scheduled activity
            // for when the component loads or reloads;
            // hoping it works on reload!
            
            const allActivitiesData = this.timesAndScheduledActivities;
            //console.log(allActivitiesData);
            for (let i = 0; i < allActivitiesData.length; i++) {
                // looking for first scheduled activity in sequential order of array;
                // that's the one we're going to set at the default active activity
                // for first loading the page
                if (allActivitiesData[i].activity_id) {

                    this.retrieveAttendanceRecords(allActivitiesData[i]);

                    return this.selectedActivityDetails = allActivitiesData[i];
                }
            }
            // if no schedule activities exist for date
            return this.selectedActivityDetails = []; // shouldn't have to return here

        }
    }
</script>
