const { defineConfig } = require ("cypress");
const preprocessor = require ("@badeball/cypress-cucumber-preprocessor");
const browserify = require ("@badeball/cypress-cucumber-preprocessor/browserify");

//cucumber setup
async function setupNodeEvents (on, config) {
  await preprocessor.addCucumberPreprocessorPlugin(on, config);
  on("file:preprocessor", browserify.default(config));
  return config;
}

//default configurations
module.exports = defineConfig ({
  e2e: {
    setupNodeEvents,
    specPattern: ['cypress/e2e/features/*.feature']
  },
  reporter: 'junit',
  reporterOptions: {
    mochaFile: 'XMLResults/test-results-[suiteName].xml',
    outputs: true,
    testCaseSwitchClassnameAndName: true,
    toConsole: true,
    attachments: true
  },
  projectId: 'w6nbcy',
  experimentalWebKitSupport: true,
  chromeWebSecurity: false,
  defaultCommandTimeout: 120000,
  pageLoadTimeout: 120000,
  requestTimeout: 8000,
  viewportHeight: 800,
  viewportWidth: 1400,
  videoUploadOnPasses: false,
  videoCompression: false,
  video: false,
  blockHosts: [
    '*clarity.ms',
    '*zendesk.com',
    '*pinterest.com',
    '*nr-data.net',
    '*zdassets.com',
    '*mczbf.com',
    // This is the server side GTM instance
    'a.visualcomfort.com',
    'a.circalighting.com',
    '*google-analytics.com',
    '*googletagmanager.com',
    '*bing.com',
    'cdn.cookielaw.org',
    'br.visualcomfort.com',
    //'*akamaihd.net', //olapic images were not coming on the PDP
  ],
  env: {
    testingEnvironment: "staging",
    country: "US",
    production: {
      link: 'https://www.visualcomfort.com',
    },
    staging: {
      link: 'https://visualcomfortstaging:Pictures1@stage.visualcomfort.com',
    },
    staging2: {
      link: 'https://visualcomfortstaging:Pictures1@stage2.visualcomfort.com',
    },
    staging3: {
      link: 'https://visualcomfortstaging:Pictures1@stage3.visualcomfort.com',
    },
    staging4: {
      link: 'https://visualcomfortstaging:Pictures1@stage4.visualcomfort.com',
    },
    staging5: {
      link: 'https://visualcomfortstaging:Pictures1@stage5.visualcomfort.com',
    },
    staging6: {
      link: 'https://visualcomfortstaging:Pictures1@stage6.visualcomfort.com',
    },
    staging7: {
      link: 'https://visualcomfortstaging:Pictures1@stage7.visualcomfort.com',
    },
    staging8: {
      link: 'https://visualcomfortstaging:Pictures1@stage8.visualcomfort.com',
    },
    staging9: {
      link: 'https://visualcomfortstaging:Pictures1@stage9.visualcomfort.com',
    },
    US: {
      retailUserEmail: 'test-cypress-retail@n8ko5unu.mailosaur.net',
      retailUserPassword: 'test#cypress#retail#n8ko5unu#mailosaur#net',
      tradeUserEmail: 'test-cypress-trade@n8ko5unu.mailosaur.net',
      tradeUserPassword: 'test#cypress#trade#n8ko5unu#mailosaur#net',
      wholesaleUserEmail: 'test-cypress-wholesale@n8ko5unu.mailosaur.net',
      wholesaleUserPassword: 'test#cypress#wholesale#n8ko5unu#mailosaur#net'
    },
    UK: {
      retailUserEmail: 'test-cypress-retail-uk@n8ko5unu.mailosaur.net',
      retailUserPassword: 'Password123!',
      tradeUserEmail: 'tradeukqa@n8ko5unu.mailosaur.net',
      tradeUserPassword: 'Password123!',
      wholesaleUserEmail: 'test-cypress-wholesale-uk@n8ko5unu.mailosaur.net',
      wholesaleUserPassword: 'Password123!'
    },
    EU: {
      retailUserEmail: 'test-cypress-retail-eu@n8ko5unu.mailosaur.net',
      retailUserPassword: 'Password123!',
      tradeUserEmail: 'test-cypress-trade-eu@n8ko5unu.mailosaur.net',
      tradeUserPassword: 'Password123!',
      wholesaleUserEmail: 'test-cypress-wholesale-eu@n8ko5unu.mailosaur.net',
      wholesaleUserPassword: 'Password123!'
    }
  },
  retries: {
    runMode: 1
  }
});