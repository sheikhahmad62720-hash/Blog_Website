import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../store/authStore'

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('../views/HomeView.vue'),
        meta: { title: 'Home' },
    },
    {
        path: '/post/:slug',
        name: 'post',
        component: () => import('../views/SinglePostView.vue'),
        meta: { title: 'Article' },
    },
    {
        path: '/category/:slug',
        name: 'category',
        component: () => import('../views/CategoryView.vue'),
        meta: { title: 'Category' },
    },
    {
        path: '/tag/:slug',
        name: 'tag',
        component: () => import('../views/TagView.vue'),
        meta: { title: 'Tag' },
    },
    {
        path: '/author/:id',
        name: 'author',
        component: () => import('../views/AuthorView.vue'),
        meta: { title: 'Author' },
    },
    {
        path: '/about',
        name: 'about',
        component: () => import('../views/AboutView.vue'),
        meta: { title: 'About' },
    },
    {
        path: '/latest',
        name: 'latest',
        component: () => import('../views/LatestView.vue'),
        meta: { title: 'Latest' },
    },
    {
        path: '/contact',
        name: 'contact',
        component: () => import('../views/ContactView.vue'),
        meta: { title: 'Contact' },
    },
    {
        path: '/search',
        name: 'search',
        component: () => import('../views/SearchView.vue'),
        meta: { title: 'Search' },
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('../views/LoginView.vue'),
        meta: { title: 'Login', guestOnly: true },
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('../views/RegisterView.vue'),
        meta: { title: 'Register', guestOnly: true },
    },
    {
        path: '/admin',
        component: () => import('../views/admin/AdminLayout.vue'),
        meta: { requiresAuth: true, requiresAdmin: true },
        children: [
            {
                path: '',
                name: 'admin.dashboard',
                component: () => import('../views/admin/DashboardView.vue'),
                meta: { title: 'Dashboard' },
            },
            {
                path: 'posts',
                name: 'admin.posts',
                component: () => import('../views/admin/PostsListView.vue'),
                meta: { title: 'Posts' },
            },
            {
                path: 'posts/create',
                name: 'admin.posts.create',
                component: () => import('../views/admin/PostEditorView.vue'),
                meta: { title: 'New Post' },
            },
            {
                path: 'posts/:slug/edit',
                name: 'admin.posts.edit',
                component: () => import('../views/admin/PostEditorView.vue'),
                meta: { title: 'Edit Post' },
            },
        ],
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: () => import('../views/NotFoundView.vue'),
        meta: { title: 'Not Found' },
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        return savedPosition || { top: 0 }
    },
})

router.beforeEach(async (to) => {
    const auth = useAuthStore()

    if (auth.isAuthenticated && !auth.user) {
        try {
            await auth.fetchMe()
        } catch {
            auth.logout()
        }
    }

    if (to.meta.requiresAuth && !auth.isAuthenticated) {
        return { name: 'login', query: { redirect: to.fullPath } }
    }

    if (to.meta.requiresAdmin && !auth.isAdmin) {
        return { name: 'home' }
    }

    if (to.meta.guestOnly && auth.isAuthenticated) {
        return { name: 'home' }
    }

    document.title = to.meta.title
        ? `${to.meta.title} · Blog`
        : 'Blog'
})

export default router
