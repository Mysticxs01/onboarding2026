<x-guest-layout>
    <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 2px solid #C59D42;">
        <h2 style="color: #1B365D; font-size: 1.5rem; font-weight: bold; margin: 0;">
            Recuperar Contraseña
        </h2>
    </div>

    <div class="mb-4 text-sm" style="color: #1B365D;">
        {{ __('¿Olvidó su contraseña? No hay problema. Déjenos saber su dirección de correo electrónico y le enviaremos un enlace para restablecer la contraseña.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" style="color: #1B365D;" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus style="border-color: #1B365D;" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-center mt-6">
            <button type="submit" class="btn-primary" style="margin: 0;">
                {{ __('Enviar Enlace de Recuperación') }}
            </button>
        </div>
    </form>
</x-guest-layout>
