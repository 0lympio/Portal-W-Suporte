<div x-data="{ show: true }" x-cloak x-show="show" class="fixed inset-0 bg-gray-600 bg-opacity-50 h-full w-full z-50">
    <div class="flex flex-col relative w-1/3 top-6 mx-auto p-5 border shadow shadow-black rounded-md bg-white items-center justify-center overflow-y-auto"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-300"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
        <h1 class="text-2xl text-gray-semparar">Comunicado</h1>
        <div class="carousel-container" x-data="{ item: 0, posts: {{ collect($popups) }} }">
            <template x-for="(post,index) in posts" :key="index">
                <div class="carousel-item" :class="{ 'active': index === item }">
                    <img :src="'{{ asset('storage') }}' + '/' + post.thumb" class="rounded-t-lg h-56 object-cover w-full" />
                    <div class="flex justify-center z-30">
                        <img src="https://picsum.photos/50/50" class="rounded-full object-center border-4 border-white -mt-6 shadow align-center" />
                    </div>
                    <p class="post-title line-clamp-2" x-text="post.title"> </p>
                    <div class="flex flex-col justify-center items-center">
                        <p class="post-description line-clamp-2" x-text="post.description">
                        </p>
                        <a :href="'posts/' + post.slug + '/1'"
                            class="text-black text-center bg-gradient-to-r from-red-300 to-red-500 scale-90 hover:scale-100  shadow-cla-blue px-4 py-1 rounded-lg">
                            Leia mais
                        </a>
                    </div>
                </div>
            </template>
            <div class="controls">
                <button type="button" class="rounded-full px-2 button-red"
                        @click="item = item > 0 ? item - 1 : posts.length - 1">
                    <i class="fa-solid fa-angle-left"></i>
                </button>
                <button type="button" class="rounded-full px-2 button-red"
                        @click="item = item < posts.length - 1 ? item + 1 : 0">
                    <i class="fa-solid fa-angle-right"></i>
                </button>
            </div>
        </div>
        <x-button @click="show = false" class="bg-blue-semparar text-white mt-2">Ver depois</x-button>
    </div>
</div>

<script>
    function popup(popups) {
        return {
            selected: 0,
            current: 0,
            last: popups.length - 1,
            count: popups.length,
            duration: 5000,
            posts: popups,
            update(val) {
                this.last = this.current;
                this.current = val;
                if (this.current >= this.count) {
                    this.last = this.popups.count - 1;
                    this.current = 0
                }
            },
            initSlide() {
                setInterval(() => {
                    this.update(this.current + 1);
                }, this.duration);
            },
        }
    }
</script>
