# SchoolZone

🏫 **SchoolZone** is an online school management system developed with Filament and Laravel. It provides an admin panel for managing all aspects of school operations, including student data, classes, teachers, exams, marks, attendance, and more.

## Table of Contents
- [Introduction](#introduction)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Usage](#usage)
- [Configuration](#configuration)
- [Troubleshooting](#troubleshooting)
- [Contributors](#contributors)
- [License](#license)

## Introduction

🏫 **SchoolZone** is designed to streamline school management by providing an easy-to-use admin panel where administrators can manage student information, classes, teachers, exams, marks, attendance, and other school-related activities. Built with Filament and Laravel, SchoolZone ensures a robust and scalable solution for school management needs.

## Features

✨ **Features**:

- **Admin Panel** 🛠️:
  - Manage student data 🧑‍🎓
  - Organize classes and assign teachers 🏫
  - Schedule and manage exams 📅
  - Record and view marks 📝
  - Track attendance 📋

- **User Management** 🔐:
  - Role-based access control 🔑
  - Secure authentication 🔒

## Technologies Used

🛠️ **Technologies Used**:
- **Filament**: Admin panel framework 🖥️
- **Laravel**: Backend framework 🌐
- **PHP**: Server-side scripting 🐘
- **MySQL**: Database 🗄️

## Installation

To run SchoolZone locally, follow these steps:

1. Clone the repository 📥:
    ```bash
    git clone https://github.com/premnath018/schoolzone.git
    ```
2. Navigate to the project directory 📁:
    ```bash
    cd schoolzone
    ```
3. Install dependencies 📦:
    ```bash
    composer install
    npm install
    npm run build
    ```
4. Set up your `.env` file with the necessary configuration, including database credentials ⚙️.
5. Generate the application key 🔑:
    ```bash
    php artisan key:generate
    ```
6. Run the migrations and seed the database 📊:
    ```bash
    php artisan migrate --seed
    ```
7. Create a admin user using filament 👤:
    ```bash
    php artisan make:filament-user
    ```
8. Start the development server 🚀:
    ```bash
    php artisan serve
    ```

## Usage

1. Open the application in your web browser 🌐.
2. Log in as an administrator 🔐.
3. Use the admin panel to manage various school operations 🛠️:
   - Add or update student information 🧑‍🎓
   - Organize classes and assign teachers 🏫
   - Schedule exams and record marks 📅📝
   - Track and manage attendance 📋

## Configuration

Ensure the following configurations are set up correctly in your `.env` file ⚙️:
- Database connection settings 🗄️

## Troubleshooting

- **Database Connection Issues** ❌: Ensure your database credentials in the `.env` file are correct and that the MySQL server is running 🗄️.
- **Error 500** 🚫: Check the Laravel log files (`storage/logs/laravel.log`) for detailed error messages and stack traces 📝.

## Contributors

👥 **Contributors**:
- [Premnath](https://github.com/premnath018) - Creator and Maintainer 💻

## License

📜 This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
