<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Icon from '../Components/Icon.vue'

const C = {
  bg: '#121214',
  panel: '#1C1C1F',
  amber: '#E8590C',
  bright: '#F4F4F5',
  dim: '#8B8B93',
  edge: '#2A2A2F',
}

const navItems = [
  { to: '/admin/overview', label: 'Overview', icon: 'chart-bar' },
  { to: '/admin/orders', label: 'Orders', icon: 'receipt' },
  { to: '/admin/clients', label: 'Clients', icon: 'users' },
  { to: '/admin/services', label: 'Services', icon: 'tool' },
  { to: '/admin/portfolio', label: 'Portfolio', icon: 'photo' },
]

const page = usePage()
const currentPath = computed(() => page.props.ziggy?.location ?? page.url ?? '')
const authUser = computed(() => page.props.auth?.user ?? null)

function isActive(to) {
  const path = currentPath.value
  return path === to || path.endsWith(to) || (to === '/admin/overview' && path.endsWith('/admin'))
}
</script>

<template>
  <div :style="{ display: 'flex', height: '100vh', overflow: 'hidden', backgroundColor: C.bg }">
    <aside
      :style="{
        width: '220px',
        flexShrink: 0,
        backgroundColor: C.panel,
        borderRight: `1px solid ${C.edge}`,
        display: 'flex',
        flexDirection: 'column',
        overflow: 'hidden',
      }"
    >
      <div :style="{ padding: '20px 16px', borderBottom: `1px solid ${C.edge}` }">
        <Link href="/home" style="text-decoration: none; display: flex; align-items: center; gap: 10px">
          <div>
            <div :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontWeight: 600, fontSize: '13px', color: C.bright, letterSpacing: '0.05em' }">
              FRAMECUT
            </div>
            <div :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '9px', color: C.amber, letterSpacing: '0.1em' }">
              ADMIN
            </div>
          </div>
        </Link>
      </div>

      <nav style="flex: 1; padding: 12px 8px; overflow-y: auto">
        <Link
          v-for="item in navItems"
          :key="item.to"
          :href="item.to"
          :style="{
            display: 'flex',
            alignItems: 'center',
            gap: '10px',
            padding: '9px 12px',
            marginBottom: '2px',
            borderRadius: '3px',
            textDecoration: 'none',
            backgroundColor: isActive(item.to) ? 'rgba(232,89,12,0.1)' : 'transparent',
            borderLeft: isActive(item.to) ? `2px solid ${C.amber}` : '2px solid transparent',
            transition: 'background 0.15s',
          }"
        >
          <span :style="{ display: 'flex', color: isActive(item.to) ? C.amber : C.dim, width: '18px', justifyContent: 'center' }">
            <Icon :name="item.icon" size="16" />
          </span>
          <span
            :style="{
              fontFamily: `'Space Grotesk', sans-serif`,
              fontSize: '13px',
              fontWeight: isActive(item.to) ? 600 : 400,
              color: isActive(item.to) ? C.bright : C.dim,
              letterSpacing: '0.02em',
            }"
          >
            {{ item.label }}
          </span>
        </Link>
      </nav>

      <div :style="{ borderTop: `1px solid ${C.edge}`, padding: '14px 16px' }">
        <div style="margin-bottom: 10px">
          <div :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontSize: '12px', color: C.bright, fontWeight: 500 }">
            {{ authUser?.name ?? 'Alex Novo' }}
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
            {{ authUser?.email ?? 'admin@framecut.studio' }}
          </div>
        </div>
        <Link
          href="/logout"
          method="post"
          as="button"
          :style="{
            width: '100%',
            padding: '7px 12px',
            background: 'none',
            cursor: 'pointer',
            border: `1px solid ${C.edge}`,
            borderRadius: '3px',
            fontFamily: `'Space Grotesk', sans-serif`,
            fontSize: '12px',
            color: C.dim,
            textAlign: 'left',
            transition: 'border-color 0.15s, color 0.15s',
          }"
        >
          Log out
        </Link>
      </div>
    </aside>

    <main style="flex: 1; overflow-y: auto">
      <slot />
    </main>
  </div>
</template>