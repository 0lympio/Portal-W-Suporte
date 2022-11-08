@props(['name'])
<div class="min-h-full w-full">
    <main>
        <div class="w-full py-6 sm:px-6 lg:px-8">
            <!-- Trocar esse conteudo código pelo conteúdo preferível [INÍCIO] -->
            <div class="px-4 py-6 sm:px-0">
                <div class="h-96 rounded-lg border-gray-200 grid grid-cols-1 gap-4 place-items-center">
                    <div class="flex flex-col items-center">
                        <div class="text-red-semparar">
                            <i class="fa-regular fa-thumbs-down fa-3x"></i>
                        </div>
                        @if ($name === 'Feed de Notícias')
                            <p class="text-red-semparar mt-4">No momento sem novidades! Mas você tem uma sugestão? Manda
                                pra gente lá pelo <strong>Fala com a gente</strong>!
                            </p>
                        @endif
                        @if ($name === 'Enquetes')
                            <p class="text-red-semparar mt-4">Hum... Nada aqui por enquanto. Mas em breve teremos novos
                                desafios. Fique ON!

                            </p>
                        @endif
                        @if (!in_array($name, ['Feed de Notícias', 'Enquetes']))
                            <p class="text-red-semparar mt-4">{{ $name }}
                                indisponíveis no momento. Aguarde, em breve teremos novidades...</p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Trocar esse conteudo código pelo conteúdo preferível [FIM] -->
        </div>
    </main>
</div>
