<div align="center">
  <h1>📰 NewsCore</h1>
  <p><b>Modern Content Management System (CMS) & Blog Berita Platform</b></p>
  <p>
    <img src="https://img.shields.io/badge/PHP-^8.3-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP Version" />
    <img src="https://img.shields.io/badge/Laravel-^13.0-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel Version" />
    <img src="https://img.shields.io/badge/Testing-PHPUnit-38B2AC?style=flat-square&logo=phpunit&logoColor=white" alt="PHPUnit" />
    <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License" />
  </p>
</div>

<hr />

<h2>📌 Ringkasan Proyek</h2>
<p>
  <b>NewsCore</b> adalah platform Content Management System (CMS) berita dan blog yang dibangun dengan arsitektur <i>clean code</i> berbasis <b>Laravel</b>. Proyek ini memprioritaskan performa tinggi, keterbacaan kode, serta pengelolaan data yang terstruktur melalui penerapan <b>Repository & Service Pattern</b> secara disiplin tanpa mengandalkan paket pihak ketiga yang berlebihan.
</p>

<hr />

<h2>🚀 Fitur Utama</h2>
<ul>
  <li><b>Authentication & Access Management:</b> Sistem otentikasi aman menggunakan Laravel Breeze.</li>
  <li><b>Content & Media Management:</b> Pengelolaan postingan berita, kategori dinamis, dan aset media secara terstruktur.</li>
  <li><b>Clean Architecture:</b> Pemisahan <i>business logic</i> dan query database menggunakan <b>Repository & Service Pattern</b>.</li>
  <li><b>Automated Testing:</b> Jaminan stabilitas aplikasi dengan pengujian otomatis menggunakan <b>PHPUnit</b>.</li>
  <li><b>Development Tools:</b> Integrasi Laravel Debugbar & Pail untuk efisiensi <i>debugging</i> dan pencatatan log.</li>
</ul>

<hr />

<h2>🛠️ Tech Stack & Dependencies</h2>
<table>
  <tr>
    <td><b>Core Framework</b></td>
    <td>PHP ^8.3, Laravel ^13.0</td>
  </tr>
  <tr>
    <td><b>Authentication</b></td>
    <td>Laravel Breeze (^2.4)</td>
  </tr>
  <tr>
    <td><b>Testing & QA</b></td>
    <td>PHPUnit (^12.5), Mockery (^1.6)</td>
  </tr>
  <tr>
    <td><b>Dev Utilities</b></td>
    <td>Laravel Debugbar, Laravel Pail, Laravel Pint, Laravel Boost</td>
  </tr>
</table>

<hr />

<h2>⚙️ Panduan Instalasi (Getting Started)</h2>

<p>Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal kamu:</p>

<ol>
  <li>
    <b>Clone Repository:</b>
    <pre><code>git clone https://github.com/donyyulihandoko/NewsCore.git
cd NewsCore</code></pre>
  </li>
  <li>
    <b>Jalankan Automated Setup (Rekomendasi):</b>
    <p>Proyek ini sudah dilengkapi dengan skrip penginstalan otomatis composer:</p>
    <pre><code>composer run setup</code></pre>
  </li>
  <li>
    <b>Menjalankan Server Lokal (Development):</b>
    <p>Menjalankan <i>server</i>, <i>queue listener</i>, dan <i>Vite</i> secara bersamaan:</p>
    <pre><code>composer run dev</code></pre>
  </li>
  <li>
    <b>Menjalankan Testing:</b>
    <pre><code>composer run test</code></pre>
  </li>
</ol>

<hr />

<h2>📝 Lisensi</h2>
<p>Proyek ini dikembangkan di bawah lisensi <a href="LICENSE">MIT License</a>.</p>
