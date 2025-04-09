export function formatCash(str: string) {
  return str
    ?.split('')
    .reverse()
    .reduce((prev, next, index) => {
      return (index % 3 ? next : next + '.') + prev
    })
}

/**
 * Lưu toast message để hiển thị sau khi chuyển trang
 * @param message Nội dung thông báo
 * @param type Loại thông báo (success, error, info, warning)
 */
export function setToastMessage(message: string, type: 'success' | 'error' | 'info' | 'warning') {
  sessionStorage.setItem('toast_message', message)
  sessionStorage.setItem('toast_type', type)
}

/**
 * Đọc và lấy toast message từ sessionStorage
 * @returns Object chứa message và type, hoặc null nếu không có
 */
export function getToastMessage() {
  const message = sessionStorage.getItem('toast_message')
  const type = sessionStorage.getItem('toast_type')

  if (message && type) {
    // Xóa thông báo sau khi đọc
    sessionStorage.removeItem('toast_message')
    sessionStorage.removeItem('toast_type')

    return {
      message,
      type: type as 'success' | 'error' | 'info' | 'warning'
    }
  }

  return null
}
