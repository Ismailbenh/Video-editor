<script setup>
defineProps({
  name: { type: String, default: '' },
  size: { type: [String, Number], default: '1em' },
})

// Tabler-style stroke icons. Keys are what gets stored in the DB
// (nav item icon / service icon) and referenced from templates as
// <Icon :name="svc.icon" />
const paths = {
  // --- admin sidebar nav ---
  'chart-bar': `
    <path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v7a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
    <path d="M15 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v11a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
    <path d="M9 3m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v16a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
  `,
  receipt: `
    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" />
    <path d="M14 8h-4" />
    <path d="M14 12h-4" />
  `,
  users: `
    <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
  `,
  tool: `
    <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" />
  `,
  photo: `
    <path d="M15 8h.01" />
    <path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" />
    <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
    <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" />
  `,

  // --- service type icons ---
  video: `
    <path d="M15 10l4.553 -2.276a1 1 0 0 1 1.447 .894v6.764a1 1 0 0 1 -1.447 .894l-4.553 -2.276v-4z" />
    <path d="M3 6m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />
  `,
  'device-mobile': `
    <path d="M6 5a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2v-14z" />
    <path d="M11 4h2" />
    <path d="M12 17v.01" />
  `,
  package: `
    <path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5" />
    <path d="M12 12l8 -4.5" />
    <path d="M12 12v9" />
    <path d="M12 12l-8 -4.5" />
  `,
  scissors: `
    <circle cx="6" cy="6" r="3" />
    <path d="M8.12 8.12L12 12m8-8L8.12 15.88" />
    <circle cx="6" cy="18" r="3" />
    <path d="M14.8 14.8L20 20" />
  `,
}
</script>

<template>
  <svg
    :width="size"
    :height="size"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="2"
    stroke-linecap="round"
    stroke-linejoin="round"
    style="display: inline-block; vertical-align: middle; flex-shrink: 0"
    v-html="paths[name] || ''"
  />
</template>