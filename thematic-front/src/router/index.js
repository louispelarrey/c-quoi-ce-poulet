import {createRouter, createWebHistory} from "vue-router";
import Login from "../components/Login/login.vue";
import Home from "../components/User/Home.vue";
import Register from "../components/Login/register.vue"
import editProfile from "../components/User/editProfile.vue"
import Users from "../components/Admin/Users.vue";
import HomeAdmin from "../components/Admin/HomeAdmin.vue";
import Meals from "../components/Menu/Meals.vue";
import {ref} from "vue";
import Restaurants from "../components/Admin/Restaurants.vue";
import Reports from "../components/Admin/Reports.vue";
import CreateRestaurant from "../components/Restaurant/CreateRestaurant.vue";

const token = localStorage.getItem("token");
let userAdmin = ref(false);

if (token) {
    const user = JSON.parse(atob(token.split('.')[1]))
    userAdmin = Object.values(user.roles).includes('ROLE_ADMIN');
}
const router = createRouter({
    history: createWebHistory('/'),
    routes: [
        {
            path: '/',
            name: 'home',
            component: function () {
                if (token && userAdmin) {
                    return HomeAdmin
                } else {
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
            component: Users,
        },
        {
            path: "/admin/restaurants",
            name: "admin_restaurants",
            component: Restaurants,
        },
        {
            path: "/restaurants/new",
            name: "create_restaurants",
            component: CreateRestaurant,
        },
        {
          path: "/admin/reports",
          name: "admin_reports",
          component: Reports,
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
