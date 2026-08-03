<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  position: { type: Number, default: 23 },
  animate: { type: Boolean, default: true },
  compact: { type: Boolean, default: false },
})

const pos = ref(props.animate ? 0 : props.position)

onMounted(() => {
  if (!props.animate) return
  setTimeout(() => {
    pos.value = props.position
  }, 200)
})

const tickCount = props.compact ? 30 : 60
const ticks = Array.from({ length: tickCount }, (_, i) => i)
const trackTop = props.compact ? 10 : 16

function tickHeight(i) {
  const isMajor = i % 10 === 0
  const isMid = i % 5 === 0
  return props.compact
    ? isMajor ? 8 : isMid ? 5 : 3
    : isMajor ? 12 : isMid ? 8 : 4
}
</script>

<template>
  <div
    class="relative w-full select-none"
    :class="compact ? 'h-6' : 'h-10'"
    role="presentation"
    aria-hidden="true"
  >
    <!-- Track -->
    <div
      class="absolute left-0 right-0 h-px bg-[#2A2A2F]"
      :style="{ top: `${trackTop}px` }"
    />

    <!-- Played portion -->
    <div
      class="absolute left-0 h-px bg-[#E8590C] opacity-50"
      :style="{
        top: `${trackTop}px`,
        width: `${pos}%`,
        transition: animate ? 'width 0.9s cubic-bezier(0.23, 1, 0.32, 1)' : 'none',
      }"
    />

    <!-- Ticks -->
    <div
      v-for="i in ticks"
      :key="`tick-${i}`"
      class="absolute w-px"
      :class="i % 10 === 0 ? 'bg-[#3D3D45]' : 'bg-[#2A2A2F]'"
      :style="{
        left: `${(i / (tickCount - 1)) * 100}%`,
        top: `${trackTop - Math.floor(tickHeight(i) / 2)}px`,
        height: `${tickHeight(i)}px`,
      }"
    />

    <!-- Timecode labels -->
    <template v-if="!compact">
      <span
        v-for="i in ticks.filter(i => i % 10 === 0)"
        :key="`label-${i}`"
        class="absolute font-mono text-[9px] text-[#8B8B93] tracking-[0.02em] opacity-70"
        :style="{
          left: `${(i / (tickCount - 1)) * 100}%`,
          top: '26px',
          transform: 'translateX(-50%)',
        }"
      >
        {{ `0:${String(i / 2).padStart(2, '0')}` }}
      </span>
    </template>

    <!-- Playhead -->
    <div
      class="absolute top-0 h-full flex flex-col items-center pointer-events-none z-[2]"
      :style="{
        left: `${pos}%`,
        transform: 'translateX(-50%)',
        transition: animate ? 'left 0.9s cubic-bezier(0.23, 1, 0.32, 1)' : 'none',
      }"
    >
      <div
        class="w-0 h-0"
        :style="{
          borderLeft: compact ? '4px solid transparent' : '5px solid transparent',
          borderRight: compact ? '4px solid transparent' : '5px solid transparent',
          borderTop: compact ? '6px solid #E8590C' : '8px solid #E8590C',
        }"
      />
      <div class="w-px flex-1 bg-[#E8590C] opacity-90" />
    </div>
  </div>
</template>