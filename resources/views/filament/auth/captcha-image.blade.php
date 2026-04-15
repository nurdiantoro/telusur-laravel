<div class="space-y-2">
    <img src="{{ $captchaUrl }}" alt="Captcha" class="h-12 rounded-md border border-gray-200">

    <button
        type="button"
        wire:click="refreshCaptcha"
        class="text-sm font-medium text-primary-600 hover:text-primary-500"
    >
        Muat ulang captcha
    </button>
</div>
