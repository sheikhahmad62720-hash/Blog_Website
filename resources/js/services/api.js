import axios from 'axios'

const api = axios.create({
    baseURL: '/api',
    headers: {
        'Accept': 'application/json',
        'X-Fingerprint': localStorage.getItem('blog_fingerprint') || generateFingerprint(),
    },
})

api.interceptors.request.use((config) => {
    const token = localStorage.getItem('blog_token')
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config
})

api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem('blog_token')
            localStorage.removeItem('blog_user')
        }
        return Promise.reject(error)
    }
)

function generateFingerprint() {
    const fp = crypto.randomUUID()
    localStorage.setItem('blog_fingerprint', fp)
    return fp
}

export default api
