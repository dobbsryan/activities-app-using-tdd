<template>
    <tr>
        <td>
            <input 
                class="m-2"
                v-model="name"
                @keyup="showEditButton"
            ></input>
        </td>
        <td>
            <div class="w-4 h-4 m-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    @click="saveToDB"
                    class="cursor-pointer"
                    v-if="inputChanged"
                >
                    <path
                        v-if="optionToEditRecord"
                        d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"
                    />
                    <path
                        v-if="optionToDeleteRecord"
                        d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"
                    />
                </svg>
            </div>
        </td>
    </tr>
</template>

<script>
    import { bus } from '../../app';

    export default {        
        props: [
            'domain'
        ],

        data() {
            return {
               name: this.domain.name,

               inputChanged: false,
               optionToEditRecord: false,
               optionToDeleteRecord: false,
            };
        },

        methods: {
            showEditButton(e) {
                if (e.code !== 'Tab') {
                    this.inputChanged = true;

                    // which button to show
                    if (this.name === '') {
                        this.optionToEditRecord = false;
                        this.optionToDeleteRecord = true;
                    } else {
                        this.optionToEditRecord = true;
                        this.optionToDeleteRecord = false;
                    }
                }
            },
            saveToDB() {
                // to prevent multi-click from the start
                this.inputChanged = false;

                if (this.name === '') {
                    // delete the record
                    axios.delete('/domains/' + this.domain.id)
                    .then((response) => {
                        // success
                        bus.$emit('deletedDomainRecord', this.domain.id);
                    })
                    .catch((error) => {
                        console.log(error);
                        window.flash('Domains assigned to Activities cannot be deleted.');
                    });
                } else {
                    // update the record
                    axios.patch('/domains/' + this.domain.id, {
                        name: this.name,
                    })
                    .then((response) => {
                        // success
                        // reset
                        this.inputChanged = false;
                    })
                    .catch((error) => {
                        console.log(error);
                        window.flash('There was a problem updating.');
                        this.inputChanged = false;
                    });
                }
            },
        }
    }
</script>