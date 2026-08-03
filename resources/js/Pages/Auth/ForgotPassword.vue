<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
  status: String,
})

const form = useForm({
  email: '',
})

function submit() {
  form.post(route('password.email'))
}
</script>

<template>
  <Head title="Forgot Password" />

  <div class="min-h-screen bg-[#121214] flex flex-col items-center justify-center p-6">
    <Link href="/home" class="no-underline flex items-center gap-2.5 mb-12">
      <span class="font-sans font-semibold text-base text-[#F4F4F5] tracking-[0.05em]">
        FRAMECUT
      </span>
    </Link>

    <div class="w-full max-w-[420px] bg-[#1C1C1F] border border-[#2A2A2F] rounded overflow-hidden">
      

      <div class="p-7">
        <div class="mb-6">
          <h1 class="font-sans text-[22px] font-bold text-[#F4F4F5] mb-1.5 tracking-[-0.01em]">
            Reset your password
          </h1>
          <p class="font-sans text-[13px] text-[#8B8B93]">
            Enter your email and we'll send you a reset link.
          </p>
        </div>

        <div
          v-if="status"
          class="bg-[#E8590C]/[0.08] border border-[#E8590C]/20 rounded-sm px-3 py-2.5 mb-5"
        >
          <p class="font-mono text-[10px] text-[#E8590C] leading-relaxed m-0">{{ status }}</p>
        </div>

        <form @submit.prevent="submit">
          <div class="mb-7">
            <label class="block font-mono text-[10px] text-[#8B8B93] tracking-[0.1em] uppercase mb-1.5">
              Email Address
            </label>
            <input
              v-model="form.email"
              type="email"
              placeholder="you@example.com"
              required
              autofocus
              autocomplete="username"
            />
            <p v-if="form.errors.email" class="font-sans text-xs text-[#EF4444] mt-1.5">
              {{ form.errors.email }}
            </p>
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="w-full py-3.5 px-6 text-white font-sans text-sm font-bold rounded-sm border-0 tracking-[0.04em] transition-colors"
            :class="[
              form.processing ? 'bg-[#E8590C]/50 cursor-wait' : 'bg-[#E8590C] cursor-pointer hover:bg-[#C44A0A]',
            ]"
          >
            {{ form.processing ? 'Sending...' : 'Email Password Reset Link' }}
          </button>
        </form>

        <div class="mt-6 text-center border-t border-[#2A2A2F] pt-5">
          <Link
            :href="route('login')"
            class="font-sans text-[13px] text-[#E8590C] font-semibold no-underline"
          >
            Back to sign in
          </Link>
        </div>
      </div>
    </div>

    <p class="mt-6 font-mono text-[10px] text-[#3D3D45] tracking-[0.08em]">
      2025 FRAMECUT STUDIO
    </p>
  </div>
</template>

<style scoped>
input[type='email'] {
  background-color: #1c1c1f;
  color: #f4f4f5;
  border: 1px solid #2a2a2f;
  border-radius: 3px;
  font-family: 'Space Grotesk', sans-serif;
  font-size: 14px;
  padding: 9px 12px;
  transition: border-color 0.15s;
  width: 100%;
}
input:focus {
  border-color: #e8590c;
  outline: none;
}
input::placeholder {
  color: #8b8b93;
}
</style>