<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '../../Layouts/AdminLayout.vue'
defineOptions({ layout: AdminLayout })

const props = defineProps({
  clients: { type: Array, default: () => [] },
  orders: { type: Array, default: () => [] },
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

const statusConfig = {
  Ongoing: { color: '#22C55E', bg: 'rgba(34,197,94,0.1)' },
  Finished: { color: '#8B8B93', bg: 'rgba(139,139,147,0.1)' },
}

// Reactive wrappers - Inertia reuses this component instance across visits,
// so plain `const clients = props.clients` would go stale after every
// accept/status-change. computed() keeps both in sync with fresh data.
const clients = computed(() => props.clients)
const orders = computed(() => props.orders)

const search = ref('')
const selected = ref(null)
const note = ref('')

const filtered = computed(() =>
  clients.value.filter(c => {
    const q = search.value.toLowerCase()
    return q === '' || c.name.toLowerCase().includes(q) || c.email.toLowerCase().includes(q)
  })
)

const clientOrders = computed(() =>
  selected.value ? orders.value.filter(o => o.clientEmail === selected.value.email) : []
)

function selectClient(client) {
  if (selected.value?.id === client.id) {
    selected.value = null
  } else {
    selected.value = client
    note.value = ''
  }
}

function rowHoverIn(e, client) {
  if (selected.value?.id !== client.id) e.currentTarget.style.backgroundColor = C.raised
}
function rowHoverOut(e, client) {
  if (selected.value?.id !== client.id) e.currentTarget.style.backgroundColor = 'transparent'
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
        ADMIN / CLIENTS
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
          Clients
        </h1>
        <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '11px', color: C.dim }">
          {{ String(clients.length).padStart(2, '0') }} TOTAL
        </span>
      </div>
    </div>

    <div style="max-width: 400px; margin-bottom: 20px">
      <div style="position: relative">
        <input
          type="text"
          placeholder="Search clients..."
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
            gridTemplateColumns: '1fr 1fr 80px 100px 90px 110px',
            gap: '12px',
            padding: '10px 16px',
            borderBottom: `1px solid ${C.edge}`,
            backgroundColor: C.bg,
          }"
        >
          <span
            v-for="col in ['CLIENT', 'EMAIL', 'JOBS', 'TOTAL PAID', 'STATUS', 'LAST ACTIVE']"
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
          v-for="client in filtered"
          :key="client.id"
          @click="selectClient(client)"
          @mouseenter="rowHoverIn($event, client)"
          @mouseleave="rowHoverOut($event, client)"
          :style="{
            display: 'grid',
            gridTemplateColumns: '1fr 1fr 80px 100px 90px 110px',
            gap: '12px',
            padding: '13px 16px',
            borderBottom: `1px solid ${C.edge}`,
            backgroundColor: selected?.id === client.id ? 'rgba(232,89,12,0.06)' : 'transparent',
            cursor: 'pointer',
            alignItems: 'center',
            transition: 'background 0.1s',
          }"
        >
          <div>
            <div :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontSize: '13px', fontWeight: 500, color: C.bright }">
              {{ client.name }}
            </div>
          </div>
          <span
            :style="{
              fontFamily: `'JetBrains Mono', monospace`,
              fontSize: '10px',
              color: C.dim,
              overflow: 'hidden',
              textOverflow: 'ellipsis',
              whiteSpace: 'nowrap',
            }"
          >
            {{ client.email }}
          </span>
          <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '12px', color: C.bright, fontWeight: 600 }">
            {{ String(client.jobCount).padStart(2, '0') }}
          </span>
          <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '13px', color: C.bright, fontWeight: 600 }">
            ${{ client.totalPaid.toLocaleString() }}
          </span>
          <span
            :style="{
              display: 'inline-flex',
              alignItems: 'center',
              gap: '5px',
              padding: '3px 8px',
              backgroundColor: statusConfig[client.status].bg,
              color: statusConfig[client.status].color,
              border: `1px solid ${statusConfig[client.status].color}`,
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
                backgroundColor: statusConfig[client.status].color,
              }"
            />
            {{ client.status }}
          </span>
          <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '10px', color: C.dim }">
            {{ client.lastActive }}
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
            <h3
              :style="{
                fontFamily: `'Space Grotesk', sans-serif`,
                fontSize: '16px',
                fontWeight: 700,
                color: C.bright,
                margin: 0,
                letterSpacing: '-0.01em',
              }"
            >
              {{ selected.name }}
            </h3>
            <div :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '10px', color: C.dim, marginTop: '2px' }">
              {{ selected.email }}
            </div>
          </div>
          <button
            @click="selected = null"
            :style="{ background: 'none', border: 'none', color: C.dim, cursor: 'pointer', fontSize: '14px', padding: '4px' }"
          >
            Close
          </button>
        </div>

        <div style="padding: 16px">
          <!-- Summary stats -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px">
            <div
              v-for="s in [
                { label: 'JOBS', value: String(selected.jobCount) },
                { label: 'TOTAL PAID', value: `$${selected.totalPaid.toLocaleString()}` },
                { label: 'STATUS', value: selected.status },
                { label: 'LAST ACTIVE', value: selected.lastActive },
              ]"
              :key="s.label"
              :style="{ backgroundColor: C.bg, borderRadius: '3px', padding: '12px', border: `1px solid ${C.edge}` }"
            >
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
                {{ s.label }}
              </div>
              <div :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '16px', fontWeight: 600, color: C.bright }">
                {{ s.value }}
              </div>
            </div>
          </div>

          <!-- Order history -->
          <div style="margin-bottom: 16px">
            <div
              :style="{
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '9px',
                color: C.dim,
                letterSpacing: '0.1em',
                textTransform: 'uppercase',
                marginBottom: '10px',
              }"
            >
              ORDER HISTORY
            </div>
            <p v-if="clientOrders.length === 0" :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontSize: '12px', color: C.dim }">
              No orders found for this client.
            </p>
            <div
              v-for="order in clientOrders"
              :key="order.id"
              :style="{
                display: 'flex',
                justifyContent: 'space-between',
                alignItems: 'center',
                padding: '9px 0',
                borderBottom: `1px solid ${C.edge}`,
              }"
            >
              <div>
                <div :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '10px', color: C.amber }">
                  {{ order.id }}
                </div>
                <div
                  :style="{
                    fontFamily: `'Space Grotesk', sans-serif`,
                    fontSize: '12px',
                    color: C.dim,
                    marginTop: '2px',
                    overflow: 'hidden',
                    textOverflow: 'ellipsis',
                    whiteSpace: 'nowrap',
                    maxWidth: '180px',
                  }"
                >
                  {{ order.service }} - {{ order.tier }}
                </div>
              </div>
              <div style="text-align: right">
                <div :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '13px', color: C.bright, fontWeight: 600 }">
                  ${{ order.price }}
                </div>
                <div :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '9px', color: C.dim }">
                  {{ order.status }}
                </div>
              </div>
            </div>
          </div>

          <!-- Private notes -->
          <div>
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
              PRIVATE NOTES
            </div>
            <textarea
              rows="3"
              placeholder="Internal notes about this client..."
              v-model="note"
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