# php-perpustakaan_sederhana

## Database setup

1. Start Apache and MySQL in XAMPP.
2. Open phpMyAdmin at `http://localhost/phpmyadmin`.
3. Import `perpus.sql` using the **Import** tab. The script creates and selects the `perpus` database and then creates its tables and sample data.
4. Open the project at `http://localhost/perpustakaan`.

The default connection in `koneksi.php` uses MySQL on `localhost`, username `root`, and an empty password. If your MySQL credentials differ, update those values there.

The sample administrator account is `admin` with password `admin123`.

The book table also supports the spreadsheet inventory fields (publisher, ISBN, DDC/classification, shelf, inventory number, and related metadata). Imported records without a status are marked `baru`; blank stock values are stored as `0`.

Book class/grade is entered manually by an administrator in the book add/edit form. Member login is disabled; member records remain stored for historical loan and return reports.

Administrators can type a borrower's name and class directly when recording a loan; previously used names and classes are suggested. Loan and return histories/reports show these values, with older loans using the linked member name where available.
