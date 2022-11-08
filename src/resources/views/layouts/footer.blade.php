<footer class="bg-gray-100 text-center lg:text-left">
    <div class="flex flex-row items-center p-6 text-gray-800">
        <div class="mb-6 md:mb-0 ml-16">
            <img src="{{ asset('images/logo-new-brand-big-100x100.png') }}">
        </div>
        <p class="w-96 ml-4 ">
            Somos a primeira empresa de pagamento automático em pedágios, estacionamentos e abastecimento do Brasil.
        </p>
    </div>
    <div class="text-center text-gray-700 p-4" style="background-color: rgba(0, 0, 0, 0.2);">
        © 2022 Copyright:
        <a class="text-gray-800" href="https://wtechnology.com.br/">W Technology. Todos os Direitos
            Reservados</a>
    </div>
</footer>


<style>
    .highlight {
        border-radius: 5px;
        border: 0;
        background: rgba(255, 255, 255, 0.7);
        color: black;
        font-size: 12px;
    }
</style>

<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip({
            classes: {
                "ui-tooltip": "highlight"
            }
        })
    })
</script>
