<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('primeiroAcesso.atualizar') }}">
            @csrf

            @if($user->id == null)
                <x-jet-input type="hidden" name="id" :value="old('id')"/>
                <x-jet-input type="hidden" name="role" :value="old('role')"/>
                <x-jet-input type="hidden" name="name" :value="old('name')"/>
            @else
                <x-jet-input type="hidden" name="id" value="{{ $user->id }}"/>
                <x-jet-input type="hidden" name="role" value="{{ $user->role }}"/>
                <x-jet-input type="hidden" name="name" value="{{ $user->name }}"/>
            @endif

            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>


            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <br>

            <div class="flex items-center justify-end mt-12">
                <div class="ml-4">
                    <a href="{{ route('login') }}"
                       class="btn btn-primary ml-5">
                        Voltar
                    </a>
                </div>
                <div class="ml-4">
                    <x-jet-button type="submit">
                        Confirmar
                    </x-jet-button>
                </div>
            </div>
        </form>

    </x-jet-authentication-card>
</x-guest-layout>

