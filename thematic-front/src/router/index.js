import { createRouter, createWebHistory } from "vue-router";
import Login from "../components/Login/login.vue";
import Home from "../components/User/Home.vue";
import Register from "../components/Login/register.vue"
import editProfile from "../components/User/editProfile.vue"
import Users from "../components/Admin/Users.vue";
import Meals from "../components/Menu/Meals.vue";
<<<<<<< Updated upstream

=======
import HomeAdmin from "../components/Admin/HomeAdmin.vue";
import {ref} from "vue";

const token = localStorage.getItem("token");
let userAdmin = ref(false);

if (token) {
  const user = JSON.parse(atob(token.split('.')[1]))
  userAdmin = Object.values(user.roles).includes('ROLE_ADMIN');
}
>>>>>>> Stashed changes
const router = createRouter({
  history: createWebHistory('/'),
  routes: [
    {
      path: "/",
      name: "home",
      component: () => Home,
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
    // {
    //   path: "/User/:id",
    //   name: "editUser",
    //   component: editUser,
    // },
    {
      path: "/profile",
      name: "editProfile",
      component: editProfile,
    },
    {
      path: "/Admin/Users",
      name: "Users",
      component: Users,
    },
    // {
    //   path: "/:pathMatch(.*)",
    //   name: "not-found",
    //   component: NotFound,
    // },
    {// TODO: change to /Restaurant/:id/Menu
      path: "/Restaurant/1/Menu",
      name: "Meals",
      component: Meals,
    }
  ],
});

export default router;
