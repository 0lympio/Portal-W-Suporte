<script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#comment',
        icons: 'small',
        toolbar_location: 'bottom',
        plugins: ['emoticons'],
        toolbar: 'undo redo bold italic emoticons',
        menubar: false,
        height: '200',
        statusbar: false,
        placeholder: 'Escreva um comentário...',
        language: 'pt_BR',
        content_style: 'body { font-family: Arial, Helvetica, sans-serif; font-size: 16px }',

        // Permite caracteres especiais (acentos e outros)
        entity_encoding: 'raw',
    });
</script>
