<template>
    <div class="bg-teal-lightest border border-teal-light text-teal-dark px-4 py-3 rounded absolute pin-r pin-b mr-6 mb-4" role="alert" v-show="show">
      <strong class="font-bold">Note:</strong>
      <span class="block sm:inline">{{ body }}</span>
    </div>
</template>

<script>
    import { bus } from '../app';

    export default {
        props: ['message'],
        data() {
            return {
                body: '',
                show: false,
            }
        },
        created() {
            if (this.message) {
                this.flash(this.message);
            }

            // listening for event 'flash' to be emitted
            window.events.$on('flash', message => {
                this.flash(message);
            });
        },
        methods: {
            flash(message) {
                this.body = message;
                this.show = true;

                this.hide();
            },
            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    };
</script>
