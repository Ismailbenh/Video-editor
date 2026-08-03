<script setup>
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
  email: String,
  token: String,
})

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

function submit() {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head title="Reset Password" />

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
            Set a new password
          </h1>
          <p class="font-sans text-[13px] text-[#8B8B93]">
            Choose a strong password you haven't used before.
          </p>
        </div>

        <form @submit.prevent="submit">
          <div class="mb-4">
            <label class="block font-mono text-[10px] text-[#8B8B93] tracking-[0.1em] uppercase mb-1.5">
              Email Address
            </label>
            <input
              v-model="form.email"
              type="email"
              required
              autocomplete="username"
            />
            <p v-if="form.errors.email" class="font-sans text-xs text-[#EF4444] mt-1.5">
              {{ form.errors.email }}
            </p>
          </div>

          <div class="mb-4">
            <label class="block font-mono text-[10px] text-[#8B8B93] tracking-[0.1em] uppercase mb-1.5">
              New Password
            </label>
            <input
              v-model="form.password"
              type="password"
              placeholder="••••••••"
              required
              autofocus
              autocomplete="new-password"
            />
            <p v-if="form.errors.password" class="font-sans text-xs text-[#EF4444] mt-1.5">
              {{ form.errors.password }}
            </p>
          </div>

          <div class="mb-7">
            <label class="block font-mono text-[10px] text-[#8B8B93] tracking-[0.1em] uppercase mb-1.5">
              Confirm Password
            </label>
            <input
              v-model="form.password_confirmation"
              type="password"
              placeholder="••••••••"
              required
              autocomplete="new-password"
            />
            <p v-if="form.errors.password_confirmation" class="font-sans text-xs text-[#EF4444] mt-1.5">
              {{ form.errors.password_confirmation }}
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
            {{ form.processing ? 'Resetting...' : 'Reset Password' }}
          </button>
        </form>
      </div>
    </div>

    <p class="mt-6 font-mono text-[10px] text-[#3D3D45] tracking-[0.08em]">
      2025 FRAMECUT STUDIO
    </p>
  </div>
</template>

<style scoped>
input[type='email'],
input[type='password'] {
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