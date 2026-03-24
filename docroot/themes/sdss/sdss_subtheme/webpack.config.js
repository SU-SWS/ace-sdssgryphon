
const path = require("path");
const Webpack = require("webpack");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");
const FileManagerPlugin = require('filemanager-webpack-plugin');
const autoprefixer = require('autoprefixer')({ grid: true });

const config = {
  isProd: process.env.NODE_ENV === "production",
  hmrEnabled: process.env.NODE_ENV !== "production" && !process.env.NO_HMR,
  distFolder: path.resolve(__dirname, "./dist/css"),
  wdsPort: 3001,
};

var webpackConfig = {
  entry: {
    "ckeditor": path.resolve("src/scss/ckeditor.scss"),
    "sdss_subtheme": path.resolve("src/scss/config/index.scss"),
    "full-width.basic-page": ["./src/scss/basic-page/full-width.scss"],
  },
  output: {
    path: config.distFolder,
    filename: '[name].js',
    assetModuleFilename: '../assets/[name][ext][query]'
  },
  mode: config.isProd ? "production" : "development",
  resolve: {
    alias: {
      'decanter-assets': path.resolve('node_modules', 'decanter/core/src/img'),
      'decanter-src': path.resolve('node_modules', 'decanter/core/src'),
      '@fortawesome': path.resolve('node_modules', '@fortawesome'),
      'fa-fonts': path.resolve('node_modules', '@fortawesome/fontawesome-free/webfonts')
    }
  },
  module: {
    rules: [
      {
        test: /\.(sa|sc|c)ss$/,
        use: [
          config.isProd ? { loader: MiniCssExtractPlugin.loader } : 'style-loader',
          { loader: 'css-loader', options: {} },
          {
            loader: 'postcss-loader',
            options: {
              postcssOptions: {
                sourceMap: true,
                plugins: [autoprefixer],
              },
            }
          },
          { loader: 'sass-loader', options: {} }
        ]
      },
      {
        test: /\.(png|jpg|gif|svg)$/i,
        type: "asset"
      },
      {
        test: /\.(woff|woff2|eot|ttf)$/i,
        type: "asset",
        generator: {
          filename: '../assets/fonts/[name][ext][query]'
        }
      }
    ]
  },
  plugins: [
    new FixStyleOnlyEntriesPlugin(),
    new MiniCssExtractPlugin({
      filename: '[name].css',
    }),
    new FileManagerPlugin({
      events: {
        onStart: {
          delete: ["dist"]
        },
        onEnd: {
          copy: [
            {
              source: path.resolve(__dirname, 'src/assets/img'),
              destination: path.resolve(__dirname, 'dist/img')
            }
          ]
        }
      }
    }),
  ],
  optimization: {
    minimizer: [
      new CssMinimizerPlugin(),
    ]
  }
};

if (config.hmrEnabled) {
  webpackConfig.plugins.push(new Webpack.HotModuleReplacementPlugin());
}
module.exports = webpackConfig;
