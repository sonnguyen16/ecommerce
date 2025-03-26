<script setup lang="ts">
import type { Blog } from '~/lib/schema'

// Thêm định nghĩa kiểu cho CKEditor
declare global {
  interface Window {
    CKEDITOR: any
  }
}

definePageMeta({
  layout: 'admin',
  middleware: 'auth'
})

const { mediaUrl } = useRuntimeConfig().public

const slug = useRoute().params.slug
const editorId = 'blog-editor'

const form = ref({
  id: '',
  title: '',
  content: '',
  thumbnail: '',
  is_public: 0
})

const error_list = ref({
  title: [],
  content: [],
  thumbnail: [],
  is_public: []
})

const submitting = ref(false)
const showToast = ref(false)
const message = ref('')
const type = ref('')
let editor: any = null

onMounted(() => {
  // Tạo script tag để load CKEditor 4 từ CDN, nếu đã có thì không tạo lại
  if (!document.getElementById('ckeditor-script')) {
    const script = document.createElement('script')
    script.id = 'ckeditor-script'
    script.src = 'https://cdn.ckeditor.com/4.20.0/standard-all/ckeditor.js'
    script.async = true

    script.onload = () => {
      initializeCKEditor()
    }

    document.head.appendChild(script)
  } else {
    initializeCKEditor()
  }
})

// Khởi tạo CKEditor 4
const initializeCKEditor = () => {
  if (window.CKEDITOR) {
    // Tắt cảnh báo bảo mật
    window.CKEDITOR.disableAutoInline = true
    window.CKEDITOR.config.disableNativeSpellChecker = false
    window.CKEDITOR.config.scayt_autoStartup = false
    window.CKEDITOR.config.title = false
    window.CKEDITOR.timestamp = new Date().getTime() // Thêm timestamp để tránh cảnh báo cache

    // Tắt cảnh báo phiên bản
    window.CKEDITOR.config.devtools_styles = false
    window.CKEDITOR.config.disableNativeTableHandles = true
    window.CKEDITOR.config.disableNativeSpellChecker = true
    window.CKEDITOR.config.removePlugins = 'exportpdf,scayt,wsc,about'

    if (editor) {
      editor.destroy()
    }

    editor = window.CKEDITOR.replace(editorId, {
      filebrowserUploadUrl: '/api/upload-image',
      toolbar: [
        { name: 'document', items: ['Source'] },
        { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
        { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll'] },
        {
          name: 'basicstyles',
          items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']
        },
        {
          name: 'paragraph',
          items: [
            'NumberedList',
            'BulletedList',
            '-',
            'Outdent',
            'Indent',
            '-',
            'Blockquote',
            '-',
            'JustifyLeft',
            'JustifyCenter',
            'JustifyRight',
            'JustifyBlock'
          ]
        },
        { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
        { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
        { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
        { name: 'colors', items: ['TextColor', 'BGColor'] },
        { name: 'tools', items: ['Maximize'] }
      ],
      extraPlugins: 'image2,uploadimage',
      height: 400
    })

    // Gắn nội dung khi editor đã sẵn sàng
    editor.on('instanceReady', () => {
      editor.setData(form.value.content)
    })

    // Lấy nội dung từ editor trước khi submit
    editor.on('change', () => {
      form.value.content = editor.getData()
    })
  }
}

if (slug.toString() !== 'null') {
  const { data: blogData } = await useClientFetch(`blogs/${slug}`)

  if (blogData) {
    form.value = {
      ...blogData.value.data
    }
  }
}

const onSubmit = async () => {
  // Cập nhật nội dung từ editor nếu đang tồn tại
  if (editor) {
    form.value.content = editor.getData()
  }

  clearError()
  submitting.value = true
  let formData = new FormData()
  for (let key in form.value) {
    if (key === 'is_public') {
      // @ts-ignore
      formData.append(key, form.value[key] === true ? 1 : 0)
    } else {
      // @ts-ignore
      formData.append(key, form.value[key])
    }
  }

  const { error }: any = await useClientFetch('blogs', {
    method: 'POST',
    body: formData
  })
  submitting.value = false

  if (error.value) {
    console.log(error)
    if (error.value.status === 422) {
      error_list.value = error.value.data.errors
    } else {
      showToastFunc('Lưu bài viết thất bại', 'error')
    }
    return
  }
  showToastFunc('Lưu bài viết thành công', 'success')
  if (slug.toString() === 'null') {
    clearForm()
  }
}

const showToastFunc = (msg: string, toastType: string) => {
  showToast.value = true
  message.value = msg
  type.value = toastType
  setTimeout(() => {
    showToast.value = false
  }, 3000)
}

const clearError = () => {
  error_list.value = {
    title: [],
    content: [],
    thumbnail: [],
    is_public: []
  }
}

const clearForm = () => {
  form.value = {
    id: '',
    title: '',
    content: '',
    thumbnail: '',
    is_public: 0
  }

  // Xóa nội dung trong editor
  if (editor) {
    editor.setData('')
  }
}

const onFileChange = (e: any) => {
  const file = e.target.files[0]
  form.value.thumbnail = file
  const reader = new FileReader()
  reader.onload = (e: any) => {
    document.getElementById('img_thumbnail')?.setAttribute('src', e.target.result)
  }
  reader.readAsDataURL(file)
}

// Xử lý khi component unmounted
onBeforeUnmount(() => {
  if (editor) {
    editor.destroy()
    editor = null
  }
})
</script>

<template>
  <h1 class="text-2xl">Quản lý bài viết</h1>
  <div class="rounded-xl p-4 mt-5 min-h-[calc(100vh-9.5rem)] bg-white flex flex-col">
    <form enctype="multipart/form-data" id="form" @submit.prevent="onSubmit">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left Column -->
        <div>
          <div class="">
            <label for="title" class="block text-gray-600">Tiêu đề bài viết</label>
            <Input v-model="form.title" :errors="error_list?.title?.[0]" />
          </div>

          <div class="">
            <label for="content" class="block text-gray-600 mb-2">Nội dung</label>
            <textarea :id="editorId" name="content" style="display: none"></textarea>
            <InputError :message="error_list?.content?.[0]" />
          </div>
        </div>

        <!-- Right Column -->
        <div>
          <div class="">
            <label for="thumbnail" class="block text-gray-600">Ảnh bài viết</label>
            <input
              @change="onFileChange"
              type="file"
              id="thumbnail"
              :class="[error_list?.thumbnail?.[0] ? 'border border-red-500' : 'border border-gray-300']"
              class="rounded-lg border border-gray-300 w-full"
            />
            <InputError :message="error_list?.thumbnail?.[0]" />
            <img
              id="img_thumbnail"
              alt="thumbnail"
              v-if="form.thumbnail"
              :src="mediaUrl + form.thumbnail"
              class="w-28 h-28 object-cover rounded-lg mb-5"
            />
          </div>

          <div class="">
            <label for="is_public" class="block text-gray-600">Trạng thái</label>
            <div class="mt-2">
              <label class="inline-flex items-center">
                <input
                  type="checkbox"
                  v-model="form.is_public"
                  class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                />
                <span class="ml-2">Công khai</span>
              </label>
            </div>
            <InputError :message="error_list?.is_public?.[0]" />
          </div>
        </div>
      </div>
      <div class="flex gap-2 mt-4">
        <button
          type="submit"
          class="px-6 py-[10px] bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700"
        >
          <Loading v-if="submitting" />
          <span v-else>Lưu bài viết</span>
        </button>
        <button
          @click.prevent="navigateTo('/manage/blogs')"
          class="px-6 py-[10px] bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-indigo-700"
        >
          <span>Thoát</span>
        </button>
      </div>
    </form>
  </div>
  <Toast :message="message" :type="type as 'error' | 'success'" :show="showToast" />
</template>

<style scoped>
:deep(.cke_chrome) {
  border: 1px solid #d1d5db !important;
  border-radius: 0.5rem;
  overflow: hidden;
}
</style>
