const path = require("path");

const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");

module.exports = (env, argv) => {

    const isDev = argv.mode === "development";

    return {

        mode: isDev ? "development" : "production",

        entry: "./js/script.js",

        output: {
            filename: "js/app.min.js",
            path: path.resolve(__dirname, "dist"),
            clean: true
        },

        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: {
                        loader: "babel-loader",
                        options: {
                            presets: [
                                "@babel/preset-env"
                            ]
                        }
                    }
                },

                {
                    test: /\.(scss|sass|css)$/i,
                    use: [
                        MiniCssExtractPlugin.loader,

                        {
                            loader: "css-loader",
                            options: {
                                sourceMap: isDev
                            }
                        },

                        {
                            loader: "postcss-loader",
                            options: {
                                sourceMap: isDev,
                                postcssOptions: {
                                    plugins: [
                                        require("autoprefixer")
                                    ]
                                }
                            }
                        },

                        {
                            loader: "sass-loader",
                            options: {
                                sourceMap: isDev
                            }
                        }
                    ]
                },

                {
                    test: /\.(png|jpe?g|gif|svg|webp|ico)$/i,
                    type: "asset/resource",
                    generator: {
                        filename: "images/[name][ext]"
                    }
                },

                {
                    test: /\.(woff2?|woff|eot|ttf|otf)$/i,
                    type: "asset/resource",
                    generator: {
                        filename: "fonts/[name][ext]"
                    }
                }
            ]
        },

        plugins: [
            new MiniCssExtractPlugin({
                filename: "css/style.min.css"
            })
        ],

        optimization: {
            minimize: !isDev,
            minimizer: [
                new CssMinimizerPlugin(),
                new TerserPlugin()
            ]
        },

        devServer: {
            static: {
                directory: path.resolve(__dirname, "dist")
            },
            port: 3000,
            hot: true,
            open: true,
            watchFiles: [
                "./js/**/*",
                "./style/**/*",
                "./index.html"
            ]
        },

        resolve: {
            extensions: [".js"]
        },

        devtool: isDev ? "source-map" : false
    };
};