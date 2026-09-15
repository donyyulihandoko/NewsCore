<div align="center">
  <h1>📰 NewsCore</h1>
  <p><b>Multi-Role Content Management System (CMS) & Content Publishing Platform</b></p>
  <p>
    <img src="https://img.shields.io/badge/PHP-^8.3-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP Version" />
    <img src="https://img.shields.io/badge/Laravel-^13.0-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel Version" />
    <img src="https://img.shields.io/badge/Database-SQLite%20%2F%20MySQL-blue?style=flat-square&logo=sqlite&logoColor=white" alt="Database" />
    <img src="https://img.shields.io/badge/Auth-Laravel%20Breeze-4B5563?style=flat-square" alt="Laravel Breeze" />
    <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License" />
  </p>
</div>

<hr />

<h2>📌 Ringkasan Proyek</h2>
<p>
  <b>NewsCore</b> adalah platform Content Management System (CMS) dan publikasi berita berbasis <b>Laravel</b> yang dirancang menggunakan arsitektur <b>Multi-Role Access Control</b> (Admin, Author, dan User). Proyek ini memiliki alur peninjauan konten (<i>Post Approval Workflow</i>) untuk memastikan artikel tersaring sebelum dipublikasikan, serta dilengkapi sistem interaksi komentar yang terintegrasi secara modular berbasis relasi database.
</p>

<hr />

<h2>🔑 Fitur Utama & Hak Akses (Multi-Role)</h2>

<h3>1. 🛡️ Admin Management Panel (<code>is_admin</code>)</h3>
<ul>
  <li><b>Dashboard & Statistics:</b> Pengawasan menyeluruh terhadap artikel yang terpublikasi (<i>Published</i>) dan artikel dalam status peninjauan (<i>Pending Posts</i>).</li>
  <li><b>Category Management:</b> Manajemen Penuh (CRUD) pada kategori berita/topik beserta Slug dan Cover Image.</li>
  <li><b>Content & Comment Moderation:</b> Otorisasi penuh terhadap seluruh draf berita dan komentar pengguna di sistem.</li>
</ul>

<h3>2. ✍️ Author Studio (<code>is_author</code>)</h3>
<ul>
  <li><b>Article Authoring:</b> Membuat, mengedit, dan mengelola draf artikel berita.</li>
  <li><b>Submission Status Tracking:</b> Memantau status moderasi artikel (<i>is_published: true/false</i>).</li>
  <li><b>Comment Management:</b> Mengelola interaksi komentar pada postingan yang dipublikasikan.</li>
</ul>

<h3>3. 👤 Reader / User Area (<code>is_user</code>)</h3>
<ul>
  <li><b>Topic & Article Exploration:</b> Menjelajahi artikel berdasarkan kategori/topik tertentu.</li>
  <li><b>Interactive Discussion:</b> Memberikan dan mengelola komentar pada publikasi berita.</li>
</ul>

<hr />

<h2>🗄️ Database Schema & Relasi Entitas</h2>

<p>Aplikasi ini dirancang dengan struktur basis data yang terintegrasi penuh menggunakan <i>foreign key constraints</i> untuk menjaga integritas data (<i>Data Integrity</i>):</p>

<table>
  <thead>
    <tr>
      <th>Nama Tabel</th>
      <th>Kolom Utama / Attributes</th>
      <th>Keterangan & Relasi Data</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><b>users</b></td>
      <td><code>id</code>, <code>name</code>, <code>username</code>, <code>email</code>, <code>role</code>, <code>password</code></td>
      <td>Menyimpan data akun. Kolom <code>role</code> menggunakan <b>ENUM</b> (<i>'admin'</i>, <i>'author'</i>, <i>'user'</i>) dengan nilai default <i>'user'</i>.</td>
    </tr>
    <tr>
      <td><b>categories</b></td>
      <td><code>id</code>, <code>name</code>, <code>slug</code>, <code>description</code>, <code>image</code></td>
      <td>Mengelompokkan artikel. Kolom <code>name</code> dan <code>slug</code> di-set <i>UNIQUE</i> untuk pencarian ramah SEO.</td>
    </tr>
    <tr>
      <td><b>posts</b></td>
      <td><code>id</code>, <code>title</code>, <code>slug</code>, <code>image</code>, <code>is_published</code>, <code>user_id</code>, <code>category_id</code>, <code>body</code></td>
      <td>
        Entitas artikel berita.
        <br />• <code>user_id</code> &rarr; FK ke <code>users(id)</code> (<i>onDelete: restrict</i>)
        <br />• <code>category_id</code> &rarr; FK ke <code>categories(id)</code> (<i>onDelete: restrict</i>)
      </td>
    </tr>
    <tr>
      <td><b>comments</b></td>
      <td><code>id</code>, <code>post_id</code>, <code>user_id</code>, <code>body</code></td>
      <td>
        Komentar pengguna pada artikel.
        <br />• <code>post_id</code> &rarr; FK ke <code>posts(id)</code> (<i>onDelete: cascade</i>)
        <br />• <code>user_id</code> &rarr; FK ke <code>users(id)</code> (<i>onDelete: cascade</i>)
      </td>
    </tr>
    <tr>
      <td><b>Infrastructure Tables</b></td>
      <td><code>sessions</code>, <code>cache</code>, <code>jobs</code>, <code>job_batches</code>, <code>failed_jobs</code></td>
      <td>Dukungan infrastruktur Laravel untuk manajemen sesi berbasis database, caching, dan pemrosesan antrean latar belakang (<i>Queue Jobs</i>).</td>
    </tr>
  </tbody>
</table>

<hr />

<h2>🛠️ Tech Stack & Dependencies</h2>
<table>
  <tr>
    <td><b>Backend Framework</b></td>
    <td>PHP ^8.3, Laravel ^13.0</td>
  </tr>
  <tr>
    <td><b>Authentication Engine</b></td>
    <td>Laravel Breeze (^2.4)</td>
  </tr>
  <tr>
    <td><b>Database & Drivers</b></td>
    <td>SQLite / MySQL (Support Database Session, Cache, & Queue Drivers)</td>
  </tr>
  <tr>
    <td><b>Testing & QA</b></td>
    <td>PHPUnit (^12.5), Mockery (^1.6)</td>
  </tr>
  <tr>
    <td><b>Dev Utilities</b></td>
    <td>Laravel Debugbar, Laravel Pail, Laravel Pint, Laravel Boost, Concurrently</td>
  </tr>
</table>

<hr />

<h2>⚙️ Panduan Instalasi (Getting Started)</h2>

<ol>
  <li>
    <b>Clone Repository:</b>
    <pre><code>git clone https://github.com/donyyulihandoko/NewsCore.git
cd NewsCore</code></pre>
  </li>
  <li>
    <b>Jalankan Automated Setup:</b>
    <p>Skrip otomatis ini akan mengunduh dependensi Composer & NPM, mengosongkan/menyalin file <code>.env</code>, membuat APP_KEY, serta menjalankan migrasi database:</p>
    <pre><code>composer run setup</code></pre>
  </li>
  <li>
    <b>Menjalankan Development Server:</b>
    <p>Perintah ini akan menjalankan <i>HTTP Server</i>, <i>Queue Worker</i>, dan <i>Vite Asset Builder</i> secara bersamaan:</p>
    <pre><code>composer run dev</code></pre>
  </li>
  <li>
    <b>Menjalankan Testing:</b>
    <pre><code>composer run test</code></pre>
  </li>
</ol>

<hr />

<h2>📝 Lisensi</h2>
<p>Proyek ini bersifat terbuka dan dikembangkan di bawah lisensi <a href="LICENSE">MIT License</a>.</p>
