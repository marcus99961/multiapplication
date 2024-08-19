<script setup>
import { useSettingStore } from '../stores/SettingStore';
import { useNotiStore } from '../stores/NotiStore';
import axios from 'axios';
import { ref, onMounted, reactive, watch } from 'vue';
import moment from 'moment';



const settingStore = useSettingStore();
const notiStore = useNotiStore();
// console.log(settingStore.setting.messagecount)


const messages = ref();
const getmessages = () => {
    axios.get('/api/getmessages')
    .then((response) => {
        messages.value = response.data;

    })
};
const counts = ref();
const getcounts = async () => {
  await axios.get('/api/getcounts')
    .then((response) => {
        counts.value = response.data;

    })
};
const complaints = ref();
const getcomplaints = () => {
    axios.get('/api/getcomplaints')
    .then((response) => {
        complaints.value = response.data;

    })
};
const complaintcounts = ref();
const getcomplaintcounts = () => {
    axios.get('/api/getcomplaintcounts')
    .then((response) => {
        complaintcounts.value = response.data;

    })
};
const lostcounts = ref();
const getlostcounts = () => {
    axios.get('/api/getlostcounts')
    .then((response) => {
        lostcounts.value = response.data;

    })
};
onMounted(() => {
    getmessages();
    getcounts();
    getcomplaints();
    getcomplaintcounts();
    getlostcounts();
});

</script>

<template>
    <nav class="main-header navbar navbar-expand" :class="settingStore.theme === 'dark' ? 'navbar-dark': 'navbar-light'">

        <ul class="navbar-nav">
            <li class="nav-item" id="toggleMenuIcon">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>


            <li class="nav-item d-none d-sm-inline-block">
                <a @click.prevent="settingStore.changeTheme" href="#" class="nav-link">
                    <i class="far" :class="settingStore.theme === 'dark' ? 'fa-moon' : 'fa-sun'"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">

            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="fas fa-search"></i>
                </a>
                <div class="navbar-search-block">
                    <form class="form-inline">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-comments"></i>
                    <span class="badge badge-danger navbar-badge" v-for="count in counts">{{ count.message_count }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="#" class="dropdown-item">

                        <div class="media" v-for="message in messages">
                            <img :src="'/storage/'+ message.avatar" alt="Avatar"
                                class="img-size-50 mr-3 img-circle">
                            <div class="media-body" >
                                <h3 class="dropdown-item-title">
                                    {{ message.userfullname }}
                                    <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">{{ message.name }}</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i>{{ message.createdHumanReadable }}</p>
                            </div>
                        </div>

                    </a>
                    <!-- <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">

                        <div class="media">
                            <img src="https://adminlte.io/themes/v3/dist/img/user8-128x128.jpg" alt="User Avatar"
                                class="img-size-50 img-circle mr-3">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    John Pierce
                                    <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">I got your message bro</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                            </div>
                        </div>

                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">

                        <div class="media">
                            <img src="https://adminlte.io/themes/v3/dist/img/user3-128x128.jpg" alt="User Avatar"
                                class="img-size-50 img-circle mr-3">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    Nora Silvester
                                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">The subject goes here</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                            </div>
                        </div>

                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Messages</a> -->
                </div>
            </li>

            <li class="nav-item dropdown" v-for="count in counts" >
                <text v-for="complaintcount in complaintcounts">
                    <text v-for="lostcount in lostcounts">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>

                    <span class="badge badge-warning navbar-badge">{{ count.message_count + complaintcount.complaint_count + lostcount.lost_count }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-header">{{ count.message_count + complaintcount.complaint_count + lostcount.lost_count }} new notifications</span>
                    <div class="dropdown-divider"></div>
                    <router-link to="/admin/complaints" class="dropdown-item">
                            <i class="nav-icon fa-solid fa-blender-phone"></i>
                            {{ count.message_count }} new messages
                        <span class="float-right text-muted text-sm"></span>
                        </router-link>

                    <div class="dropdown-divider"></div>
                    <router-link to="/admin/complaints" class="dropdown-item">
                            <i class="nav-icon fa-solid fa-blender-phone"></i>
                            {{ complaintcount.complaint_count }} new complaints
                        <span class="float-right text-muted text-sm"></span>
                        </router-link>

                    <div class="dropdown-divider"></div>
                    <router-link to="/admin/lostnfound" class="dropdown-item">
                            <i class="nav-icon fa-solid fa-table-list"></i>
                            {{ lostcount.lost_count }} new lost & found records
                        <span class="float-right text-muted text-sm"></span>
                        </router-link>

                    <!-- <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a> -->
                </div>
            </text></text>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
        </ul>
    </nav>
</template>
