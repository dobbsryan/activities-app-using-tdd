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
                       @change="updateDateForSchedulingActivities"
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
                        <draggable
                            :element="'div'"
                            :list="initTimesAndScheduledActivities"
                            :options="forScheduledList"
                            @change="sortActivities"
                        >
                            <div v-for="(timeAndScheduledActivity, index) in initTimesAndScheduledActivities"
                                class="py-2 px-4 h-8 \\bg-green flex items-center justify-between"
                                :class="timeSlotIsFilled(timeAndScheduledActivity)"
                                :key="index"
                            >
                                <div class="cursor-pointer">
                                    {{ timeAndScheduledActivity.name }}
                                </div>
                                <div
                                    class="w-4 h-4 m-2" 
                                    v-show="timeAndScheduledActivity.name"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        @click="deleteActivity(timeAndScheduledActivity.activity_id, index)"
                                        class="cursor-pointer">
                                        <path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"/>
                                    </svg>
                                </div>
                            </div>
                        </draggable>
                    </div>
                </div>
              </div>  
        </div>
        <div class="w-1/2 px-4">
            <div class="bg-white rounded pt-4">
                <div class="border-b-2 px-4 pb-4">Activities</div>
                    <div class="pt-2 pb-4">
                        <div v-for="(activity, index) in initActivities"
                            class="py-2 pl-2 pr-4 flex items-center"
                            :key="activity.id"
                            :data-id="activity.id"
                        >
                            <div class="w-6 h-6 m-2">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    @click="addActivity(activity)"
                                    class="cursor-pointer">
                                    <path d="M7.05 9.293L6.343 10 12 15.657l1.414-1.414L9.172 10l4.242-4.243L12 4.343z"/>
                                </svg>
                            </div>
                            <div class="ml-2">
                                {{ activity.name }} 
                            </div>
                       </div>
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
    import draggable from 'vuedraggable'

    export default {
        components: {
            draggable
        },

        props: [
            'date',
            'activities',
            'timesAndScheduledActivities',
       ],
        
        data() {
            return {
                initDate: this.date,
                initActivities: this.activities,
                initTimesAndScheduledActivities: this.mapOverTimesAndScheduledValuesFromBackend(this.timesAndScheduledActivities),
                editable: true, // switch to turn on off editability
                isDragging: false,
                delayedDragging: false,
            }
        },

        methods: {
            addActivity(activity) {

                // passing thru two key pieces of data from the main activities list;
                // the rest should be able to be extrapolated from other variables
                // (be careful as you streamline and update how data feeds in
                // that you still get the expected values in those cases)

                // console.log(activity.id);
                // console.log(activity.name);

                //console.log(this.initTimesAndScheduledActivities);
                
                for (const index in this.initTimesAndScheduledActivities) {
                    if (this.initTimesAndScheduledActivities[index].name === '') {
                        
                        // set the values for display
                        this.initTimesAndScheduledActivities[index].date = this.initDate;
                        this.initTimesAndScheduledActivities[index].fixed = true;
                        this.initTimesAndScheduledActivities[index].activity_id = activity.id;
                        this.initTimesAndScheduledActivities[index].name = activity.name;
                        this.initTimesAndScheduledActivities[index].time = this.timesAndScheduledActivities[index].time;

                        // axois here or in another loop?
                        axios
                            .post('/schedule-activities/' + this.initDate, {
                                // sent activity_id and datetime
                                date: this.initDate,
                                time: this.initTimesAndScheduledActivities[index].time,
                                activity_id: activity.id
                            })
                            .then((response) => {
                                // success message
                                //console.log(response);        
                            })
                            .catch((error) => {
                                console.log(error);
                            });

                        break;                    
                    }
                }

                // and if all time slots already have names, the nothing will happen
                // that is fine for now; may want to make it clearer to user in
                // future by maybe deactivating the add button or something

            },
            deleteActivity(activity_id, index) {
                // set values for display
                // wiping all but time
                this.initTimesAndScheduledActivities[index].date = undefined;
                this.initTimesAndScheduledActivities[index].fixed = false;
                this.initTimesAndScheduledActivities[index].activity_id = undefined;
                this.initTimesAndScheduledActivities[index].name = '';
                this.initTimesAndScheduledActivities[index].time = this.timesAndScheduledActivities[index].time;

                // axios
                // destroy record
                axios
                    .delete('/schedule-activities/' + this.initDate, {
                        // sent activity_id and datetime
                        // (must also specify "data: {}" for delete. whatever. glad we can google everything.)
                        data: {
                            date: this.initDate,
                            time: this.initTimesAndScheduledActivities[index].time,
                            activity_id: activity_id,
                        }
                    })
                    //.delete('/schedule-activities/' + id)
                    .then((response) => {
                        // success
                        //console.log(response);        
                    })
                    .catch((error) => {
                        console.log(error);
                    });

                // console.log(id);
            },
            sortActivities() {
                // reset the times to make sure they're associated with
                // the correct activity after change made to list
                this.initTimesAndScheduledActivities.map((list, index) => {
                    list.time = this.timesAndScheduledActivities[index].time;
                })

                // update all the scheduled activities date
                axios
                    .patch('/schedule-activities/' + this.initDate, {
                        sortedScheduleActivities: this.initTimesAndScheduledActivities
                    })
                    .then((response) => {
                        // success message
                        console.log(response);        
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            mapOverTimesAndScheduledValuesFromBackend(arr) {
                return arr.map( (list,index) => {
                    return {
                        name: list.activity,
                        time: list.time,
                        activity_id: list.activity_id,
                        date: list.date,
                        fixed: list.activity !== '' ? true : false,
                    };
                });
            },
            updateDateForSchedulingActivities() {
                
                axios
                    .get('/schedule-activities-already-scheduled-list/' + this.initDate)
                    .then((response) => {

                        //console.log(response);        
                        let myArray = response.data.timesAndScheduledActivities;
                        //console.log(myArray);
                        this.initTimesAndScheduledActivities = this.mapOverTimesAndScheduledValuesFromBackend(myArray);

                        // success message
                    });

            },
            // onAdd(event) {
            //     let id = event.item.getAttribute('data-id');
            // },
            // onMove ({relatedContext, draggedContext}) {
            //     const relatedElement = relatedContext.element;
            //     const draggedElement = draggedContext.element;
            //     return (!relatedElement || !relatedElement.fixed);
            // },
            timeSlotIsFilled: function(activity) {
                let classToPass = activity.name ? 'disabled' : '';
                return '';
            },
        },

        computed: {
            forScheduledList() {
                return {
                    name:'activity',
                    animation:200,
                    sort:true,
                    disabled: !this.editable,
                    //filter: '.disabled',
                }
            },
        },
    }
</script>
