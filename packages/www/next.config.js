const withBundleAnalyzer = require('@next/bundle-analyzer')({
    enabled: process.env.ANALYZE === 'true',
})

const nextConfig = {
    webpack(config) {
        config.module.rules.push({
            test: /\.(svg|png)$/,
            issuer: {
                test: /\.(js|ts)x?$/,
            },
            use: ['url-loader'],
        });

        return config;
    },
    async rewrites() {
        return [
            // we need to define a no-op rewrite to trigger checking
            // all pages/static files before we attempt proxying
            {
                source: '/:path*',
                destination: '/:path*',
            },
            {
                source: '/blog/:path*',
                destination: '/:path*',
            },
            {
                source: '/cms/:path*',
                destination: 'http://localhost:1337/:path*',
            }
        ]
    },
}

module.exports = withBundleAnalyzer(nextConfig)