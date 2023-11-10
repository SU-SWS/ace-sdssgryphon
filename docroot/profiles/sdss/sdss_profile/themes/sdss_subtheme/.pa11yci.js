/**
 * @file
 * Pa11y config.
 */

const isCI = process.env.CI;
const baseURL = isCI ? 'http://localhost/' : 'http://ace-sdssgryphon.test';

// Add urls for a11y testing here.
const urls = [
  '/',
  '/about',
  '/news',
  '/people',
];

module.exports = {
  defaults: {
    standard: 'WCAG2AA',
    hideElements: ['svg'],
    ignore: ['notice', 'warning'],
    chromeLaunchConfig: {
      args: ['--no-sandbox']
    },
    reporters: [
        'cli',
        ['json', { "fileName": "./test-results/a11y-test-results.json" }]
    ]
  },
  urls: urls.map(url => `${baseURL}${url}`)
};
