const path = require('path');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const ConversionOutlineJsonPlugin = require('./webpack/config/plugins/conversion-outline-json-plugin');

const modulesJsonPath = path.resolve(__dirname, '../modules-json');

module.exports = {
  entry: {
    bundle: './src/index.js',
  },

  externals: {
    underscore: '_',
    react: ['vendor', 'React'],
    'react-dom': ['vendor', 'ReactDOM'],
    jquery: 'jQuery',
    '@wordpress/hooks': ['vendor', 'wp', 'hooks'],
    '@wordpress/i18n': ['vendor', 'wp', 'i18n'],
  },

  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /node_modules/,
        use: [
          {
            loader: 'thread-loader',
            options: {
              workers: -1,
            },
          },
          {
            loader: 'babel-loader',
            options: {
              compact: false,
              presets: [
                ['@babel/preset-env', {
                  modules: false,
                  targets: '> 5%',
                }],
                '@babel/preset-react',
              ],
              cacheDirectory: false,
            },
          },
        ],
      },
    ],
  },

  plugins: [
    new ConversionOutlineJsonPlugin(),
    new CopyWebpackPlugin({
      patterns: [
        {
          from: 'module.json',
          context: path.resolve(__dirname, 'src/modules/Timeline'),
          to: path.join(modulesJsonPath, 'timeline'),
        },
        {
          from: 'conversion-outline.json',
          context: path.resolve(__dirname, 'src/modules/Timeline'),
          to: path.join(modulesJsonPath, 'timeline'),
        },
        {
          from: 'module.json',
          context: path.resolve(__dirname, 'src/modules/Timeline-item'),
          to: path.join(modulesJsonPath, 'timeline-item'),
        },
        {
          from: 'conversion-outline.json',
          context: path.resolve(__dirname, 'src/modules/Timeline-item'),
          to: path.join(modulesJsonPath, 'timeline-item'),
        },
      ],
    }),
  ],

  resolve: {
    extensions: ['.js', '.jsx', '.json'],
    fallback: {
      'divi-module-library': false,
      'divi-rest': false,
    },
  },

  output: {
    filename: 'tmdivi-timeline-module-for-divi-conversion.js',
    path: path.resolve(__dirname, 'build'),
  },

  stats: {
    errorDetails: true,
  },
};
