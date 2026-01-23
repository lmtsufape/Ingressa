<div>
    @if ($processando)
        <div wire:transition.opacity.duration.500ms
            class="fixed bottom-6 right-6 z-[10000]
                flex items-center gap-3
                !bg-blue-600 !text-white
                px-5 py-3 rounded-xl shadow-2xl
                border border-white/20 backdrop-blur-sm">

            <svg class="animate-spin h-5 w-5 !text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>

            <div class="flex flex-col">
                <span class="text-sm font-bold leading-none">
                    Processando...
                </span>
                <span class="text-xs text-blue-100 mt-1">
                    Gerando documentos
                </span>
            </div>
        </div>
    @endif

    @if ($showModal)
        <div
            style="position: fixed; inset: 0; z-index: 50; display: flex; align-items: center; justify-content: center; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">

            <div
                style="position: relative; width: 100%; max-width: 320px; background-color: white; border-radius: 12px; padding: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); text-align: center; margin: 20px;">

                @if ($downloadUrl)
                    <div
                        style="margin: 0 auto 20px auto; height: 50px; width: 50px; background-color: #dcfce7; color: #166534; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>

                    <h3 style="font-size: 18px; font-weight: bold; color: #111; margin-bottom: 8px;">Arquivo Pronto!
                    </h3>

                    <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 10px;">
                        @if ($downloadUrl)
                            <a href="{{ $downloadUrl }}"
                                style="display: block; width: 100%; padding: 10px; background-color: #2563eb; color: white; border-radius: 6px; text-decoration: none; font-weight: 600;">
                                Baixar Agora
                            </a>
                        @endif

                        <button wire:click="$set('showModal', false)"
                            style="width: 100%; padding: 10px; background-color: white; border: 1px solid #ccc; color: #333; border-radius: 6px; cursor: pointer;">
                            Fechar
                        </button>
                    </div>
                @else
                    <div
                        style="margin: 0 auto 20px auto; height: 50px; width: 50px; background-color: #f3f4f6; color: #6b7280; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>

                    <h3 style="font-size: 18px; font-weight: bold; color: #111;">Nada encontrado</h3>
                    <p style="color: #666; font-size: 14px; margin-top: 5px;">Documento indisponível.</p>

                    <div style="margin-top: 20px;">
                        <button wire:click="$set('showModal', false)"
                            style="width: 100%; padding: 10px; background-color: #111; color: white; border-radius: 6px; cursor: pointer; border: none;">
                            Entendido
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
