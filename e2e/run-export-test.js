import { chromium } from "playwright";
import fs from "fs";

// Configuration
const APP_URL = process.env.APP_URL || "http://127.0.0.1:8000";
const EXPORT_URL = `${APP_URL}/admin/export/process?format=pdf`;
const DOWNLOAD_DIR = "./tests/e2e-downloads";

async function run() {
    if (!fs.existsSync(DOWNLOAD_DIR))
        fs.mkdirSync(DOWNLOAD_DIR, { recursive: true });

    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({ acceptDownloads: true });
    const page = await context.newPage();

    console.log("Navigating to export URL:", EXPORT_URL);
    await page.goto(EXPORT_URL, { waitUntil: "networkidle" });

    // Wait for the page to call the export function which should trigger a download
    const [download] = await Promise.all([
        page.waitForEvent("download", { timeout: 20000 }),
        // try to trigger export if button exists
        page.evaluate(() => {
            const btn = document.querySelector("[data-export-trigger]");
            if (btn) btn.click();
        }),
    ]);

    const path = `${DOWNLOAD_DIR}/${await download.suggestedFilename()}`;
    await download.saveAs(path);
    console.log("Download saved to", path);

    await browser.close();
}

run().catch((err) => {
    console.error(err);
    process.exit(1);
});
