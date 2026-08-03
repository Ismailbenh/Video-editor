<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import PublicLayout from '../Layouts/PublicLayout.vue'
import Icon from '../Components/Icon.vue'
defineOptions({ layout: PublicLayout })
const props = defineProps({
  services: { type: Array, default: () => [] },
})
const services = computed(() => props.services)
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

const tierBg = {
  Basic: 'transparent',
  Pro: 'rgba(232,89,12,0.06)',
  Rush: 'rgba(232,89,12,0.03)',
}

const tierBorder = {
  Basic: C.edge,
  Pro: 'rgba(232,89,12,0.4)',
  Rush: C.edge,
}

const faqs = [
  { q: 'How do I send you my footage?', a: 'Google Drive, Dropbox, or WeTransfer links work best. You provide the link in the order form and I will start from there.' },
  { q: 'What if I need more revisions than included?', a: 'Additional revision rounds are available at a flat $25/round - just message me after delivery.' },
  { q: 'Can I request a custom package not listed here?', a: 'Absolutely. Use the contact form to describe your project and I will put together a custom quote within 24 hours.' },
  { q: 'Do you work with RAW/LOG footage?', a: 'Yes. Colour grading from LOG profiles (S-Log, C-Log, V-Log) is included in all Pro and Rush tiers at no extra cost.' },
]

function serviceLabel(idx) {
  return `SERVICE ${String(idx + 1).padStart(2, '0')}`
}
</script>

<template>
  <div>
    <!-- Page header -->
    <div :style="{ padding: '64px 24px 40px', maxWidth: '1200px', margin: '0 auto' }">
      <span :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '10px', color: C.dim, letterSpacing: '0.14em', textTransform: 'uppercase', display: 'block', marginBottom: '12px' }">
        SERVICES & PRICING
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
        Transparent pricing.<br />No surprises.
      </h1>
      <p :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '16px', color: C.dim, lineHeight: '1.65', maxWidth: '500px' }">
        Every service tier is listed below. Pick the pace that suits your deadline - all work comes with the same standard of craft.
      </p>
    </div>


    <!-- Service sections -->
    <div :style="{ maxWidth: '1200px', margin: '0 auto', padding: '0 24px 96px' }">
      <div
        v-for="(svc, idx) in services"
        :key="svc.id"
        :style="{
          marginBottom: '16px',
          backgroundColor: C.panel,
          border: `1px solid ${C.edge}`,
          borderRadius: '3px',
          overflow: 'hidden',
        }"
      >
        <!-- Service header row -->
        <div
          class="flex flex-col md:grid"
          :style="{
            display: 'grid',
            gridTemplateColumns: '1fr 2fr',
            gap: '32px',
            padding: '32px',
            borderBottom: `1px solid ${C.edge}`,
            alignItems: 'start',
          }"
        >
          <div>
            <div :style="{ display: 'flex', alignItems: 'center', gap: '12px', marginBottom: '12px' }">
              <div
                :style="{
                  width: '40px',
                  height: '40px',
                  backgroundColor: 'rgba(232,89,12,0.1)',
                  border: '1px solid rgba(232,89,12,0.2)',
                  borderRadius: '3px',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  fontSize: '18px',
                  color: C.amber,
                  flexShrink: 0,
                }"
              >
                <Icon :name="svc.icon" size="18" />
              </div>
              <div>
                <span :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '9px', color: C.dim, letterSpacing: '0.12em', textTransform: 'uppercase' }">
                  {{ serviceLabel(idx) }}
                </span>
                <h2 :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '22px', fontWeight: 700, color: C.bright, lineHeight: 1.2, letterSpacing: '-0.01em', margin: 0 }">
                  {{ svc.name }}
                </h2>
              </div>
            </div>
            <p :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '14px', color: C.dim, lineHeight: '1.7' }">
              {{ svc.description }}
            </p>
          </div>

          <div :style="{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '12px' }">
            <div
              v-for="tier in svc.tiers"
              :key="tier.name"
              :style="{
                backgroundColor: tierBg[tier.name],
                border: `1px solid ${tierBorder[tier.name]}`,
                borderRadius: '3px',
                padding: '20px',
                position: 'relative',
              }"
            >
              <div
                v-if="tier.name === 'Pro'"
                :style="{
                  position: 'absolute',
                  top: '-1px',
                  left: '12px',
                  backgroundColor: C.amber,
                  color: '#fff',
                  fontFamily: '\'JetBrains Mono\', monospace',
                  fontSize: '8px',
                  fontWeight: 600,
                  letterSpacing: '0.1em',
                  padding: '2px 6px',
                  borderRadius: '0 0 3px 3px',
                }"
              >
                POPULAR
              </div>

              <div :style="{ marginBottom: '12px', marginTop: tier.name === 'Pro' ? '8px' : '0' }">
                <span
                  :style="{
                    fontFamily: '\'JetBrains Mono\', monospace',
                    fontSize: '10px',
                    color: tier.name === 'Pro' ? C.amber : C.dim,
                    letterSpacing: '0.1em',
                    textTransform: 'uppercase',
                    display: 'block',
                    marginBottom: '6px',
                  }"
                >
                  {{ tier.name }}
                </span>
                <div :style="{ display: 'flex', alignItems: 'baseline', gap: '4px' }">
                  <span :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '26px', fontWeight: 600, color: C.bright, letterSpacing: '-0.02em' }">
                    ${{ tier.price }}
                  </span>
                </div>
                <span :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '10px', color: C.dim }">
                  {{ tier.turnaround }} delivery
                </span>
              </div>

              <ul :style="{ listStyle: 'none', padding: 0, margin: '0 0 16px' }">
                <li
                  v-for="f in tier.features"
                  :key="f"
                  :style="{ display: 'flex', alignItems: 'flex-start', gap: '8px', marginBottom: '6px' }"
                >
                  <span :style="{ color: C.amber, fontSize: '12px', flexShrink: 0, marginTop: '1px' }">-</span>
                  <span :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '12px', color: C.dim, lineHeight: '1.5' }">{{ f }}</span>
                </li>
              </ul>

              <Link
                :href="`/order?service=${svc.id}&tier=${tier.name}`"
                :style="{
                  textDecoration: 'none',
                  display: 'block',
                  textAlign: 'center',
                  padding: '8px 12px',
                  backgroundColor: tier.name === 'Pro' ? C.amber : 'transparent',
                  color: tier.name === 'Pro' ? '#fff' : C.dim,
                  border: tier.name === 'Pro' ? 'none' : `1px solid ${C.ledge}`,
                  borderRadius: '3px',
                  fontFamily: '\'Space Grotesk\', sans-serif',
                  fontSize: '12px',
                  fontWeight: 600,
                  letterSpacing: '0.03em',
                  transition: 'all 0.15s',
                }"
              >
                Order This
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- FAQ strip -->
    <section :style="{ backgroundColor: C.panel, borderTop: `1px solid ${C.edge}`, padding: '80px 24px' }">
      <div :style="{ maxWidth: '800px', margin: '0 auto' }">
        <span :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '10px', color: C.dim, letterSpacing: '0.14em', textTransform: 'uppercase', display: 'block', marginBottom: '12px' }">FAQ</span>
        <h2 :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '32px', fontWeight: 700, color: C.bright, letterSpacing: '-0.02em', marginBottom: '40px' }">Common questions.</h2>
        <div v-for="(faq, i) in faqs" :key="i" :style="{ borderBottom: `1px solid ${C.edge}`, padding: '24px 0' }">
          <div :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '16px', fontWeight: 600, color: C.bright, marginBottom: '10px' }">
            {{ faq.q }}
          </div>
          <p :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '14px', color: C.dim, lineHeight: '1.7', margin: 0 }">
            {{ faq.a }}
          </p>
        </div>
      </div>
    </section>
  </div>
</template>