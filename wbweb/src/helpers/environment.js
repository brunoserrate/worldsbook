let env = {
    host: 'http://127.0.0.1:4001/v1/',
}
if (process.env.NODE_ENV == 'development') {
    env = {
        host: 'http://127.0.0.1:4001/v1/',
        // host: 'http://18.230.195.96:4001/v1/',
    }
} else if (process.env.NODE_ENV == 'homologacao') {
    env = {
        host: 'http://127.0.0.1:4001/v1/',
    }
} else if (process.env.NODE_ENV == 'production') {
    env = {
        host: 'http://18.230.195.96:4001/v1/',
    }
}

export const environment = env