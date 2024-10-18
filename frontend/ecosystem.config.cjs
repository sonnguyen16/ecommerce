module.exports = {
    apps: [
        {
            name: "nuxt-app",
            script: ".output/server/index.mjs",
            exec_mode: "fork",
            watch: false,
            env_production: {
                PORT: 3000,
            },
        },
    ],
};