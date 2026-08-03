<script setup>

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

const statusColor = {
  New: '#3B82F6',
  'In Progress': '#F59E0B',
  Review: '#A855F7',
  Delivered: '#22C55E',
  Paid: '#8B8B93',
}

// UI-only placeholder data - swap for real orders once wired to the backend
const orders = [
  { status: 'New', price: 480 },
  { status: 'New', price: 120 },
  { status: 'In Progress', price: 80 },
  { status: 'In Progress', price: 480 },
  { status: 'Review', price: 110 },
  { status: 'Delivered', price: 750 },
  { status: 'Paid', price: 750 },
  { status: 'Paid', price: 45 },
  { status: 'Paid', price: 220 },
]

const activity = [
  { type: 'order', text: 'New order from Marcus Webb', sub: 'Batch / Mass Editing - Pro - $480', time: '2h ago', dot: '#3B82F6' },
  { type: 'delivered', text: 'Project delivered: Sofia Reyes', sub: 'Instagram Reels Editing - Pro', time: 'Yesterday', dot: '#22C55E' },
  { type: 'payment', text: 'Payment received: $750', sub: 'Priya Nair - Batch Rush', time: 'Yesterday', dot: '#E8590C' },
  { type: 'order', text: 'New order from Tom Vasquez', sub: 'YouTube Video Editing - Basic - $120', time: '2d ago', dot: '#3B82F6' },
  { type: 'review', text: 'Revision requested: Derek Munn', sub: 'Podcast Clipping - Pro', time: '2d ago', dot: '#A855F7' },
  { type: 'payment', text: 'Payment received: $45', sub: 'Aaron Liu - Reels Basic', time: '3d ago', dot: '#E8590C' },
]

const newOrders = orders.filter(o => o.status === 'New').length
const active = orders.filter(o => o.status === 'In Progress' || o.status === 'Review').length
const revenue = orders.filter(o => o.status === 'Paid').reduce((acc, o) => acc + o.price, 0)

const stats = [
  { label: 'NEW INQUIRIES', value: String(newOrders), sub: 'this week', dot: '#3B82F6' },
  { label: 'ACTIVE PROJECTS', value: String(active), sub: 'in progress / review', dot: '#F59E0B' },
  { label: 'COMPLETED', value: '12', sub: 'this month', dot: '#22C55E' },
  { label: 'REVENUE', value: `$${revenue.toLocaleString()}`, sub: 'this month', dot: '#E8590C' },
]

const weeklyData = [4, 7, 5, 9, 12, 8, 11]
const maxVal = Math.max(...weeklyData)
const weekdayLabels = ['M', 'T', 'W', 'T', 'F', 'S', 'S']

const pipelineStatuses = ['New', 'In Progress', 'Review', 'Delivered', 'Paid']

function pipelineCount(status) {
  return orders.filter(o => o.status === status).length
}

const today = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })

function rowHoverIn(e) {
  e.currentTarget.style.backgroundColor = C.raised
}
function rowHoverOut(e) {
  e.currentTarget.style.backgroundColor = 'transparent'
}
</script>

<template>
  <div style="padding: 32px; min-height: 100%">
    <!-- Page header -->
    <div style="margin-bottom: 32px">
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
        ADMIN / OVERVIEW
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
        Overview
      </h1>
    </div>

    <!-- Stat cards -->
    <div
      style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        margin-bottom: 28px;
      "
    >
      <div
        v-for="stat in stats"
        :key="stat.label"
        :style="{
          backgroundColor: C.panel,
          border: `1px solid ${C.edge}`,
          borderRadius: '3px',
          padding: '20px',
          position: 'relative',
          overflow: 'hidden',
        }"
      >
        <div
          :style="{
            position: 'absolute',
            top: 0,
            left: 0,
            width: '3px',
            height: '100%',
            backgroundColor: stat.dot,
          }"
        />
        <div style="padding-left: 8px">
          <div
            :style="{
              fontFamily: `'JetBrains Mono', monospace`,
              fontSize: '9px',
              color: C.dim,
              letterSpacing: '0.12em',
              textTransform: 'uppercase',
              marginBottom: '10px',
            }"
          >
            {{ stat.label }}
          </div>
          <div
            :style="{
              fontFamily: `'JetBrains Mono', monospace`,
              fontSize: '32px',
              fontWeight: 600,
              color: C.bright,
              letterSpacing: '-0.02em',
              marginBottom: '4px',
              lineHeight: 1,
            }"
          >
            {{ stat.value }}
          </div>
          <div
            :style="{
              fontFamily: `'Space Grotesk', sans-serif`,
              fontSize: '12px',
              color: C.dim,
            }"
          >
            {{ stat.sub }}
          </div>
        </div>
      </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 360px; gap: 20px; align-items: start">
      <!-- Activity feed -->
      <div
        :style="{
          backgroundColor: C.panel,
          border: `1px solid ${C.edge}`,
          borderRadius: '3px',
          overflow: 'hidden',
        }"
      >
        <div
          :style="{
            padding: '16px 20px',
            borderBottom: `1px solid ${C.edge}`,
            display: 'flex',
            justifyContent: 'space-between',
            alignItems: 'center',
          }"
        >
          <span
            :style="{
              fontFamily: `'JetBrains Mono', monospace`,
              fontSize: '10px',
              color: C.dim,
              letterSpacing: '0.12em',
              textTransform: 'uppercase',
            }"
          >
            RECENT ACTIVITY
          </span>
          <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '10px', color: C.ledge }">
            {{ today }}
          </span>
        </div>

        <div
          v-for="(item, i) in activity"
          :key="i"
          :style="{
            display: 'flex',
            alignItems: 'flex-start',
            gap: '14px',
            padding: '14px 20px',
            borderBottom: i < activity.length - 1 ? `1px solid ${C.edge}` : 'none',
            transition: 'background 0.15s',
          }"
          @mouseenter="rowHoverIn"
          @mouseleave="rowHoverOut"
        >
          <div
            :style="{
              width: '8px',
              height: '8px',
              borderRadius: '50%',
              backgroundColor: item.dot,
              flexShrink: 0,
              marginTop: '5px',
            }"
          />
          <div style="flex: 1; min-width: 0">
            <div
              :style="{
                fontFamily: `'Space Grotesk', sans-serif`,
                fontSize: '13px',
                fontWeight: 500,
                color: C.bright,
                marginBottom: '3px',
              }"
            >
              {{ item.text }}
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
              {{ item.sub }}
            </div>
          </div>
          <span
            :style="{
              fontFamily: `'JetBrains Mono', monospace`,
              fontSize: '10px',
              color: C.ledge,
              flexShrink: 0,
              marginTop: '2px',
            }"
          >
            {{ item.time }}
          </span>
        </div>
      </div>

      <!-- Right column: weekly chart + order status -->
      <div style="display: flex; flex-direction: column; gap: 16px">
        <!-- Mini bar chart -->
        <div
          :style="{
            backgroundColor: C.panel,
            border: `1px solid ${C.edge}`,
            borderRadius: '3px',
            padding: '20px',
          }"
        >
          <div
            :style="{
              fontFamily: `'JetBrains Mono', monospace`,
              fontSize: '10px',
              color: C.dim,
              letterSpacing: '0.12em',
              textTransform: 'uppercase',
              marginBottom: '16px',
            }"
          >
            DELIVERIES / WEEK
          </div>
          <div style="display: flex; align-items: flex-end; gap: 6px; height: 80px">
            <div
              v-for="(v, i) in weeklyData"
              :key="i"
              style="
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 4px;
                height: 100%;
                justify-content: flex-end;
              "
            >
              <div
                :style="{
                  width: '100%',
                  height: `${(v / maxVal) * 72}px`,
                  backgroundColor: i === weeklyData.length - 1 ? C.amber : C.ledge,
                  borderRadius: '2px 2px 0 0',
                  transition: 'height 0.3s',
                }"
              />
              <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '8px', color: C.ledge }">
                {{ weekdayLabels[i] }}
              </span>
            </div>
          </div>
        </div>

        <!-- Order status breakdown -->
        <div
          :style="{
            backgroundColor: C.panel,
            border: `1px solid ${C.edge}`,
            borderRadius: '3px',
            padding: '20px',
          }"
        >
          <div
            :style="{
              fontFamily: `'JetBrains Mono', monospace`,
              fontSize: '10px',
              color: C.dim,
              letterSpacing: '0.12em',
              textTransform: 'uppercase',
              marginBottom: '14px',
            }"
          >
            ORDER PIPELINE
          </div>
          <div
            v-for="status in pipelineStatuses"
            :key="status"
            :style="{
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              padding: '8px 0',
              borderBottom: `1px solid ${C.edge}`,
            }"
          >
            <div style="display: flex; align-items: center; gap: 8px">
              <div
                :style="{
                  width: '6px',
                  height: '6px',
                  borderRadius: '50%',
                  backgroundColor: statusColor[status],
                  flexShrink: 0,
                }"
              />
              <span :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontSize: '12px', color: C.dim }">
                {{ status }}
              </span>
            </div>
            <span
              :style="{
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '12px',
                color: C.bright,
                fontWeight: 600,
              }"
            >
              {{ String(pipelineCount(status)).padStart(2, '0') }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>