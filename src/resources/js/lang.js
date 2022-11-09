// the default locale
// you can for example take it from the URL.
let locale = "pt";

// the translation data
// you can load/fetch these from files or keep them hardcoded.
let messages = {
    pt: {
        category: "categoria",
        categories: "categorias",
        permission: "permissão",
        permissions: "permissões",
        create: "criar",
        store: "criar",
        destroy: "excluir",
        delete: "deletar",
        update: "editar",
        edit: "editar",
        password: "senha",
        posts: "publicações",
        post: "publicação",
        register: "registrar",
        users: "usuários",
        roles: "perfis",
        role: "perfil",
        show: "visualizar",
        content: "conteúdo",
        verification: "verificação",
        auth: "autenticação",
        import: "importar",
        index: "listar",
        changePassword: "alterar senha",
        status: "ativo/inativo",
        questions: "questões",
        questionnaires: "Enquetes",
        export: "exportar",
        read: "ler",
        addImages: "Inserir",
        displayTime: "Duração",
        comments: "comentários",
        approvals: "aprovações",
        approver: "aprovar",
        rejected: "reprovar",
        companies: "Assessorias",
    },
};

// finally, pass them to AlpineI18n:
document.addEventListener("alpine-i18n:ready", function () {
    window.AlpineI18n.create(locale, messages);
});
