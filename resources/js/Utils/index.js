export function formatCash(str) {
    if (!str) return '0'
    return str
        ?.toString()
        .split('')
        .reverse()
        .reduce((prev, next, index) => {
            return (index % 3 ? next : next + '.') + prev
        })
}

/**
 * Lưu toast message để hiển thị sau khi chuyển trang
 * @param {string} message Nội dung thông báo
 * @param {'success'|'error'|'info'|'warning'} type Loại thông báo
 */
export function setToastMessage(message, type) {
    sessionStorage.setItem('toast_message', message)
    sessionStorage.setItem('toast_type', type)
}

/**
 * Đọc và lấy toast message từ sessionStorage
 * @returns {Object|null} Object chứa message và type, hoặc null nếu không có
 */
export function getToastMessage() {
    const message = sessionStorage.getItem('toast_message')
    const type = sessionStorage.getItem('toast_type')

    if (message && type) {
        // Xóa thông báo sau khi đọc
        sessionStorage.removeItem('toast_message')
        sessionStorage.removeItem('toast_type')

        return { message, type }
    }

    return null
}

/**
 * Lấy CSRF token từ meta tag hoặc cookie
 * @returns {string|null} CSRF token
 */
export function getCsrfToken() {
    // Thử lấy từ meta tag
    const metaToken = document.querySelector('meta[name="csrf-token"]')?.content
    if (metaToken) return metaToken

    // Thử lấy từ cookie XSRF-TOKEN (Laravel tự động set)
    const cookies = document.cookie.split(';')
    for (const cookie of cookies) {
        const [name, value] = cookie.trim().split('=')
        if (name === 'XSRF-TOKEN') {
            return decodeURIComponent(value)
        }
    }

    return null
}

/**
 * Fetch wrapper tự động xử lý CSRF token
 * @param {string} url URL to fetch
 * @param {Object} options Fetch options
 * @returns {Promise<Response>}
 */
export async function apiFetch(url, options = {}) {
    const csrfToken = getCsrfToken()

    const headers = {
        'Accept': 'application/json',
        ...options.headers,
    }

    // Thêm CSRF token cho các method không phải GET/HEAD
    if (options.method && !['GET', 'HEAD'].includes(options.method.toUpperCase())) {
        if (csrfToken) {
            headers['X-XSRF-TOKEN'] = csrfToken
        }
    }

    // Nếu body là FormData, không set Content-Type (browser tự xử lý)
    if (!(options.body instanceof FormData)) {
        headers['Content-Type'] = 'application/json'
    }

    return fetch(url, {
        ...options,
        headers,
        credentials: 'same-origin', // Gửi cookie cùng request
    })
}

/**
 * POST request với CSRF tự động
 * @param {string} url URL to POST
 * @param {Object|FormData} data Data to send
 * @returns {Promise<Response>}
 */
export async function apiPost(url, data) {
    const body = data instanceof FormData ? data : JSON.stringify(data)
    return apiFetch(url, {
        method: 'POST',
        body,
    })
}

/**
 * DELETE request với CSRF tự động
 * @param {string} url URL to DELETE
 * @returns {Promise<Response>}
 */
export async function apiDelete(url) {
    return apiFetch(url, {
        method: 'DELETE',
    })
}
