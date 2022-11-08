<x-app-layout>
    <div x-data="init()" x-cloak>

        <h1 class="text-xl text-gray-semparar">Fique ON</h1>
        <template x-if="faqs.length > 0">
            <div>
                <div class="flex items-center justify-end mb-2">
                    <x-faq.search></x-faq.search>
                </div>

                <div class="flex p-6 mt-4 bg-white">
                    <div class="flex-1 mt-8 lg:mt-0">
                        <x-faq.questions></x-faq.questions>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="faqs.length === 0 ">
            <div>

                <x-no-data name="Fique ON" />
            </div>
        </template>
    </div>
    <script>
        const faqsData = []
        @foreach ($faqs as $faq)
            faqsData.push(@json($faq))
        @endforeach

        window.init = function() {

            return {
                faqs: faqsData,
                search: '',
                selected: null,

                filteredFaqs() {
                    return this.faqs.filter(
                        i => i.title.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                highlightSearch(s) {
                    if (this.search === '') return s;

                    return s.replaceAll(
                        new RegExp(`(${this.search.toLowerCase()})`, 'ig'),
                        '<strong class="font-semibold bg-blue-100">$1</strong>'
                    )
                },
            }
        }
    </script>

    <style>
        #content h1,
        #content h2,
        #content h3,
        #content h4,
        #content h5,
        #content h6 {
            font-size: revert !important;
            font-weight: revert !important;
        }

        #content ul,
        #content li,
        #content p {
            all: revert;
        }

        #content a {
            text-decoration: none;
            color: blue;
        }
    </style>
</x-app-layout>
