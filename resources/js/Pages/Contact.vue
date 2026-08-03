<script setup>
import { computed } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import PublicLayout from '../Layouts/PublicLayout.vue'

defineOptions({ layout: PublicLayout })

const page = usePage()
const flash = computed(() => page.props.flash ?? {})

const C = {
  panel: '#1C1C1F', amber: '#E8590C', bright: '#F4F4F5', dim: '#8B8B93', edge: '#2A2A2F', ledge: '#3D3D45',
}

const infoItems = [
  { label: 'EMAIL', value: 'hello@framecut.studio' },
  { label: 'RESPONSE TIME', value: 'Within 1 business day' },
  { label: 'TIMEZONE', value: 'GMT+1 (London)' },
  { label: 'AVAILABILITY', value: 'Monday - Friday' },
]

const socials = ['YouTube', 'Instagram', 'Twitter / X', 'LinkedIn']

const form = useForm({
  name: '',
  email: '',
  message: '',
})

function submit() {
  form.post(route('contact.store'))
}
</script>

<template>
  <Head title="Contact" />

  <div style="max-width:1200px;margin:0 auto;padding:64px 24px 96px;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:start;">

      <div>
        <span :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.14em',textTransform:'uppercase',display:'block',marginBottom:'12px' }">
          GET IN TOUCH
        </span>
        <h1 :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'clamp(36px,5vw,56px)',fontWeight:700,color:C.bright,lineHeight:1.05,letterSpacing:'-0.03em',marginBottom:'20px' }">
          Let's talk about your project.
        </h1>
        <p :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'16px',color:C.dim,lineHeight:'1.7',marginBottom:'40px' }">
          Whether you have a brief ready or just a rough idea, send me a message and I will get back to you within one business day.
        </p>

        <div style="margin-bottom:32px;">
          <div v-for="item in infoItems" :key="item.label"
            :style="{ display:'flex',justifyContent:'space-between',alignItems:'center',padding:'14px 0',borderBottom:`1px solid ${C.edge}` }">
            <span :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.1em',textTransform:'uppercase' }">{{ item.label }}</span>
            <span :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'13px',color:C.bright }">{{ item.value }}</span>
          </div>
        </div>

        <div>
          <h3 :style="{ fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.12em',textTransform:'uppercase',marginBottom:'12px' }">FOLLOW</h3>
          <div style="display:flex;gap:12px;">
            <span v-for="soc in socials" :key="soc"
              :style="{
                fontFamily:`'Space Grotesk',sans-serif`,fontSize:'12px',color:C.dim,
                padding:'6px 12px', border:`1px solid ${C.edge}`, borderRadius:'3px',
                cursor:'pointer', transition:'border-color 0.15s, color 0.15s', display:'inline-block',
              }">{{ soc }}</span>
          </div>
        </div>
      </div>

      <div :style="{ backgroundColor:C.panel, border:`1px solid ${C.edge}`, borderRadius:'4px', padding:'36px' }">
        <form @submit.prevent="submit">
          <h2 :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'20px',fontWeight:700,color:C.bright,marginBottom:'8px' }">
            Send a message
          </h2>
          <p :style="{ fontFamily:`'Space Grotesk',sans-serif`,fontSize:'13px',color:C.dim,marginBottom:'28px' }">
            All fields are required.
          </p>

          <div v-if="flash.success" style="margin-bottom:18px; padding:12px 14px; border:1px solid #E8590C; background:rgba(232,89,12,0.12); border-radius:3px;">
            <span style="font-family:'Space Grotesk',sans-serif;font-size:13px;color:#F4F4F5;">{{ flash.success }}</span>
          </div>

          <div style="margin-bottom:20px;">
            <label :style="{ display:'block',fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.1em',textTransform:'uppercase',marginBottom:'6px' }">YOUR NAME</label>
            <input v-model="form.name" type="text" placeholder="Jane Smith" required />
            <p v-if="form.errors.name" style="margin:6px 0 0;color:#fca5a5;font-size:12px;">{{ form.errors.name }}</p>
          </div>

          <div style="margin-bottom:20px;">
            <label :style="{ display:'block',fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.1em',textTransform:'uppercase',marginBottom:'6px' }">EMAIL ADDRESS</label>
            <input v-model="form.email" type="email" placeholder="jane@example.com" required />
            <p v-if="form.errors.email" style="margin:6px 0 0;color:#fca5a5;font-size:12px;">{{ form.errors.email }}</p>
          </div>

          <div style="margin-bottom:28px;">
            <label :style="{ display:'block',fontFamily:`'JetBrains Mono',monospace`,fontSize:'10px',color:C.dim,letterSpacing:'0.1em',textTransform:'uppercase',marginBottom:'6px' }">YOUR MESSAGE</label>
            <textarea v-model="form.message" rows="6" placeholder="Tell me about your project - type of content, approximate length, deadline, any reference work..." style="resize:vertical;min-height:130px;" required></textarea>
            <p v-if="form.errors.message" style="margin:6px 0 0;color:#fca5a5;font-size:12px;">{{ form.errors.message }}</p>
          </div>

          <button type="submit" :disabled="form.processing"
            :style="{
              width:'100%', padding:'13px 24px', backgroundColor:C.amber, color:'#fff',
              fontFamily:`'Space Grotesk',sans-serif`, fontSize:'14px', fontWeight:600,
              borderRadius:'3px', border:'none', cursor: form.processing ? 'wait' : 'pointer', letterSpacing:'0.03em',
              opacity: form.processing ? 0.8 : 1,
            }">{{ form.processing ? 'Sending...' : 'Send Message' }}</button>
        </form>
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