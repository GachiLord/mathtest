const path = require('path');

module.exports = {
  experiments: {
    topLevelAwait: true
  },
  mode: 'development',
  devtool: 'source-map',
  entry: {
    index:'./client/js/index.js',
    editor:'./client/js/editor.js',
    launch:'./client/js/launch.js',
    login:'./client/js/login.js',
    register:'./client/js/register.js',
    profile:'./client/js/profile.js'
  },
  output: {
    path: path.resolve(__dirname, 'app'),
    filename: '[name].js',
  },
  module: {
    rules: [
      {
        test: /\.css$/i,
        use: ["style-loader", "css-loader"],
      },
      {
        test: /\.(png|jpe?g|gif|svg)$/i,
        use: ['file-loader'],
      },
      {
        test: /\.(html|htm)$/i,
        loader: "html-loader",
      }
    ]
  }
  
};