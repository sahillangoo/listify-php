const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch();
  const context = await browser.newContext();
  const page = await context.newPage();

  await page.goto('http://localhost/file:index.php');

  // Type a search query into the search bar
  await page.fill('#search-bar', 'winterfel');

  // Submit the search form
  await page.click('#search-button');

  // Wait for the search results to load
  await page.waitForSelector('#search-results');

  // Assert that the search results contain the expected text
  const searchResultsText = await page.textContent('#search-results');
  expect(searchResultsText).toContain('example search query');

  await browser.close();
})();
