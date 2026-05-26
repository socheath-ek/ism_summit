\# ISM Data Science Summit – Registration App



A Symfony web application for managing guest registrations for the ISM Data Science Summit roadshow.



\## Features

\- Guest registration form with capacity management

\- Protected admin panel with CRUD operations

\- Sortable registration list

\- Excel export of registrations

\- PDF ticket with QR code after registration

\- User login system for admin access

\- Ajax-based capacity check

\- Doctrine Fixtures for test data



\## Tech Stack

\- Symfony 7.2

\- MySQL / MariaDB

\- Twig templates

\- Bootstrap 5

\- jQuery (Ajax)

\- Doctrine ORM

\- PHPSpreadsheet (Excel export)

\- DomPDF + Endroid QR Code (PDF tickets)



\## Installation



1\. Clone the repository:

git clone https://github.com/socheath-ek/ism\_summit.git

cd ism\_summit



2\. Install dependencies:

composer install --no-security-blocking --ignore-platform-reqs



3\. Configure database in .env:

DATABASE\_URL="mysql://root:@localhost:3306/ism\_summit?serverVersion=mariadb-10.4.0"



4\. Create database and tables:

php bin/console doctrine:database:create

php bin/console doctrine:schema:create



5\. Load test data:

php bin/console doctrine:fixtures:load



6\. Start the server:

php -S localhost:8000 -t public/



7\. Open browser at http://localhost:8000



\## Admin Access

\- URL: http://localhost:8000/login

\- Email: admin@ism.de

\- Password: admin123



\## Data Model

\- \*\*Summit\*\*: id, city, locationName, address, eventDate, capacity, isActive

\- \*\*Registration\*\*: id, firstName, lastName, email, company, jobTitle, phone, mealPreference, needsParking, needsAccommodation, newsletterConsent, dataProtectionConsent, status, ticketNumber, registeredAt, summit\_id

\- \*\*User\*\*: id, email, roles, password, name



\## MVC Architecture

\- \*\*Controllers\*\*: Handle HTTP requests and responses (thin)

\- \*\*Services\*\*: Contain business logic (RegistrationService, PdfTicketService, ExcelExportService)

\- \*\*Entities\*\*: Doctrine ORM models

\- \*\*Twig Templates\*\*: Views



\## Individual Option

Option C: PDF Ticket with QR Code generated after successful registration.



\## Problems Encountered

\- MySQL connection issues with multiple XAMPP installations – solved by starting mysqld.exe manually

\- PHP zip extension missing – solved by enabling in php.ini

\- Package version conflicts – solved using --no-security-blocking and --ignore-platform-reqs flags

