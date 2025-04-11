const loadState = () => ({
    message: '',
    color: '',
    timeout: 0
});
export default {
    namespaced: true,
    state: loadState(),
    mutations: {
        createSnack (state, data) {
            state.message = data.message
            state.color = data.color || 'dark'
            state.timeout = data.timeout || 2000
        }
    }
}