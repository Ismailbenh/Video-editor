<script setup>
import { ref, reactive, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '../../Layouts/AdminLayout.vue'
import Icon from '../../Components/Icon.vue'
defineOptions({ layout: AdminLayout })

const props = defineProps({
  services: { type: Array, default: () => [] },
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

const emptyTiers = () => [
  { name: 'Basic', price: 0, turnaround: '5 days', features: [] },
  { name: 'Pro', price: 0, turnaround: '3 days', features: [] },
  { name: 'Rush', price: 0, turnaround: '24 hrs', features: [] },
]

const iconOptions = [
  { value: 'video', label: 'Video' },
  { value: 'device-mobile', label: 'Mobile / Reels' },
  { value: 'package', label: 'Package / Batch' },
  { value: 'scissors', label: 'Scissors / Clipping' },
  { value: 'photo', label: 'Photo' },
  { value: 'tool', label: 'Tool' },
]

// services comes straight from the database via props — Inertia refreshes
// this automatically after every store/update/delete request below.
const services = computed(() => props.services)

const showModal = ref(false)
const editing = ref(null)
const saving = ref(false)
const form = reactive({
  name: '',
  description: '',
  icon: 'video',
  active: true,
  tiers: emptyTiers(),
})

function openAdd() {
  editing.value = null
  Object.assign(form, {
    name: '',
    description: '',
    icon: 'video',
    active: true,
    tiers: JSON.parse(JSON.stringify(emptyTiers())),
  })
  showModal.value = true
}

function openEdit(svc) {
  editing.value = svc
  Object.assign(form, JSON.parse(JSON.stringify(svc)))
  showModal.value = true
}

function handleSave() {
  if (!form.name) return
  saving.value = true

  const payload = {
    name: form.name,
    description: form.description || '',
    icon: form.icon || 'video',
    active: form.active ?? true,
    tiers: form.tiers,
  }

  const onDone = () => {
    saving.value = false
    showModal.value = false
  }

  if (editing.value) {
    router.put(`/admin/services/${editing.value.id}`, payload, {
      preserveScroll: true,
      onFinish: onDone,
    })
  } else {
    router.post('/admin/services', payload, {
      preserveScroll: true,
      onFinish: onDone,
    })
  }
}

function toggleActive(id) {
  router.patch(`/admin/services/${id}/toggle`, {}, { preserveScroll: true })
}

function deleteService(id) {
  router.delete(`/admin/services/${id}`, { preserveScroll: true })
}

function updateTierPrice(tierIdx, price) {
  form.tiers[tierIdx] = { ...form.tiers[tierIdx], price: Number(price) }
}

function updateTierTurnaround(tierIdx, turnaround) {
  form.tiers[tierIdx] = { ...form.tiers[tierIdx], turnaround }
}

function rowHoverIn(e) {
  e.currentTarget.style.backgroundColor = C.raised
}
function rowHoverOut(e) {
  e.currentTarget.style.backgroundColor = 'transparent'
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
          ADMIN / SERVICES
        </div>
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
          Services
        </h1>
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
        + Add New Service
      </button>
    </div>

    <!-- Services table -->
    <div :style="{ backgroundColor: C.panel, border: `1px solid ${C.edge}`, borderRadius: '3px', overflow: 'hidden' }">
      <div
        :style="{
          display: 'grid',
          gridTemplateColumns: '40px 1fr 1fr 90px 90px 90px 80px 120px',
          gap: '12px',
          padding: '10px 16px',
          borderBottom: `1px solid ${C.edge}`,
          backgroundColor: C.bg,
        }"
      >
        <span
          v-for="(col, i) in ['', 'SERVICE', 'DESCRIPTION', 'BASIC', 'PRO', 'RUSH', 'STATUS', 'ACTIONS']"
          :key="i"
          :style="{
            fontFamily: `'JetBrains Mono', monospace`,
            fontSize: '9px',
            color: C.dim,
            letterSpacing: '0.1em',
            textTransform: 'uppercase',
          }"
        >
          {{ col }}
        </span>
      </div>

      <div
        v-for="svc in services"
        :key="svc.id"
        :style="{
          display: 'grid',
          gridTemplateColumns: '40px 1fr 1fr 90px 90px 90px 80px 120px',
          gap: '12px',
          padding: '14px 16px',
          borderBottom: `1px solid ${C.edge}`,
          alignItems: 'center',
          opacity: svc.active ? 1 : 0.45,
          transition: 'background 0.1s',
        }"
        @mouseenter="rowHoverIn"
        @mouseleave="rowHoverOut"
      >
        <span :style="{ display: 'flex', fontSize: '18px', color: svc.active ? C.amber : C.dim }"><Icon :name="svc.icon" size="18" /></span>
        <div>
          <div :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontSize: '13px', fontWeight: 600, color: C.bright }">
            {{ svc.name }}
          </div>
        </div>
        <span
          :style="{
            fontFamily: `'Space Grotesk', sans-serif`,
            fontSize: '12px',
            color: C.dim,
            overflow: 'hidden',
            textOverflow: 'ellipsis',
            display: '-webkit-box',
            WebkitLineClamp: 2,
            WebkitBoxOrient: 'vertical',
          }"
        >
          {{ svc.description }}
        </span>
        <span
          v-for="tier in svc.tiers"
          :key="tier.name"
          :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '12px', color: C.bright, fontWeight: 600 }"
        >
          ${{ tier.price }}
        </span>
        <div>
          <span
            :style="{
              display: 'inline-block',
              padding: '3px 8px',
              backgroundColor: svc.active ? 'rgba(34,197,94,0.1)' : 'rgba(139,139,147,0.1)',
              color: svc.active ? '#22C55E' : C.dim,
              border: `1px solid ${svc.active ? '#22C55E' : C.edge}`,
              borderRadius: '2px',
              fontFamily: `'JetBrains Mono', monospace`,
              fontSize: '9px',
              letterSpacing: '0.08em',
              textTransform: 'uppercase',
            }"
          >
            {{ svc.active ? 'Active' : 'Off' }}
          </span>
        </div>
        <div style="display: flex; gap: 6px">
          <button
            @click="openEdit(svc)"
            :style="{
              padding: '5px 10px',
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
            @click="toggleActive(svc.id)"
            :style="{
              padding: '5px 10px',
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
            {{ svc.active ? 'Off' : 'On' }}
          </button>
          <button
            @click="deleteService(svc.id)"
            title="Delete"
            style="
              display: flex;
              align-items: center;
              justify-content: center;
              padding: 5px 8px;
              background-color: transparent;
              color: rgba(239, 68, 68, 0.7);
              border: 1px solid rgba(239, 68, 68, 0.2);
              border-radius: 3px;
              cursor: pointer;
            "
          >
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 6 5 6 21 6" />
              <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
              <path d="M10 11v6" />
              <path d="M14 11v6" />
              <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
            </svg>
          </button>
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
          maxWidth: '560px',
          overflow: 'hidden',
          maxHeight: '90vh',
          overflowY: 'auto',
        }"
      >
        <div
          :style="{
            padding: '20px 24px',
            borderBottom: `1px solid ${C.edge}`,
            display: 'flex',
            justifyContent: 'space-between',
            alignItems: 'center',
            position: 'sticky',
            top: 0,
            backgroundColor: C.panel,
            zIndex: 1,
          }"
        >
          <h2 :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontSize: '18px', fontWeight: 700, color: C.bright, margin: 0 }">
            {{ editing ? 'Edit Service' : 'Add New Service' }}
          </h2>
          <button
            @click="showModal = false"
            title="Close"
            :style="{ display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'none', border: 'none', color: C.dim, cursor: 'pointer', padding: '4px' }"
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </button>
        </div>

        <div style="padding: 24px">
          <div style="margin-bottom: 16px">
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
              SERVICE NAME *
            </label>
            <input type="text" placeholder="e.g. YouTube Video Editing" v-model="form.name" />
          </div>

          <div style="margin-bottom: 16px">
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
              DESCRIPTION
            </label>
            <textarea
              rows="3"
              placeholder="Short description shown on the public Services page..."
              v-model="form.description"
              style="resize: vertical"
            />
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px">
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
                ICON
              </label>
              <div style="display: flex; align-items: center; gap: 8px">
                <span
                  :style="{
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    width: '34px',
                    height: '34px',
                    flexShrink: 0,
                    color: C.amber,
                    backgroundColor: 'rgba(232,89,12,0.1)',
                    border: `1px solid rgba(232,89,12,0.2)`,
                    borderRadius: '3px',
                  }"
                >
                  <Icon :name="form.icon" size="16" />
                </span>
                <select v-model="form.icon" style="flex: 1">
                  <option v-for="opt in iconOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                </select>
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
                STATUS
              </label>
              <select
                :value="form.active ? 'active' : 'inactive'"
                @change="form.active = $event.target.value === 'active'"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>

          <div style="margin-bottom: 24px">
            <div
              :style="{
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '10px',
                color: C.dim,
                letterSpacing: '0.1em',
                textTransform: 'uppercase',
                marginBottom: '12px',
              }"
            >
              PRICING TIERS
            </div>
            <div
              v-for="(tier, i) in form.tiers"
              :key="tier.name"
              style="display: grid; grid-template-columns: 80px 1fr 1fr; gap: 10px; margin-bottom: 10px; align-items: center"
            >
              <span
                :style="{
                  fontFamily: `'JetBrains Mono', monospace`,
                  fontSize: '11px',
                  color: tier.name === 'Pro' ? C.amber : C.dim,
                  letterSpacing: '0.08em',
                }"
              >
                {{ tier.name.toUpperCase() }}
              </span>
              <div>
                <label
                  :style="{
                    display: 'block',
                    fontFamily: `'JetBrains Mono', monospace`,
                    fontSize: '9px',
                    color: C.ledge,
                    marginBottom: '4px',
                    letterSpacing: '0.08em',
                  }"
                >
                  PRICE ($)
                </label>
                <input
                  type="number"
                  placeholder="0"
                  :value="tier.price || ''"
                  @input="updateTierPrice(i, $event.target.value)"
                />
              </div>
              <div>
                <label
                  :style="{
                    display: 'block',
                    fontFamily: `'JetBrains Mono', monospace`,
                    fontSize: '9px',
                    color: C.ledge,
                    marginBottom: '4px',
                    letterSpacing: '0.08em',
                  }"
                >
                  TURNAROUND
                </label>
                <input
                  type="text"
                  placeholder="3 days"
                  :value="tier.turnaround"
                  @input="updateTierTurnaround(i, $event.target.value)"
                />
              </div>
            </div>
          </div>

          <div style="display: flex; gap: 10px">
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
              {{ editing ? 'Save Changes' : 'Add Service' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
input[type='text'],
input[type='number'],
textarea,
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
textarea:focus,
select:focus {
  border-color: #e8590c;
  outline: none;
}
input::placeholder,
textarea::placeholder {
  color: #8b8b93;
}
</style>