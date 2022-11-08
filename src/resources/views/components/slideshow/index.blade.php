<div class="overflow-x-hidden flex flex-col w-4/6 py-3 relative" x-data="carousel({{ collect($slides) }})" x-init="initSlide()" x-cloak
    id="carousel">
    <div class="sliderAx h-full inset-0 flex">
        <template x-for="(image, index) in images" :key="index">
            <div :id="$id('slider')" class="container mx-auto" :style="links[index] ? 'cursor: pointer' : ''">
                <div class="bg-cover bg-center h-full text-white py-24 px-10 object-fill"
                    :style="`background-image: url(${image})`" @click="(links[index] ? location.href=links[index] : '')">
                </div>
            </div>
        </template>
    </div>

    <div class="flex justify-center pb-2 mt-2 absolute bottom-[2%] left-[38%]">
        <template x-for="(image, index) in images" :key="index">
            <button :id="$id('button')" @click="sliderButton(index + 1)"
                class="bg-gray-300 rounded-full h-2 w-2 pb-2 mx-2"></button>
        </template>
    </div>
</div>

<script>
    $(window).ready(() => {
        let size = @json($slides->count());

        for (let i = 1; i <= size; i++) {
            if (i !== 1) {
                $(`#slider-${i}`).hide();
            }
        }

        $(`#button-1`).removeClass("bg-gray-300");
        $(`#button-1`).addClass("bg-red-semparar");
    });

    function carousel(slides) {
        return {
            duration: slides[0].duration * 1000,
            links: Alpine.raw(slides.map(slide => slide.link)),
            images: Alpine.raw(slides.map(slide => 'storage/' + slide.path)),
            current: 1,

            initSlide() {
                this.loopSlider();
            },

            loopSlider() {
                let interval = setInterval(() => {
                    this.changeSlide();

                    if (this.current < this.images.length) {
                        this.current += 1
                    } else {
                        this.current = 1
                    }

                }, this.duration);
            },

            changeSlide() {
                for (let i = 1; i <= this.images.length; i++) {
                    if (i !== this.current) {
                        $(`#slider-${i}`).fadeOut(200);
                        $(`#button-${i}`).removeClass("bg-red-semparar");
                        $(`#button-${i}`).addClass("bg-gray-300");
                    } else {
                        $(`#slider-${i}`).delay(200).fadeIn(200);
                        $(`#button-${i}`).removeClass("bg-gray-300");
                        $(`#button-${i}`).addClass("bg-red-semparar");
                    }
                }
            },

            sliderButton(index) {
                this.current = index;
                this.changeSlide();

            },
        }
    }
</script>
