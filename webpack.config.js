const path = require('path');
const argv = require('yargs')['argv'];
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');

const isDevelopment = argv.mode === 'development';
const isProduction = !isDevelopment;
const distPath = path.join(__dirname, '/public');
const devServerHost = 'http://localhost';
const devServerPort = 9009;

const browsers = require('./.envelopment')['browsers'];

const config = {
    entry: {
        main: './src/js/index.js'
    },
    output: {
        filename: 'bundle.js',
        path: distPath,
        publicPath: isDevelopment ? ( devServerHost + ':' + devServerPort + '/' ) : ''
    },
    module: {
        rules: [{
            test: /\.html$/,
            use: 'html-loader'
        }, {
            test: /\.hbs/,
            use: ['handlebars-loader'],
            exclude: /node_modules/
        }, {
            test: /\.(js|jsx)$/,
            exclude: /node_modules/,
            use: [{
                loader: 'babel-loader',
                options: {
                    presets: ['env']
                }
            }, 'eslint-loader']
        }, {
            test: /\.scss$/,
            exclude: /node_modules/,
            use: [
                isDevelopment ? 'style-loader' : MiniCssExtractPlugin.loader,
                {
                    loader: 'css-loader',
                    options: {
                        minimize: isProduction
                    }
                },
                {
                    loader: 'sass-loader',
                    query: {
                        includePaths: [
                            path.resolve(__dirname, 'node_modules/foundation-sites/scss'),
                            path.resolve(__dirname, 'node_modules/foundation-sites/src')
                        ]
                    }
                },
                'resolve-url-loader'
            ]
        }, {
            test: /\.(gif|png|jpe?g|svg)$/i,
            use: [{
                loader: 'file-loader',
                options: {
                    name: 'images/[name][hash].[ext]'
                }
            }, {
                loader: 'image-webpack-loader',
                options: {
                    mozjpeg: {
                        progressive: true,
                        quality: 70
                    }
                }
            },
            ],
        }, {
            test: /\.(eot|svg|ttf|woff|woff2)$/,
            use: {
                loader: 'file-loader',
                options: {
                    name: 'fonts/[name][hash].[ext]'
                }
            },
        }]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].css',
            chunkFilename: '[id].css'
        }),
        new HtmlWebpackPlugin({
            template: './src/templates/index.hbs'
        })
    ],
    optimization: isProduction ? {
        minimizer: [
            new UglifyJsPlugin({
                sourceMap: true,
                uglifyOptions: {
                    compress: {
                        inline: false,
                        warnings: false,
                        drop_console: true,
                        unsafe: true
                    },
                },
            }),
        ],
    } : {},
    devServer: {
        //stats: 'errors-only',
        contentBase: distPath,
        port: devServerPort,
        compress: true,
        open: isDevelopment ? browsers['canary'] : browsers['default'],
        overlay: {
            warnings: false,
            errors: true
        },
        headers: {
            //'Access-Control-Allow-Origin': '*'
        },
        proxy: {
            '**': {
                target: 'http://starliner',
                changeOrigin: true,
                logLevel: 'debug'
            }
        }
    },
    externals: [
        'foundation-sites',
        'motion-ui'
    ]
};
module.exports = config;