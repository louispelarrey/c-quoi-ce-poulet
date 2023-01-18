import { createRouter, createWebHistory } from "vue-router";
import Login from "../components/Login/login.vue";
import Home from "../components/User/Home.vue";
import Register from "../components/Login/register.vue"
import editProfile from "../components/User/editProfile.vue"
import Users from "../components/Admin/Users.vue";

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
  ],
});

export default router;
