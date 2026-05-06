# Yakamoz Aksesuar - E-Commerce Platform
This full-stack e-commerce project was developed during my internship at Kodpit Technology A.S. between July and August 2025. The platform provides a complete shopping cycle for users and a comprehensive management interface for administrators.

### Technical Specifications
Backend: Built with Core PHP and MySQL for server-side logic and database management.
Frontend: Developed using HTML5, CSS3, and Bootstrap 5.3.7 for a responsive user interface.
Interactivity: Integrated jQuery and AJAX to handle cart and favorite operations without page refreshes, improving user experience.

### Implementation Details
Database Security: Implemented MySQLi with prepared statements to prevent SQL injection attacks.
Authentication: User passwords are secured using BCRYPT hashing (password_hash and password_verify).
Admin Features: Includes modules for product CRUD operations, order status tracking (Preparing/Shipped/Delivered), and user management.
Architecture: Utilizes a modular structure with centralized database configuration and reusable components (navbar/footer) via PHP includes.

### How to Install
Clone the repository to your local server (MAMP/XAMPP).
Import the aksesuar_db.sql file into your MySQL environment.
Update db_baglanti.php with your local database credentials.
