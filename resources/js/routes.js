import Dashboard from './components/Dashboard.vue';
import ListAppointments from './pages/appointments/ListAppointments.vue';
import AppointmentForm from './pages/appointments/AppointmentForm.vue';
import UserList from './pages/users/UserList.vue';
import UpdateSetting from './pages/settings/UpdateSetting.vue';
import UpdateProfile from './pages/profile/UpdateProfile.vue';
import Login from './pages/auth/Login.vue';

import RoomList from './pages/rooms/RoomList.vue';
import MemberList from './pages/members/MemberList.vue';
import MemberDepartment from './pages/members/MemberDepartment.vue';
import CategoryList from './pages/categories/CategoryList.vue';
import DepartmentList from './pages/departments/DepartmentList.vue';
import ComplaintList from './pages/complaints/ComplaintList.vue';
import ItemList from './pages/items/ItemList.vue';
import UnitList from './pages/units/UnitList.vue';
import StockList from './pages/stocks/StockList.vue';
import StockListByLocation from './pages/stocks/StockListByLocation.vue';
import ReceiveList from './pages/receives/ReceiveList.vue';
import SupplierList from './pages/suppliers/SupplierList.vue';
import InvoiceList from './pages/invoices/InvoiceList.vue';
import IssueList from './pages/issues/IssueList.vue';
import IssueItem from './pages/issues/IssueItem.vue';
import LocationList from './pages/locations/LocationList.vue';
import ComplaintListPage from './pages/complaints/ComplaintListPage.vue';
import ServiceComplaint from './pages/complaints/ServiceComplaint.vue';
import UserComplaint from './pages/complaints/UserComplaint.vue';
import ClaimedHistory from './pages/lostnfound/ClaimedHistory.vue';
import Television from './pages/lostnfound/Television.vue';
import MessageList from './pages/messages/MessageList.vue';

export default [
    {
        path: '/login',
        name: 'admin.login',
        component: Login,
    },

    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard,
    },
    {
        path: '/admin/locations',
        name: 'admin.location',
        component: LocationList,
    },
    {
        path: '/admin/members',
        name: 'admin.member',
        component: MemberList,
    },
    {
        path: '/admin/memberdepartment',
        name: 'admin.memberdepartment',
        component: MemberDepartment,
    },
    {
        path: '/admin/categories',
        name: 'admin.category',
        component: CategoryList,
    },
    {
        path: '/admin/rooms',
        name: 'admin.room',
        component: RoomList,
    },
    {
        path: '/admin/stocks',
        name: 'admin.stock',
        component: StockList,
    },
    {
        path: '/admin/stocklists/:id',
        name: 'admin.stocklist',
        component: StockListByLocation,
    },
    {
        path: '/admin/invoices',
        name: 'admin.invoice',
        component: InvoiceList,
    },
    {
        path: '/admin/issues',
        name: 'admin.issue',
        component: IssueList,
    },
    {
        path: '/admin/issueitems/:id',
        name: 'admin.issueitem',
        component: IssueItem,
    },
    {
        path: '/admin/suppliers',
        name: 'admin.supplier',
        component: SupplierList,
    },
    {
        path: '/admin/complaints',
        name: 'admin.complaint',
        component: ComplaintList,
    },
    {
        path: '/admin/items',
        name: 'admin.item',
        component: ItemList,
    },
    {
        path: '/admin/units',
        name: 'admin.unit',
        component: UnitList,
    },
    
    {
        path: '/admin/complaintpages',
        name: 'admin.complaintpages',
        component: ComplaintListPage,
    },
    {
        path: '/admin/servicecomplaint',
        name: 'admin.servicecomplaint',
        component: ServiceComplaint,
    },
    {
        path: '/admin/usercomplaint',
        name: 'admin.usercomplaint',
        component: UserComplaint,
    },
    {
        path: '/admin/departments',
        name: 'admin.department',
        component: DepartmentList,
    },
    {
        path: '/admin/claimed',
        name: 'admin.claimed',
        component: ClaimedHistory,
    },
    {
        path: '/admin/tv',
        name: 'admin.tv',
        component: Television,
    },
    {
        path: '/admin/messages/:id',
        name: 'admin.message',
        component: MessageList,
    },
    {
        path: '/admin/receives/:id',
        name: 'admin.receive',
        component: ReceiveList,
    },

    {
        path: '/admin/appointments',
        name: 'admin.appointments',
        component: ListAppointments,
    },

    {
        path: '/admin/appointments/create',
        name: 'admin.appointments.create',
        component: AppointmentForm,
    },

    {
        path: '/admin/appointments/:id/edit',
        name: 'admin.appointments.edit',
        component: AppointmentForm,
    },

    {
        path: '/admin/users',
        name: 'admin.users',
        component: UserList,
    },

    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: UpdateSetting,
    },

    {
        path: '/admin/profile',
        name: 'admin.profile',
        component: UpdateProfile,
    }
]
