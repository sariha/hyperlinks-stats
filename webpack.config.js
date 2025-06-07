const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');

module.exports = {
    ...defaultConfig,
    output: {
        ...defaultConfig.output,
        filename: process.env.NODE_ENV === 'production'
            ? '[name].[contenthash:8].js'
            : '[name].js',
        chunkFilename: process.env.NODE_ENV === 'production'
            ? '[name].[contenthash:8].chunk.js'
            : '[name].chunk.js',
    },
    plugins: (() => {
        const plugins = defaultConfig.plugins.map(plugin => {

            if ( 'RtlCssPlugin' === plugin.constructor.name ) {
                // Remove RTL CSS plugin
                return null;
            }

            if (plugin.constructor.name === 'MiniCssExtractPlugin') {
                return new plugin.constructor({
                    filename: process.env.NODE_ENV === 'production'
                        ? '[name].[contenthash:8].css'
                        : '[name].css',
                    chunkFilename: process.env.NODE_ENV === 'production'
                        ? '[name].[contenthash:8].chunk.css'
                        : '[name].chunk.css',
                });
            }
            return plugin;
        });
		plugins.push(
            new WebpackManifestPlugin({
                fileName: 'assets.json',
                publicPath: '',
                generate: (seed, files, entrypoints) => {
                    const manifestFiles = files.reduce((manifest, file) => {
                        manifest[file.name] = file.path;
                        return manifest;
                    }, seed);

                    const entrypointFiles = entrypoints.front.filter(
                        fileName => !fileName.endsWith('.map')
                    );

                    return {
                        files: manifestFiles,
                        entrypoints: entrypointFiles,
                    };
                },
            })
        );

        return plugins;
    })(),
};
