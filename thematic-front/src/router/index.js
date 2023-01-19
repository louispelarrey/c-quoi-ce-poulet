import { createRouter, createWebHistory } from "vue-router";
import Login from "../components/Login/login.vue";
import Home from "../components/User/Home.vue";
import Register from "../components/Login/register.vue"
import editProfile from "../components/User/editProfile.vue"
import Users from "../components/Admin/Users.vue";
import Reports from "../components/Admin/Reports.vue";
import Meals from "../components/Menu/Meals.vue";
import HomeAdmin from "../components/Admin/HomeAdmin.vue";
import {ref} from "vue";

const token = localStorage.getItem("token");
let userAdmin = ref(false);

if (token) {
  const user = JSON.parse(atob(token.split('.')[1]))
  userAdmin = Object.values(user.roles).includes('ROLE_ADMIN');
}

if (userAdmin) {
  console.log("admin")
}

const router = createRouter({
  history: createWebHistory('/'),
  routes: [
    {
      path: "/",
      name: "home",
      component: function (){
        if (userAdmin) {
            return HomeAdmin;
        }else {
            return Home;
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
    // {
    //   path: "/admin",
    //   name: "admin",
    //   component: homeAdmin,
    // },
    // {
    //   path: "/:pathMatch(.*)",
    //   name: "not-found",
    //   component: NotFound,
    // },
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
      path: "/admin/reports",
      name: "admin_reports",
      component: Reports,
    }
  ],
});

const adminRoutes = [
    {
        path: "/admin/users",
        name: "admin",
        component: Users,
    }
]

//merge 2 routees
router.addRoute(adminRoutes)

export default router;
