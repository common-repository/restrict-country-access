const defaultConfig = require("@wordpress/scripts/config/webpack.config");

module.exports = {
  ...defaultConfig,
  entry: {
    ...defaultConfig.entry,
    "restrict-country": ["./src/css/select2.css", "./src/js/index.js"],
  },
};
