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

        <form method="POST" action="{{ route('primeiroAcesso.verificacao') }}">
            @csrf

            <div>

                <x-jet-label for="cpf" value="CPF:" />
                <x-jet-input id="cpf" class="block mt-1 w-full"  onkeydown="fMasc( this, mCPF );" placeholder="000.000.000-00"  type="text" name="cpf" :value="old('cpf')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="dt_nasc" value="Data de Nascimento:" />
                <x-jet-input id="dt_nasc" class="block mt-1 w-full" type="date" name="dt_nasc" required />
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
<script type="text/javascript">

    function fMasc(objeto,mascara) {
        obj=objeto
        masc=mascara
        setTimeout("fMascEx()",1)
    }

    function fMascEx() {
        obj.value=masc(obj.value)
    }

    function mCPF(cpf){
        cpf=cpf.replace(/\D/g,"")
        cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
        cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
        cpf=cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
        return cpf
    }

</script>

