<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Dropdown from '../Components/Dropdown.vue'
import DropdownLink from '../Components/DropdownLink.vue'

const C = {
  bg: '#121214',
  panel: '#1C1C1F',
  amber: '#E8590C',
  bright: '#F4F4F5',
  dim: '#8B8B93',
  edge: '#2A2A2F',
  ledge: '#3D3D45',
}

const navLinks = [
  { to: '/services', label: 'Services' },
  { to: '/portfolio', label: 'Portfolio' },
  { to: '/contact', label: 'Contact' },
]

const footerServiceLinks = ['YouTube Editing', 'Instagram Reels', 'Batch Editing', 'Stream Clipping']
const footerNavLinks = [
  { to: '/portfolio', label: 'Portfolio' },
  { to: '/order', label: 'Order Now' },
  { to: '/contact', label: 'Contact' },
  { to: '/login', label: 'Client Login' },
]
const socials = ['YT', 'IG', 'TW']

const page = usePage()
const menuOpen = ref(false)

const currentPath = computed(() => page.props.ziggy?.location ?? page.url ?? '')
const authUser = computed(() => page.props.auth?.user ?? null)
const isAdmin = computed(() => authUser.value?.role === 'admin')

function isActive(to) {
  return currentPath.value.includes(to)
}

function closeMenu() {
  menuOpen.value = false
}
</script>

<template>
  <div :style="{ backgroundColor: C.bg, minHeight: '100vh', color: C.bright }">
    <header
      :style="{
        position: 'sticky',
        top: 0,
        zIndex: 50,
        backgroundColor: 'rgba(18,18,20,0.94)',
        backdropFilter: 'blur(12px)',
        borderBottom: `1px solid ${C.edge}`,
      }"
    >
      <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px">
        <div style="display: flex; align-items: center; justify-content: space-between; height: 60px">
          <Link href="/home" style="text-decoration: none; display: flex; align-items: center; gap: 10px">
            <span :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontWeight: 600, fontSize: '15px', color: C.bright, letterSpacing: '0.05em' }">
              FRAMECUT
            </span>
          </Link>

          <nav class="hidden md:flex" style="display: flex; gap: 32px; align-items: center">
            <Link
              v-for="l in navLinks"
              :key="l.to"
              :href="l.to"
              :style="{
                textDecoration: 'none',
                fontFamily: `'Space Grotesk', sans-serif`,
                fontSize: '14px',
                fontWeight: 500,
                color: isActive(l.to) ? C.bright : C.dim,
                transition: 'color 0.15s',
                letterSpacing: '0.02em',
              }"
            >
              {{ l.label }}
            </Link>
          </nav>

          <div style="display: flex; gap: 10px; align-items: center">
            <Link
              v-if="!authUser"
              href="/login"
              class="hidden md:block"
              :style="{
                textDecoration: 'none',
                padding: '6px 14px',
                fontFamily: `'Space Grotesk', sans-serif`,
                fontSize: '13px',
                fontWeight: 500,
                color: C.dim,
              }"
            >
              Login
            </Link>

            

            <Link
              href="/order"
              :style="{
                textDecoration: 'none',
                padding: '7px 18px',
                backgroundColor: C.amber,
                color: '#fff',
                fontFamily: `'Space Grotesk', sans-serif`,
                fontSize: '13px',
                fontWeight: 600,
                borderRadius: '3px',
                letterSpacing: '0.03em',
                transition: 'background 0.15s',
                flexShrink: 0,
              }"
            >
              Order Now
            </Link>

            <div style="position: relative">
              <button
                @click="menuOpen = !menuOpen"
                class="flex md:hidden"
                style="
                  background: none;
                  border: none;
                  cursor: pointer;
                  padding: 6px;
                  display: flex;
                  flex-direction: column;
                  gap: 4px;
                  align-items: center;
                "
                :style="{ color: C.bright }"
                aria-label="Toggle menu"
                :aria-expanded="menuOpen"
              >
                <div
                  :style="{
                    width: '18px',
                    height: '1.5px',
                    backgroundColor: C.bright,
                    transition: 'transform 0.2s',
                    transform: menuOpen ? 'rotate(45deg) translate(4px, 4px)' : 'none',
                  }"
                />
                <div
                  :style="{
                    width: '18px',
                    height: '1.5px',
                    backgroundColor: C.bright,
                    opacity: menuOpen ? 0 : 1,
                    transition: 'opacity 0.2s',
                  }"
                />
                <div
                  :style="{
                    width: '18px',
                    height: '1.5px',
                    backgroundColor: C.bright,
                    transition: 'transform 0.2s',
                    transform: menuOpen ? 'rotate(-45deg) translate(4px, -4px)' : 'none',
                  }"
                />
              </button>

              <div
                v-if="menuOpen"
                :style="{
                  position: 'absolute',
                  top: 'calc(100% + 10px)',
                  right: 0,
                  width: '190px',
                  backgroundColor: C.panel,
                  border: `1px solid ${C.edge}`,
                  borderRadius: '3px',
                  boxShadow: '0 12px 28px rgba(0,0,0,0.45)',
                  zIndex: 60,
                  overflow: 'hidden',
                }"
              >
                <Link
                  v-for="l in navLinks"
                  :key="l.to"
                  :href="l.to"
                  @click="closeMenu"
                  :style="{
                    display: 'block',
                    textDecoration: 'none',
                    color: C.bright,
                    fontFamily: `'Space Grotesk', sans-serif`,
                    fontSize: '13px',
                    fontWeight: 500,
                    padding: '10px 14px',
                    borderBottom: `1px solid ${C.edge}`,
                  }"
                >
                  {{ l.label }}
                </Link>
                <Link
                  v-if="!authUser"
                  href="/login"
                  @click="closeMenu"
                  :style="{
                    display: 'block',
                    textDecoration: 'none',
                    color: C.dim,
                    fontFamily: `'Space Grotesk', sans-serif`,
                    fontSize: '13px',
                    padding: '10px 14px',
                  }"
                >
                  Login
                </Link>
                <template v-else>
                  <Link
                    v-if="isAdmin"
                    href="/admin/overview"
                    @click="closeMenu"
                    :style="{
                      display: 'block',
                      textDecoration: 'none',
                      color: C.amber,
                      fontFamily: `'Space Grotesk', sans-serif`,
                      fontSize: '13px',
                      padding: '10px 14px',
                      borderBottom: `1px solid ${C.edge}`,
                    }"
                  >
                    Dashboard
                  </Link>
                  <Link
                    href="/profile"
                    @click="closeMenu"
                    :style="{
                      display: 'block',
                      textDecoration: 'none',
                      color: C.bright,
                      fontFamily: `'Space Grotesk', sans-serif`,
                      fontSize: '13px',
                      padding: '10px 14px',
                      borderBottom: `1px solid ${C.edge}`,
                    }"
                  >
                    Profile
                  </Link>
                  <Link
                    href="/logout"
                    method="post"
                    as="button"
                    :style="{
                      display: 'block',
                      width: '100%',
                      textAlign: 'left',
                      background: 'none',
                      border: 'none',
                      cursor: 'pointer',
                      color: C.dim,
                      fontFamily: `'Space Grotesk', sans-serif`,
                      fontSize: '13px',
                      padding: '10px 14px',
                    }"
                  >
                    Log Out
                  </Link>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <main>
      <slot />
    </main>

    <footer :style="{ borderTop: `1px solid ${C.edge}`, marginTop: '96px', backgroundColor: C.panel }">
      <div style="max-width: 1200px; margin: 0 auto; padding: 56px 24px 32px">
        <div
          style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 40px;
            margin-bottom: 48px;
          "
        >
          <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px">
              <span :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontWeight: 600, fontSize: '13px', color: C.bright, letterSpacing: '0.05em' }">
                FRAMECUT
              </span>
            </div>
            <p :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontSize: '13px', color: C.dim, lineHeight: '1.75', maxWidth: '220px' }">
              Professional video editing for creators, brands, and podcasters who refuse to ship mediocre content.
            </p>
          </div>

          <div>
            <h4
              :style="{
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '10px',
                color: C.ledge,
                letterSpacing: '0.12em',
                textTransform: 'uppercase',
                marginBottom: '14px',
              }"
            >
              SERVICES
            </h4>
            <div v-for="s in footerServiceLinks" :key="s" style="margin-bottom: 8px">
              <Link
                href="/services"
                :style="{
                  textDecoration: 'none',
                  fontFamily: `'Space Grotesk', sans-serif`,
                  fontSize: '13px',
                  color: C.dim,
                  transition: 'color 0.15s',
                }"
              >
                {{ s }}
              </Link>
            </div>
          </div>

          <div>
            <h4
              :style="{
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '10px',
                color: C.ledge,
                letterSpacing: '0.12em',
                textTransform: 'uppercase',
                marginBottom: '14px',
              }"
            >
              NAVIGATE
            </h4>
            <div v-for="l in footerNavLinks" :key="l.to" style="margin-bottom: 8px">
              <Link
                :href="l.to"
                :style="{ textDecoration: 'none', fontFamily: `'Space Grotesk', sans-serif`, fontSize: '13px', color: C.dim }"
              >
                {{ l.label }}
              </Link>
            </div>
          </div>

          <div>
            <h4
              :style="{
                fontFamily: `'JetBrains Mono', monospace`,
                fontSize: '10px',
                color: C.ledge,
                letterSpacing: '0.12em',
                textTransform: 'uppercase',
                marginBottom: '14px',
              }"
            >
              CONTACT
            </h4>
            <p :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontSize: '13px', color: C.dim, marginBottom: '8px' }">
              hello@framecut.studio
            </p>
            <p :style="{ fontFamily: `'Space Grotesk', sans-serif`, fontSize: '13px', color: C.dim, marginBottom: '16px' }">
              Available Mon-Fri
            </p>
            <div style="display: flex; gap: 16px">
              <span
                v-for="soc in socials"
                :key="soc"
                :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '10px', color: C.ledge, cursor: 'pointer', letterSpacing: '0.08em' }"
              >
                {{ soc }}
              </span>
            </div>
          </div>
        </div>

        <div
          :style="{
            borderTop: `1px solid ${C.edge}`,
            paddingTop: '20px',
            display: 'flex',
            justifyContent: 'space-between',
            flexWrap: 'wrap',
            gap: '8px',
          }"
        >
          <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '10px', color: C.ledge }">
            2025 FRAMECUT STUDIO - ALEX NOVO
          </span>
          <span :style="{ fontFamily: `'JetBrains Mono', monospace`, fontSize: '10px', color: C.ledge }">
            ALL RIGHTS RESERVED
          </span>
        </div>
      </div>
    </footer>
  </div>
</template>