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
