<template>
    <div class="pt-2 pb-4 w-full">
        <div v-for="(location, index) in locations"
            class="py-2"
            :key="location.id"
            :data-id="location.id"
        >
            <div class="text-grey-darkest py-4 px-4 mb-2 bg-grey-lighter hover:bg-grey-light border-b rounded text-dark-muted cursor-pointer flex justify-between" @click="setLocation(location.id)">
                <div>{{ location.name }}</div>
                <div class="h-4 w-4" v-show="locationSelectedId === location.id"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg></div>
            </div>
       </div>
    </div>
</template>

<script>
    export default {
        props: [
            'propLocations',
            'propLocationId',
            'propLocationName',
       ],
        
        data() {
            return {
                locations: this.propLocations,
                locationId: this.propLocationId,
                locationName: this.propLocationName,
                
                locationSelectedId: this.propLocationId,
                sessionData: []
            }
        },

        methods: {
            setLocation(id) {
                // go ahead and set the value (checkmark)
                this.locationSelectedId = id;

                axios
                    .post('/home/' + id)
                    .then((response) => {
                        //console.log(response.data);
                        this.sessionData = response.data;
                    })
                    .catch((error) => {
                        // only unsetting the value (checkmark) if problem from server
                        this.locationSelectedId = '';
                        console.log(error);
                        window.flash('There was a problem setting the location.');
                    });
            }
        },

        computed: {
            
        }
    }
</script>