<template>
    <tr>
        <td>
            <textarea
                placeholder="Add activity..."
                v-model="name"
                @keyup="showAddButton"
            ></textarea>
        </td>
        <td>
            <div class="inline-block relative w-full">
                <select
                    class="appearance-none w-full h-full border-none pl-4 py-2 pr-8"
                    v-model="selected"
                    @change="showAddButton"
                >
                    <option disabled value="">Choose</option>
                    <option
                        v-for="(domain, index) in domains"
                        :value="domain.id"
                    >
                        {{domain.name}}
                    </option>
                </select>
                <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
<!--             <textarea 
                v-model="domain"
                @keyup="showAddButton"
            ></textarea> -->
        </td>
        <td>
            <textarea
                v-model="description"
                @keyup="showAddButton"
            ></textarea>
        </td>
        <td>
            <textarea
                v-model="supplies"
                @keyup="showAddButton"
            ></textarea>
        </td>
        <td>
            <textarea
                v-model="instructions"
                @keyup="showAddButton"
            ></textarea>
        </td>
        <td>
            <div class="w-6 h-6 m-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    @click="saveToDB"
                    class="cursor-pointer"
                    v-if="inputChanged"
                >
                    <path d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/>
                </svg>
            </div>
        </td>
    </tr>
</template>

<script>
    import { bus } from '../../app';

    export default {        
        props: [
            'domains'
        ],

        data() {
            return {
               name: '',
               domain: '',
               description: '',
               supplies: '',
               instructions: '',

               selected: '',

               inputChanged: false
            }
        },

        methods: {
            showAddButton(e) {
                // any key except tab, at this point, can trigger an input change
                if (e.code !== 'Tab') {
                    this.inputChanged = true;
                }
            },
            saveToDB() {
                // make sure name column isn't blank; other cols can be
                if (this.name === '') {
                    return window.flash('Name field cannot be blank');
                }
                // make sure a domain is selected
                if ( !this.selected ) { return window.flash('Please select a domain for the activity'); }
                // then post
                axios.post('/activities', {
                    name: this.name,
                    domain_id: this.selected,
                    description: this.description,
                    supplies: this.supplies,
                    instructions: this.instructions,
                })
                .then((response) => {
                    
                    // success
                    //console.log(response);
                    
                    // emit changes for updating activities list
                    let newActivity = {
                        'id': response.data.id, // to pass to updated view for editing to work
                        'name': this.name,

                        'domain': {
                            'id': this.selected,
                            'name': this.name
                        },
                        // 'domain': this.domain,
                        'domain_id': this.selected,
                        
                        'description': this.description,
                        'supplies': this.supplies,
                        'instructions': this.instructions,
                    };
                    bus.$emit('addedNewActivity', newActivity);        
                    
                    // resets
                    this.name = '';
                    // this.domain = '';
                    this.selected = '';
                    this.description = '';
                    this.supplies = '';
                    this.instructions = '';
                    this.inputChanged = false;

                })
                .catch((error) => {
                    console.log(error);
                    window.flash('There was a problem saving.');
                    this.inputChanged = false;
                });
            }
        }
    }
</script>