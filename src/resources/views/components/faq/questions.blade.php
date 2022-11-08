<template x-for="faq in filteredFaqs()" :key="faq.id">
    <x-faq.question></x-faq.question>
</template>
