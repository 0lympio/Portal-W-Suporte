<div x-show="openCalculator">
    <div id="calculator" class="ui-widget-content text-center z-[99999] absolute rounded-2xl h-[420px] w-[287px] bg-[#1C1C1C]">
        <div class="flex w-full justify-end text-2xl px-4 py-2 cursor-move">
            <button class="text-black" @click="openCalculator = false;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="overflow-x-auto">
            <div id="screen" class="relative h-12 text-black text-4xl whitespace-nowrap cursor-move">
                <p id="value" class="mr-2">0</p>
            </div>
        </div>
        <div>
            <div class="button bg-[#a2a2a2]">C</div>
            <div class="button bg-[#a2a2a2]">&plusmn;</div>
            <div class="button bg-[#a2a2a2]">%</div>
            <div class="button calc-button bg-[#ff9500]">&divide;</div>
            <div class="button number bg-[#505050]">7</div>
            <div class="button number bg-[#505050]">8</div>
            <div class="button number bg-[#505050]">9</div>
            <div class="button calc-button bg-[#ff9500]">&#xd7;</div>
            <div class="button number bg-[#505050]">4</div>
            <div class="button number bg-[#505050]">5</div>
            <div class="button number bg-[#505050]">6</div>
            <div class="button calc-button bg-[#ff9500]">-</div>
            <div class="button number bg-[#505050]">1</div>
            <div class="button number bg-[#505050]">2</div>
            <div class="button number bg-[#505050]">3</div>
            <div class="button calc-button bg-[#ff9500]">+</div>
            <div class="button number bg-[#505050]">0</div>
            <div class="button number bg-[#505050]">00</div>
            <div class="button bg-[#505050]">.</div>
            <div class="button calc-button bottom-right bg-[#ff9500]">=</div>
        </div>
    </div>
</div>

<style>
    .button {
        cursor: pointer;
        display: inline-block;
        height: 53px;
        width: 57.5px;
        margin: 3px;
        border-radius: 50%;
        line-height: 50px;
        text-align: center;
        font-size: 1.6rem;
        color: white;
    }
</style>
