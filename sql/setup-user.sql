CREATE DATABASE IF NOT EXISTS internship_calendar;
CREATE USER IF NOT EXISTS 'internship_app'@'localhost' IDENTIFIED BY 'InternshipApp123!';
GRANT ALL PRIVILEGES ON internship_calendar.* TO 'internship_app'@'localhost';
FLUSH PRIVILEGES;
