<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import PublicLayout from '../Layouts/PublicLayout.vue'
import Icon from '../Components/Icon.vue'
defineOptions({ layout: PublicLayout })

const props = defineProps({
  services: { type: Array, default: () => [] },
  confirmedOrder: { type: Object, default: null },
})
const C = {
  bg: '#121214', panel: '#1C1C1F', amber: '#E8590C',
  bright: '#F4F4F5', dim: '#8B8B93', edge: '#2A2A2F', ledge: '#3D3D45',
}

const step = ref(1)
const selectedService = ref(null)
const selectedTier = ref(null)

const steps = [{ n: 1, label: 'Service' }, { n: 2, label: 'Details' }, { n: 3, label: 'Payment' }]

const form = useForm({
  service_id: null,
  tier_name: null,
  client_name: '',
  client_email: '',
  description: '',
  deadline: '',
  file_link: '',
})

const tierPrice = computed(() => {
  if (!selectedService.value || !selectedTier.value) return null
  return selectedService.value.tiers.find(t => t.name === selectedTier.value)?.price
})
const selectedTierData = computed(() => {
  if (!selectedService.value || !selectedTier.value) return null
  return selectedService.value.tiers.find(t => t.name === selectedTier.value)
})

function selectService(svc) {
  selectedService.value = svc
  selectedTier.value = null
  form.service_id = svc.id
  form.tier_name = null
}
function selectTier(tierName) {
  selectedTier.value = tierName
  form.tier_name = tierName
}
function submitOrder() {
  // Redirects the whole browser to Stripe Checkout - Inertia::location()
  // on the backend forces a full navigation, not an XHR visit.
  form.post(route('order.store'))
}
</script>

<template>
  <div v-if="confirmedOrder" style="max-width:600px;margin:96px auto;padding:0 24px;text-align:center;">
    <div :style="{ width:'64px',height:'64px',backgroundColor:'rgba(232,89,12,0.1)',border:`2px solid ${C.amber}`,borderRadius:'50%',display:'flex',alignItems:'center',justifyContent:'center',margin:'0 auto 24px',fontSize:'20px',color:C.amber }">Paid</div>
    <h1 :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'32px',fontWeight:700,color:C.bright,marginBottom:'12px',letterSpacing:'-0.02em' }">Payment received.</h1>
    <p :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'16px',color:C.dim,lineHeight:'1.65',marginBottom:'32px' }">
      Your order is confirmed and in the queue. You'll get a confirmation email, and I'll be in touch within 24 hours to confirm the timeline.
    </p>
    <div :style="{ backgroundColor:C.panel,border:`1px solid ${C.edge}`,borderRadius:'3px',padding:'20px',marginBottom:'28px',textAlign:'left' }">
      <div :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.1em',marginBottom:'12px' }">ORDER SUMMARY</div>
      <div :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'15px',color:C.bright,marginBottom:'4px' }">{{ confirmedOrder.service_name }}</div>
      <div :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'12px',color:C.amber }">{{ confirmedOrder.tier_name }} - ${{ confirmedOrder.price }}</div>
    </div>
  </div>

  <div v-else style="max-width:1200px;margin:0 auto;padding:48px 24px 96px;">
    <div style="margin-bottom:40px;">
      <span :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.14em',textTransform:'uppercase',display:'block',marginBottom:'10px' }">PLACE AN ORDER</span>
      <h1 :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'clamp(28px,4vw,44px)',fontWeight:700,color:C.bright,letterSpacing:'-0.03em',lineHeight:1.1 }">Start a project.</h1>
    </div>

    <!-- Step indicators -->
    <div style="display:flex;gap:0;margin-bottom:48px;max-width:500px;">
      <div v-for="(s, i) in steps" :key="s.n" style="display:flex;align-items:center;flex:1;">
        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
          <div :style="{
            width:'28px',height:'28px',borderRadius:'50%',
            backgroundColor: step===s.n ? C.amber : step>s.n ? 'rgba(232,89,12,0.2)' : 'transparent',
            border:`1px solid ${step>=s.n ? C.amber : C.edge}`,
            display:'flex',alignItems:'center',justifyContent:'center',
            fontFamily:`'JetBrains Mono',monospace`,fontSize:'11px',
            color: step===s.n ? '#fff' : step>s.n ? C.amber : C.dim,
            fontWeight:600, flexShrink:0,
          }">{{ step > s.n ? 'Done' : s.n }}</div>
          <span :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'12px',color: step>=s.n ? C.bright : C.dim, fontWeight: step===s.n?600:400, whiteSpace:'nowrap' }">{{ s.label }}</span>
        </div>
        <div v-if="i < 2" :style="{ flex:1,height:'1px',backgroundColor: step>s.n ? C.amber : C.edge, margin:'0 8px', opacity:0.6 }" />
      </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 320px;gap:32px;align-items:start;">
      <!-- Main form area -->
      <div>
        <!-- STEP 1 -->
        <div v-if="step === 1">
          <h2 :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'20px',fontWeight:700,color:C.bright,marginBottom:'24px',letterSpacing:'-0.01em' }">Select a service</h2>
          <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:12px;margin-bottom:32px;">
            <div v-for="svc in props.services" :key="svc.id" @click="selectService(svc)"
              :style="{
                backgroundColor: selectedService?.id===svc.id ? 'rgba(232,89,12,0.08)' : C.panel,
                border:`1px solid ${selectedService?.id===svc.id ? C.amber : C.edge}`,
                borderRadius:'3px', padding:'20px', cursor:'pointer', transition:'all 0.15s',
              }">
              <div :style="{ color:C.amber, marginBottom:'10px' }"><Icon :name="svc.icon" size="20" /></div>
              <h3 :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'15px',fontWeight:600,color:C.bright,marginBottom:'6px',lineHeight:1.3 }">{{ svc.name }}</h3>
              <p :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'12px',color:C.dim,lineHeight:'1.5',marginBottom:'12px' }">{{ svc.description }}</p>
            </div>
          </div>

          <div v-if="selectedService" style="margin-bottom:32px;">
            <h3 :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'16px',fontWeight:600,color:C.bright,marginBottom:'16px' }">
              Choose a tier for <span :style="{ color:C.amber }">{{ selectedService.name }}</span>
            </h3>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
              <div v-for="tier in selectedService.tiers" :key="tier.name" @click="selectTier(tier.name)"
                :style="{
                  backgroundColor: selectedTier===tier.name ? 'rgba(232,89,12,0.1)' : C.panel,
                  border:`1px solid ${selectedTier===tier.name ? C.amber : C.edge}`,
                  borderRadius:'3px', padding:'16px', cursor:'pointer', transition:'all 0.15s',
                }">
                <div :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'9px',color: selectedTier===tier.name?C.amber:C.dim,letterSpacing:'0.1em',textTransform:'uppercase',marginBottom:'6px' }">{{ tier.name }}</div>
                <div :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'22px',fontWeight:600,color:C.bright,marginBottom:'4px' }">${{ tier.price }}</div>
                <div :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'9px',color:C.dim }">{{ tier.turnaround }}</div>
              </div>
            </div>
          </div>

          <button @click="step = 2" :disabled="!selectedService || !selectedTier"
            :style="{
              padding:'12px 28px',
              backgroundColor: selectedService && selectedTier ? C.amber : C.edge,
              color: selectedService && selectedTier ? '#fff' : C.dim,
              fontFamily:`'Space Grotesk',sans-serif`,fontSize:'14px',fontWeight:600,
              borderRadius:'3px', border:'none',
              cursor: selectedService && selectedTier ? 'pointer' : 'not-allowed',
              letterSpacing:'0.03em',
            }">Continue</button>
        </div>

        <!-- STEP 2 -->
        <div v-if="step === 2">
          <h2 :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'20px',fontWeight:700,color:C.bright,marginBottom:'24px' }">Job details</h2>

          <div style="margin-bottom:20px;">
            <label :style="{ display:'block',fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.1em',textTransform:'uppercase',marginBottom:'6px' }">YOUR NAME *</label>
            <input type="text" placeholder="Jane Doe" v-model="form.client_name" />
          </div>

          <div style="margin-bottom:20px;">
            <label :style="{ display:'block',fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.1em',textTransform:'uppercase',marginBottom:'6px' }">PROJECT DESCRIPTION *</label>
            <textarea rows="5" placeholder="Describe your project - type of content, length, style, any reference work or mood board..."
              v-model="form.description" style="resize:vertical;min-height:110px;"></textarea>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
            <div>
              <label :style="{ display:'block',fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.1em',textTransform:'uppercase',marginBottom:'6px' }">DEADLINE</label>
              <input type="date" v-model="form.deadline" style="color-scheme:dark;" />
            </div>
            <div>
              <label :style="{ display:'block',fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.1em',textTransform:'uppercase',marginBottom:'6px' }">YOUR EMAIL *</label>
              <input type="email" placeholder="you@example.com" v-model="form.client_email" />
            </div>
          </div>

          <div style="margin-bottom:32px;">
            <label :style="{ display:'block',fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.1em',textTransform:'uppercase',marginBottom:'6px' }">FILE / FOOTAGE LINK</label>
            <input type="url" placeholder="https://drive.google.com/... or dropbox.com/..." v-model="form.file_link" />
            <p :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'11px',color:C.dim,marginTop:'6px' }">Google Drive, Dropbox, or WeTransfer link. You can add this later if footage is not ready.</p>
          </div>

          <div style="display:flex;gap:12px;">
            <button @click="step = 1" style="padding:12px 20px;background-color:transparent;color:#8B8B93;font-family:'Space Grotesk',sans-serif;font-size:14px;border:1px solid #2A2A2F;border-radius:3px;cursor:pointer;">Back</button>
            <button @click="step = 3" :disabled="!form.client_name || !form.description || !form.client_email"
              :style="{
                padding:'12px 28px',
                backgroundColor: form.client_name && form.description && form.client_email ? C.amber : C.edge,
                color: form.client_name && form.description && form.client_email ? '#fff' : C.dim,
                fontFamily:`'Space Grotesk',sans-serif`,fontSize:'14px',fontWeight:600,
                borderRadius:'3px', border:'none',
                cursor: form.client_name && form.description && form.client_email ? 'pointer' : 'not-allowed',
                letterSpacing:'0.03em',
              }">Continue</button>
          </div>
        </div>

        <!-- STEP 3 -->
        <form v-if="step === 3" @submit.prevent="submitOrder">
          <h2 :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'20px',fontWeight:700,color:C.bright,marginBottom:'24px' }">Payment</h2>

          <div :style="{ backgroundColor:C.panel,border:`1px solid ${C.edge}`,borderRadius:'3px',padding:'28px',marginBottom:'24px',textAlign:'center' }">
            <p :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'14px',color:C.dim,marginBottom:'8px',lineHeight:'1.6' }">
              You'll be taken to Stripe's secure checkout to enter your card details.
            </p>
            <p :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.ledge }">
              We never see or store your card number.
            </p>
          </div>

          <div v-if="form.errors.service_id || form.errors.tier_name" :style="{ color: '#EF4444', fontFamily:`'Space Grotesk',sans-serif`, fontSize:'13px', marginBottom:'16px' }">
            {{ form.errors.service_id || form.errors.tier_name }}
          </div>

          <div style="display:flex;gap:12px;">
            <button type="button" @click="step = 2" style="padding:12px 20px;background-color:transparent;color:#8B8B93;font-family:'Space Grotesk',sans-serif;font-size:14px;border:1px solid #2A2A2F;border-radius:3px;cursor:pointer;">Back</button>
            <button type="submit" :disabled="form.processing"
              :style="{ flex:1,padding:'13px 28px',backgroundColor:C.amber,color:'#fff',fontFamily:`'Space Grotesk',sans-serif`,fontSize:'15px',fontWeight:700,borderRadius:'3px',border:'none',cursor: form.processing ? 'not-allowed' : 'pointer',letterSpacing:'0.04em',opacity: form.processing ? 0.7 : 1 }">
              {{ form.processing ? 'Redirecting to Stripe...' : `Continue to payment for $${tierPrice}` }}
            </button>
          </div>
        </form>
      </div>

      <!-- Order summary sidebar -->
      <div :style="{ backgroundColor:C.panel,border:`1px solid ${C.edge}`,borderRadius:'3px',padding:'24px',position:'sticky',top:'80px' }">
        <div :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.12em',textTransform:'uppercase',marginBottom:'16px' }">ORDER SUMMARY</div>

        <template v-if="selectedService">
          <div :style="{ marginBottom:'16px',paddingBottom:'16px',borderBottom:`1px solid ${C.edge}` }">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
              <span :style="{ display:'flex', color:C.amber }"><Icon :name="selectedService.icon" size="18" /></span>
              <span :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'14px',fontWeight:600,color:C.bright,lineHeight:1.3 }">{{ selectedService.name }}</span>
            </div>
            <div v-if="selectedTier" style="display:flex;justify-content:space-between;align-items:center;">
              <span :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'11px',color:C.dim,letterSpacing:'0.06em' }">{{ selectedTier }} tier</span>
              <span :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'11px',color:C.amber }">{{ selectedTierData?.turnaround }}</span>
            </div>
          </div>

          <div v-if="selectedTier" style="margin-bottom:20px;">
            <div :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.08em',marginBottom:'8px',textTransform:'uppercase' }">Includes</div>
            <div v-for="f in selectedTierData?.features" :key="f" style="display:flex;gap:8px;margin-bottom:5px;">
              <span :style="{ color:C.amber,fontSize:'11px',flexShrink:0 }">-</span>
              <span :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'12px',color:C.dim }">{{ f }}</span>
            </div>
          </div>

          <div :style="{ borderTop:`1px solid ${C.edge}`,paddingTop:'16px' }">
            <div style="display:flex;justify-content:space-between;align-items:center;">
              <span :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'14px',fontWeight:600,color:C.bright }">Total</span>
              <span :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'22px',fontWeight:600,color:C.amber }">{{ tierPrice ? `$${tierPrice}` : '-' }}</span>
            </div>
          </div>
        </template>

        <p v-else :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'13px',color:C.dim,lineHeight:'1.65' }">
          Select a service and tier to see your order summary here.
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped>
input, textarea {
  width: 100%;
  padding: 11px 12px;
  background-color: #17171A;
  border: 1px solid #2A2A2F;
  border-radius: 3px;
  color: #F4F4F5;
  font-family: 'Space Grotesk', sans-serif;
  font-size: 14px;
  outline: none;
  box-sizing: border-box;
}
input::placeholder, textarea::placeholder { color: #5A5A62; }
input:focus, textarea:focus { border-color: #E8590C; }
</style>