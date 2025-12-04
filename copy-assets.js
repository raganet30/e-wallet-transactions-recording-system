// copy-assets.js
const fs = require('fs-extra');
const path = require('path');

// Create assets directory structure
const assetsDir = path.join(__dirname, 'assets');
const cssDir = path.join(assetsDir, 'css');
const jsDir = path.join(assetsDir, 'js');
const webfontsDir = path.join(assetsDir, 'webfonts');

// Ensure directories exist
[assetsDir, cssDir, jsDir, webfontsDir].forEach(dir => {
  if (!fs.existsSync(dir)) {
    fs.mkdirSync(dir, { recursive: true });
  }
});

// Copy Bootstrap files
fs.copySync(
  path.join(__dirname, 'node_modules', 'bootstrap', 'dist', 'css', 'bootstrap.min.css'),
  path.join(cssDir, 'bootstrap.min.css')
);

fs.copySync(
  path.join(__dirname, 'node_modules', 'bootstrap', 'dist', 'js', 'bootstrap.bundle.min.js'),
  path.join(jsDir, 'bootstrap.bundle.min.js')
);

// Copy Bootstrap Icons
fs.copySync(
  path.join(__dirname, 'node_modules', 'bootstrap-icons', 'font', 'bootstrap-icons.css'),
  path.join(cssDir, 'bootstrap-icons.css')
);

fs.copySync(
  path.join(__dirname, 'node_modules', 'bootstrap-icons', 'font', 'fonts'),
  path.join(webfontsDir)
);

// Copy jQuery
fs.copySync(
  path.join(__dirname, 'node_modules', 'jquery', 'dist', 'jquery.min.js'),
  path.join(jsDir, 'jquery-3.7.0.min.js')
);

// Copy DataTables
fs.copySync(
  path.join(__dirname, 'node_modules', 'datatables.net', 'js', 'jquery.dataTables.min.js'),
  path.join(jsDir, 'dataTables.min.js')
);

fs.copySync(
  path.join(__dirname, 'node_modules', 'datatables.net-bs5', 'css', 'dataTables.bootstrap5.min.css'),
  path.join(cssDir, 'dataTables.bootstrap5.min.css')
);

fs.copySync(
  path.join(__dirname, 'node_modules', 'datatables.net-bs5', 'js', 'dataTables.bootstrap5.min.js'),
  path.join(jsDir, 'dataTables.bootstrap5.min.js')
);

console.log('✅ Assets copied successfully!');