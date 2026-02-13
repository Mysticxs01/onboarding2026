<x-guest-layout>
    <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 2px solid #C59D42;">
        <h2 style="color: #1B365D; font-size: 1.5rem; font-weight: bold; margin: 0;">
            Crear Cuenta
        </h2>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nombre Completo')" style="color: #1B365D;" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" style="border-color: #1B365D;" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Correo Electrónico')" style="color: #1B365D;" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" style="border-color: #1B365D;" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" style="color: #1B365D;" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" style="border-color: #1B365D;" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" style="color: #1B365D;" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" style="border-color: #1B365D;" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a style="color: #28A745; text-decoration: none;" href="{{ route('login') }}" onmouseover="this.style.color='#1B365D'" onmouseout="this.style.color='#28A745'">
                {{ __('¿Ya tiene cuenta?') }}
            </a>

            <button type="submit" class="btn-primary" style="margin: 0;">
                {{ __('Registrarse') }}
            </button>
        </div>
    </form>
</x-guest-layout>
