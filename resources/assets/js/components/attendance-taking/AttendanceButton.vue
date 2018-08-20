<template>
    <div class="flex justify-between" @click="toggle">
        <div>{{ resident.first_name }} {{ resident.last_name }}</div>
        <div class="h-4 w-4" v-show="this.resident.attendance_records.length"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg></div>
    </div>
</template>

<script>
    export default {
        props: [
            'resident',
            'activityDetails',
            'scheduledActivityId',
            //'attendanceData',
        ],
        data() {
            return {
                // attendance_records from Laravel backend
                // will either be 1 if already checked
                // in as attending and 0 if not
                //isCheckedIn: this.resident.attendance_records.length
                //isCheckedIn: this.attendanceRecordExists()
                // isCheckedIn: this.attendanceData
                //isCheckedIn: 1
            }
        },
        methods: {
            // attendanceRecordExists() {
            //     if ( this.resident.attendance_records && this.resident.attendance_records.length) {
            //         console.log('true');
            //         return true;
            //     } else {
            //         return false;
            //     }
            // },
            toggle() {
                if (this.resident.attendance_records.length) {
                // if (this.isCheckedIn) {
                    
                    //alert('has check mark and about to be unchecked');
                    
                    // pass to AttendanceRecordController:
                    // 1) resident id
                    // 2) activity id and date in order the let the backend get the 
                    //    scheduled activity id
                    //    that way either the resident model or scheduled activities model...
                    //    or even maybe the Attendance record model could set the row
                    //    -- may leave it as resident model since it already has the toggle method
                    // console.log('resident: ' + this.resident);
                    // console.log('date: ' + this.initDate);
                    // console.log('time: ' + this.initTime);
                    // console.log('activity id: ' + this.activityId);
                    axios.delete('/residents/' + this.resident.id + '/activities/' + this.scheduledActivityId);
                    // axios
                    //     .delete('/attendance-records/', {
                    //         // sent activity_id and datetime
                    //         // (must also specify "data: {}" for delete. whatever. glad we can google everything.)
                    //         data: {
                    //             resident: this.resident,
                    //             date: ,
                    //             activity_id: activity_id,
                    //         }
                    //     })
                    //     //.delete('/schedule-activities/' + id)
                    //     .then((response) => {
                    //         // success
                    //         //console.log(response);        
                    //     })
                    //     .catch((error) => {
                    //         console.log(error);
                    //     });
                    this.resident.attendance_records = [];
                    // this.isCheckedIn = false;
                } else {
                    axios.post('/residents/' + this.resident.id + '/activities/' + this.scheduledActivityId);
                    this.resident.attendance_records = [1];
                    // this.isCheckedIn = true;
                }
            },
            computed: {
                // attendanceRecordExists() {
                //     console.log('being called?');
                //     if (this.resident.attendance_records.length) {
                //         return true;
                //     } else {
                //         return false;
                //     }
                // }
            }
            // original
            //     if (this.isCheckedIn) {
            //         axios.delete('/residents/' + this.resident.id + '/activities/' + this.scheduledActivity.id);
            //         this.isCheckedIn = false;
            //     } else {
            //         axios.post('/residents/' + this.resident.id + '/activities/' + this.scheduledActivity.id);
            //         this.isCheckedIn = true;
            //     }
            // }
        },
    }
</script>
