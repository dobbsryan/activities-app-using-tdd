<template>
    <tr>
        <td>
            <div 
                class="my-2 ml-4"
            >
            {{firstName}} {{lastName}}
            </div>
        </td>
        <td>
            <div 
                class="my-4 ml-4"
            >
            {{activity}}
            </div>
        </td>
        <td>
            <div class="w-4 h-4 m-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    @click="deleteRecord"
                    class="cursor-pointer"
                >
                    <path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"/>
                </svg>
            </div>
        </td>
    </tr>
</template>

<script>
    import { bus } from '../../app';

    export default {        
        props: [
            'propRecord'
        ],

        data() {
            return {
               firstName: this.propRecord.resident.first_name,
               lastName: this.propRecord.resident.last_name,
               activity: this.propRecord.activity.name,

               //inputChanged: false
            };
        },

        methods: {
            deleteRecord() {
                // console.log(this.propRecord.id);
                // return;

                axios.delete('/individual-activity-attendance/' + this.propRecord.id)
                .then((response) => {
                    // success
                    // reset
                    // want to emit an event and clear this data from the props data
                    bus.$emit('deletedIndividualAttendanceRecord', this.propRecord.id);
                    
                    // this.inputChanged = false;
                })
                .catch((error) => {
                    console.log(error);
                    window.flash('There was a problem updating.');
                    //this.inputChanged = false;
                });
            },
        }
    }
</script>