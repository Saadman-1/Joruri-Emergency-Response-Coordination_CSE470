# Joruri Emergency Response Co-ordination Website

## Overview
This project is a web-based emergency response coordination system for volunteers and victims. It allows volunteers to view victim details, send messages, and receive replies. The system supports dark mode and is built with PHP, MySQL, HTML, and CSS.

It can track the real-time location of the user, allowing users to see their nearest medical or shelter center. The system also includes a customised AI chatbot for emergency situations. Additionally, victims can take donations from donors through the platform.

## Features
- **Victim List:** View all victims registered in the system, searchable by name or location.
- **Messaging:** Volunteers can send messages to victims and view replies from victims.
- **Dark Mode:** Theme automatically matches the user's preference set during login.
- **Session Management:** Secure login and session handling for volunteers and admins.

## File Structure
- `track.php`: Main dashboard for volunteers. Displays victim list and messaging interface.
- `victim_registration.php`: Victim registration and reply interface.
- `admin_interface.php`: Admin dashboard for inventory management.
- `db.php`: Database connection settings.
- `style.css`: Global styles and dark mode variables.
- `login.php`: Main login page for users and admins.

## Database
- **Database Name:** `database470`
- **Tables:**
  - `victim`: Stores victim details (name, phone, location, calamity, need, blood-group).
  - `messages`: Stores messages between volunteers and victims (`vol_message`, `vic_message`).
  - `volunteer`: Stores volunteer registration/login info.
  - `inventory`: Stores inventory items for admin management.
  - `login`: Stores user login information.
  - `medical_db`: Stores medical resource information.
  - `shelter_db`: Stores shelter information.
  - `taker`: Stores information about items taken by volunteers.

## Database Tables and Specifications

### inventory
| Column Name | Type           | Specification                |
|-------------|----------------|------------------------------|
| id          | INT            | AUTO_INCREMENT, PRIMARY KEY  |
| name        | VARCHAR(100)   | Item name                    |
| quantity    | INT            | Quantity of item             |

### messages
| Column Name  | Type           | Specification                |
|--------------|----------------|------------------------------|
| id           | INT            | AUTO_INCREMENT, PRIMARY KEY  |
| victim       | VARCHAR(100)   | Victim's name                |
| volunteer    | VARCHAR(100)   | Volunteer's name             |
| vol_message  | TEXT           | Message from volunteer       |
| vic_message  | TEXT           | Message from victim          |

### victim
| Column Name   | Type           | Specification                |
|---------------|----------------|------------------------------|
| id            | INT            | AUTO_INCREMENT, PRIMARY KEY  |
| name          | VARCHAR(100)   | Victim's name                |
| phone         | VARCHAR(20)    | Victim's phone number        |
| location      | VARCHAR(255)   | Location                     |
| calamity      | VARCHAR(100)   | Type of calamity             |
| need          | VARCHAR(255)   | Victim's need                |
| blood-group   | VARCHAR(10)    | Blood group                  |

### volunteer
| Column Name | Type           | Specification                |
|-------------|----------------|------------------------------|
| id          | INT            | AUTO_INCREMENT, PRIMARY KEY  |
| name        | VARCHAR(100)   | Volunteer name               |
| password    | VARCHAR(255)   | Volunteer password (hashed)  |

### login
| Column Name | Type           | Specification                |
|-------------|----------------|------------------------------|
| id          | INT            | AUTO_INCREMENT, PRIMARY KEY  |
| username    | VARCHAR(100)   | User's login name            |
| password    | VARCHAR(255)   | User's password (hashed)     |
| role        | VARCHAR(20)    | User role (admin/user/etc)   |

### medical_db
| Column Name | Type           | Specification                |
|-------------|----------------|------------------------------|
| id          | INT            | AUTO_INCREMENT, PRIMARY KEY  |
| name        | VARCHAR(100)   | Medical resource name        |
| type        | VARCHAR(50)    | Type of medical resource     |
| location    | VARCHAR(255)   | Location                     |
| contact     | VARCHAR(100)   | Contact info                 |

### shelter_db
| Column Name | Type           | Specification                |
|-------------|----------------|------------------------------|
| id          | INT            | AUTO_INCREMENT, PRIMARY KEY  |
| name        | VARCHAR(100)   | Shelter name                 |
| location    | VARCHAR(255)   | Shelter location             |
| capacity    | INT            | Shelter capacity             |
| contact     | VARCHAR(100)   | Contact info                 |

### taker
| Column Name | Type           | Specification                |
|-------------|----------------|------------------------------|
| id          | INT            | AUTO_INCREMENT, PRIMARY KEY  |
| name        | VARCHAR(100)   | Taker's name                 |
| item        | VARCHAR(100)   | Item taken                   |
| quantity    | INT            | Quantity taken               |
| date        | DATE           | Date of taking               |

## How to Run
1. Place all files in your XAMPP `htdocs` directory.
2. Start Apache and MySQL from XAMPP control panel.
3. Import the database structure into MySQL (see below).
4. Access the app via `http://localhost/joruri_470/login.php`.

## Database Setup
Create the required tables in your MySQL database:

```sql
CREATE DATABASE IF NOT EXISTS database470;
USE database470;

CREATE TABLE victim (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  phone VARCHAR(20),
  location VARCHAR(255),
  calamity VARCHAR(100),
  need VARCHAR(255),
  `blood-group` VARCHAR(10)
);

CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  victim VARCHAR(100),
  volunteer VARCHAR(100),
  vol_message TEXT,
  vic_message TEXT
);

CREATE TABLE volunteer (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  password VARCHAR(255)
);

CREATE TABLE inventory (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  quantity INT
);

CREATE TABLE login (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100),
  password VARCHAR(255),
  role VARCHAR(20)
);

CREATE TABLE medical_db (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  type VARCHAR(50),
  location VARCHAR(255),
  contact VARCHAR(100)
);

CREATE TABLE shelter_db (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  location VARCHAR(255),
  capacity INT,
  contact VARCHAR(100)
);

CREATE TABLE taker (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  item VARCHAR(100),
  quantity INT,
  date DATE
);
```

## Customization
- Update `db.php` with your database credentials if needed.
- Modify styles in `style.css` for branding or layout changes.

## License
This project is for educational purposes.

