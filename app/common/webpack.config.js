const path = require('path');
const webpack = require("webpack");

module.exports = {
  entry: path.resolve(__dirname, './src/scripts/app.js'),
  module: {
    rules: [
      {
        test: require.resolve("jquery"),
        loader: "expose-loader",
        options: {
          exposes: {
            globalName: "$",
            override: true,
          },
        },
      },
      {
        test: /\.(js)$/,
        exclude: /node_modules/,
        use: ['babel-loader']
      }
    ]
  },
  resolve: {
    extensions: ['*', '.js'],
    alias: {
      // bind version of jquery-ui
      "jquery-ui": "jquery-ui/jquery-ui.js",
      // bind to modules;
      modules: path.join(__dirname, "node_modules"),
    }
  },
  output: {
    path: path.resolve(__dirname, './dist'),
    filename: 'assets/js/bundle.js',
  },
  plugins: [
    new webpack.ProvidePlugin({
      "$": "jquery",
      "jQuery": "jquery",
      "window.jQuery": "jquery",
      "window.$": "jquery"
    }),
  ],
  devServer: {
    contentBase: path.resolve(__dirname, './dist'),
  },
};
