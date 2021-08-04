const path = require('path');

module.exports = {
  experiments: {
    topLevelAwait: true
  },
  mode: 'production',
  entry: {
    index:'./src/js/index.js',
    createTest:'./src/js/createTest.js',
    launchTest:'./src/js/launchTest.js',
    contact:'./src/js/contact.js',
    login:'./src/js/login.js',
    register:'./src/js/register.js',
    show:'./src/js/showContent.js'
  },
  output: {
    path: path.resolve(__dirname, 'app'),
    filename: '[name].js',
  },
  module: {
    rules: [
      {
        test: /\.m?js$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      },
      {
        test: /\.css$/i,
        use: ["style-loader", "css-loader"],
      },
      {
        test: /\.(png|jpe?g|gif|svg)$/i,
        use: ['file-loader'],
      },
    ]
  }
  
};