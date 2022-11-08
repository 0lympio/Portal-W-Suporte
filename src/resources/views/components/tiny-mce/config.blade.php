<div x-data="{ modalAddImage: false, path: null, selected: null, modalAddVideo: false }" x-cloak id="modal-add-image-or-video">
    <x-tiny-mce.modal-add-image></x-tiny-mce.modal-add-image>
    <x-tiny-mce.modal-add-video></x-tiny-mce.modal-add-video>
</div>
<script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#content',
        plugins: [
            'advlist',
            'table',
            'link',
            'image',
            'media',
            'lists',
            'code',
            'link',
            'wordcount',
            'fullscreen',
            'help',
            'emoticons',
            'codesample',
            'insertdatetime',
            'preview',
            'searchreplace',
            'charmap',
        ],
        toolbar: 'fullscreen language preview searchreplace | undo redo | styles | fontfamily fontsize | forecolor bold italic alignleft aligncenter alignright alignjustify | outdent indent | code table numlist bullist print link emoticons codesample insertdatetime charmap help',
        menubar: 'edit view insert format table help',

        height: '90vh',
        link_context_toolbar: true,
        branding: false,
        placeholder: 'Escreva seu post...',
        language: 'pt_BR',
        content_style: 'body { font-family: Arial, Helvetica, sans-serif; font-size: 16px }',

        // Permite caracteres especiais (acentos e outros)
        entity_encoding: 'raw',

        image_title: true,
        image_description: false,

        // Permite que adicione estilos, espaçamento e bordas personalizados as imagens
        image_advtab: true,

        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,

        // Barra de ferramento de contexto. Aparece quando seleciona um trecho de texto
        setup: (editor) => {
            editor.ui.registry.addContextToolbar('textselection', {
                predicate: (node) => !editor.selection.isCollapsed(),
                items: 'bold italic | forecolor | styles fontsize fontfamily | image',
                position: 'selection',
                scope: 'node',
            });
        },

        file_picker_callback: (callback, value, meta) => {
            if (meta.filetype === 'image') {
                const modal = document.getElementById('modal-add-image-or-video');
                Alpine.$data(modal).modalAddImage = true;

                const button = document.getElementById('button-add-image');

                button.addEventListener('click', () => {
                    callback(Alpine.$data(modal).path, { width: '400', height: '400' });
                    Alpine.$data(modal).selected = null;
                });
            }

            if (meta.filetype === 'media') {
                const modal = document.getElementById('modal-add-image-or-video');
                Alpine.$data(modal).modalAddVideo = true;

                const button = document.getElementById('button-add-video');

                button.addEventListener('click', () => {
                    callback(Alpine.$data(modal).path, { width: '400', height: '400' });
                    Alpine.$data(modal).selected = null;
                });
            }
        },
    });
</script>
