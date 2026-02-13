<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 2px solid #C59D42;">
        <h2 style="color: #1B365D; font-size: 1.5rem; font-weight: bold; margin: 0;">
            Iniciar Sesión
        </h2>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" style="color: #1B365D;" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" style="border-color: #1B365D;" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" style="color: #1B365D;" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" style="border-color: #1B365D;" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded" style="border-color: #1B365D; accent-color: #28A745;" name="remember">
                <span class="ms-2 text-sm" style="color: #1B365D;">{{ __('Recuérdame') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a style="color: #28A745; text-decoration: none;" href="{{ route('password.request') }}" onmouseover="this.style.color='#1B365D'" onmouseout="this.style.color='#28A745'">
                    {{ __('¿Olvidó su contraseña?') }}
                </a>
            @endif

            <button type="submit" class="btn-primary" style="margin: 0;">
                {{ __('Ingresar') }}
            </button>
        </div>
    </form>
</x-guest-layout>
