<template>
    <div class="bg-white w-full lg:w-5/6 mx-auto my-6 border-b rounded">
        <table class="table">
            <thead>
                <tr>
                    <th width="20%">Name</th>
                    <th width="15%">Domain</th>
                    <th width="25%">Description</th>
                    <th width="20%">Needed Supplies</th>
                    <th width="20%">Special Instructions</th>
                    <th></th>
                </tr>

                <tr is="row-new-activity"
                    :domains="sortedDomains"
                ></tr>
                
                <!-- @foreach ($activities as $activity) -->
                <tr is="row-edit-activity"
                    :domains="sortedDomains"
                    v-for="activity in sortedActivitiesByTwoFields"
                    :key="activity.id"
                    :activity="activity"
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
            'propActivities',
            'propDomains'
        ],
        data() {
            return {
                activities: this.propActivities,
                domains: this.propDomains
            }
        },

        computed: {
            // alphabetical order
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
            sortedActivitiesByTwoFields: function() {
                //https://stackoverflow.com/questions/9175268/javascript-sort-function-sort-by-first-then-by-second
                function cmp(a, b) {
                    if (a > b) return +1;
                    if (a < b) return -1;
                    return 0;
                }
                return this.activities.sort(function(a, b) {
                    return cmp(a.domain.name,b.domain.name) || cmp(a.name,b.name)
                });
            },
            sortedDomains: function() {
                function compare(a, b) {
                    if (a.name < b.name)
                        return -1;
                    if (a.name > b.name)
                        return 1;
                    return 0;
                }

                return this.domains.sort(compare);
            },
        },

        created() {
            bus.$on('addedNewActivity', (data) => {
                //console.log(data);
                this.activities.push(data);
            })

            //----------------
            
            // upon deletion of record within the RowEditActivity component,
            // splices record out of view here...
            bus.$on('deletedActivityRecord', (data) => {
                // loop thru the records object and delete the corresponding
                // object with the id of what record was deleted
                //console.log(data);
                const index = this.activities.findIndex(activity => activity.id === data);
                this.activities.splice(index, 1);
            });
        }
    }
</script>