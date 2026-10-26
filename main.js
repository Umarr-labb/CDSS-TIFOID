const { app, BrowserWindow, dialog } = require('electron');
const path = require('path');
const fs = require('fs');
const net = require('net');
const http = require('http');
const { spawn, spawnSync } = require('child_process');
let autoUpdater = null;
try { autoUpdater = require('electron-updater').autoUpdater; } catch (e) { autoUpdater = null; }


const isDev = !app.isPackaged;
const USER_DATA = app.getPath('userData');
const DB_PATH = path.join(USER_DATA, 'database.sqlite').replace(/\\/g, '/');

let LARAVEL_ROOT = isDev ? path.resolve(__dirname) : path.join(USER_DATA, 'laravel');
let phpProcess = null;
let mainWindow = null;
let PORT = 8000;

function resolvePhp() {
    if (process.env.PHP_PATH && fs.existsSync(process.env.PHP_PATH)) return process.env.PHP_PATH;
    const bases = ['D:\\laragon\\bin\\php', 'C:\\laragon\\bin\\php'];
    for (const base of bases) {
        if (fs.existsSync(base)) {
            const exes = fs.readdirSync(base)
                .map(d => path.join(base, d, 'php.exe'))
                .filter(p => fs.existsSync(p));
            if (exes.length) return exes.sort().pop();
        }
    }
    const xampps = ['D:\\xampp\\php\\php.exe', 'C:\\xampp\\php\\php.exe'];
    for (const p of xampps) if (fs.existsSync(p)) return p;
    // fallback: search PATH-like common locations
    if (fs.existsSync('D:\\php\\php.exe')) return 'D:\\php\\php.exe';
    if (fs.existsSync('C:\\php\\php.exe')) return 'C:\\php\\php.exe';
    return 'php';
}

function runPhp(cwd, args) {
    const fullArgs = ['-d', 'extension=pdo_sqlite', '-d', 'extension=sqlite3', ...args];
    const r = spawnSync(resolvePhp(), fullArgs, { cwd, stdio: 'pipe', windowsHide: true, env: process.env });
    if (r.stdout) process.stdout.write(r.stdout);
    if (r.stderr) process.stderr.write(r.stderr);
    if (r.status !== 0) throw new Error(`Command failed: php ${args.join(' ')} (code ${r.status}) ${r.stderr ? r.stderr.toString().trim() : ''}`);
}

function freePort(start) {
    return new Promise(resolve => {
        const s = net.createServer();
        s.listen(start, '127.0.0.1', () => { const p = s.address().port; s.close(() => resolve(p)); });
        s.on('error', () => resolve(freePort(start + 1)));
    });
}

function prepare() {
    if (!isDev && !fs.existsSync(LARAVEL_ROOT)) {
        fs.cpSync(path.join(process.resourcesPath, 'laravel'), LARAVEL_ROOT, { recursive: true });
    }
    // Ensure writable storage directories exist (needed by Laravel).
    for (const sub of ['storage/framework/cache', 'storage/framework/sessions', 'storage/framework/views', 'storage/logs', 'bootstrap/cache']) {
        fs.mkdirSync(path.join(LARAVEL_ROOT, sub), { recursive: true });
    }
    const envPath = path.join(LARAVEL_ROOT, '.env');
    let env = '';
    if (fs.existsSync(envPath)) env = fs.readFileSync(envPath, 'utf8');
    else {
        const ex = path.join(LARAVEL_ROOT, '.env.example');
        if (fs.existsSync(ex)) env = fs.readFileSync(ex, 'utf8');
    }
    if (!fs.existsSync(envPath)) {
        if (!/^DB_CONNECTION=/m.test(env)) env += '\nDB_CONNECTION=sqlite\n';
        else env = env.replace(/^DB_CONNECTION=.*/m, 'DB_CONNECTION=sqlite');
        if (/^DB_DATABASE=/m.test(env)) env = env.replace(/^DB_DATABASE=.*/m, `DB_DATABASE="${DB_PATH}"`);
        else env += `DB_DATABASE="${DB_PATH}"\n`;
        if (/^APP_URL=/m.test(env)) env = env.replace(/^APP_URL=.*/m, `APP_URL=http://127.0.0.1:${PORT}`);
        else env += `APP_URL=http://127.0.0.1:${PORT}\n`;
        fs.writeFileSync(envPath, env);
    }
    env = fs.readFileSync(envPath, 'utf8');
    if (!/^APP_KEY=.+/m.test(env) || /^APP_KEY=\s*$/m.test(env)) {
        runPhp(LARAVEL_ROOT, ['artisan', 'key:generate', '--force']);
    }
    env = fs.readFileSync(envPath, 'utf8');
    const sqlite = /DB_CONNECTION=\s*sqlite/i.test(env);
    if (sqlite) {
        fs.mkdirSync(path.dirname(DB_PATH), { recursive: true });
        const dbExists = fs.existsSync(DB_PATH);
        if (!dbExists) {
            runPhp(LARAVEL_ROOT, ['artisan', 'migrate', '--force']);
            runPhp(LARAVEL_ROOT, ['artisan', 'db:seed', '--force']);
        } else {
            runPhp(LARAVEL_ROOT, ['artisan', 'migrate', '--force']);
        }
    }
}

function startServer() {
    return new Promise((resolve, reject) => {
        const php = resolvePhp();
        const serverScript = path.join(LARAVEL_ROOT, 'vendor', 'laravel', 'framework', 'src', 'Illuminate', 'Foundation', 'resources', 'server.php');
        const publicDir = path.join(LARAVEL_ROOT, 'public');
        phpProcess = spawn(php, ['-d', 'extension=pdo_sqlite', '-d', 'extension=sqlite3', '-S', `127.0.0.1:${PORT}`, serverScript], {
            cwd: publicDir, env: process.env, windowsHide: true,
        });
        phpProcess.stdout.on('data', d => { if (process.stdout) process.stdout.write(`[php] ${d}`); });
        phpProcess.stderr.on('data', d => { if (process.stderr) process.stderr.write(`[php] ${d}`); });
        phpProcess.on('error', reject);
        const poll = () => {
            http.get(`http://127.0.0.1:${PORT}/`, res => { resolve(); })
                .on('error', () => setTimeout(poll, 400));
        };
        setTimeout(poll, 1000);
    });
}

function createWindow() {
    const icon = path.join(LARAVEL_ROOT, 'public', 'icons', 'icon-192.png');
    mainWindow = new BrowserWindow({
        width: 1280, height: 820,
        icon: fs.existsSync(icon) ? icon : undefined,
        webPreferences: { preload: path.join(__dirname, 'preload.js'), contextIsolation: true, nodeIntegration: false },
    });
    mainWindow.loadURL(`http://127.0.0.1:${PORT}`);
    mainWindow.on('closed', () => { mainWindow = null; });
}

app.whenReady().then(async () => {
    try {
        PORT = await freePort(8000);
        prepare();
        await startServer();
        createWindow();
        if (!isDev && autoUpdater) {
            try {
                autoUpdater.on('update-available', () => dialog.showMessageBox(mainWindow, { type: 'info', title: 'Pembaruan', message: 'Pembaruan tersedia, akan diunduh otomatis.' }));
                autoUpdater.on('update-downloaded', () => {
                    const r = dialog.showMessageBoxSync(mainWindow, { type: 'question', buttons: ['Restart', 'Nanti'], defaultId: 0, message: 'Pembaruan selesai diunduh. Restart sekarang?' });
                    if (r === 0) autoUpdater.quitAndInstall();
                });
                autoUpdater.checkForUpdatesAndNotify();
            } catch (e) { console.error('autoUpdater error', e); }
        }

    } catch (e) {
        dialog.showErrorBox('Gagal memulai CDSS Tifoid', String(e && e.message ? e.message : e));
        app.quit();
    }
});

app.on('window-all-closed', () => { if (process.platform !== 'darwin') app.quit(); });
app.on('activate', () => { if (!mainWindow) createWindow(); });
app.on('before-quit', () => { if (phpProcess) { try { phpProcess.kill(); } catch (e) {} phpProcess = null; } });
