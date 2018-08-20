<template>
    <div class="w-full lg:w-1/2 mb-4">
        <div class="bg-white sm:rounded py-4">
            <div class="px-4 pb-4 border-b">Attendance</div>
            <!-- <div class="p-4">...on our way...</div> -->
            <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Activity</th>
                    <th></th>
                </tr>

                <!-- <tr is="row-option-delete-individual-activity" 
                    v-for="(record, index) in records"
                    :key="record.id"
                    :prop-record="record"
                > -->
                <tr is="row-option-delete-individual-activity" 
                    v-for="(record, index) in sortedRecords"
                    :key="record.id"
                    :prop-record="record"
                >
                </tr>
                <!-- @endforeach -->
            </thead>
        </table>
        </div>
    </div>
</template>

<script>
    import { bus } from '../../app';

    export default {        
        props: [
            'propDate'
        ],

        data() {
            return { 
                date: this.propDate,
                records: [],
                // records: {
                //     resident: {
                //         first_name: ''
                //     }
                // }
            };
        },

        methods: {
            // methodEx(valueToPass) {
                 
            // }
        },

        computed: {
            // alphabetical order
            sortedRecords: function() {
                function compare(a, b) {
                    if (a.resident.first_name < b.resident.first_name)
                        return -1;
                    if (a.resident.first_name > b.resident.first_name)
                        return 1;
                    return 0;
                }

                // if (this.records.length) {
                //     console.log(this.records);
                    return this.records.sort(compare);
                // }
            }
        },

        created() {

            // initial setup of any attendance records for date
            // that already exist
            axios.post('/individual-activity-attendance', {
                date: this.date
            })
            .then((response) => {
                this.records = response.data;
            })
            .catch((error) => {
                console.log(error);
            });

            //----------------
            
            // upon changing the date within parent MainIndividualActivityAttendance component,
            // to go out and get the records from server again...
            bus.$on('changedDate', (updatedDate) => {
                axios.post('/individual-activity-attendance', {
                    date: updatedDate
                    // date: this.date
                })
                .then((response) => {
                    this.records = response.data;
                    // and make sure data { date } is updated
                    this.date = updatedDate;
                })
                .catch((error) => {
                    console.log(error);
                });
            });

            //----------------
            
            // upon adding new record within parent MainIndividualActivityAttendance component,
            // to go out and get the records from server again...
            bus.$on('addedIndividualAttendanceRecord', () => {
                axios.post('/individual-activity-attendance', {
                    date: this.date
                })
                .then((response) => {
                    this.records = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
            });
            
            //----------------
            
            // upon deletion of record within the RowOptionDeleteIndividualActivity component,
            // splices record out of view here...
            bus.$on('deletedIndividualAttendanceRecord', (data) => {
                // loop thru the records object and delete the corresponding
                // object with the id of what record was deleted
                const index = this.records.findIndex(record => record.id === data);
                this.records.splice(index, 1);
            });

        },
    }
</script>