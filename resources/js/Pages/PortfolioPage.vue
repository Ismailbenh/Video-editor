<script setup>
import { ref, computed } from 'vue'
import { testimonials } from '../data/MockData'
import PublicLayout from '../Layouts/PublicLayout.vue'
defineOptions({ layout: PublicLayout })
const props = defineProps({
  portfolioItems: { type: Array, default: () => [] },
})
const portfolioItems = computed(() => props.portfolioItems)
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

const categoryLabel = {
  all: 'All Work',
  youtube: 'YouTube',
  reels: 'Reels',
  clipping: 'Clipping',
  batch: 'Batch',
}

const categoryColor = {
  youtube: '#E8590C',
  reels: '#A855F7',
  batch: '#3B82F6',
  clipping: '#22C55E',
}

const filters = ['all', 'youtube', 'reels', 'clipping', 'batch']

const active = ref('all')

const filtered = computed(() =>
  active.value === 'all' ? portfolioItems.value : portfolioItems.value.filter(p => p.category === active.value)
)

function projectCount() {
  return String(filtered.value.length).padStart(2, '0')
}

function cardHoverIn(e, category) {
  e.currentTarget.style.borderColor = categoryColor[category]
}
function cardHoverOut(e) {
  e.currentTarget.style.borderColor = C.edge
}
</script>

<template>
  <div>
    <!-- Header -->
    <div :style="{ padding: '64px 24px 40px', maxWidth: '1200px', margin: '0 auto' }">
      <span :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '10px', color: C.dim, letterSpacing: '0.14em', textTransform: 'uppercase', display: 'block', marginBottom: '12px' }">
        PORTFOLIO
      </span>
      <h1
        :style="{
          fontFamily: '\'Space Grotesk\', sans-serif',
          fontSize: 'clamp(36px, 5vw, 60px)',
          fontWeight: 700,
          color: C.bright,
          lineHeight: 1.05,
          letterSpacing: '-0.03em',
          marginBottom: '16px',
        }"
      >
        Work that speaks<br />for itself.
      </h1>
      <p :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '16px', color: C.dim, lineHeight: '1.65', maxWidth: '480px', marginBottom: '40px' }">
        A selection of recent projects across all four service categories. Each one reflects the full editing process - concept through delivery.
      </p>

      <!-- Filter tabs -->
      <div :style="{ display: 'flex', gap: '4px', flexWrap: 'wrap' }">
        <button
          v-for="f in filters"
          :key="f"
          @click="active = f"
          :style="{
            padding: '7px 16px',
            backgroundColor: active === f ? C.amber : 'transparent',
            color: active === f ? '#fff' : C.dim,
            border: `1px solid ${active === f ? C.amber : C.edge}`,
            borderRadius: '3px',
            fontFamily: '\'Space Grotesk\', sans-serif',
            fontSize: '13px',
            fontWeight: active === f ? 600 : 400,
            cursor: 'pointer',
            transition: 'all 0.15s',
            letterSpacing: '0.02em',
          }"
        >
          {{ categoryLabel[f] }}
        </button>
      </div>
    </div>

    <!-- Grid -->
    <div :style="{ maxWidth: '1200px', margin: '0 auto', padding: '0 24px 80px' }">
      <div :style="{ marginBottom: '16px' }">
        <span :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '10px', color: C.dim, letterSpacing: '0.1em' }">
          {{ projectCount() }} PROJECTS
        </span>
      </div>

      <div :style="{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(320px, 1fr))', gap: '16px' }">
        <div
          v-for="item in filtered"
          :key="item.id"
          :style="{
            backgroundColor: C.panel,
            border: `1px solid ${C.edge}`,
            borderRadius: '3px',
            overflow: 'hidden',
            cursor: 'pointer',
            transition: 'border-color 0.2s',
          }"
          @mouseenter="e => cardHoverIn(e, item.category)"
          @mouseleave="cardHoverOut"
        >
          <div :style="{ position: 'relative', overflow: 'hidden' }">
            <img
              :src="item.thumbnail"
              :alt="item.title"
              :style="{ width: '100%', aspectRatio: '16/9', objectFit: 'cover', display: 'block', transition: 'transform 0.3s', backgroundColor: C.bg }"
            />
            <div
              v-if="item.featured"
              :style="{
                position: 'absolute',
                top: '12px',
                right: '12px',
                backgroundColor: C.amber,
                color: '#fff',
                fontFamily: '\'JetBrains Mono\', monospace',
                fontSize: '8px',
                letterSpacing: '0.1em',
                padding: '3px 8px',
                borderRadius: '2px',
              }"
            >
              FEATURED
            </div>
          </div>

          <div :style="{ padding: '18px' }">
            <div :style="{ display: 'flex', alignItems: 'center', gap: '8px', marginBottom: '8px' }">
              <span
                :style="{
                  fontFamily: '\'JetBrains Mono\', monospace',
                  fontSize: '9px',
                  letterSpacing: '0.1em',
                  textTransform: 'uppercase',
                  color: categoryColor[item.category],
                  border: `1px solid ${categoryColor[item.category]}`,
                  borderRadius: '2px',
                  padding: '2px 6px',
                  opacity: 0.9,
                }"
              >
                {{ categoryLabel[item.category] }}
              </span>
            </div>
            <h3 :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '16px', fontWeight: 600, color: C.bright, lineHeight: 1.3, marginBottom: '8px' }">
              {{ item.title }}
            </h3>
            <div :style="{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }">
              <span :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '12px', color: C.dim }">{{ item.client }}</span>
              <span :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '10px', color: C.ledge, letterSpacing: '0.05em' }">{{ item.date }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Testimonials -->
    <section :style="{ backgroundColor: C.panel, borderTop: `1px solid ${C.edge}`, padding: '80px 24px' }">
      <div :style="{ maxWidth: '1200px', margin: '0 auto' }">
        <span :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '10px', color: C.dim, letterSpacing: '0.14em', textTransform: 'uppercase', display: 'block', marginBottom: '8px' }">CLIENT REVIEWS</span>
        <h2 :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '36px', fontWeight: 700, color: C.bright, letterSpacing: '-0.02em', marginBottom: '40px' }">
          Straight from the clients.
        </h2>

        <div :style="{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '16px' }">
          <div
            v-for="t in testimonials"
            :key="t.id"
            :style="{
              backgroundColor: C.bg,
              border: `1px solid ${C.edge}`,
              borderRadius: '3px',
              padding: '24px',
            }"
          >
            <div :style="{ display: 'flex', gap: '2px', marginBottom: '14px' }">
              <span
                v-for="i in 5"
                :key="i"
                :style="{ color: i - 1 < t.rating ? C.amber : C.edge, fontSize: '13px' }"
              ></span>
            </div>
            <p :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '14px', color: C.bright, lineHeight: '1.7', marginBottom: '20px', fontStyle: 'italic', opacity: 0.85 }">
              "{{ t.text }}"
            </p>
            <div :style="{ display: 'flex', alignItems: 'center', gap: '10px' }">
              <img :src="t.avatar" :alt="t.name" :style="{ width: '32px', height: '32px', borderRadius: '50%', objectFit: 'cover', border: `1px solid ${C.edge}`, backgroundColor: C.panel }" />
              <div>
                <div :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '13px', fontWeight: 600, color: C.bright }">{{ t.name }}</div>
                <div :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '9px', color: C.dim }">{{ t.handle }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>