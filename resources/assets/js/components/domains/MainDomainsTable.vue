<template>
    <div class="bg-white w-full lg:w-1/2 md:w-5/6 mx-auto my-6 border-b rounded">
    <table class="table">
        <thead>
            <tr>
                <th>Domain Name</th>
                <th></th>
            </tr>

            <tr is="row-new-domain"></tr>
            
            <!-- @foreach ($domains as $domain) -->
            <tr is="row-edit-domain" 
                v-for="domain in sortedDomains"
                :key="domain.id"
                :domain="domain"
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
            'propDomains'
        ],
        data() {
            return {
                domains: this.propDomains
            }
        },
        computed: {
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
            bus.$on('addedNewDomain', (data) => {
                this.domains.push(data);
            })

            //----------------
            
            // upon deletion of record within the RowEditDomain component,
            // splices record out of view here...
            bus.$on('deletedDomainRecord', (data) => {
                // loop thru the records object and delete the corresponding
                // object with the id of what record was deleted
                console.log(data);
                const index = this.domains.findIndex(domain => domain.id === data);
                this.domains.splice(index, 1);
            });
        }
    }
</script>