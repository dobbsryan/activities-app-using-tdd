<template>
    <div class="flex flex-col items-center mt-4 print-area">
        <div class="w-full p-4 mb-4 bg-white sm:rounded print-area">
            <div class="flex justify-between">
                <div class="flex items-center
                            pr-padding">
                    <div class="inline-block relative w-full print:font-medium">Resident's Name:</div>
                    <div class="inline-block relative w-full print:font-medium">
                        <select
                            class="appearance-none w-full h-full border-none pl-4 py-2 pr-8"
                            v-model="selectedResident"
                            @change="getRecords"
                        >
                            <option disabled value="">Choose</option>
                            <option
                                v-for="(resident, index) in sortedResidents"
                                :value="resident.id"
                            >
                                {{ resident.first_name }} {{ resident.last_name }}
                            </option>
                        </select>
                        <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker print:hidden">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="inline-block relative w-full print:hidden">Month:</div>
                    <input type="month"
                           v-model="initDate" 
                           class="ml-4 print:font-medium"
                           @change="getRecords"
                    >
                </div>
            </div>
        </div>
        <table
            v-show="attendanceRecords.group.length"
            class="table-standard bg-white sm:rounded mb-4 print-area"
        >
            <thead>
                <tr>
                    <th width="30%" class="pr-padding-title-row">Group</th>
                    <th width="70%"></th>
                </tr>
                <tr>
                    <th width="30%" class="pr-padding-header-row">Engagement Experience</th>
                    <th width="70%" class="pr-padding-header-row">Activity</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-if="attendanceRecords.group.length"
                    v-for="(record, index) in attendanceRecords.group"
                >
                    <td class="pr-padding">{{ record.activity.domain.name }}</td>
                    <td class="pr-padding">{{ record.activity.name }}</td>
                </tr>
            </tbody>
        </table>
        <table
            v-show="attendanceRecords.individual.length"
            class="table-standard bg-white sm:rounded mb-4"
        >
            <thead>
                <tr>
                    <th width="30%" class="pr-padding-title-row">Individual</th>
                    <th width="70%"></th>
                </tr>
                <tr>
                    <th width="30%" class="pr-padding-header-row">Engagement Experience</th>
                    <th width="70%" class="pr-padding-header-row">Activity</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-if="attendanceRecords.individual.length"
                    v-for="(record, index) in attendanceRecords.individual"
                >
                    <td class="pr-padding">{{ record.activity.domain.name }}</td>
                    <td class="pr-padding">{{ record.activity.name }}</td>
                </tr>
            </tbody>
        </table>
        <table class="table bg-white sm:rounded">
            <thead>
                <tr>
                    <th width="100%">
                        <div class="flex items-center justify-between">
                            <div  class="pr-padding-title-row">Comments</div>
                            <div class="w-6 h-6">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    @click="saveToDB"
                                    class="cursor-pointer"
                                    v-if="textareaChanged"
                                >
                                    <path d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/>
                                </svg>
                            </div>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pr-padding">
                        <textarea
                            v-model="attendanceRecords.comment"
                            @keyup="showAddButton"
                            rows="5"
                        ></textarea>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</template>

<script>
    export default {        
        props: [
            'date',
            'propResidents',
        ],

        data() {
            return {
                initDate: this.date,
                residents: this.propResidents,

                selectedResident: '',

                attendanceRecords: {
                    group: '',
                    individual: '',
                    comment: '',
                },  

                textareaChanged: false,              
            };
        },

        methods: {
            getRecords() {

                if (this.selectedResident && this.initDate) {
                    
                    //alert(this.initDate);
                    //alert(this.selectedResident);
                    
                    // starting by getting the group attendance records
                    axios.post('/reports', {
                        date: this.initDate,
                        resident_id: this.selectedResident,
                        // date: this.date
                    })
                    .then((response) => {
                        this.attendanceRecords = response.data;
                        this.textareaChanged = false;
                    })
                    .catch((error) => {
                        console.log(error);
                    });

                }   
            },
            saveToDB() {

                // note button should only have been able to be 
                // clicked because comment had existed;
                // backend set to handle the three
                // scenarios; using post for all
                
                axios.post('/reports/comment', {
                    date: this.initDate, 
                    resident_id: this.selectedResident,
                    comment: this.attendanceRecords.comment
                })
                .then((response) => {
                    // window.flash('You rock! Saved.');
                })
                .catch((error) => {
                    console.log(error);
                    window.flash('There was a problem saving.');
                    this.inputChanged = false;
                });

                // hiding button regardless of response
                // prevents multiple clicks at a minimum
                this.textareaChanged = false;

                // // what to do about button
                // // if has text
                // if (this.attendanceRecords.comment !== '') {

                //     // create or update comment in database
                
                // // if empty (and note that should only have been clicked on if text existed)
                // } else {

                //     // delete comment from database
                    
                // }
            },
            showAddButton(e) {
                // any key except tab, at this point, can trigger an input change
                if (e.code !== 'Tab') {
                    this.textareaChanged = true;
                }
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
        },

        created() {
                   
        },
    }
</script>

<style scoped>
    @media print {
        @page {
            /*height: 100%;*/
            /*margin: 0;*/
            /*size: 4in 6in landscape;*/
        }
        /*.container {
            width: 100% !important;
        }*/
        /*.print-area {
            width: 100% !important;
            background-color: yellow !important;
        }*/
        /*body {
            margin: 1cm;
        }*/
        .pr-padding {
            padding: 2mm !important;
        }
        .pr-padding-title-row {
            padding: 10mm 2mm 4mm !important;
        }
        .pr-padding-header-row {
            padding: 4mm 2mm !important;
        }
        /*.pr-l-col {
            width: 30%;
        }
        .pr-r-col {
            width: 70%;
        }*/
        /*.pr-font-size {
            font-size: smaller;
        }*/
    }
</style>