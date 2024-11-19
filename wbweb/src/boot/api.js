export default ({ app, router, store }) => {
    app.mixin({
        methods: {
            logoutUser() {
                console.log("logout")
                localStorage.removeItem("auth")
                this.$router.push({ path: '/login' })
            }
        }
    })
  }
