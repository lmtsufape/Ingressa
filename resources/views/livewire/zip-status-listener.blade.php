<div>
    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-lg shadow p-4 max-w-sm w-full">
                <h2 class="font-semibold text-lg mb-2">
                    Arquivo pronto!
                </h2>
                <p class="mb-4">
                    Seu arquivo ZIP foi gerado. O download deve iniciar automaticamente.
                </p>

                <div class="flex justify-end gap-2">
                    <button wire:click="$set('showModal', false)"
                            class="px-3 py-1 rounded border">
                        Fechar
                    </button>

                    @if($downloadUrl)
                        <a href="{{ $downloadUrl }}"
                           class="px-3 py-1 rounded bg-blue-600 text-white">
                            Baixar novamente
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
