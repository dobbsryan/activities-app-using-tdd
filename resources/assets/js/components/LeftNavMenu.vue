<template>
    <div class="absolute z-50 print:hidden">
        <header class="\\bg-white bg-transparent border-b">
            <div class="items-center py-6 px-6 \\absolute">
                <div class="flex items-center">
                    <!-- <nav-menu-icon @showLeftNavMenu="handleEvent"></nav-menu-icon> -->
                    <div class="w-6 h-6 mr-6 cursor-pointer"
                         @click="toggleLeftNavMenu"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                    </div>
                </div>
            </div>
        </header>
        <transition name="slide-fade">
            <div class="w-80 border-r absolute"
                 v-if="showLeftNavMenu"
            >
                <div class="bg-white p-6 h-screen">
                    <div class="flex flex-col">
                        <a class="py-2 border-b" href="/home">Home</a>
                        <a class="py-2" href="/activities">Activities</a>
                        <a class="py-2" href="/domains">Domains</a>
                        <a class="py-2 border-b" href="/residents">Residents</a>
                        <a class="py-2" href="/schedule-activities">Schedule Activities</a>
                        <div class="py-2 text-grey-darker">Activity Attendance</div>
                        <a class="pl-6 py-2" href="/scheduled-activities">Group</a>
                        <a class="pl-6 py-2 border-b" href="/individual-activities">Individual</a>
                        <a class="py-2" href="/reports">Reports</a>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                showLeftNavMenu: false
            }
        },
        methods: {
            toggleLeftNavMenu() {
                if (this.showLeftNavMenu) {
                    this.showLeftNavMenu = false;
                } else {
                    this.showLeftNavMenu = true;
                }

                //this.showLeftNavMenu ? !this.showLeftNavMenu : this.showLeftNavMenu;
                //console.log(this.showLeftNavMenu);
            }
        }
    }
</script>
