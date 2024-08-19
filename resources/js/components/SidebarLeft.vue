<script setup>
import { useAuthUserStore } from '../stores/AuthUserStore';
import { useRouter } from 'vue-router';
import { useSettingStore } from '../stores/SettingStore';

const router = useRouter();
const authUserStore = useAuthUserStore();
const settingStore = useSettingStore();

const logout = () => {
    axios.post('/logout')
    .then((response) => {
        authUserStore.user.name = '';
        router.push('/login');
    });
};
</script>
<style>
.nav-item .nav-icon{
    color: white;
}
.nav-item .nav-link p {
    color: white;
    text-shadow: 1px;
}
</style>
<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <a href="#" class="brand-link">
            <img src="\logo.png" alt="Case MGMT"
                class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">{{ settingStore.setting.app_name }}</span>
        </a>

        <div class="sidebar">

            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img :src="authUserStore.user.avatar" class="img-circle elevation-2" alt="Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ authUserStore.user.name }}</a>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <router-link to="/admin/dashboard" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                            Dashboard
                            </p>
                        </router-link>
                    </li>
                    <!-- <li class="nav-item">
                        <router-link to="/admin/complaintpages" active-class="active" class="nav-link">
                            <i class="nav-icon fa-solid fa-blender-phone"></i>
                            <p>
                            Complaints
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/servicecomplaint" active-class="active" class="nav-link">
                            <i class="nav-icon fa-solid fa-magnifying-glass"></i>
                            <p>
                            Search
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/lostnfound"
                            :class="$route.path.startsWith('/admin/lostnfound') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fa-solid fa-table-list"></i>
                            <p>
                            Lost & Found
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item" v-show="authUserStore.user.department_id==3">
                        <router-link to="/admin/claimed" active-class="active" class="nav-link">
                            <i class="nav-icon fa-solid fa-rectangle-list"></i>
                            <p>
                            Claimed History
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/tv" active-class="active" class="nav-link">
                            <i class="nav-icon fa-solid fa-tv"></i>
                            <p>
                            In-room TVs
                            </p>
                        </router-link>
                    </li>
                   
                    <li class="nav-item">
                        <router-link to="/admin/rooms" active-class="active" class="nav-link">
                            <i class="nav-icon fa-solid fa-location-dot"></i>
                            <p>
                            Location
                            </p>
                        </router-link>
                    </li> -->
                    <li class="nav-item" v-show="authUserStore.user.role=='ADMIN'">
                        <router-link to="/admin/departments" active-class="active" class="nav-link">
                            <i class="nav-icon fa-solid fa-building"></i>
                            <p>
                                Departments
                            </p>
                        </router-link>
                    </li>
                    <!-- <li class="nav-item">
                        <router-link to="/admin/memberdepartment" active-class="active" class="nav-link">
                            <i class="nav-icon fa-solid fa-users-gear"></i>
                            <p>
                                Team Members
                            </p>
                        </router-link>
                    </li> -->
                   
                    <li class="nav-item" v-show="authUserStore.user.role=='ADMIN'">
                        <router-link to="/admin/users" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item" v-show="authUserStore.user.role=='ADMIN'">
                        <router-link to="/admin/settings" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Settings
                            </p>
                        </router-link>
                    </li>
                 
                    <li class="nav-item">
                        <router-link to="/admin/profile" active-class="active" class="nav-link">
                            <i class="nav-icon fa-solid fa-user-tie"></i>
                            <p>
                                Profile
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <form class="nav-link">
                            <a href="#" @click.prevent="logout">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>
                                    Logout
                                </p>
                            </a>
                        </form>

                    </li>
                </ul>
            </nav>

        </div>

    </aside>
</template>
