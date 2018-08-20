<template>
    <tr>
        <td>
            <textarea 
                v-model="name"
                @keyup="showEditButton"
            ></textarea>
        </td>
        <td>
            <div class="inline-block relative w-full">
                <select
                    class="appearance-none w-full h-full border-none pl-4 py-2 pr-8"
                    v-model="selected"
                    @change="showEditButton"
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
            <!-- <textarea 
                v-model="domain"
                @keyup="showEditButton"
            ></textarea> -->
        </td>
        <td>
            <textarea 
                v-model="description"
                @keyup="showEditButton"
            ></textarea>
        </td>
        <td>
            <textarea 
                v-model="supplies"
                @keyup="showEditButton"
            ></textarea>
        </td>
        <td>
            <textarea 
                v-model="instructions"
                @keyup="showEditButton"
            ></textarea>
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
            'activity',
            'domains'
        ],

        data() {
            return {
               name: this.activity.name,
               domain: this.activity.domain.name,
               description: this.activity.description,
               supplies: this.activity.supplies,
               instructions: this.activity.instructions,

               selected: '',

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

                // if user has emptied name and clicked button, we want to delete record
                if (this.name === '') {
                    // delete the record
                    axios.delete('/activities/' + this.activity.id)
                    .then((response) => {
                        // success
                        bus.$emit('deletedActivityRecord', this.activity.id);
                    })
                    .catch((error) => {
                        console.log(error);
                        window.flash('There was a problem updating.');
                    });
                } else {
                    // update the record
                    axios.patch('/activities/' + this.activity.id, {
                        name: this.name,
                        domain_id: this.selected,
                        description: this.description,
                        supplies: this.supplies,
                        instructions: this.instructions
                    })
                    .then((response) => {

                    })
                    .catch((error) => {
                        console.log(error);
                        window.flash('There was a problem updating.');
                    });
                }
            },
        },
        created() {
            this.selected = this.activity.domain.id;
        }
    }
</script>