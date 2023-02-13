import {createRouter, createWebHistory} from "vue-router";
import Login from "../components/Login/login.vue";
import Home from "../components/User/Home.vue";
import Register from "../components/Login/register.vue"
import editProfile from "../components/User/editProfile.vue"
import Users from "../components/Admin/Users.vue";
import HomeAdmin from "../components/Admin/HomeAdmin.vue";
import Meals from "../components/Menu/Meals.vue";
import {reactive} from "vue";
import Restaurants from "../components/Admin/Restaurants.vue";
import Reports from "../components/Admin/Reports.vue";
import CreateRestaurant from "../components/Restaurant/CreateRestaurant.vue";
import Commands from "../components/Commands.vue";
import HomeRestaurateur from "../components/Restaurant/HomeRestaurateur.vue";
import Error403 from "../components/Error/Error403.vue";
import Error404 from "../components/Error/Error404.vue";

const state = reactive({
    token: localStorage.getItem("token"),
    userAdmin: false,
    userRestaurateur: false,
    userDeliverer: false
});

if (state.token) {
    const user = JSON.parse(atob(state.token.split('.')[1]))
    state.userAdmin = Object.values(user.roles).includes('ROLE_ADMIN');
    state.userRestaurateur = Object.values(user.roles).includes('ROLE_RESTAURANT');
    state.userDeliverer = Object.values(user.roles).includes('ROLE_DELIVERER');
}

const router = createRouter({
    history: createWebHistory('/'),
    routes: [
        {
            path: '/',
            name: 'home',
            component: function () {
                if (state.token && state.userAdmin) {
                    return Users
                } else if (state.token && state.userRestaurateur) {
                    return HomeRestaurateur
                }else {
                    return Home
                }
            }
        },
        {
            path: "/login",
            name: "login",
            component: Login,
        },
        {
            path: "/register",
            name: "register",
            component: Register,
        },
        {
            path: "/profile",
            name: "editProfile",
            component: editProfile,
        },
        {
            path: "/Restaurant/:id/Menu",
            name: "Meals",
            component: Meals,
        },
        {
            path: "/admin/users",
            name: "admin_users",
            component: function () {
                if (state.userAdmin) {
                    return Users
                } else {
                    return Error403
                }
            }
        },
        {
            path: "/admin/restaurants",
            name: "admin_restaurants",
            component: function () {
                if (state.userAdmin) {
                    return Restaurants
                } else {
                    return Error403
                }
            }
        },
        {
            path: "/restaurants/new",
            name: "create_restaurants",
            component: CreateRestaurant,
        },
        {
            path: "/admin/reports",
            name: "admin_reports",
            component: function () {
                if (state.userAdmin) {
                    return Reports
                } else {
                    return Error403
                }
            }
        },
        {
            path: "/orders",
            name: "orders",
            component: Commands,
        },
        {
            path: "/:pathMatch(.*)*",
            name: "not_found",
            component: Error404,
        }
    ],
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("token");
    if (!token && to.path !== "/login" && to.path !== "/register") {
        next({
            path: "/login",
            query: { redirect: to.fullPath }
        });
    } else {
        next();
    }
})

export default router;
