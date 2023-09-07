// @ts-check
import { test, expect } from '@playwright/test';

/*
test for search functionality on index.php page
Try for empty search query and search query with only numbers and search query with only letters and search query with special characters and search query with numbers and letters and special characters and search query with only spaces and search query with only spaces and letters and search query with only spaces and numbers and search query with only spaces and special characters

*/

// live search functionality
test('search functionality', async ({ page }) => {
  await page.goto('http://localhost:3000/index.php');

  // Type a search query into the search bar
  await page.fill('#search-input', 'winterfell');

  // Wait for the search results to load
  await page.waitForSelector('#search-results');

  // Expect the search results to contain the expected HTML
  await expect(page).toMatchElement('#search-results', {
    html: `
      <div class="list-group text-center align-items-center" id="search-results">
        <a href="./listing.php?listing=1" class="list-group-item list-group-item-action">
          <div class="d-flex w-100 justify-content-between align-items-center">
            <h5 class="text-gradient text-primary font-weight-bold h5 mb-1">Winterfell Cafe</h5>
            <span class="text-body-secondary text-gradient text-warning text-uppercase text-xs mt-1"><i class="fa-solid fa-star"></i> 4.00 (1)</span>
            <span class="text-body-secondary text-capitalize text-xs font-weight-bold"><i class="fa-solid fa-shop"></i> restaurant</span>
            <span class="text-body-secondary text-capitalize text-xs font-weight-bold "><i class="fa-solid fa-location-dot"></i> Boulevard Road Dal lake, srinagar</span>
          </div>
        </a>
      </div>
    `,
  });
});

test('search functionality with empty search query', async ({ page }) => {
  await page.goto('http://localhost:3000/index.php');

  // Click the get started link.
  await page.fill('#search-input', '');
  await page.click('#search-button');

  // Expects page to have a heading with the name of Installation.
  await expect(page).toHaveTitle('Search Results');
});

test('search functionality with only numbers', async ({ page }) => {
  await page.goto('http://localhost:3000/index.php');

  // Click the get started link.
  await page.fill('#search-input', '123');
  await page.click('#search-button');

  // Expects page to have a heading with the name of Installation.
  await expect(page).toHaveTitle('Search Results');
});

test('search functionality with only letters', async ({ page }) => {
  await page.goto('http://localhost:3000/index.php');

  // Click the get started link.
  await page.fill('#search-input', 'test');
  await page.click('#search-button');

  // Expects page to have a heading with the name of Installation.
  await expect(page).toHaveTitle('Search Results');
});

test('search functionality with special characters', async ({ page }) => {
  await page.goto('http://localhost:3000/index.php');

  // Click the get started link.
  await page.fill('#search-input', '!@#$%^&*()');
  await page.click('#search-button');

  // Expects page to have a heading with the name of Installation.
  await expect(page).toHaveTitle('Search Results');
});

test('search functionality with spaces and letters', async ({ page }) => {
  await page.goto('http://localhost:3000/index.php');

  // Click the get started link.
  await page.fill('#search-input', 'winterfell game of thrones');
  await page.click('#search-button');

  // Expects page to have a heading with the name of Installation.
  await expect(page).toHaveTitle('Search Results');
});

test('search functionality with spaces and numbers', async ({ page }) => {
  await page.goto('http://localhost:3000/index.php');

  // Click the get started link.
  await page.fill('#search-input', '123 456');
  await page.click('#search-button');

  // Expects page to have a heading with the name of Installation.
  await expect(page).toHaveTitle('Search Results');
});

test('search functionality with spaces and special characters', async ({
  page,
}) => {
  await page.goto('http://localhost:3000/index.php');

  // Click the get started link.
  await page.fill('#search-input', '!@# $%^&*()');
  await page.click('#search-button');

  // Expects page to have a heading with the name of Installation.
  await expect(page).toHaveTitle('Search Results');
});

test('search functionality with only spaces', async ({ page }) => {
  await page.goto('http://localhost:3000/index.php');

  // Click the get started link.
  await page.fill('#search-input', '   ');
  await page.click('#search-button');

  // Expects page to have a heading with the name of Installation.
  await expect(page).toHaveTitle('Search Results');
});
