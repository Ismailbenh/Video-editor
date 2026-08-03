<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { testimonials } from '../data/MockData'
import PublicLayout from '../Layouts/PublicLayout.vue'
import Icon from '../Components/Icon.vue'
defineOptions({ layout: PublicLayout })
const props = defineProps({
  services: { type: Array, default: () => [] },
  portfolioItems: { type: Array, default: () => [] },
})
const services = computed(() => props.services)
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
  youtube: 'YouTube',
  reels: 'Reels',
  batch: 'Batch',
  clipping: 'Clipping',
}

const categoryColor = {
  youtube: '#E8590C',
  reels: '#A855F7',
  batch: '#3B82F6',
  clipping: '#22C55E',
}

const stats = [
  { value: '340+', label: 'Videos Delivered' },
  { value: '4.9', label: 'Avg Client Rating' },
  { value: '48h', label: 'Avg Turnaround' },
  { value: '60+', label: 'Happy Clients' },
]

const tools = [
  { title: 'Premiere Pro', sub: 'Primary NLE' },
  { title: 'DaVinci Resolve', sub: 'Colour Grade' },
  { title: 'After Effects', sub: 'Motion Graphics' },
  { title: 'Descript', sub: 'Transcript Edit' },
]

const featured = computed(() => portfolioItems.value.filter(p => p.featured).slice(0, 3))

function showOverlay(e) {
  e.currentTarget.querySelector('.overlay').style.opacity = '1'
}
function hideOverlay(e) {
  e.currentTarget.querySelector('.overlay').style.opacity = '0'
}
function hoverServiceIn(e) {
  e.currentTarget.style.borderColor = C.amber
  e.currentTarget.style.backgroundColor = C.raised
}
function hoverServiceOut(e) {
  e.currentTarget.style.borderColor = C.edge
  e.currentTarget.style.backgroundColor = C.panel
}
</script>

<template>
  <div>
    <!-- HERO -->
    <section
      :style="{
        position: 'relative',
        minHeight: '92vh',
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'center',
        padding: '80px 24px 0',
        overflow: 'hidden',
      }"
    >
      <!-- Background image with overlay -->
      <div
        :style="{
          position: 'absolute',
          inset: 0,
          backgroundImage: 'url(https://images.unsplash.com/photo-1574717024453-354056aee0eb?w=1600&h=900&fit=crop&auto=format)',
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          opacity: 0.07,
        }"
      />
      <div
        :style="{
          position: 'absolute',
          inset: 0,
          background: 'linear-gradient(to bottom, #121214 0%, transparent 40%, #121214 100%)',
        }"
      />

      <div :style="{ maxWidth: '1200px', margin: '0 auto', width: '100%', position: 'relative' }">
        <div :style="{ marginBottom: '20px' }">
          <span
            :style="{
              fontFamily: '\'JetBrains Mono\', monospace',
              fontSize: '11px',
              color: C.amber,
              letterSpacing: '0.14em',
              textTransform: 'uppercase',
            }"
          >
            ALEX NOVO / FREELANCE VIDEO EDITOR
          </span>
        </div>

        <h1
          :style="{
            fontFamily: '\'Space Grotesk\', sans-serif',
            fontSize: 'clamp(52px, 8vw, 110px)',
            fontWeight: 700,
            lineHeight: 0.92,
            color: C.bright,
            letterSpacing: '-0.03em',
            marginBottom: '28px',
            maxWidth: '900px',
          }"
        >
          YOUR STORY,
          <br />
          <span :style="{ color: C.amber }">CUT</span> SHARP.
        </h1>

        <p
          :style="{
            fontFamily: '\'Space Grotesk\', sans-serif',
            fontSize: '18px',
            color: C.dim,
            lineHeight: '1.65',
            maxWidth: '520px',
            marginBottom: '40px',
            fontWeight: 400,
          }"
        >
          YouTube videos, Instagram Reels, podcast clips, and batch content - edited to make your audience stop scrolling and start watching.
        </p>

        <div :style="{ display: 'flex', gap: '14px', flexWrap: 'wrap', marginBottom: '64px' }">
          <Link
            href="/order"
            :style="{
              textDecoration: 'none',
              padding: '14px 28px',
              backgroundColor: C.amber,
              color: '#fff',
              fontFamily: '\'Space Grotesk\', sans-serif',
              fontSize: '15px',
              fontWeight: 600,
              borderRadius: '3px',
              letterSpacing: '0.02em',
              display: 'inline-flex',
              alignItems: 'center',
              gap: '8px',
            }"
          >
            Get a Quote
            <span :style="{ fontSize: '12px' }"></span>
          </Link>
          <Link
            href="/portfolio"
            :style="{
              textDecoration: 'none',
              padding: '14px 28px',
              backgroundColor: 'transparent',
              color: C.bright,
              fontFamily: '\'Space Grotesk\', sans-serif',
              fontSize: '15px',
              fontWeight: 500,
              borderRadius: '3px',
              border: `1px solid ${C.ledge}`,
              letterSpacing: '0.02em',
            }"
          >
            View Work
          </Link>
        </div>

        
        
      </div>
    </section>

    <!-- STATS STRIP -->
    <section :style="{ borderTop: `1px solid ${C.edge}`, borderBottom: `1px solid ${C.edge}`, backgroundColor: C.panel }">
      <div :style="{ maxWidth: '1200px', margin: '0 auto', padding: '0 24px' }">
        <div :style="{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(160px, 1fr))', gap: 0 }">
          <div
            v-for="(stat, i) in stats"
            :key="i"
            :style="{
              padding: '28px 24px',
              borderRight: i < 3 ? `1px solid ${C.edge}` : 'none',
              textAlign: 'center',
            }"
          >
            <div
              :style="{
                fontFamily: '\'JetBrains Mono\', monospace',
                fontSize: '28px',
                fontWeight: 600,
                color: C.bright,
                marginBottom: '4px',
                letterSpacing: '-0.02em',
              }"
            >
              {{ stat.value }}
            </div>
            <div
              :style="{
                fontFamily: '\'JetBrains Mono\', monospace',
                fontSize: '10px',
                color: C.dim,
                letterSpacing: '0.1em',
                textTransform: 'uppercase',
              }"
            >
              {{ stat.label }}
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ABOUT STRIP -->
    <section :style="{ padding: '96px 24px', maxWidth: '1200px', margin: '0 auto' }">
      <div
        class="grid-cols-1 md:grid-cols-2"
        :style="{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '80px', alignItems: 'center' }"
      >
        <div>
          <span
            :style="{
              fontFamily: '\'JetBrains Mono\', monospace',
              fontSize: '10px',
              color: C.dim,
              letterSpacing: '0.14em',
              textTransform: 'uppercase',
              display: 'block',
              marginBottom: '16px',
            }"
          >
            ABOUT THE EDITOR
          </span>
          <h2
            :style="{
              fontFamily: '\'Space Grotesk\', sans-serif',
              fontSize: 'clamp(32px, 4vw, 48px)',
              fontWeight: 700,
              color: C.bright,
              lineHeight: 1.1,
              letterSpacing: '-0.02em',
              marginBottom: '20px',
            }"
          >
            Editing that earns the watch.
          </h2>
          <p :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '16px', color: C.dim, lineHeight: '1.75', marginBottom: '16px' }">
            Five years in post-production means I have built the instincts for pacing, storytelling, and platform-specific nuance that no preset pack can replicate.
          </p>
          <p :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '16px', color: C.dim, lineHeight: '1.75', marginBottom: '32px' }">
            From 90-second Reels to hour-long YouTube essays, every cut serves the story. I work with creators, brands, and production teams across 12 countries.
          </p>
          <Link
            href="/services"
            :style="{
              textDecoration: 'none',
              fontFamily: '\'Space Grotesk\', sans-serif',
              fontSize: '14px',
              fontWeight: 600,
              color: C.amber,
              display: 'inline-flex',
              alignItems: 'center',
              gap: '8px',
              letterSpacing: '0.03em',
            }"
          >
            See all services
          </Link>
        </div>

        <div :style="{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '16px' }">
          <div
            v-for="(tool, i) in tools"
            :key="i"
            :style="{
              backgroundColor: C.panel,
              border: `1px solid ${C.edge}`,
              borderRadius: '3px',
              padding: '20px',
            }"
          >
            <div :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '14px', fontWeight: 600, color: C.bright, marginBottom: '4px' }">{{ tool.title }}</div>
            <div :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '10px', color: C.dim, letterSpacing: '0.08em', textTransform: 'uppercase' }">{{ tool.sub }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- TIMELINE DIVIDER -->
    

    <!-- SERVICES TEASER -->
    <section :style="{ padding: '80px 24px', maxWidth: '1200px', margin: '0 auto' }">
      <div :style="{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', marginBottom: '40px', flexWrap: 'wrap', gap: '16px' }">
        <div>
          <span
            :style="{
              fontFamily: '\'JetBrains Mono\', monospace',
              fontSize: '10px',
              color: C.dim,
              letterSpacing: '0.14em',
              textTransform: 'uppercase',
              display: 'block',
              marginBottom: '8px',
            }"
          >
            WHAT I DO
          </span>
          <h2 :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: 'clamp(28px, 4vw, 40px)', fontWeight: 700, color: C.bright, lineHeight: 1.1, letterSpacing: '-0.02em', margin: 0 }">
            Four ways to work together.
          </h2>
        </div>
        <Link href="/services" :style="{ textDecoration: 'none', fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '13px', color: C.dim, border: `1px solid ${C.edge}`, borderRadius: '3px', padding: '8px 16px', whiteSpace: 'nowrap' }">
          Full pricing
        </Link>
      </div>

      <div :style="{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '16px' }">
        <Link
          v-for="svc in services"
          :key="svc.id"
          href="/services"
          :style="{ textDecoration: 'none' }"
        >
          <div
            :style="{
              backgroundColor: C.panel,
              border: `1px solid ${C.edge}`,
              borderRadius: '3px',
              padding: '28px',
              height: '100%',
              transition: 'border-color 0.2s, background 0.2s',
              cursor: 'pointer',
            }"
            @mouseenter="hoverServiceIn"
            @mouseleave="hoverServiceOut"
          >
            <div
              :style="{
                width: '36px',
                height: '36px',
                backgroundColor: 'rgba(232,89,12,0.1)',
                border: `1px solid rgba(232,89,12,0.2)`,
                borderRadius: '3px',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                marginBottom: '16px',
                fontSize: '16px',
                color: C.amber,
              }"
            >
              <Icon :name="svc.icon" size="16" />
            </div>
            <h3 :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '17px', fontWeight: 600, color: C.bright, marginBottom: '8px', lineHeight: 1.3 }">
              {{ svc.name }}
            </h3>
            <p :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '13px', color: C.dim, lineHeight: '1.65', marginBottom: '20px' }">
              {{ svc.description }}
            </p>
            <div :style="{ display: 'flex', gap: '8px', alignItems: 'center' }">
              <span :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '12px', color: C.amber, fontWeight: 600 }">
                from ${{ svc.tiers[0].price }}
              </span>
              <span :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '10px', color: C.dim }">
                / {{ svc.tiers[0].turnaround }}
              </span>
            </div>
          </div>
        </Link>
      </div>
    </section>

    <!-- PORTFOLIO PREVIEW -->
    <section :style="{ backgroundColor: C.panel, borderTop: `1px solid ${C.edge}`, borderBottom: `1px solid ${C.edge}`, padding: '80px 24px' }">
      <div :style="{ maxWidth: '1200px', margin: '0 auto' }">
        <div :style="{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', marginBottom: '40px', flexWrap: 'wrap', gap: '16px' }">
          <div>
            <span :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '10px', color: C.dim, letterSpacing: '0.14em', textTransform: 'uppercase', display: 'block', marginBottom: '8px' }">THE WORK</span>
            <h2 :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: 'clamp(28px, 4vw, 40px)', fontWeight: 700, color: C.bright, lineHeight: 1.1, letterSpacing: '-0.02em', margin: 0 }">
              Recent projects.
            </h2>
          </div>
          <Link href="/portfolio" :style="{ textDecoration: 'none', fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '13px', color: C.dim, border: `1px solid ${C.edge}`, borderRadius: '3px', padding: '8px 16px', whiteSpace: 'nowrap', backgroundColor: C.bg }">
            Full portfolio
          </Link>
        </div>

        <div :style="{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '20px' }">
          <div
            v-for="item in featured"
            :key="item.id"
            :style="{ position: 'relative', borderRadius: '3px', overflow: 'hidden', backgroundColor: C.bg, border: `1px solid ${C.edge}`, cursor: 'pointer' }"
            @mouseenter="showOverlay"
            @mouseleave="hideOverlay"
          >
            <img
              :src="item.thumbnail"
              :alt="item.title"
              :style="{ width: '100%', aspectRatio: '16/9', objectFit: 'cover', display: 'block' }"
            />
            <div
              class="overlay"
              :style="{
                position: 'absolute',
                inset: 0,
                backgroundColor: 'rgba(18,18,20,0.6)',
                opacity: 0,
                transition: 'opacity 0.2s',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
              }"
            >
              <div :style="{ width: '48px', height: '48px', backgroundColor: C.amber, borderRadius: '50%', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '16px', color: '#fff' }"></div>
            </div>
            <div :style="{ padding: '16px' }">
              <span
                :style="{
                  display: 'inline-block',
                  fontFamily: '\'JetBrains Mono\', monospace',
                  fontSize: '9px',
                  letterSpacing: '0.1em',
                  textTransform: 'uppercase',
                  color: categoryColor[item.category],
                  border: `1px solid ${categoryColor[item.category]}`,
                  borderRadius: '2px',
                  padding: '2px 6px',
                  marginBottom: '8px',
                  opacity: 0.85,
                }"
              >
                {{ categoryLabel[item.category] }}
              </span>
              <h3 :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '15px', fontWeight: 600, color: C.bright, lineHeight: 1.3, margin: 0 }">
                {{ item.title }}
              </h3>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TESTIMONIALS -->
    <section :style="{ padding: '96px 24px', maxWidth: '1200px', margin: '0 auto' }">
      <div :style="{ marginBottom: '48px' }">
        <span :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '10px', color: C.dim, letterSpacing: '0.14em', textTransform: 'uppercase', display: 'block', marginBottom: '8px' }">CLIENT REVIEWS</span>
        <h2 :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: 'clamp(28px, 4vw, 40px)', fontWeight: 700, color: C.bright, lineHeight: 1.1, letterSpacing: '-0.02em' }">
          What clients say.
        </h2>
      </div>

      <div :style="{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }">
        <div
          v-for="t in testimonials"
          :key="t.id"
          :style="{
            backgroundColor: C.panel,
            border: `1px solid ${C.edge}`,
            borderRadius: '3px',
            padding: '28px',
          }"
        >
          <div :style="{ display: 'flex', gap: '2px', marginBottom: '16px' }">
            <span v-for="i in t.rating" :key="i" :style="{ color: C.amber, fontSize: '14px' }"></span>
          </div>
          <p :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '15px', color: C.bright, lineHeight: '1.65', marginBottom: '24px', fontStyle: 'italic', opacity: 0.9 }">
            "{{ t.text }}"
          </p>
          <div :style="{ display: 'flex', alignItems: 'center', gap: '12px', borderTop: `1px solid ${C.edge}`, paddingTop: '16px' }">
            <img
              :src="t.avatar"
              :alt="t.name"
              :style="{ width: '36px', height: '36px', borderRadius: '50%', objectFit: 'cover', border: `1px solid ${C.edge}` }"
            />
            <div>
              <div :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '13px', fontWeight: 600, color: C.bright }">{{ t.name }}</div>
              <div :style="{ fontFamily: '\'JetBrains Mono\', monospace', fontSize: '10px', color: C.dim, letterSpacing: '0.04em' }">{{ t.service }}</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA BAND -->
    <section
      :style="{
        margin: '0 24px 96px',
        maxWidth: '1152px',
        marginLeft: 'auto',
        marginRight: 'auto',
        backgroundColor: C.panel,
        border: `1px solid ${C.edge}`,
        borderRadius: '4px',
        padding: '56px 48px',
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center',
        flexWrap: 'wrap',
        gap: '24px',
        position: 'relative',
        overflow: 'hidden',
      }"
    >
      <div
        :style="{
          position: 'absolute',
          top: 0,
          left: 0,
          width: '4px',
          height: '100%',
          backgroundColor: C.amber,
        }"
      />
      <div>
        <h2 :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: 'clamp(24px, 3vw, 36px)', fontWeight: 700, color: C.bright, lineHeight: 1.15, letterSpacing: '-0.02em', marginBottom: '8px' }">
          Ready to ship better content?
        </h2>
        <p :style="{ fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '15px', color: C.dim }">
          Most projects start within 24 hours of order placement.
        </p>
      </div>
      <div :style="{ display: 'flex', gap: '12px', flexWrap: 'wrap' }">
        <Link href="/order" :style="{ textDecoration: 'none', padding: '13px 28px', backgroundColor: C.amber, color: '#fff', fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '14px', fontWeight: 600, borderRadius: '3px', whiteSpace: 'nowrap' }">
          Place an Order
        </Link>
        <Link href="/contact" :style="{ textDecoration: 'none', padding: '13px 28px', color: C.bright, fontFamily: '\'Space Grotesk\', sans-serif', fontSize: '14px', fontWeight: 500, border: `1px solid ${C.ledge}`, borderRadius: '3px', whiteSpace: 'nowrap' }">
          Ask a Question
        </Link>
      </div>
    </section>
  </div>
</template>