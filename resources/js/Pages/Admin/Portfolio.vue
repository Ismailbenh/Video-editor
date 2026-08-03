<script setup>
import { ref, reactive, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '../../Layouts/AdminLayout.vue'
defineOptions({ layout: AdminLayout })

const props = defineProps({
  portfolioItems: { type: Array, default: () => [] },
})

const C = {
  bg: '#121214',
  panel: '#1C1C1F',
  raised: '#222226',
  amber: '#E8590C',
  bright: '#F4F4F5',
  dim: '#8B8B93',
  edge: '#2A2A2F',
  ledge: '#3D3D45',
}

const categoryColor = {
  youtube: '#E8590C',
  reels: '#A855F7',
  batch: '#3B82F6',
  clipping: '#22C55E',
}

const categoryOptions = ['youtube', 'reels', 'batch', 'clipping']

const emptyItem = () => ({
  id: '',
  title: '',
  category: 'youtube',
  thumbnail: '',
  thumbnailFile: null,
  client: '',
  date: new Date().toISOString().slice(0, 7),
})

// items comes straight from the database via props - Inertia refreshes
// this automatically after every store/update/delete request below.
const items = computed(() => props.portfolioItems)

const showModal = ref(false)
const editing = ref(null)
const thumbnailPreview = ref('')
const form = reactive({ ...emptyItem() })

function openAdd() {
  editing.value = null
  Object.assign(form, emptyItem())
  thumbnailPreview.value = ''
  showModal.value = true
}

function openEdit(item) {
  editing.value = item
  Object.assign(form, { ...item, thumbnailFile: null })
  thumbnailPreview.value = item.thumbnail ? getThumbnailSrc(item.thumbnail) : ''
  showModal.value = true
}

function getThumbnailSrc(thumbnail) {
  if (!thumbnail) return ''
  return thumbnail.startsWith('http') ? thumbnail : `/storage/${thumbnail}`
}

function handleSave() {
  if (!form.title) return

  const onDone = () => {
    showModal.value = false
  }

  if (form.thumbnailFile instanceof File) {
    const payload = new FormData()
    payload.append('title', form.title)
    payload.append('category', form.category)
    payload.append('client', form.client || '')
    payload.append('date', form.date || emptyItem().date)
    payload.append('thumbnail', form.thumbnailFile)

    if (editing.value) {
      router.put(`/admin/portfolio/${editing.value.id}`, payload, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: onDone,
      })
    } else {
      router.post('/admin/portfolio', payload, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: onDone,
      })
    }

    return
  }

  const payload = {
    title: form.title,
    category: form.category,
    thumbnail: form.thumbnail || '',
    client: form.client || '',
    date: form.date || emptyItem().date,
  }

  if (editing.value) {
    router.put(`/admin/portfolio/${editing.value.id}`, payload, {
      preserveScroll: true,
      onSuccess: onDone,
    })
  } else {
    router.post('/admin/portfolio', payload, {
      preserveScroll: true,
      onSuccess: onDone,
    })
  }
}

function onThumbnailChange(event) {
  const file = event.target.files?.[0] || null
  form.thumbnailFile = file

  if (file) {
    thumbnailPreview.value = URL.createObjectURL(file)
  } else {
    thumbnailPreview.value = form.thumbnail ? getThumbnailSrc(form.thumbnail) : ''
  }
}

function deleteItem(id) {
  router.delete(`/admin/portfolio/${id}`, { preserveScroll: true })
}

function cardHoverIn(e) {
  e.currentTarget.style.borderColor = C.ledge
}
function cardHoverOut(e) {
  e.currentTarget.style.borderColor = C.edge
}

function closeOnBackdrop(e) {
  if (e.target === e.currentTarget) showModal.value = false
}
</script>

<template>
  <div style="padding: 32px; min-height: 100%">
    <div
      style="
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
      "
    >
      <div>
        <div
          :style="{
            fontFamily: `'JetBrains Mono', monospace`,
            fontSize: '10px',
            color: C.dim,
            letterSpacing: '0.12em',
            textTransform: 'uppercase',
            marginBottom: '4px',
          }"
        >
          ADMIN / PORTFOLIO
        </div>
        <div style="display: flex; align-items: center; gap: 12px">
          <h1
            :style="{
              fontFamily: `'Space Grotesk', sans-serif`,
              fontSize: '24px',
              fontWeight: 700,
              color: C.bright,
              margin: 0,
              letterSpacing: '-0.01em',
            }"
          >
            Portfolio
          </h1>
          <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '11px', color: C.dim }">
            {{ String(items.length).padStart(2, '0') }} PIECES
          </span>
        </div>
      </div>
      <button
        @click="openAdd"
        :style="{
          padding: '9px 18px',
          backgroundColor: C.amber,
          color: '#fff',
          fontFamily: `'Space Grotesk', sans-serif`,
          fontSize: '13px',
          fontWeight: 600,
          border: 'none',
          borderRadius: '3px',
          cursor: 'pointer',
          letterSpacing: '0.03em',
        }"
      >
        + Add New Piece
      </button>
    </div>

    <!-- Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px">
      <div
        v-for="item in items"
        :key="item.id"
        :style="{
          backgroundColor: C.panel,
          border: `1px solid ${C.edge}`,
          borderRadius: '3px',
          overflow: 'hidden',
          transition: 'border-color 0.15s',
        }"
        @mouseenter="cardHoverIn"
        @mouseleave="cardHoverOut"
      >
        <!-- Thumbnail -->
        <div :style="{ position: 'relative', backgroundColor: C.bg }">
          <img
            v-if="item.thumbnail"
            :src="getThumbnailSrc(item.thumbnail)"
            :alt="item.title"
            style="width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block"
          />
          <div
            v-else
            :style="{
              width: '100%',
              aspectRatio: '16/9',
              backgroundColor: C.raised,
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }"
          >
            <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '11px', color: C.ledge }">
              NO THUMBNAIL
            </span>
          </div>

          <span
            :style="{
              position: 'absolute',
              top: '8px',
              right: '8px',
              fontFamily: `'JetBrains Mono', monospace`,
              fontSize: '8px',
              letterSpacing: '0.1em',
              textTransform: 'uppercase',
              color: categoryColor[item.category],
              border: `1px solid ${categoryColor[item.category]}`,
              borderRadius: '2px',
              padding: '2px 6px',
              backgroundColor: 'rgba(18,18,20,0.7)',
            }"
          >
            {{ item.category }}
          </span>
        </div>

        <!-- Info -->
        <div style="padding: 14px">
          <h3
            :style="{
              fontFamily: `'Space Grotesk', sans-serif`,
              fontSize: '13px',
              fontWeight: 600,
              color: C.bright,
              lineHeight: 1.3,
              marginBottom: '4px',
              overflow: 'hidden',
              textOverflow: 'ellipsis',
              whiteSpace: 'nowrap',
            }"
          >
            {{ item.title }}
          </h3>
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px">
            <span :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontSize: '11px', color: C.dim }">
              {{ item.client }}
            </span>
            <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '10px', color: C.ledge }">
              {{ item.date }}
            </span>
          </div>

          <div style="display: flex; gap: 6px">
            <button
              @click="openEdit(item)"
              :style="{
                flex: 1,
                padding: '6px',
                backgroundColor: 'transparent',
                color: C.dim,
                border: `1px solid ${C.edge}`,
                borderRadius: '3px',
                fontFamily: `'Space Grotesk', sans-serif`,
                fontSize: '11px',
                cursor: 'pointer',
                transition: 'all 0.15s',
              }"
            >
              Edit
            </button>
            <button
              @click="deleteItem(item.id)"
              style="
                padding: 6px 8px;
                background-color: transparent;
                color: rgba(239, 68, 68, 0.6);
                border: 1px solid rgba(239, 68, 68, 0.2);
                border-radius: 3px;
                font-family: 'Space Grotesk', sans-serif;
                font-size: 11px;
                cursor: pointer;
              "
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add/Edit modal -->
    <div
      v-if="showModal"
      style="
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
      "
      @click="closeOnBackdrop"
    >
      <div
        :style="{
          backgroundColor: C.panel,
          border: `1px solid ${C.edge}`,
          borderRadius: '4px',
          width: '100%',
          maxWidth: '480px',
          overflow: 'hidden',
        }"
      >
        <div
          :style="{
            padding: '20px 24px',
            borderBottom: `1px solid ${C.edge}`,
            display: 'flex',
            justifyContent: 'space-between',
            alignItems: 'center',
          }"
        >
          <h2 :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontSize: '18px', fontWeight: 700, color: C.bright, margin: 0 }">
            {{ editing ? 'Edit Portfolio Piece' : 'Add New Piece' }}
          </h2>
          <button
            @click="showModal = false"
            :style="{ background: 'none', border: 'none', color: C.dim, cursor: 'pointer', fontSize: '14px', padding: '4px' }"
          >
            Close
          </button>
        </div>

        <div style="padding: 24px; display: flex; flex-direction: column; gap: 16px">
          <div>
            <label
              :style="{
                display: 'block',
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '10px',
                color: C.dim,
                letterSpacing: '0.1em',
                textTransform: 'uppercase',
                marginBottom: '6px',
              }"
            >
              TITLE *
            </label>
            <input type="text" placeholder="Project title" v-model="form.title" />
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px">
            <div>
              <label
                :style="{
                  display: 'block',
                  fontFamily: `'JetBrains Mono', monospace`,
                  fontSize: '10px',
                  color: C.dim,
                  letterSpacing: '0.1em',
                  textTransform: 'uppercase',
                  marginBottom: '6px',
                }"
              >
                CATEGORY
              </label>
              <select v-model="form.category">
                <option v-for="cat in categoryOptions" :key="cat" :value="cat">
                  {{ cat.charAt(0).toUpperCase() + cat.slice(1) }}
                </option>
              </select>
            </div>
            <div>
              <label
                :style="{
                  display: 'block',
                  fontFamily: `'JetBrains Mono', monospace`,
                  fontSize: '10px',
                  color: C.dim,
                  letterSpacing: '0.1em',
                  textTransform: 'uppercase',
                  marginBottom: '6px',
                }"
              >
                DATE (YYYY-MM)
              </label>
              <input type="month" v-model="form.date" style="color-scheme: dark" />
            </div>
          </div>

          <div>
            <label
              :style="{
                display: 'block',
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '10px',
                color: C.dim,
                letterSpacing: '0.1em',
                textTransform: 'uppercase',
                marginBottom: '6px',
              }"
            >
              CLIENT NAME
            </label>
            <input type="text" placeholder="Client or brand name" v-model="form.client" />
          </div>

          <div>
            <label
              :style="{
                display: 'block',
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '10px',
                color: C.dim,
                letterSpacing: '0.1em',
                textTransform: 'uppercase',
                marginBottom: '6px',
              }"
            >
              PHOTO / SCREENSHOT FILE
            </label>

            <div class="upload-field">
              <label for="portfolio-thumbnail" class="upload-button">Browse Image</label>
              <input id="portfolio-thumbnail" type="file" accept="image/*" class="upload-input" @change="onThumbnailChange" />
              <span class="upload-file-name">{{ form.thumbnailFile ? form.thumbnailFile.name : 'PNG, JPG, WEBP • up to 2MB' }}</span>
            </div>

            <div v-if="thumbnailPreview" class="thumbnail-preview-wrap">
              <img :src="thumbnailPreview" alt="Selected preview" class="thumbnail-preview" />
            </div>
            <div v-else class="thumbnail-preview-placeholder">
              Choose an image to preview it here.
            </div>
          </div>

          <div :style="{ display: 'flex', gap: '10px', paddingTop: '8px', borderTop: `1px solid ${C.edge}` }">
            <button
              @click="showModal = false"
              :style="{
                flex: 1,
                padding: '11px',
                backgroundColor: 'transparent',
                color: C.dim,
                border: `1px solid ${C.edge}`,
                borderRadius: '3px',
                fontFamily: `'Space Grotesk', sans-serif`,
                fontSize: '13px',
                cursor: 'pointer',
              }"
            >
              Cancel
            </button>
            <button
              @click="handleSave"
              :style="{
                flex: 2,
                padding: '11px',
                backgroundColor: C.amber,
                color: '#fff',
                border: 'none',
                borderRadius: '3px',
                fontFamily: `'Space Grotesk', sans-serif`,
                fontSize: '13px',
                fontWeight: 700,
                cursor: 'pointer',
                letterSpacing: '0.03em',
              }"
            >
              {{ editing ? 'Save Changes' : 'Add Piece' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
input[type='text'],
input[type='url'],
input[type='month'],
select {
  width: 100%;
  background-color: #1c1c1f;
  color: #f4f4f5;
  border: 1px solid #2a2a2f;
  border-radius: 3px;
  font-family: 'Space Grotesk', sans-serif;
  font-size: 13px;
  padding: 9px 10px;
}
input:focus,
select:focus {
  border-color: #e8590c;
  outline: none;
}
.upload-field {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 10px;
}
.upload-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background-color: #e8590c;
  color: #fff;
  border: none;
  border-radius: 3px;
  padding: 9px 14px;
  font-family: 'Space Grotesk', sans-serif;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}
.upload-input {
  display: none;
}
.upload-file-name {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 12px;
  color: #8b8b93;
}
.thumbnail-preview-wrap {
  margin-top: 8px;
  border: 1px solid #2a2a2f;
  border-radius: 3px;
  overflow: hidden;
  background-color: #121214;
}
.thumbnail-preview {
  display: block;
  width: 100%;
  max-height: 220px;
  object-fit: cover;
}
.thumbnail-preview-placeholder {
  margin-top: 8px;
  border: 1px dashed #2a2a2f;
  border-radius: 3px;
  padding: 12px;
  font-family: 'Space Grotesk', sans-serif;
  font-size: 12px;
  color: #8b8b93;
}
input[type='checkbox'].check-toggle {
  appearance: none;
  -webkit-appearance: none;
  width: 18px;
  height: 18px;
  margin: 0;
  border: 1px solid #2a2a2f;
  border-radius: 4px;
  background-color: #1c1c1f;
  position: relative;
  cursor: pointer;
  transition: all 0.15s ease;
}
input[type='checkbox'].check-toggle:checked {
  background-color: #e8590c;
  border-color: #e8590c;
}
input[type='checkbox'].check-toggle:checked::after {
  content: '';
  position: absolute;
  left: 5px;
  top: 1px;
  width: 4px;
  height: 9px;
  border: solid #fff;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}
input::placeholder {
  color: #8b8b93;
}
</style>