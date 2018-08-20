<template>
    <div class="bg-white w-full lg:w-1/2 md:w-5/6 mx-auto my-6 border-b rounded">
        <table class="table">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th></th>
                </tr>

                <tr is="row-new-resident"></tr>
                
                <!-- @foreach ($residents as $resident) -->
                <tr is="row-edit-resident" 
                    v-for="resident in sortedResidents"
                    :key="resident.id"
                    :resident="resident"
                >
                </tr>
                <!-- @endforeach -->
            </thead>
        </table>
    </div>
</template>

<script>
    import { bus } from '../../app';

    export default {
        props: [
            'propResidents'
        ],
        data() {
            return {
                residents: this.propResidents
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
            bus.$on('addedNewResident', (data) => {
                this.residents.push(data);
            })

            //----------------
            
            // upon deletion of record within the RowEditActivity component,
            // splices record out of view here...
            bus.$on('softDeletedResidentRecord', (data) => {
                // loop thru the records object and delete the corresponding
                // object with the id of what record was deleted
                //console.log(data);
                const index = this.residents.findIndex(resident => resident.id === data);
                this.residents.splice(index, 1);
            });
        }
    }
</script>