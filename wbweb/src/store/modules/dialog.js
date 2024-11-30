export default {
    namespaced: true,
    state: {
        dialog_historia: false,
        livro_detail: {
            _id: "",
            tags: [],
            usuario: {},
            status: {},
            projetos: [],
            createdAt: "",
            updatedAt: "",
            caminho_capa: "",
            categoria: {},
            conteudo_adulto: false,
            descricao: "",
            direitos_autorais: {},
            historia_finalizada: false,
            idioma: {},
            publico_alvo: {},
            titulo: "",
            capitulos: [],
            total_capitulos: 0,
            total_visualizacoes: 0,
            quantidade_comentarios: 0,
            total_votos: 0
        },
    },
    mutations: {
        OPEN_DIALOG(state, livro) {
            state.dialog_historia = true;
            state.livro_detail = livro;
        },
        CLOSE_DIALOG(state) {
            state.dialog_historia = false
            state.livro_detail = {
                _id: "",
                tags: [],
                usuario: {},
                status: {},
                projetos: [],
                createdAt: "",
                updatedAt: "",
                caminho_capa: "",
                categoria: {},
                conteudo_adulto: false,
                descricao: "",
                direitos_autorais: {},
                historia_finalizada: false,
                idioma: {},
                publico_alvo: {},
                titulo: "",
                capitulos: [],
                total_capitulos: 0,
                total_visualizacoes: 0,
                quantidade_comentarios: 0,
                total_votos: 0
            }
        },
    },
};