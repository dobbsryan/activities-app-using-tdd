<template>
    <tr>
        <td>
            <input
                class="m-2"
                placeholder="Add resident..."
                v-model="firstName"
                @keyup="showAddButton"
            ></input>
        </td>
        <td>
            <input
                class="m-2"
                v-model="lastName"
                @keyup="showAddButton"
            ></input>
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
        props: {
        },

        data() {
            return {
               firstName: '',
               lastName: '',

               inputChanged: false
            };
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
                if (this.firstName === '') {
                    return window.flash('Name field cannot be blank');
                }
                // then post
                axios.post('/residents', {
                    first_name: this.firstName,
                    last_name: this.lastName,
                })
                .then((response) => {
                    
                    // success
                    //console.log(response);
                    
                    // emit changes for updating activities list
                    let newResident = {
                        'id': response.data.id, // to pass to updated view for editing to work
                        'first_name': this.firstName,
                        'last_name': this.lastName,
                    };
                    bus.$emit('addedNewResident', newResident);        
                    
                    // resets
                    this.firstName = '';
                    this.lastName = '';
                    this.inputChanged = false;

                })
                .catch((error) => {
                    console.log(error);
                    window.flash('There was a problem saving.');
                    this.inputChanged = false;
                });
            },
        }
    }
</script>