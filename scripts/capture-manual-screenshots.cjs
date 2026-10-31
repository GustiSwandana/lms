const puppeteer = require('puppeteer-core');
const fs = require('fs');
const path = require('path');

const EDGE_PATH = 'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe';
const BASE_URL = 'https://demo.gustiswandana.space';
const OUTPUT_DIR = path.join(__dirname, '..', 'docs', 'screenshots');

if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR, { recursive: true });
}

async function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function run() {
    console.log('Launching Edge browser via:', EDGE_PATH);
    const browser = await puppeteer.launch({
        executablePath: EDGE_PATH,
        headless: 'new',
        defaultViewport: { width: 1280, height: 800 },
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-gpu']
    });

    try {
        // --- SESI 1: PUBLIK ---
        console.log('--- Sesi 1: Halaman Publik ---');
        const publicPage = await browser.newPage();

        console.log('1. Capturing Beranda & Katalog...');
        await publicPage.goto(`${BASE_URL}`, { waitUntil: 'networkidle2', timeout: 30000 });
        await sleep(1500);
        await publicPage.screenshot({ path: path.join(OUTPUT_DIR, '01-beranda-katalog.png') });

        console.log('2. Capturing Halaman Masuk...');
        await publicPage.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle2', timeout: 30000 });
        await sleep(1000);
        await publicPage.screenshot({ path: path.join(OUTPUT_DIR, '02-halaman-masuk.png') });

        console.log('3. Capturing Pendaftaran Siswa...');
        await publicPage.goto(`${BASE_URL}/register`, { waitUntil: 'networkidle2', timeout: 30000 });
        await sleep(1000);
        await publicPage.screenshot({ path: path.join(OUTPUT_DIR, '03-pendaftaran-siswa.png') });

        console.log('4. Capturing E-Sertifikat Digital Publik...');
        await publicPage.goto(`${BASE_URL}/sertifikat/CERT-SSK-BUDI-5BAC414B`, { waitUntil: 'networkidle2', timeout: 30000 });
        await sleep(1500);
        await publicPage.screenshot({ path: path.join(OUTPUT_DIR, '04-verifikasi-sertifikat.png') });
        await publicPage.close();

        // --- SESI 2: SISWA ---
        console.log('--- Sesi 2: Siswa Pembelajar ---');
        const studentContext = await browser.createBrowserContext();
        const studentPage = await studentContext.newPage();

        console.log('Logging in as student...');
        await studentPage.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle2', timeout: 30000 });
        await studentPage.type('input[name="email"]', 'student@edutube.org');
        await studentPage.type('input[name="password"]', 'password');
        await Promise.all([
            studentPage.waitForNavigation({ waitUntil: 'networkidle2', timeout: 30000 }),
            studentPage.click('button[type="submit"]')
        ]);
        await sleep(1500);

        console.log('5. Capturing Dasbor Siswa...');
        await studentPage.screenshot({ path: path.join(OUTPUT_DIR, '05-dasbor-siswa.png') });

        console.log('6. Capturing Ruang Kelas Video (Classroom)...');
        await studentPage.goto(`${BASE_URL}/belajar/apa-sih-ssk-sekolah-siaga-kependudukan/materi/19`, { waitUntil: 'networkidle2', timeout: 30000 });
        await sleep(2500);
        await studentPage.screenshot({ path: path.join(OUTPUT_DIR, '06-ruang-belajar-video.png') });

        console.log('7. Capturing Tab Catatan Waktu...');
        await studentPage.evaluate(() => {
            const tabs = Array.from(document.querySelectorAll('button'));
            const noteTab = tabs.find(t => t.textContent.includes('Catatan'));
            if (noteTab) noteTab.click();
        });
        await sleep(1000);
        await studentPage.screenshot({ path: path.join(OUTPUT_DIR, '07-catatan-waktu.png') });

        console.log('8. Capturing Kuis Checkpoint...');
        await studentPage.evaluate(() => {
            window.scrollBy(0, 850);
        });
        await sleep(1000);
        await studentPage.screenshot({ path: path.join(OUTPUT_DIR, '08-kuis-checkpoint.png') });
        await studentPage.close();
        await studentContext.close();

        // --- SESI 3: ADMINISTRATOR PUSAT ---
        console.log('--- Sesi 3: Admin Pusat ---');
        const adminContext = await browser.createBrowserContext();
        const adminPage = await adminContext.newPage();

        console.log('Logging in as Central Admin...');
        await adminPage.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle2', timeout: 30000 });
        await adminPage.type('input[name="email"]', 'admin@edutube.org');
        await adminPage.type('input[name="password"]', 'password');
        await Promise.all([
            adminPage.waitForNavigation({ waitUntil: 'networkidle2', timeout: 30000 }),
            adminPage.click('button[type="submit"]')
        ]);
        await sleep(1500);

        console.log('9. Capturing Dasbor Admin...');
        await adminPage.screenshot({ path: path.join(OUTPUT_DIR, '09-dasbor-admin.png') });

        console.log('10. Capturing Unified Course Builder...');
        await adminPage.goto(`${BASE_URL}/admin/courses/5/builder`, { waitUntil: 'networkidle2', timeout: 30000 });
        await sleep(2000);
        await adminPage.screenshot({ path: path.join(OUTPUT_DIR, '10-course-builder.png') });

        console.log('11. Capturing Manajemen Siswa...');
        await adminPage.goto(`${BASE_URL}/admin/students`, { waitUntil: 'networkidle2', timeout: 30000 });
        await sleep(1500);
        await adminPage.screenshot({ path: path.join(OUTPUT_DIR, '11-manajemen-siswa.png') });

        await adminPage.close();
        await adminContext.close();

        console.log('All 11 screenshots successfully captured!');
    } catch (err) {
        console.error('Error during capture:', err);
    } finally {
        await browser.close();
    }
}

run();
