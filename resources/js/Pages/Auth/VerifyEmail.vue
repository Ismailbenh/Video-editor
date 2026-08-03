<script setup>
import { computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  status: String,
})

const form = useForm({})

function submit() {
  form.post(route('verification.send'))
}

const verificationLinkSent = computed(() => props.status === 'verification-link-sent')
</script>

<template>
  <Head title="Email Verification" />

  <div class="min-h-screen bg-[#121214] flex flex-col items-center justify-center p-6">
    <div class="flex items-center gap-2.5 mb-12">
      
      <span class="font-sans font-semibold text-base text-[#F4F4F5] tracking-[0.05em]">
        FRAMECUT
      </span>
    </div>

    <div class="w-full max-w-[420px] bg-[#1C1C1F] border border-[#2A2A2F] rounded overflow-hidden">
      

      <div class="p-7">
        <div class="mb-6">
          <h1 class="font-sans text-[22px] font-bold text-[#F4F4F5] mb-1.5 tracking-[-0.01em]">
            Verify your email
          </h1>
          <p class="font-sans text-[13px] text-[#8B8B93] leading-relaxed">
            Thanks for signing up! Before getting started, please verify your email by clicking the link we just emailed you.
          </p>
        </div>

        <div
          v-if="verificationLinkSent"
          class="bg-[#E8590C]/[0.08] border border-[#E8590C]/20 rounded-sm px-3 py-2.5 mb-5"
        >
          <p class="font-mono text-[10px] text-[#E8590C] leading-relaxed m-0">
            A new verification link has been sent to the email you provided during registration.
          </p>
        </div>

        <form @submit.prevent="submit">
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full py-3.5 px-6 text-white font-sans text-sm font-bold rounded-sm border-0 tracking-[0.04em] transition-colors mb-4"
            :class="[
              form.processing ? 'bg-[#E8590C]/50 cursor-wait' : 'bg-[#E8590C] cursor-pointer hover:bg-[#C44A0A]',
            ]"
          >
            {{ form.processing ? 'Sending...' : 'Resend Verification Email' }}
          </button>
        </form>

        <div class="text-center border-t border-[#2A2A2F] pt-5">
          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="font-sans text-[13px] text-[#8B8B93] hover:text-[#E8590C] bg-transparent border-0 cursor-pointer"
          >
            Log Out
          </Link>
        </div>
      </div>
    </div>

    <p class="mt-6 font-mono text-[10px] text-[#3D3D45] tracking-[0.08em]">
      2025 FRAMECUT STUDIO
    </p>
  </div>
</template>