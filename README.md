# Ticket Stats

**Ticket Stats** is a web-based support ticket management system built with Laravel 11, designed to help users track, manage, and analyze support tickets efficiently. It provides a user-friendly dashboard, ticket management with filtering and pagination, and detailed performance statistics with visualizations using Chart.js. The application uses MySQL as its database backend.

---

## Features

- **Dashboard**: View a summary of tickets with counts for total, open, in-progress, and assigned tickets, plus a paginated and filterable ticket list.
- **Ticket Management**: Create, edit, delete, and assign tickets to yourself with a comprehensive interface supporting pagination and column-based filtering.
- **Statistics**: Analyze support performance with metrics like:
  - Total tickets answered
  - Average resolution time
  - Resolution time distribution
  - Tickets answered per period (day/week/month)
  - Efficiency score
  - Priority handling breakdown
  - Peak performance hours
  - Escalated tickets and backlog
  - Visualized with Chart.js bar and line charts
- **Authentication**: Secure login and registration using Laravel Breeze.
- **Pagination & Filtering**: Filter tickets by any column (customer, topic, priority, status, etc.) with pagination support.
- **Responsive Design**: Built with Tailwind CSS for a modern, mobile-friendly UI.

---

## Prerequisites

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **MySQL**: 5.7 or higher
- **Node.js**: 16.x or higher (with npm)
- **Git**: For cloning the repository

---

## Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/miaouliswide/ticket-stats.git
   cd ticket-stats

2. **Install dependencies**:
   ```bash
   npm install
   npm install chart.js
3. **env file**:
   ```bash
    APP_NAME="Ticket Stats"
    APP_ENV=local
    APP_KEY=
    APP_DEBUG=true
    APP_URL=http://localhost:8000
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=ticket_stats
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
5. **Generate app key**:
   ```bash 
   php artisan key:generate
7. **Create Database**:
   ```bash
   CREATE DATABASE ticket_stats;
   
   php artisan migrate

## License
This project is open-source and available under the MIT License.

## Roadmap
-API Endpoints for external integrations

-Export Reports in CSV/PDF
![chrome_Vo0PY8wx5N](https://github.com/user-attachments/assets/530e9455-5660-4150-8fc1-8bffa3ad7e74)

![chrome_aYzk1N8mp3](https://github.com/user-attachments/assets/86cca42c-1d76-4efa-ba73-b9acd25a88ea)

![chrome_XGIbHLG0Pq](https://github.com/user-attachments/assets/5151e620-37c7-4087-94c7-6a7fb4e70914)

