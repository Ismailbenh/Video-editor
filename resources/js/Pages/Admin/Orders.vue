<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '../../Layouts/AdminLayout.vue'
defineOptions({ layout: AdminLayout })
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

const props = defineProps({
  orders: { type: Array, default: () => [] },
})

// Reactive wrapper - Inertia reuses this component instance across visits,
// so a plain `const orders = props.orders` would go stale after every
// create/update. computed() keeps it in sync with fresh backend data.
const orders = computed(() => props.orders)

const statusConfig = {
  New: { color: '#3B82F6', bg: 'rgba(59,130,246,0.12)' },
  'In Progress': { color: '#F59E0B', bg: 'rgba(245,158,11,0.12)' },
  Review: { color: '#A855F7', bg: 'rgba(168,85,247,0.12)' },
  Delivered: { color: '#22C55E', bg: 'rgba(34,197,94,0.12)' },
  Paid: { color: '#8B8B93', bg: 'rgba(139,139,147,0.12)' },
}

const tierColor = {
  Basic: '#8B8B93',
  Pro: '#E8590C',
  Rush: '#EF4444',
}

const allStatuses = ['All', 'New', 'In Progress', 'Review', 'Delivered', 'Paid']

const search = ref('')
const statusFilter = ref('All')
const selected = ref(null)
const notes = ref('')

function orderCode(order) {
  return `ORD-${String(order.id).padStart(3, '0')}`
}

const filtered = computed(() =>
  props.orders.filter(o => {
    const q = search.value.toLowerCase()
    const matchSearch =
      q === '' ||
      o.client_name.toLowerCase().includes(q) ||
      orderCode(o).toLowerCase().includes(q) ||
      o.service_name.toLowerCase().includes(q)
    const matchStatus = statusFilter.value === 'All' || o.status === statusFilter.value
    return matchSearch && matchStatus
  })
)

function selectOrder(order) {
  if (selected.value?.id === order.id) {
    selected.value = null
  } else {
    selected.value = order
    notes.value = order.notes || ''
  }
}

function acceptOrder(id) {
  router.patch(route('admin.orders.accept', id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      if (selected.value?.id === id) selected.value = { ...selected.value, status: 'In Progress' }
    },
  })
}

function saveNotes() {
  if (!selected.value) return
  router.patch(route('admin.orders.update-notes', selected.value.id), { notes: notes.value }, {
    preserveScroll: true,
  })
}

function rowHoverIn(e, order) {
  if (selected.value?.id !== order.id) e.currentTarget.style.backgroundColor = C.raised
}
function rowHoverOut(e, order) {
  if (selected.value?.id !== order.id) e.currentTarget.style.backgroundColor = 'transparent'
}
</script>

<template>
  <div style="padding: 32px; min-height: 100%">
    <div style="margin-bottom: 24px">
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
        ADMIN / ORDERS
      </div>
      <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px">
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
          Orders
        </h1>
        <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '11px', color: C.dim }">
          {{ String(orders.length).padStart(2, '0') }} TOTAL
        </span>
      </div>
    </div>

    <!-- Filters -->
    <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap">
      <div style="position: relative; flex: 1; min-width: 200px">
        <input
          type="text"
          placeholder="Search orders, clients..."
          v-model="search"
          style="padding-left: 32px"
        />
        <span
          :style="{
            position: 'absolute',
            left: '10px',
            top: '50%',
            transform: 'translateY(-50%)',
            color: C.dim,
            fontSize: '12px',
            pointerEvents: 'none',
          }"
        ></span>
      </div>
      <div style="display: flex; gap: 4px; flex-wrap: wrap">
        <button
          v-for="s in allStatuses"
          :key="s"
          @click="statusFilter = s"
          :style="{
            padding: '7px 12px',
            backgroundColor: statusFilter === s ? C.amber : 'transparent',
            color: statusFilter === s ? '#fff' : C.dim,
            border: `1px solid ${statusFilter === s ? C.amber : C.edge}`,
            borderRadius: '3px',
            fontFamily: `'Space Grotesk', sans-serif`,
            fontSize: '12px',
            fontWeight: statusFilter === s ? 600 : 400,
            cursor: 'pointer',
            transition: 'all 0.15s',
            whiteSpace: 'nowrap',
          }"
        >
          {{ s }}
        </button>
      </div>
    </div>

    <div
      :style="{
        display: 'grid',
        gridTemplateColumns: selected ? '1fr 380px' : '1fr',
        gap: '16px',
        alignItems: 'start',
      }"
    >
      <!-- Table -->
      <div :style="{ backgroundColor: C.panel, border: `1px solid ${C.edge}`, borderRadius: '3px', overflow: 'hidden' }">
        <div
          :style="{
            display: 'grid',
            gridTemplateColumns: '80px 1fr 1fr 80px 90px 100px',
            gap: '12px',
            padding: '10px 16px',
            borderBottom: `1px solid ${C.edge}`,
            backgroundColor: C.bg,
          }"
        >
          <span
            v-for="col in ['ID', 'CLIENT', 'SERVICE', 'PRICE', 'STATUS', 'DEADLINE']"
            :key="col"
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
          v-if="filtered.length === 0"
          :style="{
            padding: '40px',
            textAlign: 'center',
            color: C.dim,
            fontFamily: `'Space Grotesk', sans-serif`,
            fontSize: '14px',
          }"
        >
          No orders match your filters.
        </div>

        <div
          v-for="order in filtered"
          :key="order.id"
          @click="selectOrder(order)"
          @mouseenter="rowHoverIn($event, order)"
          @mouseleave="rowHoverOut($event, order)"
          :style="{
            display: 'grid',
            gridTemplateColumns: '80px 1fr 1fr 80px 90px 100px',
            gap: '12px',
            padding: '12px 16px',
            borderBottom: `1px solid ${C.edge}`,
            backgroundColor: selected?.id === order.id ? 'rgba(232,89,12,0.06)' : 'transparent',
            cursor: 'pointer',
            transition: 'background 0.1s',
            alignItems: 'center',
          }"
        >
          <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '10px', color: C.amber }">
            {{ orderCode(order) }}
          </span>
          <div>
            <div :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontSize: '13px', fontWeight: 500, color: C.bright }">
              {{ order.client_name }}
            </div>
            <div
              :style="{
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '10px',
                color: C.dim,
                overflow: 'hidden',
                textOverflow: 'ellipsis',
                whiteSpace: 'nowrap',
              }"
            >
              {{ order.client_email }}
            </div>
          </div>
          <div>
            <div
              :style="{
                fontFamily: `'Space Grotesk', sans-serif`,
                fontSize: '12px',
                color: C.bright,
                overflow: 'hidden',
                textOverflow: 'ellipsis',
                whiteSpace: 'nowrap',
              }"
            >
              {{ order.service_name }}
            </div>
            <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '9px', color: tierColor[order.tier_name], letterSpacing: '0.08em' }">
              {{ order.tier_name.toUpperCase() }}
            </span>
          </div>
          <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '13px', color: C.bright, fontWeight: 600 }">
            ${{ order.price }}
          </span>
          <span
            :style="{
              display: 'inline-flex',
              alignItems: 'center',
              gap: '5px',
              padding: '3px 8px',
              backgroundColor: statusConfig[order.status].bg,
              color: statusConfig[order.status].color,
              border: `1px solid ${statusConfig[order.status].color}`,
              borderRadius: '2px',
              fontFamily: `'JetBrains Mono', monospace`,
              fontSize: '9px',
              letterSpacing: '0.08em',
              textTransform: 'uppercase',
              whiteSpace: 'nowrap',
              opacity: 0.9,
            }"
          >
            <span
              :style="{
                width: '5px',
                height: '5px',
                borderRadius: '50%',
                backgroundColor: statusConfig[order.status].color,
                flexShrink: 0,
              }"
            />
            {{ order.status }}
          </span>
          <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '10px', color: C.dim }">
            {{ order.deadline }}
          </span>
        </div>
      </div>

      <!-- Detail panel -->
      <div
        v-if="selected"
        :style="{
          backgroundColor: C.panel,
          border: `1px solid ${C.edge}`,
          borderRadius: '3px',
          overflow: 'hidden',
          position: 'sticky',
          top: '80px',
        }"
      >
        <div
          :style="{
            padding: '16px',
            borderBottom: `1px solid ${C.edge}`,
            display: 'flex',
            justifyContent: 'space-between',
            alignItems: 'center',
          }"
        >
          <div>
            <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '10px', color: C.amber }">
              {{ orderCode(selected) }}
            </span>
            <h3
              :style="{
                fontFamily: `'Space Grotesk', sans-serif`,
                fontSize: '15px',
                fontWeight: 700,
                color: C.bright,
                margin: '4px 0 0',
                letterSpacing: '-0.01em',
              }"
            >
              {{ selected.client_name }}
            </h3>
          </div>
          <button
            @click="selected = null"
            :style="{ background: 'none', border: 'none', color: C.dim, cursor: 'pointer', fontSize: '14px', padding: '4px' }"
          >
            Close
          </button>
        </div>

        <div style="padding: 16px">
          <!-- Accept - only for orders that haven't been accepted into a client yet -->
          <button
            v-if="selected.status === 'New'"
            @click="acceptOrder(selected.id)"
            :style="{
              width: '100%',
              padding: '10px',
              marginBottom: '16px',
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
            Accept Order
          </button>

          <!-- Status - read-only badge; the only status action here is Accept above -->
          <div style="margin-bottom: 16px">
            <div
              :style="{
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '9px',
                color: C.dim,
                letterSpacing: '0.1em',
                textTransform: 'uppercase',
                marginBottom: '8px',
              }"
            >
              STATUS
            </div>
            <span
              :style="{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '5px',
                padding: '4px 10px',
                backgroundColor: statusConfig[selected.status].bg,
                color: statusConfig[selected.status].color,
                border: `1px solid ${statusConfig[selected.status].color}`,
                borderRadius: '2px',
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '9px',
                letterSpacing: '0.06em',
                textTransform: 'uppercase',
              }"
            >
              {{ selected.status }}
            </span>
          </div>

          <div
            v-for="row in [
              { label: 'SERVICE', value: selected.service_name },
              { label: 'TIER', value: selected.tier_name },
              { label: 'PRICE', value: `$${selected.price}` },
              { label: 'DEADLINE', value: selected.deadline },
              { label: 'ORDERED', value: selected.created_at },
              { label: 'EMAIL', value: selected.client_email },
            ]"
            :key="row.label"
            :style="{
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'flex-start',
              padding: '8px 0',
              borderBottom: `1px solid ${C.edge}`,
            }"
          >
            <span
              :style="{
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '9px',
                color: C.dim,
                letterSpacing: '0.1em',
                textTransform: 'uppercase',
                flexShrink: 0,
              }"
            >
              {{ row.label }}
            </span>
            <span :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontSize: '12px', color: C.bright, textAlign: 'right', marginLeft: '12px' }">
              {{ row.value }}
            </span>
          </div>

          <div v-if="selected.file_link" :style="{ padding: '8px 0', borderBottom: `1px solid ${C.edge}` }">
            <div
              :style="{
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '9px',
                color: C.dim,
                letterSpacing: '0.1em',
                textTransform: 'uppercase',
                marginBottom: '4px',
              }"
            >
              FILES
            </div>
            <a
              :href="selected.file_link"
              target="_blank"
              rel="noopener noreferrer"
              :style="{
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '10px',
                color: C.amber,
                textDecoration: 'none',
                display: 'block',
                overflow: 'hidden',
                textOverflow: 'ellipsis',
                whiteSpace: 'nowrap',
              }"
            >
              {{ selected.file_link }}
            </a>
          </div>

          <div :style="{ padding: '8px 0', borderBottom: `1px solid ${C.edge}` }">
            <div
              :style="{
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '9px',
                color: C.dim,
                letterSpacing: '0.1em',
                textTransform: 'uppercase',
                marginBottom: '6px',
              }"
            >
              DESCRIPTION
            </div>
            <p :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontSize: '12px', color: C.dim, lineHeight: '1.6', margin: 0 }">
              {{ selected.description }}
            </p>
          </div>

          <div style="margin-top: 16px">
            <div
              :style="{
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '9px',
                color: C.dim,
                letterSpacing: '0.1em',
                textTransform: 'uppercase',
                marginBottom: '6px',
              }"
            >
              INTERNAL NOTES
            </div>
            <textarea
              rows="3"
              placeholder="Private notes (not visible to client)..."
              v-model="notes"
              @blur="saveNotes"
              style="resize: vertical; font-size: 12px; min-height: 70px"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
input[type='text'],
textarea {
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
textarea:focus {
  border-color: #e8590c;
  outline: none;
}
input::placeholder,
textarea::placeholder {
  color: #8b8b93;
}
</style>